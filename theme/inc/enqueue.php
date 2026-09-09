<?php
/**
 * Stylesheets and scripts.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The eight stylesheets, in the one order that works.
 *
 * This order is load-bearing, not cosmetic: `utilities.css` has to come after
 * `sections.css` so its single-purpose classes win, and `animations.css` last of all.
 * Each sheet declares the previous one as a dependency so WordPress cannot reorder
 * them, and so a future edit here cannot quietly break the cascade.
 */
const OSOUS_STYLE_ORDER = array(
	'tokens',
	'base',
	'layout',
	'components',
	'navigation',
	'sections',
	'utilities',
	'animations',
);

/**
 * Front-end assets.
 */
function osous_enqueue_assets() {
	$previous = array();

	foreach ( OSOUS_STYLE_ORDER as $sheet ) {
		$handle = 'osous-' . $sheet;
		wp_enqueue_style(
			$handle,
			get_theme_file_uri( "assets/css/{$sheet}.css" ),
			$previous,
			OSOUS_VERSION
		);
		$previous = array( $handle );
	}

	// WordPress requires style.css to be registered for the theme header; it holds no rules.
	wp_register_style( 'osous-theme', get_stylesheet_uri(), array(), OSOUS_VERSION );

	$defer = array(
		'strategy'  => 'defer',
		'in_footer' => true,
	);

	wp_enqueue_script( 'osous-navigation', get_theme_file_uri( 'assets/js/navigation.js' ), array(), OSOUS_VERSION, $defer );
	wp_enqueue_script( 'osous-reveal', get_theme_file_uri( 'assets/js/reveal.js' ), array(), OSOUS_VERSION, $defer );

	/*
	 * GSAP and Swiper are only worth loading where their markup exists. Until the
	 * section blocks land in Phase 3 this is page-based; afterwards each block will
	 * enqueue its own script and these conditionals disappear.
	 */
	if ( is_front_page() ) {
		wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', $defer );
		wp_enqueue_script( 'osous-industries-carousel', get_theme_file_uri( 'assets/js/industries-carousel.js' ), array( 'swiper' ), OSOUS_VERSION, $defer );
		osous_enqueue_gsap();
		wp_enqueue_script( 'osous-overlapping-cards', get_theme_file_uri( 'assets/js/overlapping-cards.js' ), array( 'gsap-scrolltrigger' ), OSOUS_VERSION, $defer );
	}
}
add_action( 'wp_enqueue_scripts', 'osous_enqueue_assets' );

/**
 * GSAP with ScrollTrigger, registered once and shared by the pinned sections.
 */
function osous_enqueue_gsap() {
	$defer = array(
		'strategy'  => 'defer',
		'in_footer' => true,
	);

	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js', array(), '3.13.0', $defer );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js', array( 'gsap' ), '3.13.0', $defer );
}
