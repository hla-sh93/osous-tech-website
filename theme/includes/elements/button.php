<?php
/**
 * A button.
 *
 * `narrow` puts it in the design's 200px box, which is how a button under a paragraph
 * keeps to its own width instead of stretching across the column.
 *
 * The osous/button block prints this file: blocks/button/block.json names it as the block's
 * render, and blocks/button/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

if ( $attributes['narrow'] ) {
	echo '<div class="_w-max-200">';
}
osous_button(
	$attributes['text'],
	$attributes['url'],
	$attributes['style'],
	isset( $attributes['className'] ) ? $attributes['className'] : ''
);
if ( $attributes['narrow'] ) {
	echo '</div>';
}
