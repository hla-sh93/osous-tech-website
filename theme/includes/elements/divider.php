<?php
/**
 * A divider.
 *
 * The osous/divider block prints this file: blocks/divider/block.json names it as the block's
 * render, and blocks/divider/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_style = osous_option( 'divider', $attributes['style'] );
if ( $attributes['narrow'] ) {
	echo '<div class="_w-max-200">';
}
echo '<div'
	. osous_class_attr(
		'' === $osous_style ? 'divider c-border-neutral' : $osous_style,
		osous_option( 'align', $attributes['place'] ),
		osous_option( 'span', $attributes['span'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '></div>';
if ( $attributes['narrow'] ) {
	echo '</div>';
}
