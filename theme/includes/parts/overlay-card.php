<?php
/**
 * A card built around a photograph, with a gradient over it: a number, a title and a
 * line of text.
 *
 * Called as: get_template_part( 'includes/parts/overlay-card', null, array( 'title' => …, … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $number The number in the badge; empty for no badge.
 *     @type string $title  The title.
 *     @type string $text   A line of text.
 *     @type array  $image  The photograph: src, alt, srcset, sizes.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'number' => '',
		'title'  => '',
		'text'   => '',
		'image'  => array(),
	)
);
?>
<div class="card-3">
	<?php osous_number( $osous_args['number'] ); ?>
	<div class="div-block-2">
		<h3 class="h3 c-on-primary"><?php echo osous_inline( $osous_args['title'] ); ?></h3>
		<p class="p c-on-primary-subtle"><?php echo osous_inline( $osous_args['text'] ); ?></p>
	</div>
	<div class="card-3-gradient"></div>
	<?php osous_image( (array) $osous_args['image'], 'card-3-image' ); ?>
</div>
