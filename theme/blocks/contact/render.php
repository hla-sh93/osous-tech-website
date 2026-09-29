<?php
/**
 * The contact section.
 *
 * The form itself is Contact Form 7, named by its shortcode; inc/contact.php makes the
 * plugin render as the design. Exactly one shortcode and nothing else is accepted, so
 * an author who can edit the page cannot slip markup past the template inside the box
 * that is meant to hold a plugin's placeholder.
 *
 * @package Osous
 */

$osous_id     = empty( $attributes['anchor'] ) ? '' : ' id="' . esc_attr( $attributes['anchor'] ) . '"';
$osous_phones = is_array( $attributes['phones'] ) ? $attributes['phones'] : array();
$osous_form   = trim( (string) $attributes['form'] );
?>
<section class="section contact"<?php echo $osous_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped where it is built, above. ?>>
	<div class="container-full c-surface-neutral">
		<div class="grid-2">
			<div class="container">
				<div class="f-v-l-c">
					<div class="heading">
						<h2 class="h2"><?php echo osous_inline( $attributes['title'] ); ?></h2>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="content">
						<h3 class="h3"><?php echo osous_inline( $attributes['subtitle'] ); ?></h3>
						<div class="call-us">
							<?php foreach ( $osous_phones as $osous_phone ) : ?>
								<div class="t-m-lg"><?php echo osous_inline( isset( $osous_phone['label'] ) ? $osous_phone['label'] : '' ); ?></div>
								<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', isset( $osous_phone['number'] ) ? $osous_phone['number'] : '' ) ); ?>"><span class="t-b-lg link"><?php echo esc_html( isset( $osous_phone['number'] ) ? $osous_phone['number'] : '' ); ?></span></a>
							<?php endforeach; ?>
							<div class="span-2"></div>
							<div class="t-m-lg"><?php echo osous_inline( $attributes['emailLabel'] ); ?></div>
							<a href="mailto:<?php echo esc_attr( $attributes['email'] ); ?>"><span class="t-b-lg link reveal-left"><?php echo esc_html( $attributes['email'] ); ?></span></a>
						</div>
						<div class="spacer-3x"></div>
					</div>
				</div>
			</div>
			<div class="modal form-panel">
				<div class="heading">
					<h3 class="h3 c-on-primary a-c"><?php echo osous_inline( $attributes['formTitle'] ); ?></h3>
					<div class="t-r-lg c-on-primary-subtle a-c"><?php echo osous_inline( $attributes['formText'] ); ?></div>
				</div>
				<div class="form-block">
					<?php
					if ( '' !== $osous_form && preg_match( '/^' . get_shortcode_regex() . '$/s', $osous_form ) ) {
						echo do_shortcode( $osous_form ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the plugin's own markup, printed as core's shortcode block prints it.
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
