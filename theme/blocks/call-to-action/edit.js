/**
 * A call to action, in the editor.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { Arrow, FORMATS, Link, LinkField } from '../shared/controls';

export default function Edit( { attributes, setAttributes } ) {
	const { title, text, buttonText, buttonUrl } = attributes;

	const blockProps = useBlockProps( { className: 'section top-tight' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Button', 'osous' ) }>
					<LinkField
						value={ buttonUrl }
						onChange={ set( 'buttonUrl' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="container c-surface-white c-border-neutral">
					<div className="heading center">
						<RichText
							tagName="h2"
							className="h2 a-c"
							value={ title }
							onChange={ set( 'title' ) }
							allowedFormats={ FORMATS }
							placeholder={ __( 'Heading…', 'osous' ) }
						/>
						<RichText
							tagName="p"
							className="p a-c"
							value={ text }
							onChange={ set( 'text' ) }
							allowedFormats={ FORMATS }
							placeholder={ __( 'Text…', 'osous' ) }
						/>
						<div className="spacer-3x"></div>
						<Link href={ buttonUrl } className="btn outline">
							<RichText
								tagName="div"
								className="t-r-md"
								value={ buttonText }
								onChange={ set( 'buttonText' ) }
								allowedFormats={ [] }
								placeholder={ __( 'Button…', 'osous' ) }
							/>
							<div className="icon-24 c-on-neutral">
								<Arrow />
							</div>
						</Link>
					</div>
				</div>
			</section>
		</>
	);
}
