/**
 * A container, in the editor.
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
	const {
		width,
		fit,
		surface,
		border,
		elevation,
		mobilePadding,
		carousel,
		fullHeight,
		backdrop,
	} = attributes;

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'width', width ),
			fit && 'fit',
			classFor( 'surface', surface ),
			border && 'c-border-neutral',
			elevation && 'elevation',
			mobilePadding && 'padding-x-on-mobile',
			carousel && 'grid-to-carousel',
			fullHeight && 'h-100',
			classFor( 'backdrop', backdrop )
		),
	} );
	const innerProps = useInnerBlocksProps( blockProps );

	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Panel', 'osous' ) }>
					<StyleSelect
						group="width"
						label={ __( 'Width', 'osous' ) }
						value={ width }
						onChange={ set( 'width' ) }
					/>
					<StyleSelect
						group="surface"
						label={ __( 'Colour', 'osous' ) }
						help={ __(
							'The band inside the section. It can be a different colour from the section itself.',
							'osous'
						) }
						value={ surface }
						onChange={ set( 'surface' ) }
					/>
					<StyleSelect
						group="backdrop"
						label={ __( 'Backdrop picture', 'osous' ) }
						value={ backdrop }
						onChange={ set( 'backdrop' ) }
					/>
					<Toggle
						label={ __( 'Fit to its content', 'osous' ) }
						checked={ fit }
						onChange={ set( 'fit' ) }
					/>
					<Toggle
						label={ __( 'Thin border', 'osous' ) }
						checked={ border }
						onChange={ set( 'border' ) }
					/>
					<Toggle
						label={ __( 'Shadow', 'osous' ) }
						checked={ elevation }
						onChange={ set( 'elevation' ) }
					/>
					<Toggle
						label={ __( 'Side padding on a phone', 'osous' ) }
						checked={ mobilePadding }
						onChange={ set( 'mobilePadding' ) }
					/>
					<Toggle
						label={ __( 'Full height', 'osous' ) }
						checked={ fullHeight }
						onChange={ set( 'fullHeight' ) }
					/>
					<Toggle
						label={ __(
							'Grid scrolls sideways below desktop width',
							'osous'
						) }
						checked={ carousel }
						onChange={ set( 'carousel' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}
