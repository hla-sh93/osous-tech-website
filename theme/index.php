<?php
/**
 * The blog: the posts, twelve to a page, in the design's grid.
 *
 * WordPress requires index.php and uses it for the posts page. Every other page of the
 * site has a file of its own under pages/.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'includes/header' );
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
						get_template_part( 'includes/loops/content' );
					endwhile;
					?>
				</div>
				<?php osous_pagination(); ?>
			</div>
		</div>
	</section>
</main>
<?php
get_template_part( 'includes/footer' );
