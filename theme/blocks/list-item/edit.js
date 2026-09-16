/**
 * A list item, in the editor. Its marker comes from the list it is in.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { FORMATS, StyleSelect } from '../shared/controls';
import { classFor, cx } from '../shared/design';

export default function Edit( { attributes, setAttributes, context } ) {
	const { content, tone } = attributes;
	const marker =
		classFor( 'list', context[ 'osous/listStyle' ] ) || 'list-item-bullet';

	const blockProps = useBlockProps( { className: marker } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'List item', 'osous' ) }>
					<StyleSelect
						group="tone"
						label={ __( 'Colour', 'osous' ) }
						value={ tone }
						onChange={ ( value ) =>
							setAttributes( { tone: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<li { ...blockProps }>
				<RichText
					tagName="div"
					className={ cx( 't-r-md', classFor( 'tone', tone ) ) }
					value={ content }
					onChange={ ( value ) =>
						setAttributes( { content: value } )
					}
					allowedFormats={ FORMATS }
					placeholder={ __( 'Point…', 'osous' ) }
				/>
			</li>
		</>
	);
}
