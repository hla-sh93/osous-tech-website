<?php
/**
 * One post's body.
 *
 * A post is written in the editor with WordPress's own blocks: a post is prose, and
 * prose wants a paragraph block. `.content` is the design's wrapper for exactly that.
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
