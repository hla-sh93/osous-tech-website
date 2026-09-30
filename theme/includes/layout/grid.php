<?php
/**
 * A grid: its cells are the blocks inside it.
 *
 * The osous/grid block prints this file: blocks/grid/block.json names it as the block's
 * render, and blocks/grid/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_columns = max( 1, min( 5, (int) $attributes['columns'] ) );

echo '<div'
	. osous_class_attr(
		'grid-' . $osous_columns,
		osous_option( 'gap', $attributes['gap'] ),
		osous_option( 'carousel', $attributes['carousel'] ),
		$attributes['fullHeight'] ? 'h-100' : '',
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
