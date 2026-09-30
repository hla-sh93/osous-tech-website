<?php
/**
 * The blocks: registration, the editor bundle, and what a page may hold.
 *
 * Every block lives in its own folder under blocks/ -- block.json for what it is, what it
 * stores and which file under includes/ prints it, edit.js for how it is edited. WordPress
 * reads block.json and that file by itself; edit.js is compiled with the rest into
 * build/index.js.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register every block under blocks/.
 *
 * A folder with a block.json is a block. Nothing else lists them, so adding one is adding
 * a folder.
 */
function osous_register_blocks() {
	foreach ( glob( OSOUS_DIR . '/blocks/*/block.json' ) as $metadata ) {
		register_block_type( dirname( $metadata ) );
	}
}
add_action( 'init', 'osous_register_blocks' );

/**
 * The editor: one script for all the blocks, and the design's option tables with it.
 *
 * The tables are handed over rather than repeated in JavaScript, so the labels a person
 * sees in the editor and the classes the templates print come from one place
 * (functions/design.php). The theme's asset paths go with them: the blocks store pictures as
 * `assets/…`, and the editor has to know where that is to show them. The kinds of page go
 * too (functions/templates.php): a new page is asked which one it is.
 */
function osous_enqueue_editor() {
	$asset = include OSOUS_DIR . '/build/index.asset.php';

	wp_enqueue_script(
		'osous-blocks',
		get_theme_file_uri( 'build/index.js' ),
		$asset['dependencies'],
		$asset['version'],
		true
	);
	wp_set_script_translations( 'osous-blocks', 'osous', OSOUS_DIR . '/languages' );

	wp_add_inline_script(
		'osous-blocks',
		'window.osousEditor = ' . wp_json_encode(
			array(
				'themeUrl' => trailingslashit( get_template_directory_uri() ),
				'homeUrl'  => home_url( '/' ),
				'design'   => osous_design_for_editor(),
				'kinds'    => osous_page_kinds_for_editor(),
			)
		) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'osous_enqueue_editor' );

/**
 * The category the blocks sit in, so the inserter shows them as this theme's own.
 *
 * @param array $categories Block categories.
 * @return array
 */
function osous_block_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'osous',
			'title' => __( 'Osous', 'osous' ),
		)
	);

	return $categories;
}
add_filter( 'block_categories_all', 'osous_block_category' );

/**
 * The categories the section library and the page starters sit in.
 *
 * The patterns themselves are registered by functions/patterns.php; all they need is for
 * the category their headers name to exist. Without this they land in "Uncategorized"
 * and the library reads as a pile rather than as this site's sections.
 */
function osous_register_pattern_category() {
	register_block_pattern_category(
		'osous',
		array(
			'label'       => __( 'Osous sections', 'osous' ),
			'description' => __( 'The sections this design is built from.', 'osous' ),
		)
	);
	register_block_pattern_category(
		'osous-pages',
		array(
			'label'       => __( 'Osous pages', 'osous' ),
			'description' => __( 'A whole page of each kind, as a new page of that kind starts.', 'osous' ),
		)
	);
}
add_action( 'init', 'osous_register_pattern_category', 9 );

/**
 * What the inserter offers.
 *
 * On a page: the theme's own blocks, the handful of core blocks a prose area is written
 * in, and synced patterns. Core's columns, groups, buttons and the rest would produce
 * markup the design has no styles for, and a page that looks broken for a reason nobody
 * can see.
 * On a post: everything but the theme's blocks. The blog is written in ordinary blocks,
 * and a page section dropped into an article would be a page section in an article.
 *
 * @param array|bool              $allowed Currently allowed block types.
 * @param WP_Block_Editor_Context $context Editor context.
 * @return array|bool
 */
function osous_allowed_blocks( $allowed, $context ) {
	if ( empty( $context->post ) ) {
		return $allowed;
	}

	$registered = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
	$ours       = array_filter(
		$registered,
		function ( $name ) {
			return 0 === strpos( $name, 'osous/' );
		}
	);

	/*
	 * `core/block` is a synced pattern: a section kept once under Patterns and placed on
	 * as many pages as want it, so that editing it there changes it everywhere. It holds
	 * this theme's blocks like any page does; allowing it is what puts "Create pattern" in
	 * a section's menu.
	 */
	if ( 'page' === $context->post->post_type ) {
		return array_merge(
			array( 'core/paragraph', 'core/heading', 'core/list', 'core/list-item', 'core/html', 'core/block' ),
			array_values( $ours )
		);
	}

	return array_values( array_diff( is_array( $allowed ) ? $allowed : $registered, $ours ) );
}
add_filter( 'allowed_block_types_all', 'osous_allowed_blocks', 10, 2 );

/**
 * Stop WordPress editing the rendered blocks on their way out.
 *
 * `the_content` runs `do_blocks` first and then a row of filters meant for prose written
 * by hand. On markup a template has already finished they do harm rather than good:
 * `wptexturize` rewrites quotes and dashes inside every heading, which is a change to the
 * words themselves; `wpautop` looks for loose text to wrap and would find some the moment
 * a block rendered any. Neither can improve output that is already exactly right.
 *
 * Pages only. Posts are written by hand in the editor and want the whole row.
 */
function osous_unfilter_page_content() {
	if ( ! is_page() ) {
		return;
	}
	remove_filter( 'the_content', 'wptexturize' );
	remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_content', 'shortcode_unautop' );
	remove_filter( 'the_content', 'convert_smilies', 20 );
}
add_action( 'template_redirect', 'osous_unfilter_page_content' );
