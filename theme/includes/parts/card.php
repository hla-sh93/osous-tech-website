<?php
/**
 * A white card: an icon, a title and a line of text; outlined or filled; a large figure;
 * a button at the end.
 *
 * Called as: get_template_part( 'includes/parts/card', null, array( 'title' => …, … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $media      'box' for the icon area, 'none' for no icon area.
 *     @type array  $icon       The icon: src, alt.
 *     @type string $link       Where the icon links to.
 *     @type bool   $outline    Draw the outline.
 *     @type bool   $fill       Draw the fill.
 *     @type string $stat       A large figure above the title.
 *     @type string $title      The title.
 *     @type string $text       A line of text.
 *     @type string $buttonText The button's label; empty for no button.
 *     @type string $buttonUrl  Where the button goes.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'media'      => 'box',
		'icon'       => array(),
		'link'       => '#',
		'outline'    => true,
		'fill'       => false,
		'stat'       => '',
		'title'      => '',
		'text'       => '',
		'buttonText' => '',
		'buttonUrl'  => '#',
	)
);
?>
<div class="card-1">
	<?php if ( $osous_args['outline'] ) : ?>
		<div class="card1-outline"></div>
	<?php endif; ?>
	<?php if ( 'none' !== $osous_args['media'] ) : ?>
		<a href="<?php echo esc_url( osous_url( $osous_args['link'] ) ); ?>" class="card-media"><?php osous_image( (array) $osous_args['icon'], 'icon-128' ); ?></a>
	<?php endif; ?>
	<?php if ( $osous_args['fill'] ) : ?>
		<div class="card1-background"></div>
	<?php endif; ?>
	<?php if ( '' !== $osous_args['stat'] ) : ?>
		<div class="h2 thin position-relative-10"><?php echo osous_inline( $osous_args['stat'] ); ?></div>
	<?php endif; ?>
	<?php if ( '' !== $osous_args['title'] ) : ?>
		<h3 class="position-relative-10"><?php echo osous_inline( $osous_args['title'] ); ?></h3>
	<?php endif; ?>
	<?php if ( '' !== $osous_args['text'] ) : ?>
		<p class="position-relative-10"><?php echo osous_inline( $osous_args['text'] ); ?></p>
	<?php endif; ?>
	<?php
	if ( '' !== $osous_args['buttonText'] ) {
		osous_button( $osous_args['buttonText'], $osous_args['buttonUrl'], 'primary', '_w-max-200 m-t-auto' );
	}
	?>
</div>
