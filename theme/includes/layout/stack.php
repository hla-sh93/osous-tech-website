<?php
/**
 * A stack: its contents one under the other, with a small gap.
 *
 * The osous/stack block prints this file: blocks/stack/block.json names it as the block's
 * render, and blocks/stack/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

?>
<div<?php echo osous_class_attr( 'content', isset( $attributes['className'] ) ? $attributes['className'] : '' ); ?>><?php osous_inner( $content ); ?></div>
