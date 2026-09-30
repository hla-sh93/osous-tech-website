<?php
/**
 * Pages by identity: links that follow a page when its address changes, and the old
 * address sent on to the new one.
 *
 * A page's slug is something an editor may change at any time -- it is a field in the
 * editor's sidebar -- and three things used to break when one did. Links stored in the
 * content name a page by its path (`dell-iraq.html`, the static site's own form, which
 * is what keeps the content readable and independent of where WordPress is installed):
 * they pointed at the old address. Visitors and search engines holding the old address
 * got a 404, because WordPress keeps a page's old slugs for posts and deliberately not
 * for pages. And anything that found a page by name found nothing.
 *
 * So a page is known here by what it is, not by what it is called today:
 *
 *   - when a published page's slug changes, the old one is kept on the page, in the same
 *     meta key WordPress uses for a post's old slugs;
 *   - a stored link is resolved to the page that has that path now, or that had it
 *     before, and printed as that page's current address;
 *   - a request for an address that no longer exists is answered with a permanent
 *     redirect to the page that used to live there.
 *
 * Menus already hold pages by id, the header's button holds one by id (Site details),
 * and a page's template is kept on the page itself -- so after a rename the page, its
 * template, the menus, every link to it and its old address all still work.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Where a page's former slugs are kept: WordPress's own key for a post's.
 *
 * Core writes it for posts and skips hierarchical types, so on a page it is this theme's
 * to write; should core ever do the same for pages, it will find them where it expects.
 */
const OSOUS_OLD_SLUG = '_wp_old_slug';

/**
 * Keep a published page's old slug when it is given a new one.
 *
 * Only what was public is kept: a draft renamed ten times before it is published has had
 * no address anybody could hold. A page given one of its old slugs back stops listing it
 * as old. Moving a page to the bin renames it too -- WordPress adds `__trashed` to free
 * the slug -- and that is not a change of address to remember.
 *
 * @param int     $post_id     The page.
 * @param WP_Post $post_after  It, as saved.
 * @param WP_Post $post_before It, as it was.
 */
function osous_remember_slug( $post_id, $post_after, $post_before ) {
	if ( 'page' !== $post_after->post_type || $post_after->post_name === $post_before->post_name
		|| 'trash' === $post_after->post_status ) {
		return;
	}

	$old = get_post_meta( $post_id, OSOUS_OLD_SLUG, false );

	if ( 'publish' === $post_before->post_status && '' !== $post_before->post_name
		&& ! in_array( $post_before->post_name, $old, true ) ) {
		add_post_meta( $post_id, OSOUS_OLD_SLUG, $post_before->post_name );
	}
	if ( in_array( $post_after->post_name, $old, true ) ) {
		delete_post_meta( $post_id, OSOUS_OLD_SLUG, $post_after->post_name );
	}

	osous_page_index( true );
}
add_action( 'post_updated', 'osous_remember_slug', 10, 3 );

/**
 * Every published page's address, and every address a page used to have.
 *
 * Two small reads -- three columns of the pages, and the old slugs -- kept for the request
 * and, where the site has an object cache, until a post next changes. A page with twenty
 * links on it asks this twenty times and the database twice.
 *
 * @param bool $reset Forget what is known; the next call reads again.
 * @return array{paths:array<string,int>,names:array<string,int>,uris:array<int,string>,old:array<string,int>}
 */
function osous_page_index( $reset = false ) {
	static $index = null;

	if ( $reset ) {
		$index = null;
		return array();
	}
	if ( null !== $index ) {
		return $index;
	}

	$key   = 'page_index:' . wp_cache_get_last_changed( 'posts' );
	$index = wp_cache_get( $key, 'osous' );
	if ( is_array( $index ) ) {
		return $index;
	}

	global $wpdb;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- three columns of every published page in one read, cached just below; WP_Query would load each page's whole content to answer it.
	$rows = $wpdb->get_results( "SELECT ID, post_name, post_parent FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status = 'publish' ORDER BY ID" );

	$by_id = array();
	foreach ( $rows as $row ) {
		$by_id[ (int) $row->ID ] = $row;
	}

	$index = array(
		'paths' => array(),
		'names' => array(),
		'uris'  => array(),
		'old'   => array(),
	);
	foreach ( $by_id as $id => $row ) {
		// The page's path is its slug under its parents', as WordPress builds a page's address.
		$uri    = $row->post_name;
		$parent = (int) $row->post_parent;
		$depth  = 0;
		while ( $parent && isset( $by_id[ $parent ] ) && $depth < 20 ) {
			$uri    = $by_id[ $parent ]->post_name . '/' . $uri;
			$parent = (int) $by_id[ $parent ]->post_parent;
			++$depth;
		}
		$index['uris'][ $id ]   = $uri;
		$index['paths'][ $uri ] = $id;
		if ( ! isset( $index['names'][ $row->post_name ] ) ) {
			$index['names'][ $row->post_name ] = $id;
		}
	}

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- the old slugs of the pages, one indexed read, cached with the rest.
	$old = $wpdb->get_results( $wpdb->prepare( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_id", OSOUS_OLD_SLUG ) );
	foreach ( $old as $row ) {
		// Only a page's, and the latest page to have used a slug wins it.
		if ( isset( $by_id[ (int) $row->post_id ] ) ) {
			$index['old'][ $row->meta_value ] = (int) $row->post_id;
		}
	}

	wp_cache_set( $key, $index, 'osous', DAY_IN_SECONDS );

	return $index;
}

/**
 * The page a path means: the one that is there now, or the one that used to be.
 *
 * In that order, so an address always belongs to the page that has it today. A page
 * moved under another parent is found by its own slug; a renamed one by the slugs it has
 * had.
 *
 * @param string $path A page's path, `about` or `solutions/backup`, without slashes round it.
 * @return int The page's id, or 0.
 */
function osous_page_id( $path ) {
	$path = trim( (string) $path, '/' );
	if ( '' === $path ) {
		return 0;
	}

	$index = osous_page_index();
	if ( isset( $index['paths'][ $path ] ) ) {
		return $index['paths'][ $path ];
	}

	$name = wp_basename( $path );
	if ( isset( $index['names'][ $name ] ) ) {
		return $index['names'][ $name ];
	}

	return isset( $index['old'][ $name ] ) ? $index['old'][ $name ] : 0;
}

/**
 * A page's address as it is today.
 *
 * Built the way WordPress builds a page's permalink -- its path under the site's address,
 * with the trailing slash the permalink settings ask for, and the site's address itself
 * for the front page -- from the index above, so printing a link costs no query.
 *
 * @param int $page_id The page.
 * @return string Its address, or '' for a page that is not published.
 */
function osous_page_url( $page_id ) {
	$page_id = (int) $page_id;
	$index   = osous_page_index();
	if ( ! isset( $index['uris'][ $page_id ] ) ) {
		return '';
	}
	if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) === $page_id ) {
		return home_url( '/' );
	}

	return home_url( user_trailingslashit( '/' . $index['uris'][ $page_id ], 'page' ) );
}

/**
 * Send an address that no longer exists on to the page that used to have it.
 *
 * WordPress does this for a post whose slug changed and, on purpose, not for a page. It
 * runs before core's own guess at what a 404 meant, which matches on the first letters
 * of a slug and would send `/dell-iraq/` nowhere once the page is called `dell`. A page
 * moved under another page is sent on the same way: its slug is what it was, and only
 * its address has changed.
 */
function osous_old_slug_redirect() {
	if ( ! is_404() ) {
		return;
	}

	global $wp;
	$request = isset( $wp->request ) ? trim( (string) $wp->request, '/' ) : '';
	if ( '' === $request ) {
		return;
	}

	/*
	 * A slug a page used to have, or -- for a page moved under another one, whose slug is
	 * the same and whose address is not -- the slug of a page that lives somewhere else.
	 */
	$index = osous_page_index();
	$name  = sanitize_title( wp_basename( $request ) );
	$page  = 0;
	if ( isset( $index['old'][ $name ] ) ) {
		$page = $index['old'][ $name ];
	} elseif ( isset( $index['names'][ $name ] ) ) {
		$page = $index['names'][ $name ];
	}

	$url = $page ? osous_page_url( $page ) : '';
	if ( '' !== $url ) {
		wp_safe_redirect( $url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'osous_old_slug_redirect', 9 );
