<?php
/**
 * One point of a list. Its marker -- bullet or tick -- comes from the list it is in.
 *
 * The osous/list-item block prints this file: blocks/list-item/block.json names it as the block's
 * render, and blocks/list-item/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

$osous_style = isset( $block->context['osous/listStyle'] ) ? osous_option( 'list', $block->context['osous/listStyle'] ) : '';
?>
<li<?php echo osous_class_attr( '' === $osous_style ? 'list-item-bullet' : $osous_style ); ?>><div<?php echo osous_class_attr( 't-r-md', osous_option( 'tone', $attributes['tone'] ) ); ?>><?php echo osous_inline( $attributes['content'] ); ?></div></li>
