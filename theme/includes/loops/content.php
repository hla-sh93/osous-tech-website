<?php
/**
 * A post in a grid: its picture, its date and its title, all one link.
 *
 * Used inside the loop by index.php, archive.php and search.php.
 *
 * @package Osous
 */

?>
<div>
	<a href="<?php the_permalink(); ?>" class="blog-card card-media">
		<div class="blog-image-thumb-container">
			<?php echo osous_post_thumb( get_the_ID() ); ?>
		</div>
		<div class="spacer-1x"></div>
		<div class="t-r-sm c-on-neutral-subtle"><?php echo esc_html( get_the_date() ); ?></div>
		<h2 class="h2"><?php echo esc_html( get_the_title() ); ?></h2>
	</a>
</div>
