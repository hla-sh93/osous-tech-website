/**
 * Text, in the editor: typed straight into the page.
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

export default function Edit( { attributes, setAttributes } ) {
	const { content, tagName, style, tone, weight, align, place, span } =
		attributes;
	const tag = tagName === 'div' ? 'div' : 'p';

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'text', style ),
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
				<PanelBody title={ __( 'Text', 'osous' ) }>
					<StyleSelect
						group="text"
						label={ __( 'Style', 'osous' ) }
						value={ style }
						onChange={ set( 'style' ) }
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
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'HTML element', 'osous' ) }
						help={ __(
							'A paragraph for running text; a plain box for a label.',
							'osous'
						) }
						value={ tag }
						options={ [
							{ value: 'p', label: __( 'Paragraph', 'osous' ) },
							{ value: 'div', label: __( 'Plain box', 'osous' ) },
						] }
						onChange={ set( 'tagName' ) }
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
				placeholder={ __( 'Text…', 'osous' ) }
			/>
		</>
	);
}
