<?php
/**
 * A card.
 *
 * The outline and the fill are separate layers under the words, which is how the design
 * draws a border that the content can sit on top of. Their order matters: the outline
 * goes first and the fill after the icon, as the stylesheet expects.
 *
 * @package Osous
 */

$osous_icon = is_array( $attributes['icon'] ) ? $attributes['icon'] : array();
?>
<div class="card-1">
	<?php if ( $attributes['outline'] ) : ?>
		<div class="card1-outline"></div>
	<?php endif; ?>
	<?php if ( 'none' !== $attributes['media'] ) : ?>
		<a href="<?php echo esc_url( osous_url( $attributes['link'] ) ); ?>" class="card-media"><?php osous_image( $osous_icon, 'icon-128' ); ?></a>
	<?php endif; ?>
	<?php if ( $attributes['fill'] ) : ?>
		<div class="card1-background"></div>
	<?php endif; ?>
	<?php if ( '' !== $attributes['stat'] ) : ?>
		<div class="h2 thin position-relative-10"><?php echo osous_inline( $attributes['stat'] ); ?></div>
	<?php endif; ?>
	<?php if ( '' !== $attributes['title'] ) : ?>
		<h3 class="position-relative-10"><?php echo osous_inline( $attributes['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( '' !== $attributes['text'] ) : ?>
		<p class="position-relative-10"><?php echo osous_inline( $attributes['text'] ); ?></p>
	<?php endif; ?>
	<?php
	if ( '' !== $attributes['buttonText'] ) {
		osous_button( $attributes['buttonText'], $attributes['buttonUrl'], 'primary', '_w-max-200 m-t-auto' );
	}
	?>
</div>
