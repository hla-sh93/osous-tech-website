<?php
/**
 * Every static page, including the front page.
 *
 * There is deliberately no per-page template. A page that needs something new needs a
 * new section, not a new template -- that is the difference between a theme and the 57
 * hand-maintained pages this replaces.
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
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
get_footer();
