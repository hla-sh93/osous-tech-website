<?php
/**
 * A section: the band across the page, and everything inside it.
 *
 * The osous/section block prints this file: blocks/section/block.json names it as the block's
 * render, and blocks/section/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

echo '<section'
	. ( empty( $attributes['anchor'] ) ? '' : ' id="' . esc_attr( $attributes['anchor'] ) . '"' )
	. osous_class_attr(
		osous_option( 'spacing', $attributes['spacing'] ),
		osous_option( 'background', $attributes['background'] ),
		osous_option( 'height', $attributes['height'] ),
		$attributes['joinBelow'] ? 'm-b-0' : '',
		isset( $attributes['className'] ) ? $attributes['className'] : '',
		osous_picture_class( $attributes )
	)
	. osous_picture_style( $attributes )
	. '>';
osous_inner( $content );
echo '</section>';
