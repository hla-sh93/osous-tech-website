<?php
/**
 * A partner's mark.
 *
 * With a caption it is a card in the partner strip; without one it is a bare mark in a
 * grid, which is the other shape the design has for the same thing.
 *
 * The osous/partner block prints this file: blocks/partner/block.json names it as the block's
 * render, and blocks/partner/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_logo = is_array( $attributes['logo'] ) ? $attributes['logo'] : array();
$osous_href = esc_url( osous_url( $attributes['link'] ) );

if ( '' === $attributes['caption'] ) :
	?>
	<div class="partner-card-logo _w-100"><a href="<?php echo $osous_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped where it is built, above. ?>" class="card-media"><?php osous_image( $osous_logo, 'icon-128' ); ?></a></div>
	<?php
else :
	?>
	<div class="partner-card">
		<div class="partner-card-logo"><a href="<?php echo $osous_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- as above. ?>" class="card-media"><?php osous_image( $osous_logo, 'icon-128' ); ?></a></div>
		<p class="p"><?php echo osous_inline( $attributes['caption'] ); ?></p>
	</div>
	<?php
endif;
