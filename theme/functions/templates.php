<?php
/**
 * The kinds of page, and the template each one is.
 *
 * A kind is three things with one name: a template under templates/, which the editor's
 * Template panel offers; a starter under patterns/starters/, which a new page of that kind
 * begins from; and a title. WordPress stores the template on the page itself, as its
 * page-template meta, so a page keeps its kind whatever its slug becomes and nothing here
 * ever looks a page up by name.
 *
 * All the templates print the same frame -- the header, the page's blocks, the footer --
 * because what makes a partner page a partner page is its sections, and those are the
 * page's own content. The template is the kind's name inside WordPress, what its starter
 * hangs from, and where a part that every page of the kind must carry would go.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The kinds, in the order the editor offers them.
 *
 * `title` is also the `Template Name` of the kind's file and `description` the line the
 * editor shows under it; _tools/kinds.py keeps the same table for the migration, and
 * _tools/verify_templates.py holds the three together.
 *
 * @return array<string,array{title:string,description:string,template:string,starter:string}>
 */
function osous_page_kinds() {
	return array(
		'home'     => array(
			'title'       => __( 'Home page', 'osous' ),
			'description' => __( 'The front page: the hero with the partner marks, the company in a few lines, the services, the card stack, the industries and a call to action.', 'osous' ),
			'template'    => 'templates/page-home.php',
			'starter'     => 'osous/starter-home',
		),
		'about'    => array(
			'title'       => __( 'About page', 'osous' ),
			'description' => __( 'The company: who it is, its mission and values, its numbers, its partners and its certifications.', 'osous' ),
			'template'    => 'templates/page-about.php',
			'starter'     => 'osous/starter-about',
		),
		'solution' => array(
			'title'       => __( 'Solution page', 'osous' ),
			'description' => __( 'One solution: what it is, what it covers as cards, why this company, a pinned list of points and a call to action.', 'osous' ),
			'template'    => 'templates/page-solution.php',
			'starter'     => 'osous/starter-solution',
		),
		'partner'  => array(
			'title'       => __( 'Partner page', 'osous' ),
			'description' => __( 'One technology partner: the partnership, what is offered as numbered cards, the solutions as cards, a pinned list of reasons and a call to action.', 'osous' ),
			'template'    => 'templates/page-partner.php',
			'starter'     => 'osous/starter-partner',
		),
		'service'  => array(
			'title'       => __( 'Service page', 'osous' ),
			'description' => __( 'One service: what it is, what it includes, how it is delivered and a call to action.', 'osous' ),
			'template'    => 'templates/page-service.php',
			'starter'     => 'osous/starter-service',
		),
		'contact'  => array(
			'title'       => __( 'Contact page', 'osous' ),
			'description' => __( 'The ways to reach the company, and the enquiry form.', 'osous' ),
			'template'    => 'templates/page-contact.php',
			'starter'     => 'osous/starter-contact',
		),
		'legal'    => array(
			'title'       => __( 'Legal page', 'osous' ),
			'description' => __( 'A page of plain text under a page header: a policy, the terms.', 'osous' ),
			'template'    => 'templates/page-legal.php',
			'starter'     => 'osous/starter-legal',
		),
	);
}

/**
 * The kind a page is, from the template WordPress holds for it.
 *
 * @param int|WP_Post|null $post The page; the current one when absent.
 * @return string The kind's key, or '' for a page on the default template.
 */
function osous_page_kind( $post = null ) {
	$template = get_page_template_slug( $post );
	if ( ! $template ) {
		return '';
	}
	foreach ( osous_page_kinds() as $key => $kind ) {
		if ( $kind['template'] === $template ) {
			return $key;
		}
	}

	return '';
}

/**
 * The kinds, as the editor needs them: a list, in order, each with its key.
 *
 * @return array<int,array<string,string>>
 */
function osous_page_kinds_for_editor() {
	$list = array();
	foreach ( osous_page_kinds() as $key => $kind ) {
		$list[] = array( 'key' => $key ) + $kind;
	}

	return $list;
}
