<?php
/**
 * The section block.
 *
 * One block type, not fifteen. A section is a tree of typed nodes (see inc/sections.php),
 * and the block carries that tree as an attribute; the editor walks it and offers a field
 * for every piece of text and a picker for every image, while the shape of the section
 * stays in the theme where it can be verified.
 *
 * Counting the markup is what settled this. The 401 sections reduce to 35 role sequences,
 * but the insides of those sections spread over 171 distinct shapes, because every card
 * has optional parts -- an outline, a background, an icon, a heading, a button. Fifteen
 * bespoke blocks would each have grown a dozen attributes, and the fifteenth would still
 * not have fitted. Carrying the tree fits all 401 exactly, which is the requirement.
 *
 * Bespoke blocks can still replace this one archetype at a time later; they would call
 * the same renderer, so nothing below has to change when they do.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render one section block.
 *
 * @param array $attributes Block attributes: `nodes`, and a `label` for the editor.
 * @return string
 */
function osous_render_section_block( $attributes ) {
	if ( empty( $attributes['nodes'] ) || ! is_array( $attributes['nodes'] ) ) {
		return '';
	}

	ob_start();
	osous_render_nodes( $attributes['nodes'] );

	return ob_get_clean();
}

/**
 * Register the block from its block.json.
 */
function osous_register_blocks() {
	register_block_type(
		OSOUS_DIR . '/blocks/section',
		array( 'render_callback' => 'osous_render_section_block' )
	);

	/*
	 * The editor's own strings -- "Heading", "Duplicate", "Light grey" -- are in the
	 * bundle, and WordPress only translates a script's strings when told which text
	 * domain they belong to. The handle is the one block.json's `editorScript` registers.
	 */
	wp_set_script_translations( 'osous-section-editor-script', 'osous', OSOUS_DIR . '/languages' );
}
add_action( 'init', 'osous_register_blocks' );

/**
 * The category the section library sits in.
 *
 * WordPress registers everything in `theme/patterns/` by itself; all it needs is for the
 * category those files name to exist. Without this they land in "Uncategorized" and the
 * library reads as a pile rather than as this site's sections.
 */
function osous_register_pattern_category() {
	register_block_pattern_category(
		'osous',
		array(
			'label'       => __( 'Osous sections', 'osous' ),
			'description' => __( 'The sections this design is built from.', 'osous' ),
		)
	);
}
add_action( 'init', 'osous_register_pattern_category', 9 );

/**
 * Keep the inserter to this theme's own blocks on pages.
 *
 * Every section on this site is an `osous/section`; core's paragraphs and columns would
 * only produce markup the design has no styles for, and a page that looks broken for a
 * reason nobody can see. Posts are left alone -- the blog is written in normal blocks.
 *
 * @param array|bool              $allowed Currently allowed block types.
 * @param WP_Block_Editor_Context $context Editor context.
 * @return array|bool
 */
function osous_allowed_blocks( $allowed, $context ) {
	if ( empty( $context->post ) || 'page' !== $context->post->post_type ) {
		return $allowed;
	}

	return array( 'osous/section' );
}
add_filter( 'allowed_block_types_all', 'osous_allowed_blocks', 10, 2 );

/**
 * Stop WordPress editing the rendered sections on their way out.
 *
 * `the_content` runs `do_blocks` first and then a row of filters meant for prose written
 * by hand. On markup a renderer has already finished they do harm rather than good:
 * `wptexturize` rewrites quotes and dashes inside every heading, which is a change to the
 * words themselves; `wpautop` looks for loose text to wrap and would find some the moment
 * a section rendered any. Neither can improve output that is already exactly right.
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
