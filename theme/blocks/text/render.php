<?php
/**
 * A paragraph or a label.
 *
 * @package Osous
 */

$osous_tag = 'div' === $attributes['tagName'] ? 'div' : 'p';

echo '<' . tag_escape( $osous_tag )
	. osous_class_attr(
		osous_option( 'text', $attributes['style'] ),
		osous_option( 'tone', $attributes['tone'] ),
		osous_option( 'weight', $attributes['weight'] ),
		'center' === $attributes['align'] ? 'a-c' : '',
		osous_option( 'align', $attributes['place'] ),
		osous_option( 'span', $attributes['span'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>' . osous_inline( $attributes['content'] ) . '</' . tag_escape( $osous_tag ) . '>';
