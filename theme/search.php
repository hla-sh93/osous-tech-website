<?php
/**
 * Search results.
 *
 * Posts come out as blog cards, because that is what a post looks like everywhere else on
 * this site. Pages have no picture, so they come out as a cell with a heading and the
 * first line of their text.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$osous_query = get_search_query();
$osous_found = (int) $GLOBALS['wp_query']->found_posts;
?>

<main>
	<?php
	osous_page_header(
		/* translators: %d: number of results. */
		sprintf( _n( '%d result', '%d results', $osous_found, 'osous' ), $osous_found ),
		$osous_query ? $osous_query : __( 'Search', 'osous' )
	);
	?>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<?php get_search_form(); ?>
			</div>

			<?php if ( have_posts() ) : ?>
				<div role="list" class="grid-3">
					<?php
					while ( have_posts() ) :
						the_post();
						if ( 'post' === get_post_type() ) {
							get_template_part( 'includes/loops/content' );
							continue;
						}
						?>
						<div class="cell">
							<h3><a href="<?php the_permalink(); ?>" class="link"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						</div>
						<?php
					endwhile;
					?>
				</div>
				<?php osous_pagination(); ?>
			<?php else : ?>
				<?php
				get_template_part(
					'includes/loops/content-none',
					null,
					array( 'message' => __( 'Nothing matched. Try fewer words, or a different one.', 'osous' ) )
				);
				?>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
