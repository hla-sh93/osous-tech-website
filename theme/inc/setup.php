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
			'footer-discover' => __( 'Footer — Discover column', 'osous' ),
			'footer-legal'    => __( 'Footer — Legal links', 'osous' ),
		)
	);
}
add_action( 'after_setup_theme', 'osous_setup' );

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
