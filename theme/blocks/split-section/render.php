<?php
/**
 * A split section.
 *
 * One indicator dot per feature; the scroll script (assets/js/split-section.js) lights
 * the dot of the feature in view.
 *
 * @package Osous
 */

$osous_count = count( $block->inner_blocks );
?>
<section class="section split-section">
	<div class="container-full c-surface-neutral h-100">
		<div class="misc">
			<div class="div-block">
				<?php if ( '' !== $attributes['text'] ) : ?>
					<div class="heading">
						<h2 class="t-r-md"><?php echo osous_inline( $attributes['title'] ); ?></h2>
						<p><?php echo osous_inline( $attributes['text'] ); ?></p>
					</div>
				<?php else : ?>
					<h2 class="t-r-md"><?php echo osous_inline( $attributes['title'] ); ?></h2>
				<?php endif; ?>
				<div class="hide-on-tablet">
					<div class="flex-h">
						<?php for ( $osous_i = 0; $osous_i < $osous_count; $osous_i++ ) : ?>
							<div<?php echo osous_class_attr( 'indicator', 0 === $osous_i ? 'active' : '' ); ?>></div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="feature-card-wrapper"><?php osous_inner( $content ); ?></div>
	</div>
</section>
