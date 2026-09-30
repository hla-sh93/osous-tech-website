<?php
/**
 * A list of points.
 *
 * `role="list"` is kept on purpose: the bullets are drawn by the stylesheet rather than
 * the browser, and some screen readers stop announcing a list once its markers are
 * styled away unless the role says so.
 *
 * The osous/list block prints this file: blocks/list/block.json names it as the block's
 * render, and blocks/list/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

?>
<ul role="list"<?php echo osous_class_attr( 'list', osous_option( 'tone', $attributes['tone'] ) ); ?>><?php osous_inner( $content ); ?></ul>
