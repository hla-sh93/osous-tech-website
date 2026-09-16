<?php
/**
 * An image.
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
			isset( $attributes['className'] ) ? $attributes['className'] : '',
		)
	)
);
