<?php
/**
 * One blog post.
 *
 * The body is written in the editor with WordPress's own blocks, not with this theme's
 * sections: a post is prose, and prose wants a paragraph block. `.content` is the design's
 * wrapper for exactly that.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

<main>
	<?php osous_page_header( get_the_date(), get_the_title() ); ?>

	<section class="section">
		<div class="container c-surface-white fit">
			<div class="content">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
</main>

	<?php
endwhile;

get_footer();
