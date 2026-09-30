<?php
/**
 * The empty state of a list: an archive with nothing in it, a search with no result.
 *
 * Called as: get_template_part( 'includes/loops/content-none', null, array( 'message' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $message The line to show.
 * }
 */

$osous_message = isset( $args['message'] ) ? $args['message'] : __( 'Nothing has been published here yet.', 'osous' );
?>
<div class="heading center">
	<p class="p a-c"><?php echo esc_html( $osous_message ); ?></p>
</div>
