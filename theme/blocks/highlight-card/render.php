<?php
/**
 * A highlight card.
 *
 * @package Osous
 */

$osous_icon = is_array( $attributes['icon'] ) ? $attributes['icon'] : array();
?>
<div class="card-5">
	<a href="<?php echo esc_url( osous_url( $attributes['link'] ) ); ?>" class="card-media"><?php osous_image( $osous_icon, 'icon-128' ); ?></a>
	<?php if ( '' !== $attributes['title'] ) : ?>
		<h3 class="h3 c-on-primary"><?php echo osous_inline( $attributes['title'] ); ?></h3>
	<?php endif; ?>
	<p class="p-r-sm c-on-secondary position-relative"><?php echo osous_inline( $attributes['text'] ); ?></p>
	<div class="card3-bg"></div>
	<?php if ( $attributes['outline'] ) : ?>
		<div class="card1-outline"></div>
	<?php endif; ?>
</div>
