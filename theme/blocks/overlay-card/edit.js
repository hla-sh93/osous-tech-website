/**
 * A photo card, in the editor.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { FORMATS, ImageControl, Img, NumberBadge } from '../shared/controls';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { number, title, text, image } = attributes;

	const blockProps = useBlockProps( { className: 'card-3' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Card', 'osous' ) }>
					<ImageControl
						label={ __( 'Photograph', 'osous' ) }
						value={ image }
						onChange={ set( 'image' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<NumberBadge
					value={ number }
					onChange={ set( 'number' ) }
					show={ isSelected }
				/>
				<div className="div-block-2">
					<RichText
						tagName="h3"
						className="h3 c-on-primary"
						value={ title }
						onChange={ set( 'title' ) }
						allowedFormats={ FORMATS }
						placeholder={ __( 'Title…', 'osous' ) }
					/>
					<RichText
						tagName="p"
						className="p c-on-primary-subtle"
						value={ text }
						onChange={ set( 'text' ) }
						allowedFormats={ FORMATS }
						placeholder={ __( 'Text…', 'osous' ) }
					/>
				</div>
				<div className="card-3-gradient"></div>
				<Img image={ image } className="card-3-image" />
			</div>
		</>
	);
}
