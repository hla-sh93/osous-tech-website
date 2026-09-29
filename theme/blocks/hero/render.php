<?php
/**
 * The hero.
 *
 * The marquee is the row of marks twice over: the second copy follows the first so the
 * scroll never shows a gap, and it is hidden from assistive technology because it says
 * nothing the first does not. The marks themselves are image blocks inside the hero.
 *
 * @package Osous
 */

?>
<section class="bg-img-6 h-100-vh">
	<div class="container h-85">
		<div class="flex-v a-c-c h-100">
			<div class="heading center">
				<h1 class="h1 c-on-secondary a-c"><?php echo osous_inline( $attributes['title'] ); ?></h1>
				<p class="p c-on-secondary a-c"><?php echo osous_inline( $attributes['text'] ); ?></p>
			</div>
		</div>
	</div>
	<div class="infinite-carousel-wrapper">
		<div class="infinite-carousel"><?php osous_inner( $content ); ?></div>
		<div class="infinite-carousel" aria-hidden="true">
			<?php
			foreach ( $block->inner_blocks as $osous_mark ) {
				if ( 'osous/image' === $osous_mark->name ) {
					osous_image( array_merge( $osous_mark->attributes, array( 'alt' => '' ) ), 'infinite-carousel-logo' );
				}
			}
			?>
		</div>
	</div>
</section>
