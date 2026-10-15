/**
 * A section, in the editor: the band, with its container inside.
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
	const { spacing, background, height, joinBelow, picture, pictureShade } =
		attributes;
	const drawn = pictureProps( picture, pictureShade );

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'spacing', spacing ),
			classFor( 'background', background ),
			classFor( 'height', height ),
			joinBelow && 'm-b-0',
			drawn.className
		),
		style: drawn.style,
	} );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'osous/container' ] ],
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Appearance', 'osous' ) }>
					<StyleSelect
						group="background"
						label={ __( 'Background', 'osous' ) }
						value={ background }
						onChange={ ( value ) =>
							setAttributes( { background: value } )
						}
					/>
					<PictureControl
						picture={ picture }
						shade={ pictureShade }
						onChange={ setAttributes }
					/>
					<StyleSelect
						group="spacing"
						label={ __( 'Spacing', 'osous' ) }
						help={ __(
							'How much room the section takes above and below its content.',
							'osous'
						) }
						value={ spacing }
						onChange={ ( value ) =>
							setAttributes( { spacing: value } )
						}
					/>
					<StyleSelect
						group="height"
						label={ __( 'Height', 'osous' ) }
						value={ height }
						onChange={ ( value ) =>
							setAttributes( { height: value } )
						}
					/>
					<Toggle
						label={ __( 'Join the section below', 'osous' ) }
						help={ __(
							'Removes the gap underneath, so the two read as one band.',
							'osous'
						) }
						checked={ joinBelow }
						onChange={ ( value ) =>
							setAttributes( { joinBelow: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...innerProps } />
		</>
	);
}
