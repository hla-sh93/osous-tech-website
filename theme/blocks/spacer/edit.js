/**
 * A spacer, in the editor.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { StyleSelect } from '../shared/controls';
import { classFor } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { size } = attributes;

	const blockProps = useBlockProps( {
		className: classFor( 'spacer', size ) || 'spacer-3x',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Spacer', 'osous' ) }>
					<StyleSelect
						group="spacer"
						label={ __( 'Height', 'osous' ) }
						value={ size }
						onChange={ ( value ) =>
							setAttributes( { size: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }></div>
		</>
	);
}
