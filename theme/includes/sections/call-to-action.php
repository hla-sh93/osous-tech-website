<?php
/**
 * A white panel with a heading, a line of text and an outlined button.
 *
 * Called as: get_template_part( 'includes/sections/call-to-action', null, array( 'title' => …, … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title      The heading.
 *     @type string $text       The line of text.
 *     @type string $buttonText The button's label.
 *     @type string $buttonUrl  Where the button goes; the contact page unless said otherwise.
 *     @type string $anchor     The section's id, for links to it; empty for none.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title'      => '',
		'text'       => '',
		'buttonText' => '',
		'buttonUrl'  => 'contact.html',
		'anchor'     => '',
	)
);
?>
<section class="section top-tight"<?php echo '' === $osous_args['anchor'] ? '' : ' id="' . esc_attr( $osous_args['anchor'] ) . '"'; ?>>
	<div class="container c-surface-white c-border-neutral">
		<div class="heading center">
			<h2 class="h2 a-c"><?php echo osous_inline( $osous_args['title'] ); ?></h2>
			<p class="p a-c"><?php echo osous_inline( $osous_args['text'] ); ?></p>
			<div class="spacer-3x"></div>
			<?php osous_button( $osous_args['buttonText'], $osous_args['buttonUrl'], 'outline' ); ?>
		</div>
	</div>
</section>
