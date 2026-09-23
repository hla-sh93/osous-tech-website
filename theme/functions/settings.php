<?php
/**
 * The details that are not page content.
 *
 * The header's button, two office addresses, four phone numbers, an email, three social
 * links, a map, the WhatsApp number, the footer's headings, badges and copyright line,
 * and the credit. They appear on every page, belong to nobody's page in particular, and
 * until they lived here only somebody editing the theme could reach them.
 *
 * Everything is declared once in `osous_settings()`: the Customizer panel is built from
 * that table, and so are the defaults. Adding a field means adding a row, not touching
 * four places.
 *
 * Defaults are today's values, so the site looks identical until somebody changes
 * something — which is also what lets `_tools/verify_chrome.py` keep checking the header
 * and footer against the static reference, and `_tools/verify_settings.py` prove that
 * every field here reaches the page.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Every editable site detail: where it belongs, what it is, and what it is today.
 *
 * Types: `text`, `textarea`, `url`, `email`, `image` (a picture from the Media Library,
 * stored as its URL) and `page` (a page of this site, stored as its id).
 *
 * @return array<string,array<string,mixed>>
 */
function osous_settings() {
	return array(

		'button_label'     => array(
			'section' => 'header',
			'label'   => __( 'Button label', 'osous' ),
			'type'    => 'text',
			'help'    => __( 'The button at the right of the menu, and in the phone menu.', 'osous' ),
			'default' => __( 'Contact', 'osous' ),
		),
		'button_page'      => array(
			'section' => 'header',
			'label'   => __( 'Button goes to', 'osous' ),
			'type'    => 'page',
			'help'    => __( 'Leave unset for the page called "contact".', 'osous' ),
			'default' => 0,
		),

		'email'            => array(
			'section' => 'contact',
			'label'   => __( 'Email address', 'osous' ),
			'type'    => 'email',
			'default' => 'info@osoustaqnia.com',
		),
		'whatsapp'         => array(
			'section' => 'contact',
			'label'   => __( 'WhatsApp number', 'osous' ),
			'type'    => 'text',
			'help'    => __( 'In full international form. The floating badge links to it.', 'osous' ),
			'default' => '+9647870606069',
		),

		'uae_name'         => array(
			'section' => 'offices',
			'label'   => __( 'First office, name', 'osous' ),
			'type'    => 'text',
			'default' => __( 'UAE', 'osous' ),
		),
		'uae_address'      => array(
			'section' => 'offices',
			'label'   => __( 'First office, address', 'osous' ),
			'type'    => 'textarea',
			'help'    => __( 'One line per line.', 'osous' ),
			'default' => '6th Floor, The Meydan Hotel, Nad Al Sheba, Dubai',
		),
		'uae_phone'        => array(
			'section' => 'offices',
			'label'   => __( 'First office, phone', 'osous' ),
			'type'    => 'text',
			'default' => '+971 58 532 5606',
		),
		'iraq_name'        => array(
			'section' => 'offices',
			'label'   => __( 'Second office, name', 'osous' ),
			'type'    => 'text',
			'default' => __( 'IRAQ', 'osous' ),
		),
		'iraq_address'     => array(
			'section' => 'offices',
			'label'   => __( 'Second office, address', 'osous' ),
			'type'    => 'textarea',
			'default' => "Villa S 11/5, Atconz, Erbil \n62nd St, Baghdad",
		),
		'iraq_phone_1'     => array(
			'section' => 'offices',
			'label'   => __( 'Second office, phone', 'osous' ),
			'type'    => 'text',
			'default' => '+964 750 841 7028',
		),
		'iraq_phone_2'     => array(
			'section' => 'offices',
			'label'   => __( 'Second office, second phone', 'osous' ),
			'type'    => 'text',
			'help'    => __( 'Leave empty to show only one.', 'osous' ),
			'default' => '+964 780 445 5104',
		),
		'map_embed'        => array(
			'section' => 'offices',
			'label'   => __( 'Map embed URL', 'osous' ),
			'type'    => 'url',
			'help'    => __( 'From Google Maps: Share → Embed a map → copy the src of the iframe.', 'osous' ),
			'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3217.6770720498216!2d44.022947900000005!3d36.24733779999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40071f6fc027b687%3A0x2a5846c52d553dfc!2sOSOUS%20AL%20TAQNIA!5e0!3m2!1sen!2siq!4v1769344992153!5m2!1sen!2siq',
		),

		'facebook'         => array(
			'section' => 'social',
			'label'   => __( 'Facebook', 'osous' ),
			'type'    => 'url',
			'default' => 'https://www.facebook.com/profile.php?id=61582436337354',
		),
		'linkedin'         => array(
			'section' => 'social',
			'label'   => __( 'LinkedIn', 'osous' ),
			'type'    => 'url',
			'default' => 'https://www.linkedin.com/company/osous-al-taqnia/',
		),
		'instagram'        => array(
			'section' => 'social',
			'label'   => __( 'Instagram', 'osous' ),
			'type'    => 'url',
			'help'    => __( 'No account yet; the link points nowhere until there is one.', 'osous' ),
			'default' => '#',
		),

		'discover_heading' => array(
			'section' => 'footer',
			'label'   => __( 'Heading over the first column', 'osous' ),
			'type'    => 'text',
			'default' => __( 'Discover', 'osous' ),
		),
		'follow_heading'   => array(
			'section' => 'footer',
			'label'   => __( 'Heading over the social links', 'osous' ),
			'type'    => 'text',
			'default' => __( 'Follow us', 'osous' ),
		),
		'send_heading'     => array(
			'section' => 'footer',
			'label'   => __( 'Heading over the email address', 'osous' ),
			'type'    => 'text',
			'default' => __( 'Send us', 'osous' ),
		),
		'badge_1'          => array(
			'section' => 'footer',
			'label'   => __( 'First badge', 'osous' ),
			'type'    => 'image',
			'help'    => __( 'Remove it to show no badge there.', 'osous' ),
			'default' => get_theme_file_uri( 'assets/images/27001.webp' ),
		),
		'badge_1_alt'      => array(
			'section' => 'footer',
			'label'   => __( 'First badge, what it says', 'osous' ),
			'type'    => 'text',
			'default' => 'ISO 27001',
		),
		'badge_2'          => array(
			'section' => 'footer',
			'label'   => __( 'Second badge', 'osous' ),
			'type'    => 'image',
			'default' => get_theme_file_uri( 'assets/images/9001.webp' ),
		),
		'badge_2_alt'      => array(
			'section' => 'footer',
			'label'   => __( 'Second badge, what it says', 'osous' ),
			'type'    => 'text',
			'default' => 'ISO 9001',
		),
		'copyright'        => array(
			'section' => 'footer',
			'label'   => __( 'Copyright line', 'osous' ),
			'type'    => 'text',
			'help'    => __( '{year} becomes the current year.', 'osous' ),
			/* translators: {year} is replaced by the current year. */
			'default' => __( '© {year} Osous Al Taqnia. All rights reserved.', 'osous' ),
		),

		'credit_name'      => array(
			'section' => 'credit',
			'label'   => __( 'Developed by', 'osous' ),
			'type'    => 'text',
			'default' => 'Osous Technology',
		),
		'credit_url'       => array(
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
 * The copyright line, with the year filled in.
 *
 * @return string Escaped text.
 */
function osous_copyright() {
	return esc_html( str_replace( '{year}', gmdate( 'Y' ), osous_setting( 'copyright' ) ) );
}

/**
 * Print one of the footer's badges, or nothing when its picture has been removed.
 *
 * @param string $key   `badge_1` or `badge_2`.
 * @param string $slot_class The slot's class; the stylesheet sizes each slot.
 */
function osous_badge( $key, $slot_class ) {
	$src = osous_setting( $key );
	if ( '' === $src ) {
		return;
	}
	printf(
		'<img src="%s" alt="%s" class="%s">',
		esc_url( $src ),
		esc_attr( osous_setting( $key . '_alt' ) ),
		esc_attr( $slot_class )
	);
}

/**
 * The header's button: its label, where it goes, and which page that is.
 *
 * Unset, it goes to the page called "contact", as the design's did. Set to a page that
 * has since been deleted or unpublished, the same: a button that leads to a 404 is worse
 * than one that ignores a stale choice.
 *
 * @return array{label:string,url:string,id:int}
 */
function osous_header_button() {
	$id   = (int) osous_setting( 'button_page' );
	$page = $id ? get_post( $id ) : null;
	if ( ! $page || 'page' !== $page->post_type || 'publish' !== $page->post_status ) {
		$page = get_page_by_path( 'contact', OBJECT, 'page' );
	}

	return array(
		'label' => osous_setting( 'button_label' ),
		'url'   => $page ? get_permalink( $page ) : home_url( '/contact/' ),
		'id'    => $page ? (int) $page->ID : 0,
	);
}

/**
 * The Customizer panel, built from the table above.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function osous_customize_register( $wp_customize ) {
	$sections = array(
		'header'  => array( __( 'Header', 'osous' ), __( 'The button beside the menu. The logo is under Site Identity.', 'osous' ) ),
		'contact' => array( __( 'Contact', 'osous' ), __( 'The email address and the WhatsApp number the floating badge links to.', 'osous' ) ),
		'offices' => array( __( 'Offices', 'osous' ), __( 'The addresses, the phone numbers and the map in the footer.', 'osous' ) ),
		'social'  => array( __( 'Social links', 'osous' ), __( 'Where the three icons in the footer point.', 'osous' ) ),
		'footer'  => array( __( 'Footer', 'osous' ), __( 'The headings, the two badges and the copyright line.', 'osous' ) ),
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
		'image'    => 'esc_url_raw',
		'email'    => 'sanitize_email',
		'textarea' => 'sanitize_textarea_field',
		'text'     => 'sanitize_text_field',
		'page'     => 'absint',
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

		$args = array(
			'section'     => 'osous_' . $field['section'],
			'label'       => $field['label'],
			'description' => isset( $field['help'] ) ? $field['help'] : '',
		);

		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'osous_' . $key, $args ) );
			continue;
		}

		$args['type'] = 'page' === $type ? 'dropdown-pages' : $type;
		$wp_customize->add_control( 'osous_' . $key, $args );
	}
}
add_action( 'customize_register', 'osous_customize_register' );
