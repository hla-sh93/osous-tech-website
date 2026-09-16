<?php
/**
 * A stack: its contents one under the other, with a small gap.
 *
 * @package Osous
 */

?>
<div<?php echo osous_class_attr( 'content', isset( $attributes['className'] ) ? $attributes['className'] : '' ); ?>><?php osous_inner( $content ); ?></div>
