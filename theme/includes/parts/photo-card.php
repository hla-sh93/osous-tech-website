<?php
/**
 * A frosted-glass card on a photograph: a number, an icon, a title and a line of text.
 * The card stack and the industries carousel wrap each one in a slot of their own.
 *
 * Called as: get_template_part( 'includes/parts/photo-card', null, array( 'title' => …, … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $number  The number in the badge; empty for no badge.
 *     @type array  $icon    The icon: src, alt.
 *     @type string $link    Where the icon links to.
 *     @type string $media   'box' for the icon area, 'none' for no icon area.
 *     @type string $title   The title.
 *     @type string $text    A line of text.
 *     @type string $wrapper Classes of the slot around the card; empty for none.
 *     @type bool   $active  The carousel's first, highlighted card.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'number'  => '',
		'icon'    => array(),
		'link'    => '#',
		'media'   => 'box',
		'title'   => '',
		'text'    => '',
		'wrapper' => '',
		'active'  => false,
	)
);

if ( '' !== $osous_args['wrapper'] ) {
	echo '<div' . osous_class_attr( $osous_args['wrapper'] ) . '>';
}
?>
<div<?php echo osous_class_attr( 'card-4 bg-blur', $osous_args['active'] ? 'active' : '' ); ?>>
	<?php osous_number( $osous_args['number'] ); ?>
	<?php if ( 'none' !== $osous_args['media'] ) : ?>
		<a href="<?php echo esc_url( osous_url( $osous_args['link'] ) ); ?>" class="card-media"><?php osous_image( (array) $osous_args['icon'], 'icon-128' ); ?></a>
	<?php endif; ?>
	<div class="content">
		<?php if ( '' !== $osous_args['title'] ) : ?>
			<h3><?php echo osous_inline( $osous_args['title'] ); ?></h3>
		<?php endif; ?>
		<p class="paragraph"><?php echo osous_inline( $osous_args['text'] ); ?></p>
	</div>
</div>
<?php
if ( '' !== $osous_args['wrapper'] ) {
	echo '</div>';
}
