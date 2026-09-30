<?php
/**
 * The items of a pinned list.
 *
 * The first item ships highlighted, as the design's markup does: the scroll script
 * moves the highlight from item to item from there.
 *
 * The osous/pinned-items block prints this file: blocks/pinned-items/block.json names it as the block's
 * render, and blocks/pinned-items/edit.js draws the same shape in the editor.
 *
 * @package Osous
 */

?>
<div class="list-items-wrapper"><?php osous_inner( preg_replace( '/class="list-item"/', 'class="list-item active"', $content, 1 ) ); ?></div>
