<?php
/**
 * The page header.
 *
 * Also printed by the blog and the archives (functions/blog.php), with the post's title in
 * place of an attribute, so the band at the top of every page comes from one template.
 *
 * The osous/page-header block prints this file: blocks/page-header/block.json names it as the block's
 * render, and blocks/page-header/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

?>
<section class="section header">
	<div class="container h-100">
		<div class="header-contents">
			<?php if ( '' !== $attributes['eyebrow'] ) : ?>
				<div class="t-r-md c-on-neutral-subtle"><?php echo osous_inline( $attributes['eyebrow'] ); ?></div>
				<div class="_w-max-200"><div class="divider gradient-1"></div></div>
			<?php endif; ?>
			<div>
				<h1 class="h2 a-c"><?php echo osous_inline( $attributes['title'] ); ?></h1>
				<?php if ( '' !== $attributes['text'] ) : ?>
					<p class="p-r-sm c-on-neutral a-c"><?php echo osous_inline( $attributes['text'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
