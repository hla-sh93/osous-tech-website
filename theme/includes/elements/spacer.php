<?php
/**
 * A spacer.
 *
 * The osous/spacer block prints this file: blocks/spacer/block.json names it as the block's
 * render, and blocks/spacer/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_size = osous_option( 'spacer', $attributes['size'] );
echo '<div' . osous_class_attr( '' === $osous_size ? 'spacer-3x' : $osous_size ) . '></div>';
