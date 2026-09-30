<?php
/**
 * A page: its blocks, in the order the editor holds them.
 *
 * Called as: get_template_part( 'includes/loops/content', 'page' ) by page.php and by
 * every template under templates/. The blocks print themselves -- each from its file
 * under includes/ -- so this is the whole of a page's body.
 *
 * @package Osous
 */

?>
<main>
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
