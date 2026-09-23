/**
 * A divider, in the editor.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { StyleSelect, Toggle } from '../shared/controls';
import { classFor, cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { style, narrow, place, span } = attributes;
	const rule = cx(
		classFor( 'divider', style ) || 'divider c-border-neutral',
		classFor( 'align', place ),
		classFor( 'span', span )
	);

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
				<PanelBody
					title={ __( 'In the grid', 'osous' ) }
					initialOpen={ false }
				>
					<StyleSelect
						group="align"
						label={ __( 'Vertical position', 'osous' ) }
						value={ place }
						onChange={ ( value ) =>
							setAttributes( { place: value } )
						}
					/>
					<StyleSelect
						group="span"
						label={ __( 'Width', 'osous' ) }
						help={ __(
							'How many of the grid’s columns it takes.',
							'osous'
						) }
						value={ span }
						onChange={ ( value ) =>
							setAttributes( { span: value } )
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
