<?php
/**
 * A page that has no file of its own under pages/.
 *
 * It shows what was written in the editor. Every page of the site has a file, and
 * functions/templates.php sends it there; this is what a new page looks like until it
 * is given one.
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
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
<?php
get_template_part( 'includes/footer' );
