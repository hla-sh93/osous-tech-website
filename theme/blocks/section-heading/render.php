<?php
/**
 * A section heading: the area at the top of a section that introduces it.
 *
 * @package Osous
 */

echo '<div'
	. osous_class_attr(
		'heading',
		'center' === $attributes['align'] ? 'center' : '',
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
