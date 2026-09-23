<?php
/**
 * The home page's opening: a full-height photograph, the headline, and a marquee of
 * partner marks. The marquee is printed twice so it can scroll without a gap; the copy
 * is hidden from screen readers.
 *
 * Called as: get_template_part( 'includes/sections/hero', null, array( 'title' => …, 'logos' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title The headline.
 *     @type string $text  The line under it.
 *     @type array  $logos The marks, each src and alt.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title' => '',
		'text'  => '',
		'logos' => array(),
	)
);
?>
<section class="bg-img-6 h-100-vh">
	<div class="container h-85">
		<div class="flex-v a-c-c h-100">
			<div class="heading center">
				<h1 class="h1 c-on-secondary a-c"><?php echo osous_inline( $osous_args['title'] ); ?></h1>
				<p class="p c-on-secondary a-c"><?php echo osous_inline( $osous_args['text'] ); ?></p>
			</div>
		</div>
	</div>
	<div class="infinite-carousel-wrapper">
		<div class="infinite-carousel">
			<?php
			foreach ( $osous_args['logos'] as $osous_logo ) {
				osous_image( (array) $osous_logo, 'infinite-carousel-logo' );
			}
			?>
		</div>
		<div class="infinite-carousel" aria-hidden="true">
			<?php
			foreach ( $osous_args['logos'] as $osous_logo ) {
				osous_image( array_merge( (array) $osous_logo, array( 'alt' => '' ) ), 'infinite-carousel-logo' );
			}
			?>
		</div>
	</div>
</section>
