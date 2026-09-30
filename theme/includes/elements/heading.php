<?php
/**
 * A heading.
 *
 * The tag is the heading's level; the size class is separate, because the design draws
 * a page title as an h1 in the h2 size and a card title as an h3 in the h2 size. Left
 * unset, the size follows the level.
 *
 * The osous/heading block prints this file: blocks/heading/block.json names it as the block's
 * render, and blocks/heading/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_tag  = osous_heading_tag( $attributes['level'] );
$osous_size = '' === $attributes['size'] ? $osous_tag : osous_option( 'heading', $attributes['size'] );

echo '<' . tag_escape( $osous_tag )
	. osous_class_attr(
		$osous_size,
		osous_option( 'tone', $attributes['tone'] ),
		osous_option( 'weight', $attributes['weight'] ),
		'center' === $attributes['align'] ? 'a-c' : '',
		osous_option( 'align', $attributes['place'] ),
		osous_option( 'span', $attributes['span'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>' . osous_inline( $attributes['content'] ) . '</' . tag_escape( $osous_tag ) . '>';
