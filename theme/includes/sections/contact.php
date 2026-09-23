<?php
/**
 * The contact section: the ways to reach the company, and the enquiry form.
 *
 * The form is Contact Form 7's, rendered as the design; functions/contact.php makes it
 * so. The form is named by its shortcode, so it has to exist under that name.
 *
 * Called as: get_template_part( 'includes/sections/contact', null, array( 'title' => …, 'phones' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title      The heading.
 *     @type string $subtitle   The heading over the phone numbers.
 *     @type array  $phones     The numbers, each a label and a number.
 *     @type string $emailLabel The label over the email address.
 *     @type string $email      The email address.
 *     @type string $formTitle  The heading over the form.
 *     @type string $formText   The line under it.
 *     @type string $form       The form's shortcode.
 *     @type string $anchor     The section's id; empty for none.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title'      => '',
		'subtitle'   => '',
		'phones'     => array(),
		'emailLabel' => __( 'Email', 'osous' ),
		'email'      => '',
		'formTitle'  => '',
		'formText'   => '',
		'form'       => '[contact-form-7 title="Contact" html_class="contact-form"]',
		'anchor'     => '',
	)
);

$osous_form = trim( (string) $osous_args['form'] );

osous_enqueue_contact_form();
?>
<section class="section contact"<?php echo '' === $osous_args['anchor'] ? '' : ' id="' . esc_attr( $osous_args['anchor'] ) . '"'; ?>>
	<div class="container-full c-surface-neutral">
		<div class="grid-2">
			<div class="container">
				<div class="f-v-l-c">
					<div class="heading">
						<h2 class="h2"><?php echo osous_inline( $osous_args['title'] ); ?></h2>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="content">
						<h3 class="h3"><?php echo osous_inline( $osous_args['subtitle'] ); ?></h3>
						<div class="call-us">
							<?php foreach ( (array) $osous_args['phones'] as $osous_phone ) : ?>
								<div class="t-m-lg"><?php echo osous_inline( isset( $osous_phone['label'] ) ? $osous_phone['label'] : '' ); ?></div>
								<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', isset( $osous_phone['number'] ) ? $osous_phone['number'] : '' ) ); ?>"><span class="t-b-lg link"><?php echo esc_html( isset( $osous_phone['number'] ) ? $osous_phone['number'] : '' ); ?></span></a>
							<?php endforeach; ?>
							<div class="span-2"></div>
							<div class="t-m-lg"><?php echo osous_inline( $osous_args['emailLabel'] ); ?></div>
							<a href="mailto:<?php echo esc_attr( $osous_args['email'] ); ?>"><span class="t-b-lg link reveal-left"><?php echo esc_html( $osous_args['email'] ); ?></span></a>
						</div>
						<div class="spacer-3x"></div>
					</div>
				</div>
			</div>
			<div class="modal form-panel">
				<div class="heading">
					<h3 class="h3 c-on-primary a-c"><?php echo osous_inline( $osous_args['formTitle'] ); ?></h3>
					<div class="t-r-lg c-on-primary-subtle a-c"><?php echo osous_inline( $osous_args['formText'] ); ?></div>
				</div>
				<div class="form-block">
					<?php
					if ( '' !== $osous_form && preg_match( '/^' . get_shortcode_regex() . '$/s', $osous_form ) ) {
						echo do_shortcode( $osous_form ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the plugin's own markup, as core's shortcode block prints it.
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
