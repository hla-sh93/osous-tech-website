<?php
/**
 * Contact Form 7, made to render as the design.
 *
 * The form on the contact page is a Contact Form 7 form called "Contact", printed by
 * includes/sections/contact.php. The plugin's assets load on the page that prints the
 * section and nowhere else; its fields get the design's labels; its mail goes to the
 * address in Site details.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The plugin's stylesheet, and the design's one rule after it.
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
 * The plugin's scripts and styles, when the contact section is printed.
 *
 * Called from includes/sections/contact.php, so a page gets them because it holds the
 * form. Enqueued while the body is being printed, they land in the footer.
 */
function osous_enqueue_contact_form() {
	if ( ! function_exists( 'wpcf7_enqueue_scripts' ) ) {
		return;
	}
	wpcf7_enqueue_scripts();
	osous_contact_form_styles();
}

/**
 * The design's labels on the plugin's fields, for people who cannot see the placeholders.
 *
 * @param string $html The form's fields.
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
 * `[_osous_email]` in the form's mail settings: the address in Site details.
 *
 * @param string $output   The tag's value so far.
 * @param string $name     The tag's name.
 * @param string $html     Whether HTML is wanted.
 * @param mixed  $mail_tag The tag object.
 * @return string
 */
function osous_contact_form_recipient( $output, $name, $html, $mail_tag = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter -- the filter's signature.
	if ( '_osous_email' === $name ) {
		return osous_setting( 'email' );
	}

	return $output;
}
add_filter( 'wpcf7_special_mail_tags', 'osous_contact_form_recipient', 10, 4 );
