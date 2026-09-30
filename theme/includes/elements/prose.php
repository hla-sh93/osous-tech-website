<?php
/**
 * Prose: paragraphs, lists and headings, written in WordPress's own blocks.
 *
 * The osous/prose block prints this file: blocks/prose/block.json names it as the block's
 * render, and blocks/prose/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

echo '<div'
	. osous_class_attr(
		'plain' === $attributes['style'] ? '' : 'rich-text-block',
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( osous_prose_markup( $content ) );
echo '</div>';
