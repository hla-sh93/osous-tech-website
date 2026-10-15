<?php
/**
 * The design's vocabulary: every choice a block offers, and the class it stands for.
 *
 * One table, read by the templates under blocks/ (to turn a value into a class), by the
 * editor (to label the options), and by _tools/verify_presets.py (to prove every class
 * here has a rule in assets/css). Nothing is invented: each entry is a class the reference
 * pages already use, so each is a look the stylesheets already draw.
 *
 * Values are words -- "neutral", "tall", "small" -- rather than class names, because they
 * are what a person picks from a list and what ends up in the page's block markup. The
 * image backdrops are the exception: their classes are the design's own names for its
 * pictures (`bg-img-7`), and a second name for each would say nothing more.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The tables.
 *
 * Each group maps a value to `array( class, label )`. An empty value is the block's plain
 * state and always maps to no class.
 *
 * @return array<string,array<string,array{0:string,1:string}>>
 */
function osous_design() {
	static $design = null;

	if ( null !== $design ) {
		return $design;
	}

	/*
	 * The pictures the stylesheets draw behind a panel, by the design's own names for them.
	 * Not every number exists: the design has no picture 3, 4, 6 or 12 (3 and 6 are section
	 * backgrounds, the other two were never drawn), and _tools/verify_presets.py is what
	 * keeps this list to the rules assets/css actually has.
	 */
	$backdrops = array( '' => array( '', __( 'None', 'osous' ) ) );
	foreach ( array( 1, 2, 7, 8, 9, 10, 11, 13, 14, 15, 16 ) as $n ) {
		/* translators: %d: the number of the design's backdrop picture. */
		$backdrops[ "bg-img-{$n}" ] = array( "bg-img-{$n}", sprintf( __( 'Picture %d', 'osous' ), $n ) );
	}
	$backdrops['img-4']          = array( 'img-4', __( 'Picture 4, wide', 'osous' ) );
	$backdrops['bg-12']          = array( 'bg-12', __( 'Pattern 12', 'osous' ) );
	$backdrops['bg-17']          = array( 'bg-17', __( 'Pattern 17', 'osous' ) );
	$backdrops['bg-azura']       = array( 'bg-azura', __( 'Azure blue', 'osous' ) );
	$backdrops['bg-img-partner'] = array( 'bg-img-partner', __( 'Partner mark', 'osous' ) );

	$design = array(

		// ------------------------------------------------------------ the section --
		'spacing'    => array(
			'default'   => array( 'section', __( 'Normal', 'osous' ) ),
			'tall'      => array( 'section full', __( 'Tall', 'osous' ) ),
			'slim'      => array( 'section p-y-1', __( 'Slim', 'osous' ) ),
			'tight-top' => array( 'section top-tight', __( 'Tight at the top', 'osous' ) ),
			'none'      => array( 'section padding-none', __( 'No padding', 'osous' ) ),
			'flat'      => array( 'section p-y-0', __( 'No padding above or below', 'osous' ) ),
			'no-bottom' => array( 'section p-b-0', __( 'No padding below', 'osous' ) ),
			'flush'     => array( '', __( 'Flush', 'osous' ) ),
		),
		'background' => array(
			''               => array( '', __( 'White', 'osous' ) ),
			'neutral'        => array( 'c-surface-neutral', __( 'Light grey', 'osous' ) ),
			'white'          => array( 'c-surface-white', __( 'White panel', 'osous' ) ),
			'dark'           => array( 'c-surface-secondary', __( 'Dark blue', 'osous' ) ),
			'dark-pattern'   => array( 'c-surface-secondary bg-img-1', __( 'Dark blue, pattern', 'osous' ) ),
			'dark-pattern-2' => array( 'c-surface-secondary bg-img-2', __( 'Dark blue, second pattern', 'osous' ) ),
			'photo'          => array( 'bg-img-5', __( 'Photo backdrop', 'osous' ) ),
			'photo-2'        => array( 'overflow-hidden bg-3', __( 'Photo backdrop, second', 'osous' ) ),
		),

		/*
		 * Over a picture of the site's own (osous_picture_class()): the two veils the design
		 * lays over its own photographs -- black at half strength on the Azure page, the
		 * brand's dark blue at half strength on the full-height dark sections.
		 */
		'shade'      => array(
			''     => array( '', __( 'No', 'osous' ) ),
			'dark' => array( 'shade-dark', __( 'Darker', 'osous' ) ),
			'blue' => array( 'shade-blue', __( 'Darker, in the dark blue', 'osous' ) ),
		),
		'height'     => array(
			''               => array( '', __( 'As tall as its content', 'osous' ) ),
			'three-quarters' => array( 'h-75', __( 'Three quarters of the screen', 'osous' ) ),
			'screen'         => array( 'h-100-vh', __( 'The whole screen', 'osous' ) ),
		),

		// ---------------------------------------------------------- containers --
		'width'      => array(
			'default' => array( 'container', __( 'Standard', 'osous' ) ),
			'full'    => array( 'container-full', __( 'Full width', 'osous' ) ),
			'small'   => array( 'container-sm', __( 'Narrow', 'osous' ) ),
			'wide'    => array( 'container-default', __( 'Wide', 'osous' ) ),
		),
		'surface'    => array(
			''          => array( '', __( 'None', 'osous' ) ),
			'white'     => array( 'c-surface-white', __( 'White', 'osous' ) ),
			'neutral'   => array( 'c-surface-neutral', __( 'Light grey', 'osous' ) ),
			'primary'   => array( 'c-surface-primary', __( 'Purple', 'osous' ) ),
			'secondary' => array( 'c-surface-secondary', __( 'Dark blue', 'osous' ) ),
			'plain'     => array( 'c-white', __( 'Plain white', 'osous' ) ),
		),
		'backdrop'   => $backdrops,
		'panel'      => array(
			'modal' => array( 'modal', __( 'Lifted panel', 'osous' ) ),
			'card'  => array( 'card', __( 'Card', 'osous' ) ),
		),

		// ---------------------------------------------------------------- grids --
		'gap'        => array(
			''   => array( '', __( 'Normal', 'osous' ) ),
			'sm' => array( 'gap-sm', __( 'Small', 'osous' ) ),
			'md' => array( 'gap-md', __( 'Medium', 'osous' ) ),
			'1x' => array( 'gap-1x', __( 'One unit', 'osous' ) ),
		),
		'carousel'   => array(
			''        => array( '', __( 'Never', 'osous' ) ),
			'desktop' => array( 'grid-to-carousel', __( 'Below desktop width', 'osous' ) ),
			'mobile'  => array( 'carousel-in-mobile', __( 'On a phone', 'osous' ) ),
		),
		'align'      => array(
			''              => array( '', __( 'Top', 'osous' ) ),
			'center'        => array( 'self-center', __( 'Middle', 'osous' ) ),
			'start'         => array( 'self-start', __( 'Top, explicitly', 'osous' ) ),
			'center-tablet' => array( 'self-center-tablet', __( 'Middle on a tablet', 'osous' ) ),
			'justify-start' => array( 'justify-start', __( 'Left', 'osous' ) ),
			'both'          => array( 'place-center', __( 'Middle, both ways', 'osous' ) ),
			'across'        => array( 'justify-center', __( 'Centred across', 'osous' ) ),
			'end-tablet'    => array( 'self-end-tablet', __( 'Bottom on a tablet', 'osous' ) ),
		),
		'span'       => array(
			''  => array( '', __( 'One column', 'osous' ) ),
			'2' => array( 'span-2', __( 'Two columns', 'osous' ) ),
			'3' => array( 'span-3', __( 'Three columns', 'osous' ) ),
			'4' => array( 'span-4', __( 'Four columns', 'osous' ) ),
		),
		'tablet-row' => array(
			''    => array( '', __( 'Where the grid puts it', 'osous' ) ),
			'1'   => array( 'row-tablet-1', __( 'First row', 'osous' ) ),
			'2'   => array( 'row-tablet-2', __( 'Second row', 'osous' ) ),
			'3'   => array( 'row-tablet-3', __( 'Third row', 'osous' ) ),
			'3-4' => array( 'row-tablet-3-4', __( 'Third and fourth rows', 'osous' ) ),
			'4'   => array( 'row-tablet-4', __( 'Fourth row', 'osous' ) ),
			'5'   => array( 'row-tablet-5', __( 'Fifth row', 'osous' ) ),
		),
		'mobile-row' => array(
			''  => array( '', __( 'Where the grid puts it', 'osous' ) ),
			'4' => array( 'row-mobile-4', __( 'Fourth row', 'osous' ) ),
			'5' => array( 'row-mobile-5', __( 'Fifth row', 'osous' ) ),
		),
		'direction'  => array(
			''            => array( '', __( 'As the blocks fall', 'osous' ) ),
			'stack'       => array( 'flex-v', __( 'Stacked, with a gap', 'osous' ) ),
			'stack-tight' => array( 'vflex', __( 'Stacked', 'osous' ) ),
			'row'         => array( 'flex-h', __( 'Side by side, with a gap', 'osous' ) ),
			'row-tight'   => array( 'hflex', __( 'Side by side', 'osous' ) ),
		),
		'arrange'    => array(
			''            => array( '', __( 'Top left', 'osous' ) ),
			'center'      => array( 'a-c-c', __( 'Centred', 'osous' ) ),
			'left-middle' => array( 'a-l-c', __( 'Left, middle', 'osous' ) ),
		),

		// ----------------------------------------------------------------- text --
		'heading'    => array(
			''      => array( '', __( 'Same as its level', 'osous' ) ),
			'h1'    => array( 'h1', __( 'Largest', 'osous' ) ),
			'h2'    => array( 'h2', __( 'Large', 'osous' ) ),
			'h3'    => array( 'h3', __( 'Medium', 'osous' ) ),
			'label' => array( 't-r-md', __( 'Label', 'osous' ) ),
			'none'  => array( '', __( 'Unstyled', 'osous' ) ),
		),
		'text'       => array(
			'p'           => array( 'p', __( 'Paragraph', 'osous' ) ),
			'p-r-sm'      => array( 'p-r-sm', __( 'Small paragraph', 'osous' ) ),
			'paragraph'   => array( 'paragraph', __( 'Card paragraph', 'osous' ) ),
			'paragraph-2' => array( 'paragraph-2', __( 'Narrow paragraph', 'osous' ) ),
			'p-b'         => array( 'p-b', __( 'Bold paragraph', 'osous' ) ),
			't-r-sm'      => array( 't-r-sm', __( 'Small label', 'osous' ) ),
			't-r-md'      => array( 't-r-md', __( 'Label', 'osous' ) ),
			't-r-lg'      => array( 't-r-lg', __( 'Large label', 'osous' ) ),
			't-m-lg'      => array( 't-m-lg', __( 'Large label, medium weight', 'osous' ) ),
			't-b-md'      => array( 't-b-md', __( 'Bold label', 'osous' ) ),
			't-b-lg'      => array( 't-b-lg', __( 'Large bold label', 'osous' ) ),
			'h2'          => array( 'h2', __( 'Heading, large', 'osous' ) ),
			'h3'          => array( 'h3', __( 'Heading, medium', 'osous' ) ),
			'none'        => array( '', __( 'Unstyled', 'osous' ) ),
		),
		'tone'       => array(
			''                  => array( '', __( 'Default', 'osous' ) ),
			'on-primary'        => array( 'c-on-primary', __( 'On purple', 'osous' ) ),
			'on-primary-subtle' => array( 'c-on-primary-subtle', __( 'On purple, subtle', 'osous' ) ),
			'on-secondary'      => array( 'c-on-secondary', __( 'On dark blue', 'osous' ) ),
			'on-neutral'        => array( 'c-on-neutral', __( 'On grey', 'osous' ) ),
			'on-neutral-subtle' => array( 'c-on-neutral-subtle', __( 'On grey, subtle', 'osous' ) ),
		),
		'weight'     => array(
			''     => array( '', __( 'Normal', 'osous' ) ),
			'thin' => array( 'thin', __( 'Thin', 'osous' ) ),
		),

		// --------------------------------------------------------------- images --
		'image-size' => array(
			''             => array( '', __( 'Natural', 'osous' ) ),
			'icon-24'      => array( 'icon-24', __( 'Icon, 24px', 'osous' ) ),
			'icon-60'      => array( 'icon-60', __( 'Icon, 60px', 'osous' ) ),
			'icon-128'     => array( 'icon-128', __( 'Icon, 128px', 'osous' ) ),
			'icon-160'     => array( 'icon-160', __( 'Icon, 160px', 'osous' ) ),
			'icon-256'     => array( 'icon-256', __( 'Icon, 256px', 'osous' ) ),
			'marquee-logo' => array( 'infinite-carousel-logo', __( 'Marquee logo', 'osous' ) ),
		),
		'image-fit'  => array(
			''       => array( '', __( 'Natural', 'osous' ) ),
			'half'   => array( 'image h-50', __( 'Half height', 'osous' ) ),
			'full'   => array( 'image h-100', __( 'Full height', 'osous' ) ),
			'header' => array( 'header-img-1', __( 'Header picture', 'osous' ) ),
			'tall'   => array( 'image-custom-1', __( 'Tall picture', 'osous' ) ),
		),

		// --------------------------------------------------------- small things --
		'button'     => array(
			'primary' => array( 'btn', __( 'Purple', 'osous' ) ),
			'outline' => array( 'btn outline', __( 'Outlined, with arrow', 'osous' ) ),
			'text'    => array( 'btn text', __( 'Text, with arrow', 'osous' ) ),
			'neutral' => array( 'btn neutral', __( 'Grey', 'osous' ) ),
		),
		'divider'    => array(
			'neutral'  => array( 'divider c-border-neutral', __( 'Thin grey line', 'osous' ) ),
			'gradient' => array( 'divider gradient-1', __( 'Gradient rule', 'osous' ) ),
		),
		'spacer'     => array(
			'1x' => array( 'spacer-1x', __( 'One unit', 'osous' ) ),
			'2x' => array( 'spacer-2x', __( 'Two units', 'osous' ) ),
			'3x' => array( 'spacer-3x', __( 'Three units', 'osous' ) ),
			'5x' => array( 'spacer-5x', __( 'Five units', 'osous' ) ),
			'8'  => array( 'spacer-8', __( 'Eight units', 'osous' ) ),
		),
		'list'       => array(
			'bullet' => array( 'list-item-bullet', __( 'Bullets', 'osous' ) ),
			'tick'   => array( 'list-item-tick', __( 'Ticks', 'osous' ) ),
		),
	);

	return $design;
}

/**
 * The class a value stands for.
 *
 * Unknown values give no class at all: a block's markup can carry only what the design
 * names, whatever finds its way into the attribute.
 *
 * @param string $group One of the keys of osous_design().
 * @param string $value The chosen value.
 * @return string Class names, space separated, or ''.
 */
function osous_option( $group, $value ) {
	$design = osous_design();
	$value  = (string) $value;

	return isset( $design[ $group ][ $value ] ) ? $design[ $group ][ $value ][0] : '';
}

/**
 * The tables in the shape the editor's select controls want: `{ group: [ { value, label } ] }`.
 *
 * @return array<string,array<int,array{value:string,label:string}>>
 */
function osous_design_for_editor() {
	$out = array();
	foreach ( osous_design() as $group => $options ) {
		$out[ $group ] = array();
		foreach ( $options as $value => $option ) {
			$out[ $group ][] = array(
				'value' => (string) $value,
				'label' => $option[1],
				'class' => $option[0],
			);
		}
	}

	return $out;
}
