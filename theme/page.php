<?php
/**
 * Static page.
 *
 * Every page uses this one template. Phase 3 replaces the_content() with the section
 * loop; until then it renders whatever the editor holds, which is enough to prove the
 * chrome and the asset pipeline.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
