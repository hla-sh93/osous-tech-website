/**
 * A highlight card, in the editor.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import {
	FORMATS,
	ImageControl,
	Img,
	Link,
	LinkField,
	OptionalText,
	Toggle,
} from '../shared/controls';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { icon, link, title, text, outline } = attributes;

	const blockProps = useBlockProps( { className: 'card-5' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Card', 'osous' ) }>
					<ImageControl
						label={ __( 'Icon', 'osous' ) }
						value={ icon }
						onChange={ set( 'icon' ) }
					/>
					<LinkField
						label={ __( 'Icon links to', 'osous' ) }
						value={ link }
						onChange={ set( 'link' ) }
					/>
					<Toggle
						label={ __( 'Outline', 'osous' ) }
						checked={ outline }
						onChange={ set( 'outline' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<Link href={ link } className="card-media">
					<Img image={ icon } className="icon-128" />
				</Link>
				<OptionalText
					tagName="h3"
					className="h3 c-on-primary"
					value={ title }
					onChange={ set( 'title' ) }
					placeholder={ __( 'Title…', 'osous' ) }
					show={ isSelected }
				/>
				<RichText
					tagName="p"
					className="p-r-sm c-on-secondary position-relative"
					value={ text }
					onChange={ set( 'text' ) }
					allowedFormats={ FORMATS }
					placeholder={ __( 'Text…', 'osous' ) }
				/>
				<div className="card3-bg"></div>
				{ outline && <div className="card1-outline"></div> }
			</div>
		</>
	);
}
