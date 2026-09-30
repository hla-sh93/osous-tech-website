<?php
/**
 * Template Name: Solution page
 * Template Post Type: page
 *
 * One solution: what it is, what it covers as cards, why this company, a pinned list of
 * points and a call to action.
 *
 * The usual order of its sections, as disaster-recovery-iraq has them (each of the 27 solution pages has its own):
 * Page header · Columns · Cards · Columns · Highlight cards · Pinned list · Call to action.
 *
 * The sections are the page's own blocks, in the order the editor holds them: this file is
 * the kind's frame -- the header, the page's blocks, the footer -- and the place for
 * anything every page of this kind must carry. A new page of this kind starts from
 * patterns/starters/solution.php. A page keeps this template whatever its slug becomes;
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
