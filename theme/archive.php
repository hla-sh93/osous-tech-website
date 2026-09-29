<?php
/**
 * A category, tag, author or date archive.
 *
 * The same grid as the blog index, under the archive's own name. Without this file
 * every one of those URLs fell through to index.php, which exists because WordPress
 * requires it and is not a design.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
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
							osous_blog_card();
						endwhile;
						?>
					</div>
					<?php osous_pagination(); ?>
				</div>
			<?php else : ?>
				<div class="heading center">
					<p class="p a-c">
						<?php esc_html_e( 'Nothing has been published here yet.', 'osous' ); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
