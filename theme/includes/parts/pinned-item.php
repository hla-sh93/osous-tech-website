<?php
/**
 * One point of a pinned list: a title and a line of text.
 *
 * Called as: get_template_part( 'includes/parts/pinned-item', null, array( 'title' => …, 'text' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $title      The title.
 *     @type string $text       The text.
 *     @type string $titleStyle 'medium' or 'bold'.
 *     @type string $textStyle  'normal', 'small', 'plain' or 'bold'.
 *     @type bool   $active     The highlighted item -- the first, as the page loads.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'title'      => '',
		'text'       => '',
		'titleStyle' => 'medium',
		'textStyle'  => 'normal',
		'active'     => false,
	)
);

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
$osous_title_class   = isset( $osous_title_classes[ $osous_args['titleStyle'] ] ) ? $osous_title_classes[ $osous_args['titleStyle'] ] : 't-m-lg';
$osous_text_class    = isset( $osous_text_classes[ $osous_args['textStyle'] ] ) ? $osous_text_classes[ $osous_args['textStyle'] ] : 'p';
?>
<div<?php echo osous_class_attr( 'list-item', $osous_args['active'] ? 'active' : '' ); ?>>
	<?php if ( '' !== $osous_args['title'] ) : ?>
		<div<?php echo osous_class_attr( $osous_title_class ); ?>><?php echo osous_inline( $osous_args['title'] ); ?></div>
	<?php endif; ?>
	<?php if ( '' !== $osous_args['text'] ) : ?>
		<p<?php echo osous_class_attr( $osous_text_class ); ?>><?php echo osous_inline( $osous_args['text'] ); ?></p>
	<?php endif; ?>
</div>
