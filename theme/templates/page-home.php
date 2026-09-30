<?php
/**
 * Template Name: Home page
 * Template Post Type: page
 *
 * The front page: the hero with the partner marks, the company in a few lines, the
 * services, the card stack, the industries and a call to action.
 *
 * The usual order of its sections, as the home page has them:
 * Hero · Text pair · Columns · Card stack · Industries carousel · Call to action.
 *
 * The sections are the page's own blocks, in the order the editor holds them: this file is
 * the kind's frame -- the header, the page's blocks, the footer -- and the place for
 * anything every page of this kind must carry. A new page of this kind starts from
 * patterns/starters/home.php. A page keeps this template whatever its slug becomes;
 * WordPress stores the choice on the page itself.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'includes/loops/content', 'page' );
get_footer();
