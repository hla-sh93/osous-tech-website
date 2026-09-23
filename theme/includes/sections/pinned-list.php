<?php
/**
 * A heading that holds still while a list of points scrolls past beside it. The first
 * point is highlighted as the page loads; the scroll script moves the highlight.
 *
 * Called as: get_template_part( 'includes/sections/pinned-list', null, array( 'heading' => …, 'items' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $heading     The heading that holds still.
 *     @type string $text        A line under it; empty for none.
 *     @type string $textClass   The line's classes; 'p' unless the design says otherwise.
 *     @type string $columnClass The heading column's classes; 'cell' unless said otherwise.
 *     @type string $tagName     'section' or 'div' for the scrolling box, as the page has it.
 *     @type array  $items       The points, each the arguments of includes/parts/pinned-item.php.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'heading'     => '',
		'text'        => '',
		'textClass'   => 'p',
		'columnClass' => 'cell',
		'tagName'     => 'section',
		'items'       => array(),
	)
);

$osous_tag = 'div' === $osous_args['tagName'] ? 'div' : 'section';

osous_scroll_module( 'list-scroll' );
?>
<section class="section list-scroll-section">
	<div class="container">
		<?php echo '<' . tag_escape( $osous_tag ) . ' class="list-scroll">'; ?>
			<div class="grid-2">
				<div<?php echo osous_class_attr( $osous_args['columnClass'] ); ?>>
					<h2 class="h2"><?php echo osous_inline( $osous_args['heading'] ); ?></h2>
					<?php if ( '' !== $osous_args['text'] ) : ?>
						<p<?php echo osous_class_attr( $osous_args['textClass'] ); ?>><?php echo osous_inline( $osous_args['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="list-items-wrapper">
					<?php
					foreach ( $osous_args['items'] as $osous_i => $osous_item ) {
						get_template_part( 'includes/parts/pinned-item', null, array_merge( (array) $osous_item, array( 'active' => 0 === $osous_i ) ) );
					}
					?>
				</div>
			</div>
		<?php echo '</' . tag_escape( $osous_tag ) . '>'; ?>
	</div>
</section>
