<?php
/**
 * A partner's mark, linking to its page, with a caption or without one.
 *
 * Called as: get_template_part( 'includes/parts/partner', null, array( 'logo' => …, 'link' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type array  $logo    The mark: src, alt.
 *     @type string $link    The partner's page.
 *     @type string $caption A line under the mark; empty for the plain mark.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'logo'    => array(),
		'link'    => '#',
		'caption' => '',
	)
);

$osous_href = esc_url( osous_url( $osous_args['link'] ) );

if ( '' === $osous_args['caption'] ) :
	?>
	<div class="partner-card-logo _w-100"><a href="<?php echo $osous_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped where it is built, above. ?>" class="card-media"><?php osous_image( (array) $osous_args['logo'], 'icon-128' ); ?></a></div>
	<?php
else :
	?>
	<div class="partner-card">
		<div class="partner-card-logo"><a href="<?php echo $osous_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- as above. ?>" class="card-media"><?php osous_image( (array) $osous_args['logo'], 'icon-128' ); ?></a></div>
		<p class="p"><?php echo osous_inline( $osous_args['caption'] ); ?></p>
	</div>
	<?php
endif;
