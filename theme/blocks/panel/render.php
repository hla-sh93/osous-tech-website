<?php
/**
 * A panel: a lifted box or a plain card, coloured, with content inside.
 *
 * @package Osous
 */

$osous_kind = osous_option( 'panel', $attributes['kind'] );

echo '<div'
	. osous_class_attr(
		'' === $osous_kind ? 'modal' : $osous_kind,
		osous_option( 'surface', $attributes['surface'] ),
		osous_option( 'backdrop', $attributes['backdrop'] ),
		$attributes['fullHeight'] ? 'h-100' : '',
		isset( $attributes['className'] ) ? $attributes['className'] : ''
	)
	. '>';
osous_inner( $content );
echo '</div>';
