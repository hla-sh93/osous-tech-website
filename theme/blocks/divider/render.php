<?php
/**
 * A divider.
 *
 * @package Osous
 */

$osous_style = osous_option( 'divider', $attributes['style'] );
if ( $attributes['narrow'] ) {
	echo '<div class="_w-max-200">';
}
echo '<div' . osous_class_attr( '' === $osous_style ? 'divider c-border-neutral' : $osous_style, isset( $attributes['className'] ) ? $attributes['className'] : '' ) . '></div>';
if ( $attributes['narrow'] ) {
	echo '</div>';
}
