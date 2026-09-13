<?php
/**
 * The details that are not page content.
 *
 * Two office addresses, four phone numbers, an email, three social links, a map and the
 * WhatsApp number. They appear on every page, belong to nobody's page in particular, and
 * until now lived in footer.php where only somebody editing the theme could reach them.
 *
 * Everything is declared once in `osous_settings()`: the Customizer panel is built from
 * that table, and so are the defaults. Adding a field means adding a row, not touching
 * four places.
 *
 * Defaults are today's values, so the site looks identical until somebody changes
 * something — which is also what lets `_tools/verify_chrome.py` keep checking the footer
 * against the static reference.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Every editable site detail: where it belongs, what it is, and what it is today.
 *
 * @return array<string,array<string,string>>
 */
function osous_settings() {
	return array(

		'email'        => array(
			'section' => 'contact',
			'label'   => __( 'Email address', 'osous' ),
			'type'    => 'email',
			'default' => 'info@osoustaqnia.com',
		),
		'whatsapp'     => array(
			'section' => 'contact',
			'label'   => __( 'WhatsApp number', 'osous' ),
			'type'    => 'text',
			'help'    => __( 'In full international form. The floating badge links to it.', 'osous' ),
			'default' => '+9647870606069',
		),

		'uae_address'  => array(
			'section' => 'offices',
			'label'   => __( 'UAE address', 'osous' ),
			'type'    => 'textarea',
			'help'    => __( 'One line per line.', 'osous' ),
			'default' => '6th Floor, The Meydan Hotel, Nad Al Sheba, Dubai',
		),
		'uae_phone'    => array(
			'section' => 'offices',
			'label'   => __( 'UAE phone', 'osous' ),
			'type'    => 'text',
			'default' => '+971 58 532 5606',
		),
		'iraq_address' => array(
			'section' => 'offices',
			'label'   => __( 'Iraq address', 'osous' ),
			'type'    => 'textarea',
			'default' => "Villa S 11/5, Atconz, Erbil \n62nd St, Baghdad",
		),
		'iraq_phone_1' => array(
			'section' => 'offices',
			'label'   => __( 'Iraq phone', 'osous' ),
			'type'    => 'text',
			'default' => '+964 750 841 7028',
		),
		'iraq_phone_2' => array(
			'section' => 'offices',
			'label'   => __( 'Iraq phone, second', 'osous' ),
			'type'    => 'text',
			'help'    => __( 'Leave empty to show only one.', 'osous' ),
			'default' => '+964 780 445 5104',
		),
		'map_embed'    => array(
			'section' => 'offices',
			'label'   => __( 'Map embed URL', 'osous' ),
			'type'    => 'url',
			'help'    => __( 'From Google Maps: Share → Embed a map → copy the src of the iframe.', 'osous' ),
			'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3217.6770720498216!2d44.022947900000005!3d36.24733779999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40071f6fc027b687%3A0x2a5846c52d553dfc!2sOSOUS%20AL%20TAQNIA!5e0!3m2!1sen!2siq!4v1769344992153!5m2!1sen!2siq',
		),

		'facebook'     => array(
			'section' => 'social',
			'label'   => __( 'Facebook', 'osous' ),
			'type'    => 'url',
			'default' => 'https://www.facebook.com/profile.php?id=61582436337354',
		),
		'linkedin'     => array(
			'section' => 'social',
			'label'   => __( 'LinkedIn', 'osous' ),
			'type'    => 'url',
			'default' => 'https://www.linkedin.com/company/osous-al-taqnia/',
		),
		'instagram'    => array(
			'section' => 'social',
			'label'   => __( 'Instagram', 'osous' ),
			'type'    => 'url',
			'help'    => __( 'No account yet; the link points nowhere until there is one.', 'osous' ),
			'default' => '#',
		),

		'credit_name'  => array(
			'section' => 'credit',
			'label'   => __( 'Developed by', 'osous' ),
			'type'    => 'text',
			'default' => 'Osous Technology',
		),
		'credit_url'   => array(
			'section' => 'credit',
			'label'   => __( 'Developed by, link', 'osous' ),
			'type'    => 'url',
			'default' => 'https://www.osoustech.com/',
		),
	);
}

/**
 * One site detail, as it should be printed.
 *
 * @param string $key Key from osous_settings().
 * @return string
 */
function osous_setting( $key ) {
	$settings = osous_settings();
	if ( ! isset( $settings[ $key ] ) ) {
		return '';
	}

	return (string) get_theme_mod( 'osous_' . $key, $settings[ $key ]['default'] );
}

/**
 * A phone number as a `tel:` link.
 *
 * The number is written for people to read — `+964 750 841 7028` — and dialled without
 * the spaces, so one setting serves both rather than asking anyone to keep two in step.
 *
 * @param string $number Displayed number.
 * @return string
 */
function osous_tel( $number ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $number );
}

/**
 * An address, with its line breaks.
 *
 * The trailing `<br>` is in the original markup and holds the paragraph's height; it is
 * not decoration and removing it moves the footer by a line.
 *
 * @param string $key Key from osous_settings().
 * @return string Escaped markup.
 */
function osous_address( $key ) {
	$lines = preg_split( '/\R/', osous_setting( $key ) );
	$lines = array_map( 'esc_html', $lines );

	return implode( '<br>', $lines ) . '<br>';
}

/**
 * The Customizer panel, built from the table above.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function osous_customize_register( $wp_customize ) {
	$sections = array(
		'contact' => array( __( 'Contact', 'osous' ), __( 'The email address and the WhatsApp number the floating badge links to.', 'osous' ) ),
		'offices' => array( __( 'Offices', 'osous' ), __( 'The addresses, the phone numbers and the map in the footer.', 'osous' ) ),
		'social'  => array( __( 'Social links', 'osous' ), __( 'Where the three icons in the footer point.', 'osous' ) ),
		'credit'  => array( __( 'Credit', 'osous' ), __( 'The "Developed by" line at the foot of every page.', 'osous' ) ),
	);

	$wp_customize->add_panel(
		'osous',
		array(
			'title'       => __( 'Site details', 'osous' ),
			'description' => __( 'The parts of every page that are not page content.', 'osous' ),
			'priority'    => 20,
		)
	);

	$order = 10;
	foreach ( $sections as $id => $section ) {
		$wp_customize->add_section(
			'osous_' . $id,
			array(
				'title'       => $section[0],
				'description' => $section[1],
				'panel'       => 'osous',
				'priority'    => $order,
			)
		);
		$order += 10;
	}

	$sanitisers = array(
		'url'      => 'esc_url_raw',
		'email'    => 'sanitize_email',
		'textarea' => 'sanitize_textarea_field',
		'text'     => 'sanitize_text_field',
	);

	foreach ( osous_settings() as $key => $field ) {
		$type = $field['type'];

		$wp_customize->add_setting(
			'osous_' . $key,
			array(
				'default'           => $field['default'],
				'type'              => 'theme_mod',
				'transport'         => 'refresh',
				'sanitize_callback' => $sanitisers[ $type ],
			)
		);

		$wp_customize->add_control(
			'osous_' . $key,
			array(
				'section'     => 'osous_' . $field['section'],
				'label'       => $field['label'],
				'description' => isset( $field['help'] ) ? $field['help'] : '',
				'type'        => 'textarea' === $type ? 'textarea' : $type,
			)
		);
	}
}
add_action( 'customize_register', 'osous_customize_register' );
