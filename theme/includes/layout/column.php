<?php
/**
 * A column: one cell of a grid.
 *
 * The osous/column block prints this file: blocks/column/block.json names it as the block's
 * render, and blocks/column/edit.js draws the same shape in the editor.
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
		osous_option( 'tablet-row', $attributes['tabletRow'] ),
		osous_option( 'mobile-row', $attributes['mobileRow'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
