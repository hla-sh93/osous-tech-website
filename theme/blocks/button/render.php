<?php
/**
 * A button.
 *
 * `narrow` puts it in the design's 200px box, which is how a button under a paragraph
 * keeps to its own width instead of stretching across the column.
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
