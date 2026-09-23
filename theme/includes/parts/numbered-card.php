<?php
/**
 * A purple card: a number, a small icon, a title, a line of text, and after the fill a
 * box of prose -- paragraphs, a list -- then a button.
 *
 * Called as: get_template_part( 'includes/parts/numbered-card', null, array( 'number' => '01', … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $number     The number in the badge; empty for no badge.
 *     @type array  $icon       The small icon: src, alt.
 *     @type bool   $fill       Draw the fill.
 *     @type string $title      The title.
 *     @type string $text       A line of text.
 *     @type string $prose      Paragraphs and lists, as HTML.
 *     @type string $buttonText The button's label; empty for no button.
 *     @type string $buttonUrl  Where the button goes.
 *     @type bool   $outline    Draw the outline.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'number'     => '',
		'icon'       => array(),
		'fill'       => true,
		'title'      => '',
		'text'       => '',
		'prose'      => '',
		'buttonText' => '',
		'buttonUrl'  => '#',
		'outline'    => false,
	)
);
?>
<div class="card-2">
	<?php osous_number( $osous_args['number'] ); ?>
	<?php osous_image( (array) $osous_args['icon'], 'icon-60' ); ?>
	<?php if ( '' !== $osous_args['title'] ) : ?>
		<h3 class="h3 c-on-primary"><?php echo osous_inline( $osous_args['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( '' !== $osous_args['text'] ) : ?>
		<p class="p c-on-primary-subtle"><?php echo osous_inline( $osous_args['text'] ); ?></p>
	<?php endif; ?>
	<?php if ( $osous_args['fill'] ) : ?>
		<div class="card2-background"></div>
	<?php endif; ?>
	<?php if ( osous_has_prose( $osous_args['prose'] ) ) : ?>
		<div class="rich-text-block"><?php osous_prose( $osous_args['prose'] ); ?></div>
	<?php endif; ?>
	<?php
	if ( '' !== $osous_args['buttonText'] ) {
		osous_button( $osous_args['buttonText'], $osous_args['buttonUrl'], 'primary', '_w-max-200 m-t-auto' );
	}
	?>
	<?php if ( $osous_args['outline'] ) : ?>
		<div class="card1-outline"></div>
	<?php endif; ?>
</div>
