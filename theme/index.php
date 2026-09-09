<?php
/**
 * Required fallback template.
 *
 * Everything real gets its own template as the phases land — page.php, front-page.php,
 * home.php, single.php. This exists because WordPress requires it, and because a theme
 * that renders nothing at all is impossible to smoke-test.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main>
	<section class="section">
		<div class="container">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<h2 class="h2"><?php the_title(); ?></h2>
					<div class="p"><?php the_content(); ?></div>
					<?php
				endwhile;
			else :
				?>
				<h2 class="h2"><?php esc_html_e( 'Nothing here yet', 'osous' ); ?></h2>
				<?php
			endif;
			?>
		</div>
	</section>
</main>

<?php
get_footer();
