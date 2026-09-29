<?php
/**
 * A pinned list: the heading column and the items, side by side.
 *
 * The scroll script (assets/js/list-scroll.js) pins `.list-scroll` and highlights each
 * item as it crosses the middle of the window.
 *
 * @package Osous
 */

$osous_tag = 'div' === $attributes['tagName'] ? 'div' : 'section';
?>
<section class="section list-scroll-section">
	<div class="container">
		<?php echo '<' . tag_escape( $osous_tag ) . ' class="list-scroll">'; ?>
			<div class="grid-2"><?php osous_inner( $content ); ?></div>
		<?php echo '</' . tag_escape( $osous_tag ) . '>'; ?>
	</div>
</section>
