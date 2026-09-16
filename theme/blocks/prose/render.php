<?php
/**
 * Prose: paragraphs, lists and headings, written in WordPress's own blocks.
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
