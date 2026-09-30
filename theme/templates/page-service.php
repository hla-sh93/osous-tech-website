<?php
/**
 * Template Name: Service page
 * Template Post Type: page
 *
 * One service: what it is, what it includes, how it is delivered and a call to action.
 *
 * The usual order of its sections, as managed-it-services-iraq has them (each of the 5 service pages has its own):
 * Page header · Columns · Numbered cards · Columns · Pinned list · Call to action.
 *
 * The sections are the page's own blocks, in the order the editor holds them: this file is
 * the kind's frame -- the header, the page's blocks, the footer -- and the place for
 * anything every page of this kind must carry. A new page of this kind starts from
 * patterns/starters/service.php. A page keeps this template whatever its slug becomes;
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
