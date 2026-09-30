<?php
/**
 * The pattern library: the sections, and the starters a new page begins from.
 *
 * WordPress registers the files directly under patterns/ by itself and looks no deeper.
 * This theme keeps its patterns one level down -- patterns/sections/ for the sections,
 * patterns/starters/ for whole pages -- so that a reader can tell one from the other, and
 * registers them the way core does: the header of each file says what it is, and the
 * file itself is the content, read when the editor asks for it.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The header fields a pattern file may carry, named as core names them.
 */
const OSOUS_PATTERN_HEADERS = array(
	'title'         => 'Title',
	'slug'          => 'Slug',
	'description'   => 'Description',
	'viewportWidth' => 'Viewport Width',
	'inserter'      => 'Inserter',
	'categories'    => 'Categories',
	'keywords'      => 'Keywords',
	'blockTypes'    => 'Block Types',
	'postTypes'     => 'Post Types',
	'templateTypes' => 'Template Types',
);

/**
 * The groups under patterns/ that hold patterns.
 */
const OSOUS_PATTERN_GROUPS = array( 'sections', 'starters' );

/**
 * Every pattern file and what its header says, keyed by path.
 *
 * Cached per theme version, as core caches its own scan, and read fresh while the
 * development mode is `theme` -- the mode the local site runs in, and the reason a
 * pattern added here appears without anybody clearing anything.
 *
 * @return array<string,array<string,string>>
 */
function osous_pattern_files() {
	$key    = 'osous_patterns_' . OSOUS_VERSION;
	$cached = wp_is_development_mode( 'theme' ) ? false : get_transient( $key );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$found = array();
	foreach ( OSOUS_PATTERN_GROUPS as $group ) {
		foreach ( glob( OSOUS_DIR . '/patterns/' . $group . '/*.php' ) as $file ) {
			$data = get_file_data( $file, OSOUS_PATTERN_HEADERS, 'osous_pattern' );
			if ( '' === $data['slug'] || '' === $data['title'] ) {
				continue;
			}
			$found[ $file ] = $data;
		}
	}

	if ( ! wp_is_development_mode( 'theme' ) ) {
		set_transient( $key, $found, DAY_IN_SECONDS );
	}

	return $found;
}

/**
 * Register the library.
 *
 * The lists -- categories, keywords, the block and post types a starter is for -- are
 * comma separated in the header, as core reads them; `Inserter: no` keeps a pattern out
 * of the inserter without unregistering it, which is what a one-off section wants.
 */
function osous_register_patterns() {
	foreach ( osous_pattern_files() as $file => $data ) {
		/*
		 * The title and the description go through gettext under the contexts core uses
		 * for a theme's own patterns/ folder. `wp i18n make-pot` reads these headers under
		 * the same two contexts, so a translation of the theme translates its library.
		 */
		$pattern = array(
			// phpcs:ignore WordPress.WP.I18n.LowLevelTranslationFunction,WordPress.WP.I18n.NonSingularStringLiteralText -- the text is a file header, extracted by make-pot from the file itself; core translates its own patterns/ folder with this same call.
			'title'    => translate_with_gettext_context( $data['title'], 'Pattern title', 'osous' ),
			'filePath' => $file,
		);
		if ( '' !== $data['description'] ) {
			// phpcs:ignore WordPress.WP.I18n.LowLevelTranslationFunction,WordPress.WP.I18n.NonSingularStringLiteralText -- as above.
			$pattern['description'] = translate_with_gettext_context( $data['description'], 'Pattern description', 'osous' );
		}
		if ( '' !== $data['viewportWidth'] ) {
			$pattern['viewportWidth'] = (int) $data['viewportWidth'];
		}
		if ( '' !== $data['inserter'] ) {
			$pattern['inserter'] = in_array( strtolower( $data['inserter'] ), array( 'yes', 'true' ), true );
		}
		foreach ( array( 'categories', 'keywords', 'blockTypes', 'postTypes', 'templateTypes' ) as $list ) {
			if ( '' !== $data[ $list ] ) {
				$pattern[ $list ] = array_values( array_filter( array_map( 'trim', explode( ',', $data[ $list ] ) ) ) );
			}
		}
		register_block_pattern( $data['slug'], $pattern );
	}
}
add_action( 'init', 'osous_register_patterns' );
