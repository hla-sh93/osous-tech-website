<?php
/**
 * A section heading: the area at the top of a section that introduces it.
 *
 * The osous/section-heading block prints this file: blocks/section-heading/block.json names it as the block's
 * render, and blocks/section-heading/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

echo '<div'
	. osous_class_attr(
		'heading',
		'center' === $attributes['align'] ? 'center' : '',
		osous_option( 'align', $attributes['place'] ),
		osous_option( 'span', $attributes['span'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
