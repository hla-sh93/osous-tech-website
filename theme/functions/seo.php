<?php
/**
 * What the theme tells a search engine, and what it leaves to the SEO plugin.
 *
 * A page's title, its description, its canonical address, its robots line, what it shows
 * when it is shared, and the schema that describes the company are an SEO plugin's to
 * print -- Rank Math on this site, as on the one it replaces. They are edited where that
 * plugin puts them: a box on every page for the page's own, and the plugin's settings
 * for the site's. The theme's part in that is to stay out of the way: it declares
 * `title-tag` (functions/setup.php) so the plugin owns the <title>, calls wp_head(), and
 * prints no title, description or canonical of its own -- two of any of those is worse
 * than none.
 *
 * What is here is the one line of the old site's <head> that no SEO plugin writes:
 * hreflang. Every page says which language and region it is written for, and names itself
 * as the default; a page that exists in another language as well lists that version,
 * from its own Page options.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a string can be a language tag: `en`, `en-US`, `ar-IQ`, `zh-Hant-TW`.
 *
 * @param string $code The tag.
 * @return bool
 */
function osous_is_language_tag( $code ) {
	return (bool) preg_match( '/^[A-Za-z]{2,3}(-[A-Za-z0-9]{2,8})*$/', (string) $code );
}

/**
 * A page's other language versions, from its Page options.
 *
 * One per line, a language tag and an address: `en-SY https://example.com/sy`. A line
 * that is not that is left out rather than printed wrong.
 *
 * @param int $page_id The page.
 * @return array<string,string> Language tag => address.
 */
function osous_page_alternates( $page_id ) {
	$found = array();
	$lines = preg_split( '/\R/', (string) get_post_meta( $page_id, '_osous_alternates', true ) );

	foreach ( $lines as $line ) {
		$parts = preg_split( '/\s+/', trim( $line ), 2 );
		if ( 2 !== count( $parts ) || ! osous_is_language_tag( $parts[0] ) ) {
			continue;
		}
		$url = esc_url_raw( $parts[1] );
		if ( '' !== $url ) {
			$found[ $parts[0] ] = $url;
		}
	}

	return $found;
}

/**
 * The hreflang links of the page being shown.
 *
 * The page in the site's own language, then its other versions, then itself again as the
 * default for a reader no version is written for -- the order the old site printed them
 * in. Nothing at all when Site details has no language, which is how to hand hreflang
 * over to a translation plugin that writes its own.
 */
function osous_hreflang() {
	$language = trim( osous_setting( 'hreflang' ) );
	if ( ! osous_is_language_tag( $language ) ) {
		return;
	}

	$page_id = 0;
	if ( is_singular() ) {
		$page_id = get_queried_object_id();
	} elseif ( is_home() && ! is_front_page() ) {
		$page_id = (int) get_option( 'page_for_posts' );
	} elseif ( ! is_front_page() ) {
		return;
	}

	$url = $page_id ? get_permalink( $page_id ) : home_url( '/' );
	if ( ! $url ) {
		return;
	}

	$links = array( $language => $url );
	if ( $page_id ) {
		foreach ( osous_page_alternates( $page_id ) as $code => $address ) {
			if ( ! isset( $links[ $code ] ) ) {
				$links[ $code ] = $address;
			}
		}
	}
	$links['x-default'] = $url;

	foreach ( $links as $code => $address ) {
		printf( '<link rel="alternate" hreflang="%s" href="%s">' . "\n", esc_attr( $code ), esc_url( $address ) );
	}
}
add_action( 'wp_head', 'osous_hreflang', 1 );
