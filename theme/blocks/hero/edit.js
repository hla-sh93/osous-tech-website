/**
 * The hero, in the editor.
 *
 * The marks are image blocks inside the first row of the marquee; the second row, which
 * only exists so the scroll never shows a gap, repeats them and is not edited.
 */
import {
	RichText,
	store as blockEditorStore,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { FORMATS, Img } from '../shared/controls';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, text } = attributes;

	const marks = useSelect(
		( select ) =>
			select( blockEditorStore )
				.getBlocks( clientId )
				.filter( ( block ) => block.name === 'osous/image' )
				.map( ( block ) => block.attributes ),
		[ clientId ]
	);

	const blockProps = useBlockProps( { className: 'bg-img-6 h-100-vh' } );
	const innerProps = useInnerBlocksProps(
		{ className: 'infinite-carousel' },
		{ template: [ [ 'osous/image', { size: 'marquee-logo' } ] ] }
	);
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
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
							placeholder={ __( 'A line under it…', 'osous' ) }
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
	);
}
