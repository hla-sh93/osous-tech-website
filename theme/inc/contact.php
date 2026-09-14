<?php
/**
 * The contact form: Contact Form 7, inside a section.
 *
 * The reference page was cloned from the live site, and the live site's form is Contact
 * Form 7 with Simple Cloudflare Turnstile. What the design shows is CF7's own rendering
 * with the plugin's scaffolding stripped -- the same field names, the same `<p>` and
 * `<br>` shape CF7's auto-paragraphing produces, CF7's default `aria-label` on the form,
 * and a `.cf7-cf-turnstile` box where the widget mounts. So WordPress renders the real
 * thing: the page's section carries a shortcode node (see inc/sections.php), and this
 * file makes CF7's rendering come out as the design, and no heavier than it needs to be.
 *
 * What CF7 adds around the design -- the wrapper, the hidden fields, the control wraps --
 * is listed by name in _tools/verify_wp.py, which takes it away again before comparing
 * the page to the reference.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Does a page's section data carry a Contact Form 7 shortcode?
 *
 * @param int $post_id Page ID. Defaults to the queried page.
 * @return bool
 */
function osous_page_has_contact_form( $post_id = 0 ) {
	$found = false;

	$scan = function ( $items ) use ( &$scan, &$found ) {
		foreach ( (array) $items as $node ) {
			if ( ! is_array( $node ) || $found ) {
				continue;
			}
			if ( 'sc' === ( $node['t'] ?? '' ) && 0 === strpos( trim( (string) ( $node['x'] ?? '' ) ), '[contact-form-7' ) ) {
				$found = true;
			}
			if ( isset( $node['k'] ) ) {
				$scan( $node['k'] );
			}
		}
	};
	$scan( osous_page_sections( $post_id ) );

	return $found;
}

/*
 * CF7 puts its script and stylesheet on every page of the site by default. Only the page
 * with the form gets them here, which leaves the other 56 pages' <head> exactly as the
 * reference has it.
 */
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

/**
 * CF7's stylesheet and the theme's one rule on top of it.
 *
 * The stylesheet is CF7's rather than a copy: it is what the live site loads, so the
 * validation tips and the sent/failed messages look there as they look here.
 *
 * CF7 loads `includes/controller.php` -- where `wpcf7_enqueue_styles()` lives, and where
 * its handle is registered on `wp_enqueue_scripts` -- only when `is_admin()` is false. In
 * the admin, and so while the editor canvas collects its assets, neither the function nor
 * the registration exists. So the handle is registered here when it is not yet, from the
 * same file CF7 would have named, and enqueued by name; CF7's own function is called where
 * it exists, for the RTL sheet and the action it fires.
 */
function osous_contact_form_styles() {
	if ( ! wp_style_is( 'contact-form-7', 'registered' ) ) {
		wp_register_style(
			'contact-form-7',
			wpcf7_plugin_url( 'includes/css/styles.css' ),
			array(),
			WPCF7_VERSION
		);
	}
	if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
		wpcf7_enqueue_styles();
	} else {
		wp_enqueue_style( 'contact-form-7' );
	}

	// After CF7's sheet, so its one rule wins: see assets/css/contact-form.css.
	wp_enqueue_style(
		'osous-contact-form',
		get_theme_file_uri( 'assets/css/contact-form.css' ),
		array( 'contact-form-7' ),
		osous_asset_version( 'assets/css/contact-form.css' )
	);
}

/**
 * CF7's script and both stylesheets, on the page that has the form.
 */
function osous_enqueue_contact_form() {
	if ( ! function_exists( 'wpcf7_enqueue_scripts' ) || ! osous_page_has_contact_form() ) {
		return;
	}
	wpcf7_enqueue_scripts();
	osous_contact_form_styles();
}
add_action( 'wp_enqueue_scripts', 'osous_enqueue_contact_form', 20 );

/**
 * The same two stylesheets inside the editor canvas.
 *
 * The canvas renders the form through the same shortcode, so without CF7's sheet it paints
 * CF7's scaffolding -- the fieldset of hidden fields as a bordered box, the empty response
 * box with a border -- and shows an editor a form the page never has. `enqueue_block_assets`
 * is the hook that runs inside the iframe; on the front end the function above has already
 * done this for the one page that needs it.
 */
function osous_enqueue_contact_form_editor() {
	if ( ! is_admin() || ! defined( 'WPCF7_VERSION' ) ) {
		return;
	}
	osous_contact_form_styles();
}
add_action( 'enqueue_block_assets', 'osous_enqueue_contact_form_editor' );

/**
 * The `aria-label` and `required` each control carries in the design.
 *
 * CF7 has no form-tag option for either: it marks a required field with `aria-required`
 * and validates it itself, and it names a field only by its placeholder. The design's
 * fields have no visible label, so the spoken one is the only label they have. Adding it
 * here keeps that, and `required` beside `aria-required` says the same thing twice in the
 * two vocabularies -- with `novalidate` on the form, the browser does nothing with it.
 *
 * @param string $html The form's inner markup, tags already rendered.
 * @return string
 */
function osous_contact_form_labels( $html ) {
	$labels = array(
		'contact-name'    => __( 'Your name', 'osous' ),
		'contact-company' => __( 'Company', 'osous' ),
		'contact-email'   => __( 'Email address', 'osous' ),
		'contact-mobile'  => __( 'Mobile number', 'osous' ),
		'contact-message' => __( 'Your message', 'osous' ),
	);

	foreach ( $labels as $name => $label ) {
		$html = str_replace(
			' name="' . $name . '"',
			' aria-label="' . esc_attr( $label ) . '" name="' . $name . '"',
			$html
		);
	}

	return str_replace( ' aria-required="true"', ' aria-required="true" required', $html );
}
add_filter( 'wpcf7_form_elements', 'osous_contact_form_labels' );

/**
 * `[_osous_email]` in a mail template: the address in Customize → Site details.
 *
 * So the person who changes the email address on the site changes where the form's mail
 * goes at the same time, instead of remembering a second place.
 *
 * @param string $output   What the tag becomes.
 * @param string $name     Tag name, e.g. `_site_title`.
 * @param bool   $html     Whether the template is HTML.
 * @param mixed  $mail_tag The mail tag object.
 * @return string
 */
function osous_contact_form_recipient( $output, $name, $html, $mail_tag = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter -- the filter's signature.
	if ( '_osous_email' === $name ) {
		return osous_setting( 'email' );
	}

	return $output;
}
add_filter( 'wpcf7_special_mail_tags', 'osous_contact_form_recipient', 10, 4 );
