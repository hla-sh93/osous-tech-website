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

import { StyleSelect, Toggle } from '../shared/controls';
import { classFor, cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { align, place, span } = attributes;

	const blockProps = useBlockProps( {
		className: cx(
			'heading',
			align === 'center' && 'center',
			classFor( 'align', place ),
			classFor( 'span', span )
		),
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
			<div { ...innerProps } />
		</>
	);
}
