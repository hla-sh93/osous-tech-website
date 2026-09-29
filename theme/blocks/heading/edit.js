/**
 * A heading, in the editor: typed straight into the page.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { FORMATS, StyleSelect } from '../shared/controls';
import { classFor, cx } from '../shared/design';

const LEVELS = [ 1, 2, 3, 4, 5, 6 ].map( ( level ) => ( {
	value: level,
	/* translators: %d: heading level, 1 to 6. */
	label: __( 'Heading %d', 'osous' ).replace( '%d', level ),
} ) );

export default function Edit( { attributes, setAttributes } ) {
	const { content, level, size, tone, weight, align, place, span } =
		attributes;
	const tag = `h${ level >= 1 && level <= 6 ? level : 2 }`;

	const blockProps = useBlockProps( {
		className: cx(
			size === '' ? tag : classFor( 'heading', size ),
			classFor( 'tone', tone ),
			classFor( 'weight', weight ),
			align === 'center' && 'a-c',
			classFor( 'align', place ),
			classFor( 'span', span )
		),
	} );

	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Heading', 'osous' ) }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Level', 'osous' ) }
						help={ __(
							'The outline of the page: one level-1 heading, then 2, then 3.',
							'osous'
						) }
						value={ level }
						options={ LEVELS }
						onChange={ ( value ) =>
							setAttributes( { level: parseInt( value, 10 ) } )
						}
					/>
					<StyleSelect
						group="heading"
						label={ __( 'Size', 'osous' ) }
						value={ size }
						onChange={ set( 'size' ) }
					/>
					<StyleSelect
						group="tone"
						label={ __( 'Colour', 'osous' ) }
						value={ tone }
						onChange={ set( 'tone' ) }
					/>
					<StyleSelect
						group="weight"
						label={ __( 'Weight', 'osous' ) }
						value={ weight }
						onChange={ set( 'weight' ) }
					/>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Alignment', 'osous' ) }
						value={ align }
						options={ [
							{ value: '', label: __( 'Left', 'osous' ) },
							{ value: 'center', label: __( 'Centre', 'osous' ) },
						] }
						onChange={ set( 'align' ) }
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'In the grid', 'osous' ) }
					initialOpen={ false }
				>
					<StyleSelect
						group="align"
						label={ __( 'Vertical position', 'osous' ) }
						value={ place }
						onChange={ set( 'place' ) }
					/>
					<StyleSelect
						group="span"
						label={ __( 'Width', 'osous' ) }
						help={ __(
							'How many of the grid’s columns it takes.',
							'osous'
						) }
						value={ span }
						onChange={ set( 'span' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<RichText
				{ ...blockProps }
				identifier="content"
				tagName={ tag }
				value={ content }
				onChange={ set( 'content' ) }
				allowedFormats={ FORMATS }
				placeholder={ __( 'Heading…', 'osous' ) }
			/>
		</>
	);
}
