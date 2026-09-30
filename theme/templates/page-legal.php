<?php
/**
 * Template Name: Legal page
 * Template Post Type: page
 *
 * A page of plain text under a page header: a policy, the terms.
 *
 * The usual order of its sections, as 2 of the 2 legal pages have them:
 * Page header · Section · Rich text.
 *
 * The sections are the page's own blocks, in the order the editor holds them: this file is
 * the kind's frame -- the header, the page's blocks, the footer -- and the place for
 * anything every page of this kind must carry. A new page of this kind starts from
 * patterns/starters/legal.php. A page keeps this template whatever its slug becomes;
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
