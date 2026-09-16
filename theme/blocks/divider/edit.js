/**
 * A divider, in the editor.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { StyleSelect, Toggle } from '../shared/controls';
import { classFor } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { style, narrow } = attributes;
	const rule = classFor( 'divider', style ) || 'divider c-border-neutral';

	const blockProps = useBlockProps( {
		className: narrow ? '_w-max-200' : rule,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Divider', 'osous' ) }>
					<StyleSelect
						group="divider"
						label={ __( 'Style', 'osous' ) }
						value={ style }
						onChange={ ( value ) =>
							setAttributes( { style: value } )
						}
					/>
					<Toggle
						label={ __( 'Short', 'osous' ) }
						help={ __(
							'200px wide rather than the width of its column.',
							'osous'
						) }
						checked={ narrow }
						onChange={ ( value ) =>
							setAttributes( { narrow: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			{ narrow ? (
				<div { ...blockProps }>
					<div className={ rule }></div>
				</div>
			) : (
				<div { ...blockProps }></div>
			) }
		</>
	);
}
