<?php
/**
 * A page with no kind: the default template.
 *
 * The kinds of page have a template each under templates/, chosen when the page is made
 * and shown in the editor's Template panel. There is deliberately no template per page,
 * and none chosen by a page's slug: a page that needs something new needs a new section,
 * not a new file -- that is the difference between a theme and the 57 hand-maintained
 * pages this replaces.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'includes/loops/content', 'page' );
get_footer();
