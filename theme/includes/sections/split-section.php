<?php
/**
 * Features that slide through a fixed window as the page scrolls, with a dot for each.
 *
 * Called as: get_template_part( 'includes/sections/split-section', null, array( 'title' => …, 'items' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title The small heading.
 *     @type string $text  A line under it; empty for the heading alone.
 *     @type array  $items The features, each the arguments of includes/parts/feature-card.php.
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

osous_scroll_module( 'split-section' );
?>
<section class="section split-section">
	<div class="container-full c-surface-neutral h-100">
		<div class="misc">
			<div class="div-block">
				<?php if ( '' !== $osous_args['text'] ) : ?>
					<div class="heading">
						<h2 class="t-r-md"><?php echo osous_inline( $osous_args['title'] ); ?></h2>
						<p><?php echo osous_inline( $osous_args['text'] ); ?></p>
					</div>
				<?php else : ?>
					<h2 class="t-r-md"><?php echo osous_inline( $osous_args['title'] ); ?></h2>
				<?php endif; ?>
				<div class="hide-on-tablet">
					<div class="flex-h">
						<?php foreach ( array_keys( $osous_args['items'] ) as $osous_i ) : ?>
							<div<?php echo osous_class_attr( 'indicator', 0 === $osous_i ? 'active' : '' ); ?>></div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="feature-card-wrapper">
			<?php
			foreach ( $osous_args['items'] as $osous_item ) {
				get_template_part( 'includes/parts/feature-card', null, (array) $osous_item );
			}
			?>
		</div>
	</div>
</section>
