<?php
/**
 * A card with a coloured wash behind it: an icon, a title and a line of text.
 *
 * Called as: get_template_part( 'includes/parts/highlight-card', null, array( 'title' => …, … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type array  $icon    The icon: src, alt.
 *     @type string $link    Where the icon links to.
 *     @type string $title   The title.
 *     @type string $text    A line of text.
 *     @type bool   $outline Draw the outline.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'icon'    => array(),
		'link'    => '#',
		'title'   => '',
		'text'    => '',
		'outline' => true,
	)
);
?>
<div class="card-5">
	<a href="<?php echo esc_url( osous_url( $osous_args['link'] ) ); ?>" class="card-media"><?php osous_image( (array) $osous_args['icon'], 'icon-128' ); ?></a>
	<?php if ( '' !== $osous_args['title'] ) : ?>
		<h3 class="h3 c-on-primary"><?php echo osous_inline( $osous_args['title'] ); ?></h3>
	<?php endif; ?>
	<p class="p-r-sm c-on-secondary position-relative"><?php echo osous_inline( $osous_args['text'] ); ?></p>
	<div class="card3-bg"></div>
	<?php if ( $osous_args['outline'] ) : ?>
		<div class="card1-outline"></div>
	<?php endif; ?>
</div>
