/**
 * The hero, in the editor.
 *
 * The marks are image blocks inside the first row of the marquee; the second row, which
 * only exists so the scroll never shows a gap, repeats them and is not edited.
 */
import {
	InspectorControls,
	RichText,
	store as blockEditorStore,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { FORMATS, Img, PictureControl, pictureProps } from '../shared/controls';
import { cx } from '../shared/design';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, text, picture, pictureShade } = attributes;
	const drawn = pictureProps( picture, pictureShade );

	const marks = useSelect(
		( select ) =>
			select( blockEditorStore )
				.getBlocks( clientId )
				.filter( ( block ) => block.name === 'osous/image' )
				.map( ( block ) => block.attributes ),
		[ clientId ]
	);

	const blockProps = useBlockProps( {
		className: cx( 'bg-img-6 h-100-vh', drawn.className ),
		style: drawn.style,
	} );
	const innerProps = useInnerBlocksProps(
		{ className: 'infinite-carousel' },
		{ template: [ [ 'osous/image', { size: 'marquee-logo' } ] ] }
	);
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Background', 'osous' ) }>
					<PictureControl
						picture={ picture }
						shade={ pictureShade }
						onChange={ setAttributes }
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="container h-85">
					<div className="flex-v a-c-c h-100">
						<div className="heading center">
							<RichText
								tagName="h1"
								className="h1 c-on-secondary a-c"
								value={ title }
								onChange={ set( 'title' ) }
								allowedFormats={ FORMATS }
								placeholder={ __( 'Headline…', 'osous' ) }
							/>
							<RichText
								tagName="p"
								className="p c-on-secondary a-c"
								value={ text }
								onChange={ set( 'text' ) }
								allowedFormats={ FORMATS }
								placeholder={ __(
									'A line under it…',
									'osous'
								) }
							/>
						</div>
					</div>
				</div>
				<div className="infinite-carousel-wrapper">
					<div { ...innerProps } />
					<div className="infinite-carousel" aria-hidden="true">
						{ marks.map( ( mark, i ) => (
							<Img
								key={ i }
								image={ { ...mark, alt: '' } }
								className="infinite-carousel-logo"
							/>
						) ) }
					</div>
				</div>
			</section>
		</>
	);
}
