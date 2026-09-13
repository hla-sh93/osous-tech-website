<?php
/**
 * The blog index.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main>
	<?php
	$osous_blog = get_option( 'page_for_posts' ) ? get_post( get_option( 'page_for_posts' ) ) : null;
	osous_page_header( '', $osous_blog ? $osous_blog->post_title : __( 'Blog', 'osous' ) );
	?>

	<section class="section">
		<div class="container fit c-surface-white">
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
		</div>
	</section>
</main>

<?php
get_footer();
