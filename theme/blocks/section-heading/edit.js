/**
 * A section heading, in the editor.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { Toggle } from '../shared/controls';
import { cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { align } = attributes;

	const blockProps = useBlockProps( {
		className: cx( 'heading', align === 'center' && 'center' ),
	} );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'osous/heading' ] ],
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'osous' ) }>
					<Toggle
						label={ __( 'Centred', 'osous' ) }
						checked={ align === 'center' }
						onChange={ ( value ) =>
							setAttributes( { align: value ? 'center' : '' } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}
