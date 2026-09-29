/**
 * The card stack, in the editor.
 *
 * The scroll script does not run here, so the cards sit one under another
 * (assets/css/editor.css lays them out plainly), which is what there is to edit.
 */
import {
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { FORMATS } from '../shared/controls';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'section overlapping-cards',
	} );
	const innerProps = useInnerBlocksProps(
		{ className: 'cards-overlapping-wrapper' },
		{ template: [ [ 'osous/photo-card', { media: 'none' } ] ] }
	);

	return (
		<section { ...blockProps }>
			<div className="flex-v h-100 a-c-c">
				<div className="container">
					<div className="grid-2">
						<div className="cell self-center why-us-heading">
							<RichText
								tagName="h2"
								className="h2 c-on-secondary"
								value={ attributes.title }
								onChange={ ( value ) =>
									setAttributes( { title: value } )
								}
								allowedFormats={ FORMATS }
								placeholder={ __( 'Heading…', 'osous' ) }
							/>
						</div>
						<div { ...innerProps } />
					</div>
				</div>
			</div>
		</section>
	);
}
