<?php
/**
 * A heading beside glass cards that fan out as the page scrolls.
 *
 * Called as: get_template_part( 'includes/sections/card-stack', null, array( 'title' => …, 'items' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title The heading.
 *     @type array  $items The cards, each the arguments of includes/parts/photo-card.php.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title' => '',
		'items' => array(),
	)
);

osous_scroll_module( 'overlapping-cards' );
?>
<section class="section overlapping-cards">
	<div class="flex-v h-100 a-c-c">
		<div class="container">
			<div class="grid-2">
				<div class="cell self-center why-us-heading">
					<h2 class="h2 c-on-secondary"><?php echo osous_inline( $osous_args['title'] ); ?></h2>
				</div>
				<div class="cards-overlapping-wrapper">
					<?php
					foreach ( $osous_args['items'] as $osous_item ) {
						get_template_part( 'includes/parts/photo-card', null, array_merge( (array) $osous_item, array( 'wrapper' => 'card-overlapping-item' ) ) );
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
