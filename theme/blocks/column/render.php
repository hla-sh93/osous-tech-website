<?php
/**
 * A column: one cell of a grid.
 *
 * @package Osous
 */

echo '<div'
	. osous_class_attr(
		'cell',
		osous_option( 'align', $attributes['align'] ),
		osous_option( 'span', $attributes['span'] ),
		$attributes['wideOnMobile'] ? 'span-mobile-2' : '',
		$attributes['mediaFirst'] ? 'media-first' : '',
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
