<?php
/**
 * What a page carries besides its blocks: its options, its original, and a way to copy it.
 *
 * All of it is stored on the page -- post meta, by the page's id -- and edited in the
 * editor's sidebar, in the "Page options" panel (blocks/editor/page-panel.js):
 *
 *   - whether the floating WhatsApp badge is shown on this page;
 *   - the other language versions of this page, for search engines (functions/seo.php);
 *   - which file under pages/ is the page's original, and a button that puts the
 *     original sections back.
 *
 * And in the list of pages, under each one: "Duplicate", which makes a draft with the
 * same sections on the same template -- the quickest way to a second page of a kind that
 * is already right.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The page meta the editor reads and writes.
 *
 * Each key starts with an underscore, which keeps it out of the Custom Fields box, and so
 * needs an `auth_callback` to say who may change it: whoever may edit the page.
 */
function osous_register_page_meta() {
	$may_edit = static function ( $allowed, $meta_key, $post_id ) {
		return current_user_can( 'edit_post', $post_id );
	};

	register_post_meta(
		'page',
		'_osous_hide_whatsapp',
		array(
			'type'              => 'boolean',
			'single'            => true,
			'default'           => false,
			'show_in_rest'      => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'auth_callback'     => $may_edit,
		)
	);
	register_post_meta(
		'page',
		'_osous_alternates',
		array(
			'type'              => 'string',
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_textarea_field',
			'auth_callback'     => $may_edit,
		)
	);
	register_post_meta(
		'page',
		'_osous_original',
		array(
			'type'              => 'string',
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback'     => $may_edit,
		)
	);
}
add_action( 'init', 'osous_register_page_meta' );

/**
 * One of the page's switches, for the page being shown.
 *
 * @param string $name `hide_whatsapp`.
 * @return bool False anywhere that is not a page.
 */
function osous_page_option( $name ) {
	if ( ! is_page() ) {
		return false;
	}

	return (bool) get_post_meta( get_queried_object_id(), '_osous_' . $name, true );
}

/**
 * Whether the page being shown opens on the hero.
 *
 * The hero is a full-height photograph with the navbar floating over it, and the navbar
 * has a form for that: light type, no white pill. The design has one such page, the front
 * page -- but what calls for the dark navbar is the hero, not being the front page, so it
 * is the page's first block that decides. A second page that starts from the Home kind
 * gets the right header; a front page that is given to another page without a hero gets
 * the ordinary one.
 *
 * @return bool
 */
function osous_page_opens_dark() {
	if ( ! is_page() ) {
		return false;
	}
	$content = ltrim( (string) get_post_field( 'post_content', get_queried_object_id() ) );

	return 0 === strpos( $content, '<!-- wp:osous/hero' );
}

/**
 * The file under pages/ that a key names, or '' when there is none.
 *
 * The key is what `_osous_original` holds: the slug the page was installed with, which is
 * the file's name. sanitize_key() leaves letters, digits, dashes and underscores, so the
 * path can only ever be a file directly inside pages/.
 *
 * @param string $key The original's key.
 * @return string Absolute path, or ''.
 */
function osous_original_file( $key ) {
	$key = sanitize_key( $key );
	if ( '' === $key ) {
		return '';
	}
	$file = get_theme_file_path( 'pages/' . $key . '.php' );

	return file_exists( $file ) ? $file : '';
}

/**
 * The original's blocks, for the editor's "Restore the original sections".
 *
 * GET /wp-json/osous/v1/original/<key>, for whoever may edit pages. The file is a PHP
 * file that prints block markup, read the way WordPress reads a pattern file.
 */
function osous_register_original_route() {
	register_rest_route(
		'osous/v1',
		'/original/(?P<key>[a-z0-9_-]+)',
		array(
			'methods'             => 'GET',
			'permission_callback' => static function () {
				return current_user_can( 'edit_pages' );
			},
			'callback'            => static function ( $request ) {
				$file = osous_original_file( $request['key'] );
				if ( '' === $file ) {
					return new WP_Error(
						'osous_no_original',
						__( 'This page has no original in the theme.', 'osous' ),
						array( 'status' => 404 )
					);
				}

				ob_start();
				include $file;
				$content = ob_get_clean();

				return rest_ensure_response(
					array(
						'key'     => sanitize_key( $request['key'] ),
						'content' => trim( (string) $content ),
					)
				);
			},
		)
	);
}
add_action( 'rest_api_init', 'osous_register_original_route' );

/**
 * "Duplicate", under each page in the list of pages.
 *
 * @param array   $actions The row's actions.
 * @param WP_Post $post    The page.
 * @return array
 */
function osous_duplicate_link( $actions, $post ) {
	if ( 'page' !== $post->post_type || ! current_user_can( 'edit_post', $post->ID ) || ! current_user_can( 'edit_pages' ) ) {
		return $actions;
	}

	$url = wp_nonce_url(
		admin_url( 'admin-post.php?action=osous_duplicate_page&post=' . $post->ID ),
		'osous_duplicate_page_' . $post->ID
	);

	$actions['osous_duplicate'] = sprintf(
		'<a href="%s" aria-label="%s">%s</a>',
		esc_url( $url ),
		/* translators: %s: the page's title. */
		esc_attr( sprintf( __( 'Duplicate “%s”', 'osous' ), $post->post_title ) ),
		esc_html__( 'Duplicate', 'osous' )
	);

	return $actions;
}
add_filter( 'page_row_actions', 'osous_duplicate_link', 10, 2 );

/**
 * Make the copy: a draft with the page's sections, on its template, with its options.
 *
 * Not its address, which has to be its own; not its original, because the copy was never
 * installed from a file; and not what a search engine is told about the page it was
 * copied from, which would be two pages claiming one title.
 */
function osous_duplicate_page() {
	$source_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	check_admin_referer( 'osous_duplicate_page_' . $source_id );

	$source = get_post( $source_id );
	if ( ! $source || 'page' !== $source->post_type
		|| ! current_user_can( 'edit_post', $source_id ) || ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'This page cannot be duplicated.', 'osous' ) );
	}

	$copy_id = wp_insert_post(
		// Slashed, because wp_insert_post() unslashes: the blocks' attributes carry \u escapes.
		wp_slash(
			array(
				'post_type'    => 'page',
				'post_status'  => 'draft',
				/* translators: %s: the title of the page that was copied. */
				'post_title'   => sprintf( __( '%s (copy)', 'osous' ), $source->post_title ),
				'post_content' => $source->post_content,
				'post_excerpt' => $source->post_excerpt,
				'post_parent'  => $source->post_parent,
				'menu_order'   => $source->menu_order,
			)
		),
		true
	);
	if ( is_wp_error( $copy_id ) ) {
		wp_die( esc_html( $copy_id->get_error_message() ) );
	}

	foreach ( array( '_wp_page_template', '_osous_hide_whatsapp', '_thumbnail_id' ) as $key ) {
		$value = get_post_meta( $source_id, $key, true );
		if ( '' !== $value && false !== $value ) {
			update_post_meta( $copy_id, $key, wp_slash( $value ) );
		}
	}

	wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $copy_id ) );
	exit;
}
add_action( 'admin_post_osous_duplicate_page', 'osous_duplicate_page' );
