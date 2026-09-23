<?php
/**
 * One file per page.
 *
 * A page whose slug has a file under pages/ is that file: pages/about.php is the About
 * page, pages/index.php the home page. The file holds the page's sections and its
 * words; the header, the footer and the design's components come from includes/. A
 * page with no file falls through to page.php and shows what was written in the
 * editor, which is how a new page starts until it is given a file.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The page's own file, when it has one.
 *
 * @param string $template The template WordPress chose.
 * @return string
 */
function osous_page_file( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$file = $slug ? locate_template( 'pages/' . $slug . '.php' ) : '';

	return $file ? $file : $template;
}
add_filter( 'template_include', 'osous_page_file' );
