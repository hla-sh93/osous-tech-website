/**
 * A group, in the editor.
 *
 * A wrapper that stacks the blocks inside it or sets them side by side, arranges them,
 * and takes its place in the grid around it. Anything the design gave a wrapper that is
 * none of these still rides in the Advanced panel's "Additional CSS class(es)".
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

const TAGS = [
	{ value: 'div', label: 'div' },
	{ value: 'section', label: 'section' },
	{ value: 'span', label: 'span' },
	{ value: 'p', label: 'p' },
];

export default function Edit( { attributes, setAttributes } ) {
	const {
		direction,
		arrange,
		place,
		stackOnMobile,
		fullHeight,
		tabletRow,
		mobileRow,
	} = attributes;
	const Tag = TAGS.some( ( tag ) => tag.value === attributes.tagName )
		? attributes.tagName
		: 'div';

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'direction', direction ),
			classFor( 'arrange', arrange ),
			classFor( 'align', place ),
			stackOnMobile && 'flex-v-on-mobile',
			fullHeight && 'h-100',
			classFor( 'tablet-row', tabletRow ),
			classFor( 'mobile-row', mobileRow )
		),
	} );
	const innerProps = useInnerBlocksProps( blockProps );

	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'osous' ) }>
					<StyleSelect
						group="direction"
						label={ __( 'The blocks inside', 'osous' ) }
						value={ direction }
						onChange={ set( 'direction' ) }
					/>
					<StyleSelect
						group="arrange"
						label={ __( 'Arranged', 'osous' ) }
						value={ arrange }
						onChange={ set( 'arrange' ) }
					/>
					<Toggle
						label={ __( 'Stacked on a phone', 'osous' ) }
						checked={ stackOnMobile }
						onChange={ set( 'stackOnMobile' ) }
					/>
					<Toggle
						label={ __( 'Full height', 'osous' ) }
						checked={ fullHeight }
						onChange={ set( 'fullHeight' ) }
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
						onChange={ set( 'place' ) }
					/>
					<StyleSelect
						group="tablet-row"
						label={ __( 'Row on a tablet', 'osous' ) }
						value={ tabletRow }
						onChange={ set( 'tabletRow' ) }
					/>
					<StyleSelect
						group="mobile-row"
						label={ __( 'Row on a phone', 'osous' ) }
						value={ mobileRow }
						onChange={ set( 'mobileRow' ) }
					/>
				</PanelBody>
				<PanelBody
					title={ __( 'Markup', 'osous' ) }
					initialOpen={ false }
				>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'HTML element', 'osous' ) }
						value={ Tag }
						options={ TAGS }
						onChange={ set( 'tagName' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<Tag { ...innerProps } />
		</>
	);
}
