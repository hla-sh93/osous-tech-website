<?php
/**
 * One item of a pinned list.
 *
 * @package Osous
 */

$osous_title_classes = array(
	'medium' => 't-m-lg',
	'bold'   => 't-b-lg',
);
$osous_text_classes  = array(
	'normal' => 'p',
	'small'  => 'p-r-sm',
	'plain'  => '',
	'bold'   => 'p-b',
);
$osous_title_class   = isset( $osous_title_classes[ $attributes['titleStyle'] ] ) ? $osous_title_classes[ $attributes['titleStyle'] ] : 't-m-lg';
$osous_text_class    = isset( $osous_text_classes[ $attributes['textStyle'] ] ) ? $osous_text_classes[ $attributes['textStyle'] ] : 'p';
?>
<div class="list-item">
	<?php if ( '' !== $attributes['title'] ) : ?>
		<div<?php echo osous_class_attr( $osous_title_class ); ?>><?php echo osous_inline( $attributes['title'] ); ?></div>
	<?php endif; ?>
	<?php if ( '' !== $attributes['text'] ) : ?>
		<p<?php echo osous_class_attr( $osous_text_class ); ?>><?php echo osous_inline( $attributes['text'] ); ?></p>
	<?php endif; ?>
</div>
