/**
 * A split section, in the editor.
 *
 * The scroll script does not run here, so the features are laid out one after another
 * (assets/css/editor.css unclips the window); a dot is drawn for each, as on the page.
 */
import {
	RichText,
	store as blockEditorStore,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { FORMATS, OptionalText } from '../shared/controls';
import { cx } from '../shared/design';

export default function Edit( {
	attributes,
	setAttributes,
	clientId,
	isSelected,
} ) {
	const { title, text } = attributes;

	const count = useSelect(
		( select ) => select( blockEditorStore ).getBlockCount( clientId ),
		[ clientId ]
	);

	const blockProps = useBlockProps( { className: 'section split-section' } );
	const innerProps = useInnerBlocksProps(
		{ className: 'feature-card-wrapper' },
		{ template: [ [ 'osous/feature-card' ] ] }
	);
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	const heading = (
		<RichText
			tagName="h2"
			className="t-r-md"
			value={ title }
			onChange={ set( 'title' ) }
			allowedFormats={ FORMATS }
			placeholder={ __( 'Heading…', 'osous' ) }
		/>
	);

	return (
		<section { ...blockProps }>
			<div className="container-full c-surface-neutral h-100">
				<div className="misc">
					<div className="div-block">
						{ text || isSelected ? (
							<div className="heading">
								{ heading }
								<OptionalText
									tagName="p"
									value={ text }
									onChange={ set( 'text' ) }
									placeholder={ __(
										'A line under the heading…',
										'osous'
									) }
									show={ isSelected }
								/>
							</div>
						) : (
							heading
						) }
						<div className="hide-on-tablet">
							<div className="flex-h">
								{ Array.from( { length: count }, ( _, i ) => (
									<div
										key={ i }
										className={ cx(
											'indicator',
											i === 0 && 'active'
										) }
									></div>
								) ) }
							</div>
						</div>
					</div>
				</div>
				<div { ...innerProps } />
			</div>
		</section>
	);
}
