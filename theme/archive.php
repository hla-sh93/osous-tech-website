<?php
/**
 * A category, tag, author or date archive: the same grid as the blog, under the
 * archive's own name.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'includes/header' );
?>
<main>
	<?php osous_archive_header(); ?>
	<section class="section">
		<div class="container fit c-surface-white">
			<?php if ( have_posts() ) : ?>
				<div>
					<div role="list" class="grid-3">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'includes/loops/content' );
						endwhile;
						?>
					</div>
					<?php osous_pagination(); ?>
				</div>
			<?php else : ?>
				<?php get_template_part( 'includes/loops/content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_template_part( 'includes/footer' );
