/**
 * A glass card, in the editor.
 *
 * Inside the card stack and the carousel the parent names a wrapper through block
 * context, and the card wraps itself in it here as it does on the page.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import {
	FORMATS,
	ImageControl,
	Img,
	Link,
	LinkField,
	NumberBadge,
	OptionalText,
} from '../shared/controls';

export default function Edit( {
	attributes,
	setAttributes,
	context,
	isSelected,
} ) {
	const { number, icon, link, media, title, text } = attributes;
	const wrapper = context[ 'osous/cardWrapper' ] || '';

	const blockProps = useBlockProps( {
		className: wrapper || 'card-4 bg-blur',
	} );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	const card = (
		<div { ...( wrapper ? { className: 'card-4 bg-blur' } : blockProps ) }>
			<NumberBadge
				value={ number }
				onChange={ set( 'number' ) }
				show={ isSelected }
			/>
			{ media !== 'none' && (
				<Link href={ link } className="card-media">
					<Img image={ icon } className="icon-128" />
				</Link>
			) }
			<div className="content">
				<OptionalText
					tagName="h3"
					value={ title }
					onChange={ set( 'title' ) }
					placeholder={ __( 'Title…', 'osous' ) }
					show={ isSelected }
				/>
				<RichText
					tagName="p"
					className="paragraph"
					value={ text }
					onChange={ set( 'text' ) }
					allowedFormats={ FORMATS }
					placeholder={ __( 'Text…', 'osous' ) }
				/>
			</div>
		</div>
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Card', 'osous' ) }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Icon area', 'osous' ) }
						value={ media === 'none' ? 'none' : 'box' }
						options={ [
							{ value: 'box', label: __( 'Shown', 'osous' ) },
							{ value: 'none', label: __( 'None', 'osous' ) },
						] }
						onChange={ set( 'media' ) }
					/>
					{ media !== 'none' && (
						<>
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
						</>
					) }
				</PanelBody>
			</InspectorControls>
			{ wrapper ? <div { ...blockProps }>{ card }</div> : card }
		</>
	);
}
