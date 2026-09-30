<?php
/**
 * A container: the box a section's content sits in.
 *
 * The osous/container block prints this file: blocks/container/block.json names it as the block's
 * render, and blocks/container/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

echo '<div'
	. osous_class_attr(
		osous_option( 'width', $attributes['width'] ),
		$attributes['fit'] ? 'fit' : '',
		osous_option( 'surface', $attributes['surface'] ),
		$attributes['border'] ? 'c-border-neutral' : '',
		$attributes['elevation'] ? 'elevation' : '',
		$attributes['mobilePadding'] ? 'padding-x-on-mobile' : '',
		$attributes['carousel'] ? 'grid-to-carousel' : '',
		$attributes['fullHeight'] ? 'h-100' : '',
		osous_option( 'backdrop', $attributes['backdrop'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
