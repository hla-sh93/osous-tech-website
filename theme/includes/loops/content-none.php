<?php
/**
 * Nothing to list: an empty archive, or a search that matched nothing.
 *
 * @package Osous
 *
 * @var array $args { @type string $message What to say. }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'message' => __( 'Nothing has been published here yet.', 'osous' ),
	)
);
?>
<div class="heading center">
	<p class="p a-c"><?php echo esc_html( $osous_args['message'] ); ?></p>
</div>
