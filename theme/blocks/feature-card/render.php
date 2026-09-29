<?php
/**
 * One feature of a split section.
 *
 * @package Osous
 */

$osous_image = is_array( $attributes['image'] ) ? $attributes['image'] : array();
?>
<div class="feature-card">
	<div class="feature-content-item">
		<div class="feature-content-item-content">
			<h3 class="h2"><?php echo osous_inline( $attributes['title'] ); ?></h3>
			<p class="p"><?php echo osous_inline( $attributes['text'] ); ?></p>
		</div>
	</div>
	<div<?php echo osous_class_attr( 'feature-image-item', $attributes['mediaFirst'] ? 'media-first' : '' ); ?>>
		<?php osous_image( $osous_image, 'feature-image-item-image' ); ?>
	</div>
</div>
