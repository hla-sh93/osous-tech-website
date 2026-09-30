<?php
/**
 * One post, as it appears in a grid: the blog, an archive, a search.
 *
 * Called as: get_template_part( 'includes/loops/content' ) inside the loop, or with
 * array( 'post' => $post ) for a post that is not the current one.
 *
 * The static reference page is a snapshot of the first twelve posts as production had
 * them, so this markup has to come out the same as that snapshot for the checks to keep
 * meaning something -- and then go on working for the hundred posts that follow.
 *
 * @package Osous
 *
 * @var array $args {
 *     @type int|WP_Post $post The post; the current one when absent.
 * }
 */

$osous_post = get_post( isset( $args['post'] ) ? $args['post'] : null );
if ( ! $osous_post ) {
	return;
}
?>
<div>
	<a href="<?php echo esc_url( get_permalink( $osous_post ) ); ?>" class="blog-card card-media">
		<div class="blog-image-thumb-container">
			<?php echo osous_post_thumb( $osous_post->ID ); ?>
		</div>
		<div class="spacer-1x"></div>
		<div class="t-r-sm c-on-neutral-subtle"><?php echo esc_html( get_the_date( '', $osous_post ) ); ?></div>
		<h2 class="h2"><?php echo esc_html( get_the_title( $osous_post ) ); ?></h2>
	</a>
</div>
