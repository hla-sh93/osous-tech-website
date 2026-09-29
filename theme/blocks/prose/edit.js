/**
 * Prose, in the editor: ordinary paragraphs, headings and lists inside the design's box.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { Toggle } from '../shared/controls';

export default function Edit( { attributes, setAttributes } ) {
	const { style } = attributes;

	const blockProps = useBlockProps( {
		className: style === 'plain' ? undefined : 'rich-text-block',
	} );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'core/paragraph' ] ],
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Prose', 'osous' ) }>
					<Toggle
						label={ __( 'Styled as an article', 'osous' ) }
						help={ __(
							'The design’s spacing for running text. Off, the words take the style of the box around them.',
							'osous'
						) }
						checked={ style !== 'plain' }
						onChange={ ( value ) =>
							setAttributes( {
								style: value ? 'block' : 'plain',
							} )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}
