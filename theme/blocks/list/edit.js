/**
 * A list, in the editor.
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
	const { style, tone } = attributes;

	const blockProps = useBlockProps( {
		className: cx( 'list', classFor( 'tone', tone ) ),
		role: 'list',
	} );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'osous/list-item' ] ],
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'List', 'osous' ) }>
					<StyleSelect
						group="list"
						label={ __( 'Marker', 'osous' ) }
						value={ style }
						onChange={ ( value ) =>
							setAttributes( { style: value } )
						}
					/>
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
			<ul { ...innerProps } />
		</>
	);
}
