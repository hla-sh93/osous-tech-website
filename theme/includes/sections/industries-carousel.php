<?php
/**
 * A heading and a swipeable row of glass cards on a photograph. The first card is the
 * highlighted one as the page loads; Swiper takes it from there.
 *
 * Called as: get_template_part( 'includes/sections/industries-carousel', null, array( 'title' => …, 'items' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title The heading.
 *     @type string $text  The line under it.
 *     @type array  $items The cards, each the arguments of includes/parts/photo-card.php.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title' => '',
		'text'  => '',
		'items' => array(),
	)
);

osous_enqueue_carousel();
?>
<section class="section overflow-hidden bg-3">
	<div class="container swiper-container">
		<div class="flex-h a-sb-t">
			<div class="heading">
				<h2 class="h2 c-on-secondary"><?php echo osous_inline( $osous_args['title'] ); ?></h2>
				<p class="p c-on-secondary"><?php echo osous_inline( $osous_args['text'] ); ?></p>
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
		<div class="flex-h swiper-wrapper">
			<?php
			foreach ( $osous_args['items'] as $osous_i => $osous_item ) {
				get_template_part(
					'includes/parts/photo-card',
					null,
					array_merge(
						(array) $osous_item,
						array(
							'wrapper' => 'swiper-slide industry-specific',
							'active'  => 0 === $osous_i,
						)
					)
				);
			}
			?>
		</div>
	</div>
</section>
