<?php
/**
 * Theme supports and registrations.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare what the theme supports.
 *
 * `title-tag` matters here: Rank Math owns the document title on this site, so the
 * theme must never print its own <title>.
 */
function osous_setup() {
	load_theme_textdomain( 'osous', OSOUS_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

	/*
	 * The editor canvas gets the site's own stylesheets, in the same order the front end
	 * loads them. Without this a section renders its real markup and then shows it in
	 * Times New Roman on white, which makes the preview useless for the one thing a
	 * preview is for. WordPress iframes the canvas, so the sheets arrive unaltered.
	 */
	add_theme_support( 'editor-styles' );
	foreach ( OSOUS_STYLE_ORDER as $sheet ) {
		add_editor_style( "assets/css/{$sheet}.css" );
	}

	/*
	 * Last, and only here: the scroll scripts do not run in the canvas, so without it
	 * every revealed element sits at `opacity: 0` and the editor is a page of empty
	 * coloured rectangles. It says nothing the design does not already say under
	 * `prefers-reduced-motion`.
	 */
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'osous_setup' );

/**
 * The three menu locations.
 *
 * On `init`, not in osous_setup(): their names are translated, and since WordPress 6.7 a
 * string translated before `init` is a notice in the debug log -- "translation loading for
 * the osous domain was triggered too early". Nothing reads the locations before `init`.
 */
function osous_register_menus() {
	register_nav_menus(
		array(
			'primary'         => __( 'Header mega menu', 'osous' ),
			'footer-discover' => __( 'Footer — Discover column', 'osous' ),
			'footer-legal'    => __( 'Footer — Legal links', 'osous' ),
		)
	);
}
add_action( 'init', 'osous_register_menus' );

/**
 * The favicon, when the site has not been given one in Settings → General.
 *
 * WordPress only prints an icon for a Site Icon attachment in the Media Library. The
 * design ships its own, and a brand-new install should not show a blank tab until
 * someone remembers to upload it.
 */
function osous_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	printf(
		'<link rel="icon" href="%s" sizes="32x32">' . PHP_EOL,
		esc_url( get_theme_file_uri( 'assets/images/favicon.png' ) )
	);
}
add_action( 'wp_head', 'osous_favicon' );

/**
 * Preconnect to the font hosts.
 *
 * Going through the filter rather than printing the tags in header.php lets WordPress
 * drop its own weaker dns-prefetch for the same host.
 *
 * @param array  $urls     URLs for this relation.
 * @param string $relation Relation type.
 * @return array
 */
function osous_resource_hints( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'osous_resource_hints', 10, 2 );

/**
 * Menus in their places, and a front page.
 *
 * A WXR import carries the pages and the menus but not where the menus go or which page
 * is the front one: the first is a theme setting and the second is two options, and
 * neither travels in an export file. A site missing them has no navigation at all and a
 * list of blog posts where the home page should be -- both easy to miss, and both easy
 * to fix in a way nobody remembers doing.
 *
 * Runs on admin_init rather than on activation, because the theme is usually switched on
 * before the content is imported, and on activation there is nothing yet to point at.
 * It only ever fills in what is empty, so a later choice made in the admin stands.
 */
function osous_wire_up() {
	$menus     = array(
		'primary'         => 'Primary',
		'footer-discover' => 'Footer Discover',
		'footer-legal'    => 'Footer Legal',
	);
	$locations = get_nav_menu_locations();
	$changed   = false;

	foreach ( $menus as $location => $name ) {
		if ( ! empty( $locations[ $location ] ) ) {
			continue;
		}
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			$locations[ $location ] = $menu->term_id;
			$changed                = true;
		}
	}
	if ( $changed ) {
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	if ( 'page' !== get_option( 'show_on_front' ) ) {
		$front = get_page_by_path( 'index', OBJECT, 'page' );
		if ( $front ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front->ID );
		}
	}

	/*
	 * The blog, the same way: the page called "blog" is the posts index, and the grid is
	 * three by four. WordPress's own default of ten a page would show ten cards and a pager
	 * where the design shows twelve. Both are left alone once anybody has set them.
	 */
	if ( ! get_option( 'page_for_posts' ) ) {
		$blog = get_page_by_path( 'blog', OBJECT, 'page' );
		if ( $blog ) {
			update_option( 'page_for_posts', $blog->ID );
		}
	}
	if ( 10 === (int) get_option( 'posts_per_page' ) ) {
		update_option( 'posts_per_page', 12 );
	}

	/*
	 * The privacy page. A fresh WordPress points Settings → Privacy at a draft it made
	 * itself; once that draft is deleted -- which the import notes ask for, so the site's
	 * own page can keep its address -- the setting points at nothing. The site's page is
	 * the one at that address.
	 */
	$privacy = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( ! $privacy || 'publish' !== get_post_status( $privacy ) ) {
		$page = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
		if ( $page && 'publish' === $page->post_status ) {
			update_option( 'wp_page_for_privacy_policy', $page->ID );
		}
	}
}
add_action( 'admin_init', 'osous_wire_up' );
