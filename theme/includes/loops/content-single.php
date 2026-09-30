<?php
/**
 * A post's body: its words in a white container, under the page header.
 *
 * Called as: get_template_part( 'includes/loops/content-single' ) inside the loop.
 *
 * @package Osous
 */

?>
<section class="section">
	<div class="container c-surface-white fit">
		<div class="content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
