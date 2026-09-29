/**
 * A section, in the editor: the band, with its container inside.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { StyleSelect, Toggle } from '../shared/controls';
import { classFor, cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { spacing, background, height, joinBelow } = attributes;

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'spacing', spacing ),
			classFor( 'background', background ),
			classFor( 'height', height ),
			joinBelow && 'm-b-0'
		),
	} );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'osous/container' ] ],
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Appearance', 'osous' ) }>
					<StyleSelect
						group="background"
						label={ __( 'Background', 'osous' ) }
						value={ background }
						onChange={ ( value ) =>
							setAttributes( { background: value } )
						}
					/>
					<StyleSelect
						group="spacing"
						label={ __( 'Spacing', 'osous' ) }
						help={ __(
							'How much room the section takes above and below its content.',
							'osous'
						) }
						value={ spacing }
						onChange={ ( value ) =>
							setAttributes( { spacing: value } )
						}
					/>
					<StyleSelect
						group="height"
						label={ __( 'Height', 'osous' ) }
						value={ height }
						onChange={ ( value ) =>
							setAttributes( { height: value } )
						}
					/>
					<Toggle
						label={ __( 'Join the section below', 'osous' ) }
						help={ __(
							'Removes the gap underneath, so the two read as one band.',
							'osous'
						) }
						checked={ joinBelow }
						onChange={ ( value ) =>
							setAttributes( { joinBelow: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...innerProps } />
		</>
	);
}
