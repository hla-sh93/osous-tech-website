<?php
/**
 * One blog post: the page header with its date and title, then the body.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'includes/header' );
while ( have_posts() ) :
	the_post();
	?>
<main>
	<?php osous_page_header( get_the_date(), get_the_title() ); ?>
	<?php get_template_part( 'includes/loops/content', 'single' ); ?>
</main>
	<?php
endwhile;
get_template_part( 'includes/footer' );
