<?php
/**
 * A spacer.
 *
 * @package Osous
 */

$osous_size = osous_option( 'spacer', $attributes['size'] );
echo '<div' . osous_class_attr( '' === $osous_size ? 'spacer-3x' : $osous_size ) . '></div>';
