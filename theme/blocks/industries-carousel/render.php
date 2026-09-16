<?php
/**
 * The industries carousel.
 *
 * Swiper (assets/js/industries-carousel.js) drives the slides. The first card ships
 * with `active`, as the design's markup does; each card wraps itself in a slide, named
 * through block context.
 *
 * @package Osous
 */

?>
<section class="section overflow-hidden bg-3">
	<div class="container swiper-container">
		<div class="flex-h a-sb-t">
			<div class="heading">
				<h2 class="h2 c-on-secondary"><?php echo osous_inline( $attributes['title'] ); ?></h2>
				<p class="p c-on-secondary"><?php echo osous_inline( $attributes['text'] ); ?></p>
			</div>
			<div class="button-wrapper">
				<div class="hide-on-tablet">
					<div class="flex-h">
						<button type="button" class="btn outline swiper-button-prev" aria-label="<?php esc_attr_e( 'Previous slide', 'osous' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-arrow-left.svg' ) ); ?>" alt="" class="icon-24"></button>
						<button type="button" class="btn outline swiper-button-next" aria-label="<?php esc_attr_e( 'Next slide', 'osous' ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-arrow-right.svg' ) ); ?>" alt="" class="icon-24"></button>
					</div>
				</div>
			</div>
		</div>
		<div class="flex-h swiper-wrapper"><?php osous_inner( preg_replace( '/class="card-4 bg-blur"/', 'class="card-4 bg-blur active"', $content, 1 ) ); ?></div>
	</div>
</section>
