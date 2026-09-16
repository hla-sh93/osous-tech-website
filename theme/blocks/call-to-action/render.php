<?php
/**
 * A call to action.
 *
 * @package Osous
 */

$osous_id = empty( $attributes['anchor'] ) ? '' : ' id="' . esc_attr( $attributes['anchor'] ) . '"';
?>
<section class="section top-tight"<?php echo $osous_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped where it is built, above. ?>>
	<div class="container c-surface-white c-border-neutral">
		<div class="heading center">
			<h2 class="h2 a-c"><?php echo osous_inline( $attributes['title'] ); ?></h2>
			<p class="p a-c"><?php echo osous_inline( $attributes['text'] ); ?></p>
			<div class="spacer-3x"></div>
			<?php osous_button( $attributes['buttonText'], $attributes['buttonUrl'], 'outline' ); ?>
		</div>
	</div>
</section>
