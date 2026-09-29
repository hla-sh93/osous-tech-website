/**
 * A partner's mark, in the editor.
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
} from '../shared/controls';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { logo, link, caption } = attributes;
	const captioned = caption !== '' || isSelected;

	const blockProps = useBlockProps( {
		className: captioned ? 'partner-card' : 'partner-card-logo _w-100',
	} );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	const mark = (
		<Link href={ link } className="card-media">
			<Img image={ logo } className="icon-128" />
		</Link>
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Partner', 'osous' ) }>
					<ImageControl
						label={ __( 'Mark', 'osous' ) }
						value={ logo }
						onChange={ set( 'logo' ) }
					/>
					<LinkField
						label={ __( 'Links to', 'osous' ) }
						value={ link }
						onChange={ set( 'link' ) }
					/>
				</PanelBody>
			</InspectorControls>
			{ captioned ? (
				<div { ...blockProps }>
					<div className="partner-card-logo">{ mark }</div>
					<RichText
						tagName="p"
						className="p"
						value={ caption }
						onChange={ set( 'caption' ) }
						allowedFormats={ FORMATS }
						placeholder={ __(
							'Caption, or leave empty…',
							'osous'
						) }
					/>
				</div>
			) : (
				<div { ...blockProps }>{ mark }</div>
			) }
		</>
	);
}
