<?php
/**
 * Template Name: About page
 * Template Post Type: page
 *
 * The company: who it is, its mission and values, its numbers, its partners and its
 * certifications.
 *
 * The usual order of its sections, as the about page has them:
 * Page header · Columns · Divider · Columns · Numbered cards · Bullet list · Partner logos · Cards · Bullet list.
 *
 * The sections are the page's own blocks, in the order the editor holds them: this file is
 * the kind's frame -- the header, the page's blocks, the footer -- and the place for
 * anything every page of this kind must carry. A new page of this kind starts from
 * patterns/starters/about.php. A page keeps this template whatever its slug becomes;
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
