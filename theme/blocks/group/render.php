<?php
/**
 * A group: a wrapper with the design's layout classes on it and nothing else.
 *
 * @package Osous
 */

$osous_tag = in_array( $attributes['tagName'], array( 'div', 'section', 'span', 'p' ), true ) ? $attributes['tagName'] : 'div';

echo '<' . tag_escape( $osous_tag ) . osous_class_attr( isset( $attributes['className'] ) ? $attributes['className'] : '' ) . '>';
osous_inner( $content );
echo '</' . tag_escape( $osous_tag ) . '>';
