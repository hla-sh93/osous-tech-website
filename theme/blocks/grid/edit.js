/**
 * A grid, in the editor.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { StyleSelect, Toggle } from '../shared/controls';
import { classFor, cx } from '../shared/design';

const COLUMNS = [
	{ value: 1, label: __( 'One column', 'osous' ) },
	{ value: 2, label: __( 'Two columns', 'osous' ) },
	{ value: 3, label: __( 'Three columns', 'osous' ) },
	{ value: 4, label: __( 'Four columns', 'osous' ) },
	{ value: 5, label: __( 'Five columns', 'osous' ) },
];

export default function Edit( { attributes, setAttributes } ) {
	const { columns, gap, carousel, fullHeight } = attributes;
	const count = Math.max( 1, Math.min( 5, parseInt( columns, 10 ) || 2 ) );

	const blockProps = useBlockProps( {
		className: cx(
			`grid-${ count }`,
			classFor( 'gap', gap ),
			classFor( 'carousel', carousel ),
			fullHeight && 'h-100'
		),
	} );
	const innerProps = useInnerBlocksProps( blockProps );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'osous' ) }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Columns', 'osous' ) }
						help={ __(
							'How many across on a wide screen. They stack on a phone either way.',
							'osous'
						) }
						value={ count }
						options={ COLUMNS }
						onChange={ ( value ) =>
							setAttributes( { columns: parseInt( value, 10 ) } )
						}
					/>
					<StyleSelect
						group="gap"
						label={ __( 'Gap', 'osous' ) }
						value={ gap }
						onChange={ ( value ) =>
							setAttributes( { gap: value } )
						}
					/>
					<StyleSelect
						group="carousel"
						label={ __( 'Scrolls sideways', 'osous' ) }
						value={ carousel }
						onChange={ ( value ) =>
							setAttributes( { carousel: value } )
						}
					/>
					<Toggle
						label={ __( 'Full height', 'osous' ) }
						checked={ fullHeight }
						onChange={ ( value ) =>
							setAttributes( { fullHeight: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}
