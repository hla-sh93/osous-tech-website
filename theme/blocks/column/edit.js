/**
 * A column, in the editor.
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
	const { align, span, wideOnMobile, mediaFirst } = attributes;

	const blockProps = useBlockProps( {
		className: cx(
			'cell',
			classFor( 'align', align ),
			classFor( 'span', span ),
			wideOnMobile && 'span-mobile-2',
			mediaFirst && 'media-first'
		),
	} );
	// Its own lock: inside a pinned list it would otherwise inherit the list's.
	const innerProps = useInnerBlocksProps( blockProps, {
		templateLock: false,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Placement', 'osous' ) }>
					<StyleSelect
						group="align"
						label={ __( 'Vertical position', 'osous' ) }
						value={ align }
						onChange={ ( value ) =>
							setAttributes( { align: value } )
						}
					/>
					<StyleSelect
						group="span"
						label={ __( 'Width', 'osous' ) }
						help={ __(
							'How many of the grid’s columns this one takes.',
							'osous'
						) }
						value={ span }
						onChange={ ( value ) =>
							setAttributes( { span: value } )
						}
					/>
					<Toggle
						label={ __( 'Full width on a phone', 'osous' ) }
						checked={ wideOnMobile }
						onChange={ ( value ) =>
							setAttributes( { wideOnMobile: value } )
						}
					/>
					<Toggle
						label={ __( 'Picture first on a phone', 'osous' ) }
						checked={ mediaFirst }
						onChange={ ( value ) =>
							setAttributes( { mediaFirst: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...innerProps } />
		</>
	);
}
