/**
 * A purple card, in the editor.
 *
 * Its longer text is ordinary blocks -- paragraphs and lists -- typed inside the card.
 * The area for them is drawn when there are some, or while the card is selected.
 */
import {
	InnerBlocks,
	InspectorControls,
	RichText,
	store as blockEditorStore,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import {
	Field,
	ImageControl,
	Img,
	Link,
	LinkField,
	NumberBadge,
	OptionalText,
	Toggle,
} from '../shared/controls';

export default function Edit( {
	attributes,
	setAttributes,
	clientId,
	isSelected,
} ) {
	const { number, icon, fill, title, text, buttonText, buttonUrl, outline } =
		attributes;

	const hasProse = useSelect(
		( select ) => select( blockEditorStore ).getBlockCount( clientId ) > 0,
		[ clientId ]
	);

	const blockProps = useBlockProps( { className: 'card-2' } );
	const proseProps = useInnerBlocksProps(
		{ className: 'rich-text-block' },
		{
			template: [ [ 'core/paragraph' ] ],
			renderAppender: hasProse
				? undefined
				: InnerBlocks.ButtonBlockAppender,
		}
	);
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Card', 'osous' ) }>
					<ImageControl
						label={ __( 'Small icon', 'osous' ) }
						value={ icon }
						onChange={ set( 'icon' ) }
					/>
					<Toggle
						label={ __( 'Filled', 'osous' ) }
						checked={ fill }
						onChange={ set( 'fill' ) }
					/>
					<Toggle
						label={ __( 'Outline', 'osous' ) }
						checked={ outline }
						onChange={ set( 'outline' ) }
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
				<NumberBadge
					value={ number }
					onChange={ set( 'number' ) }
					show={ isSelected }
				/>
				<Img image={ icon } className="icon-60" />
				<OptionalText
					tagName="h3"
					className="h3 c-on-primary"
					value={ title }
					onChange={ set( 'title' ) }
					placeholder={ __( 'Title…', 'osous' ) }
					show={ isSelected }
				/>
				<OptionalText
					tagName="p"
					className="p c-on-primary-subtle"
					value={ text }
					onChange={ set( 'text' ) }
					placeholder={ __( 'Text…', 'osous' ) }
					show={ isSelected }
				/>
				{ fill && <div className="card2-background"></div> }
				{ ( hasProse || isSelected ) && <div { ...proseProps } /> }
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
				{ outline && <div className="card1-outline"></div> }
			</div>
		</>
	);
}
