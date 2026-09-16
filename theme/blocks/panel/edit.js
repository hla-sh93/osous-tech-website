/**
 * A panel, in the editor.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { StyleSelect } from '../shared/controls';
import { classFor, cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { kind, surface, backdrop } = attributes;

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'panel', kind ) || 'modal',
			classFor( 'surface', surface ),
			classFor( 'backdrop', backdrop )
		),
	} );
	const innerProps = useInnerBlocksProps( blockProps );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Panel', 'osous' ) }>
					<StyleSelect
						group="panel"
						label={ __( 'Kind', 'osous' ) }
						value={ kind }
						onChange={ ( value ) =>
							setAttributes( { kind: value } )
						}
					/>
					<StyleSelect
						group="surface"
						label={ __( 'Colour', 'osous' ) }
						value={ surface }
						onChange={ ( value ) =>
							setAttributes( { surface: value } )
						}
					/>
					<StyleSelect
						group="backdrop"
						label={ __( 'Backdrop picture', 'osous' ) }
						value={ backdrop }
						onChange={ ( value ) =>
							setAttributes( { backdrop: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}
