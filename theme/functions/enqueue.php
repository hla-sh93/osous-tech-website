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
/**
 * Inter and Sora, the two families the design is drawn in.
 *
 * A constant because the editor canvas needs the same URL: everything else the canvas
 * shows comes from the files in OSOUS_STYLE_ORDER, and none of them carries a font.
 */
const OSOUS_FONTS_URL = 'https://fonts.googleapis.com/css?family=Inter:regular,700%7CSora:200,600&display=swap';

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
 * Scroll modules, keyed by the block that needs each one.
 *
 * A page gets a script because it holds the block the script drives, so a section moved
 * from one page to another brings its behaviour with it. The static site keeps a list of
 * page slugs instead, in 57 hand-written script blocks, and two of them are already wrong.
 */
const OSOUS_SCROLL_MODULES = array(
	'osous/pinned-list'   => 'list-scroll',
	'osous/split-section' => 'split-section',
	'osous/card-stack'    => 'overlapping-cards',
);

/**
 * The version string for one theme asset.
 *
 * Released builds carry the theme version, which is what invalidates caches after a
 * deploy. While developing, that version does not move and browsers happily serve a
 * stale stylesheet for hours, so the file's own modification time stands in -- an edit
 * is visible on the next reload without touching style.css.
 *
 * @param string $path Theme-relative path, e.g. `assets/js/reveal.js`.
 * @return string
 */
function osous_asset_version( $path ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return OSOUS_VERSION;
	}
	$file = get_theme_file_path( $path );

	return file_exists( $file ) ? (string) filemtime( $file ) : OSOUS_VERSION;
}

/**
 * Whether the page being shown holds a block -- in its own content, or inside a synced
 * pattern it holds.
 *
 * WordPress's has_block() reads the page's content and stops there. A synced pattern is
 * one line in that content, `wp:block {"ref":12}`, and the section it stands for is in
 * another post; a pinned list placed that way would arrive without the script that pins
 * it. So the patterns a page refers to are read too, and the ones those refer to.
 *
 * @param string          $name    A block's name, `osous/pinned-list`.
 * @param string|int|null $content Content to look in; the current post's when absent.
 * @param int             $depth   How far into patterns-within-patterns this is.
 * @return bool
 */
function osous_has_block( $name, $content = null, $depth = 0 ) {
	if ( ! is_string( $content ) ) {
		$post    = get_post( $content );
		$content = $post ? $post->post_content : '';
	}
	if ( has_block( $name, $content ) ) {
		return true;
	}
	if ( $depth >= 3 || ! preg_match_all( '/<!--\s+wp:block\s+\{[^}]*"ref":(\d+)/', $content, $found ) ) {
		return false;
	}
	foreach ( array_unique( $found[1] ) as $ref ) {
		$synced = get_post( (int) $ref );
		if ( $synced && 'wp_block' === $synced->post_type && osous_has_block( $name, $synced->post_content, $depth + 1 ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Front-end assets.
 */
function osous_enqueue_assets() {
	/*
	 * Enqueued rather than hardcoded in header.php so the handle can be a dependency of
	 * the first theme sheet, which keeps it above them in the cascade exactly as the
	 * static pages have it.
	 */
	wp_enqueue_style(
		'osous-fonts',
		OSOUS_FONTS_URL,
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
			osous_asset_version( "assets/css/{$sheet}.css" )
		);
		$previous = array( $handle );
	}

	// WordPress requires style.css to be registered for the theme header; it holds no rules.
	wp_register_style( 'osous-theme', get_stylesheet_uri(), array(), OSOUS_VERSION );

	$defer = array(
		'strategy'  => 'defer',
		'in_footer' => true,
	);

	wp_enqueue_script( 'osous-navigation', get_theme_file_uri( 'assets/js/navigation.js' ), array(), osous_asset_version( 'assets/js/navigation.js' ), $defer );
	wp_enqueue_script( 'osous-reveal', get_theme_file_uri( 'assets/js/reveal.js' ), array(), osous_asset_version( 'assets/js/reveal.js' ), $defer );

	foreach ( OSOUS_SCROLL_MODULES as $block => $module ) {
		if ( ! osous_has_block( $block ) ) {
			continue;
		}
		osous_enqueue_gsap();
		wp_enqueue_script(
			'osous-' . $module,
			get_theme_file_uri( "assets/js/{$module}.js" ),
			array( 'gsap-scrolltrigger' ),
			osous_asset_version( "assets/js/{$module}.js" ),
			$defer
		);
	}

	if ( osous_has_block( 'osous/industries-carousel' ) ) {
		wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', $defer );
		wp_enqueue_script( 'osous-industries-carousel', get_theme_file_uri( 'assets/js/industries-carousel.js' ), array( 'swiper' ), osous_asset_version( 'assets/js/industries-carousel.js' ), $defer );
	}
}
add_action( 'wp_enqueue_scripts', 'osous_enqueue_assets' );

/**
 * The fonts, inside the editor canvas.
 *
 * `add_editor_style()` takes local files and inlines them; it cannot give the canvas a
 * remote stylesheet without WordPress fetching it over the network on every editor load,
 * which is slow where it works and hangs where outbound HTTP is closed. `enqueue_block_assets`
 * runs inside the iframe, so a plain <link> lands there and the browser fetches the fonts
 * exactly as it does on the front end.
 *
 * Without this, every heading in the editor is drawn in a fallback face. It reads as very
 * nearly right -- which is worse than obviously wrong, because the type is what somebody
 * is judging when they choose a heading or a spacing.
 */
function osous_enqueue_editor_fonts() {
	if ( ! is_admin() ) {
		return; // The front end has them already, with the cascade order that matters there.
	}

	wp_enqueue_style(
		'osous-fonts',
		OSOUS_FONTS_URL,
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google serves its own.
	);
}
add_action( 'enqueue_block_assets', 'osous_enqueue_editor_fonts' );

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
