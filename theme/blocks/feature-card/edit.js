/**
 * A feature of a split section, in the editor.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { FORMATS, ImageControl, Img, Toggle } from '../shared/controls';
import { cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { title, text, image, mediaFirst } = attributes;

	const blockProps = useBlockProps( { className: 'feature-card' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Feature', 'osous' ) }>
					<ImageControl
						label={ __( 'Picture', 'osous' ) }
						value={ image }
						onChange={ set( 'image' ) }
					/>
					<Toggle
						label={ __( 'Picture first on a phone', 'osous' ) }
						checked={ mediaFirst }
						onChange={ set( 'mediaFirst' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<div className="feature-content-item">
					<div className="feature-content-item-content">
						<RichText
							tagName="h3"
							className="h2"
							value={ title }
							onChange={ set( 'title' ) }
							allowedFormats={ FORMATS }
							placeholder={ __( 'Title…', 'osous' ) }
						/>
						<RichText
							tagName="p"
							className="p"
							value={ text }
							onChange={ set( 'text' ) }
							allowedFormats={ FORMATS }
							placeholder={ __( 'Text…', 'osous' ) }
						/>
					</div>
				</div>
				<div
					className={ cx(
						'feature-image-item',
						mediaFirst && 'media-first'
					) }
				>
					<Img image={ image } className="feature-image-item-image" />
				</div>
			</div>
		</>
	);
}
