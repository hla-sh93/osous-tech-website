<?php
/**
 * A photo card: the words sit on a gradient over the picture.
 *
 * The osous/overlay-card block prints this file: blocks/overlay-card/block.json names it as the block's
 * render, and blocks/overlay-card/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_image = is_array( $attributes['image'] ) ? $attributes['image'] : array();
?>
<div class="card-3">
	<?php osous_number( $attributes['number'] ); ?>
	<div class="div-block-2">
		<h3 class="h3 c-on-primary"><?php echo osous_inline( $attributes['title'] ); ?></h3>
		<p class="p c-on-primary-subtle"><?php echo osous_inline( $attributes['text'] ); ?></p>
	</div>
	<div class="card-3-gradient"></div>
	<?php osous_image( $osous_image, 'card-3-image' ); ?>
</div>
