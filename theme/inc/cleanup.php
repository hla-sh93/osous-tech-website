<?php
/**
 * Trimming what WordPress adds by default.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Drop the emoji polyfill.
 *
 * It costs two scripts and an inline stylesheet on every page, and this design uses no
 * emoji at all. Nothing in the reference markup corresponds to it.
 */
function osous_remove_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter(
		'tiny_mce_plugins',
		function ( $plugins ) {
			return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
		}
	);
}
add_action( 'init', 'osous_remove_emoji' );

/**
 * Hide the WordPress version from the page source.
 */
remove_action( 'wp_head', 'wp_generator' );

/*
 * Deliberately left in place:
 *
 *   wp-block-library      ~8KB inline
 *   global-styles         ~9KB inline, generated from core's default theme.json
 *   classic-theme-styles  ~1KB inline
 *
 * None of it affects the rendering today — the chrome measures identically to the static
 * reference with all three present. They are worth revisiting once the section blocks
 * land in Phase 3, because at that point the block styles are actually in use and
 * removing the wrong one would be a real regression rather than a saving.
 */
