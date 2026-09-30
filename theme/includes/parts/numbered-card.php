<?php
/**
 * A purple card.
 *
 * Its longer text -- a few paragraphs, a list -- is written in ordinary blocks inside
 * the card, and comes out in the design's prose box after the card's fill layer.
 *
 * The osous/numbered-card block prints this file: blocks/numbered-card/block.json names it as the block's
 * render, and blocks/numbered-card/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

?>
<div class="card-2">
	<?php osous_number( $attributes['number'] ); ?>
	<?php osous_image( is_array( $attributes['icon'] ) ? $attributes['icon'] : array(), 'icon-60' ); ?>
	<?php if ( '' !== $attributes['title'] ) : ?>
		<h3 class="h3 c-on-primary"><?php echo osous_inline( $attributes['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( '' !== $attributes['text'] ) : ?>
		<p class="p c-on-primary-subtle"><?php echo osous_inline( $attributes['text'] ); ?></p>
	<?php endif; ?>
	<?php if ( $attributes['fill'] ) : ?>
		<div class="card2-background"></div>
	<?php endif; ?>
	<?php if ( osous_has_prose( $content ) ) : ?>
		<div class="rich-text-block"><?php osous_inner( osous_prose_markup( $content ) ); ?></div>
	<?php endif; ?>
	<?php
	if ( '' !== $attributes['buttonText'] ) {
		osous_button( $attributes['buttonText'], $attributes['buttonUrl'], 'primary', '_w-max-200 m-t-auto' );
	}
	?>
	<?php if ( $attributes['outline'] ) : ?>
		<div class="card1-outline"></div>
	<?php endif; ?>
</div>
