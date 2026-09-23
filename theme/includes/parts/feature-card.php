<?php
/**
 * One feature of a split section: a title and text beside a picture.
 *
 * Called as: get_template_part( 'includes/parts/feature-card', null, array( 'title' => …, … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title      The title.
 *     @type string $text       The text.
 *     @type array  $image      The picture: src, alt, srcset, sizes.
 *     @type bool   $mediaFirst The picture before the words on a phone.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title'      => '',
		'text'       => '',
		'image'      => array(),
		'mediaFirst' => false,
	)
);
?>
<div class="feature-card">
	<div class="feature-content-item">
		<div class="feature-content-item-content">
			<h3 class="h2"><?php echo osous_inline( $osous_args['title'] ); ?></h3>
			<p class="p"><?php echo osous_inline( $osous_args['text'] ); ?></p>
		</div>
	</div>
	<div<?php echo osous_class_attr( 'feature-image-item', $osous_args['mediaFirst'] ? 'media-first' : '' ); ?>>
		<?php osous_image( (array) $osous_args['image'], 'feature-image-item-image' ); ?>
	</div>
</div>
