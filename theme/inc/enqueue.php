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
 * Scroll modules, keyed by the class whose presence on the page calls for them.
 *
 * Keeping the trigger in the markup rather than in a list of page slugs is what stops
 * this drifting. On the static site the same mapping lives in 57 hand-written script
 * blocks, and two of them are already wrong.
 */
const OSOUS_SCROLL_MODULES = array(
	'list-scroll'            => 'list-scroll',
	'split-section'          => 'split-section',
	'card-overlapping-item'  => 'overlapping-cards',
);

/**
 * Front-end assets.
 */
function osous_enqueue_assets() {
	/*
	 * Inter and Sora, the two families the design is drawn in. Enqueued rather than
	 * hardcoded in header.php so the handle can be a dependency of the first theme
	 * sheet, which keeps it above them in the cascade exactly as the static pages have it.
	 */
	wp_enqueue_style(
		'osous-fonts',
		'https://fonts.googleapis.com/css?family=Inter:regular,700%7CSora:200,600&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google serves its own.
	);

	$previous = array( 'osous-fonts' );

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

	$features = osous_section_features( osous_page_sections() );

	foreach ( OSOUS_SCROLL_MODULES as $class => $module ) {
		if ( empty( $features[ $class ] ) ) {
			continue;
		}
		osous_enqueue_gsap();
		wp_enqueue_script(
			'osous-' . $module,
			get_theme_file_uri( "assets/js/{$module}.js" ),
			array( 'gsap-scrolltrigger' ),
			OSOUS_VERSION,
			$defer
		);
	}

	if ( ! empty( $features['swiper-container'] ) ) {
		wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', $defer );
		wp_enqueue_script( 'osous-industries-carousel', get_theme_file_uri( 'assets/js/industries-carousel.js' ), array( 'swiper' ), OSOUS_VERSION, $defer );
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
