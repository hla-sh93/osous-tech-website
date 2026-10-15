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

import {
	PictureControl,
	pictureProps,
	StyleSelect,
	Toggle,
} from '../shared/controls';
import { classFor, cx } from '../shared/design';

export default function Edit( { attributes, setAttributes } ) {
	const { kind, surface, backdrop, fullHeight, picture, pictureShade } =
		attributes;
	const drawn = pictureProps( picture, pictureShade );

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'panel', kind ) || 'modal',
			classFor( 'surface', surface ),
			classFor( 'backdrop', backdrop ),
			fullHeight && 'h-100',
			drawn.className
		),
		style: drawn.style,
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
					<PictureControl
						picture={ picture }
						shade={ pictureShade }
						onChange={ setAttributes }
					/>
					<Toggle
						label={ __( 'Full height', 'osous' ) }
						help={ __(
							'As tall as the column it sits in.',
							'osous'
						) }
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
