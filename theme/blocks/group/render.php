<?php
/**
 * A group: a wrapper that stacks the blocks inside it or sets them side by side, and
 * takes its place in the grid around it.
 *
 * @package Osous
 */

$osous_tag = in_array( $attributes['tagName'], array( 'div', 'section', 'span', 'p' ), true ) ? $attributes['tagName'] : 'div';

echo '<' . tag_escape( $osous_tag )
	. osous_class_attr(
		osous_option( 'direction', $attributes['direction'] ),
		osous_option( 'arrange', $attributes['arrange'] ),
		osous_option( 'align', $attributes['place'] ),
		$attributes['stackOnMobile'] ? 'flex-v-on-mobile' : '',
		$attributes['fullHeight'] ? 'h-100' : '',
		osous_option( 'tablet-row', $attributes['tabletRow'] ),
		osous_option( 'mobile-row', $attributes['mobileRow'] ),
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</' . tag_escape( $osous_tag ) . '>';
