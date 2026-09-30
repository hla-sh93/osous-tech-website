<?php
/**
 * An image.
 *
 * The osous/image block prints this file: blocks/image/block.json names it as the block's
 * render, and blocks/image/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

osous_image(
	$attributes,
	implode(
		' ',
		array(
			osous_option( 'image-size', $attributes['size'] ),
			osous_option( 'image-fit', $attributes['fit'] ),
			$attributes['rounded'] ? 'radii-container' : '',
			osous_option( 'align', $attributes['place'] ),
			osous_option( 'span', $attributes['span'] ),
			osous_option( 'tablet-row', $attributes['tabletRow'] ),
			$attributes['spaceBelow'] ? 'm-b-1x' : '',
			isset( $attributes['className'] ) ? $attributes['className'] : '',
		)
	)
);
