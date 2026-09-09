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

	register_nav_menus(
		array(
			'primary'         => __( 'Header mega menu', 'osous' ),
			'footer-discover' => __( 'Footer — Discover', 'osous' ),
			'footer-services' => __( 'Footer — Services', 'osous' ),
			'footer-iraq'     => __( 'Footer — Iraq office', 'osous' ),
			'footer-uae'      => __( 'Footer — UAE office', 'osous' ),
			'footer-legal'    => __( 'Footer — Legal', 'osous' ),
		)
	);
}
add_action( 'after_setup_theme', 'osous_setup' );
