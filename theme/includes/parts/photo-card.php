<?php
/**
 * A glass card.
 *
 * Inside the card stack and the industries carousel each card sits in a wrapper of the
 * parent's choosing -- the item the scroll script moves, the slide Swiper moves. The
 * parent names it through block context and the card wraps itself, so the parent's
 * template does not have to know what a card looks like.
 *
 * The osous/photo-card block prints this file: blocks/photo-card/block.json names it as the block's
 * render, and blocks/photo-card/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_icon    = is_array( $attributes['icon'] ) ? $attributes['icon'] : array();
$osous_wrapper = isset( $block->context['osous/cardWrapper'] ) ? (string) $block->context['osous/cardWrapper'] : '';

if ( '' !== $osous_wrapper ) {
	echo '<div' . osous_class_attr( $osous_wrapper ) . '>';
}
?>
<div class="card-4 bg-blur">
	<?php osous_number( $attributes['number'] ); ?>
	<?php if ( 'none' !== $attributes['media'] ) : ?>
		<a href="<?php echo esc_url( osous_url( $attributes['link'] ) ); ?>" class="card-media"><?php osous_image( $osous_icon, 'icon-128' ); ?></a>
	<?php endif; ?>
	<div class="content">
		<?php if ( '' !== $attributes['title'] ) : ?>
			<h3><?php echo osous_inline( $attributes['title'] ); ?></h3>
		<?php endif; ?>
		<p class="paragraph"><?php echo osous_inline( $attributes['text'] ); ?></p>
	</div>
</div>
<?php
if ( '' !== $osous_wrapper ) {
	echo '</div>';
}
