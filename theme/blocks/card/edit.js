/**
 * A card, in the editor.
 *
 * The words are typed in place. The parts a card may or may not have -- the outline,
 * the fill, the icon, the figure, the button -- are switched in the sidebar, and a part
 * with no words is drawn only while the card is selected, so the canvas holds exactly
 * the elements the page will.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import {
	Field,
	ImageControl,
	Img,
	Link,
	LinkField,
	OptionalText,
	Toggle,
} from '../shared/controls';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const {
		icon,
		link,
		media,
		outline,
		fill,
		stat,
		title,
		text,
		buttonText,
		buttonUrl,
	} = attributes;

	const blockProps = useBlockProps( { className: 'card-1' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

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
					<Toggle
						label={ __( 'Outline', 'osous' ) }
						checked={ outline }
						onChange={ set( 'outline' ) }
					/>
					<Toggle
						label={ __( 'Filled', 'osous' ) }
						checked={ fill }
						onChange={ set( 'fill' ) }
					/>
					<Field
						label={ __( 'Large figure', 'osous' ) }
						help={ __(
							'A number or a percentage drawn big above the title, or nothing.',
							'osous'
						) }
						value={ stat }
						onChange={ set( 'stat' ) }
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'Button', 'osous' ) }
					initialOpen={ false }
				>
					<Field
						label={ __( 'Label', 'osous' ) }
						help={ __( 'Leave empty for no button.', 'osous' ) }
						value={ buttonText }
						onChange={ set( 'buttonText' ) }
					/>
					<LinkField
						value={ buttonUrl }
						onChange={ set( 'buttonUrl' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ outline && <div className="card1-outline"></div> }
				{ media !== 'none' && (
					<Link href={ link } className="card-media">
						<Img image={ icon } className="icon-128" />
					</Link>
				) }
				{ fill && <div className="card1-background"></div> }
				{ stat && (
					<div className="h2 thin position-relative-10">{ stat }</div>
				) }
				<OptionalText
					tagName="h3"
					className="position-relative-10"
					value={ title }
					onChange={ set( 'title' ) }
					placeholder={ __( 'Title…', 'osous' ) }
					show={ isSelected }
				/>
				<OptionalText
					tagName="p"
					className="position-relative-10"
					value={ text }
					onChange={ set( 'text' ) }
					placeholder={ __( 'Text…', 'osous' ) }
					show={ isSelected }
				/>
				{ buttonText && (
					<Link
						href={ buttonUrl }
						className="btn _w-max-200 m-t-auto"
					>
						<RichText
							tagName="div"
							className="t-r-md c-on-primary"
							value={ buttonText }
							onChange={ set( 'buttonText' ) }
							allowedFormats={ [] }
						/>
					</Link>
				) }
			</div>
		</>
	);
}
