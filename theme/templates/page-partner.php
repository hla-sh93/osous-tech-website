<?php
/**
 * Template Name: Partner page
 * Template Post Type: page
 *
 * One technology partner: the partnership, what is offered as numbered cards, the
 * solutions as cards, a pinned list of reasons and a call to action.
 *
 * The usual order of its sections, as 15 of the 19 partner pages have them:
 * Page header · Columns · Columns · Rich text · Cards · Pinned list · Call to action.
 *
 * The sections are the page's own blocks, in the order the editor holds them: this file is
 * the kind's frame -- the header, the page's blocks, the footer -- and the place for
 * anything every page of this kind must carry. A new page of this kind starts from
 * patterns/starters/partner.php. A page keeps this template whatever its slug becomes;
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
