/**
 * An image, in the editor.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { ImageControl, StyleSelect, Toggle } from '../shared/controls';
import { classFor, cx } from '../shared/design';
import { resolveSrcset, resolveUrl } from '../shared/url';

export default function Edit( { attributes, setAttributes } ) {
	const {
		src,
		alt,
		srcset,
		sizes,
		priority,
		size,
		fit,
		rounded,
		place,
		span,
		tabletRow,
		spaceBelow,
	} = attributes;

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'image-size', size ),
			classFor( 'image-fit', fit ),
			rounded && 'radii-container',
			classFor( 'align', place ),
			classFor( 'span', span ),
			classFor( 'tablet-row', tabletRow ),
			spaceBelow && 'm-b-1x'
		),
	} );

	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Image', 'osous' ) }>
					<ImageControl
						value={ { src, alt, srcset, sizes } }
						onChange={ ( image ) =>
							setAttributes( {
								src: image.src || '',
								alt: image.alt || '',
								srcset: image.srcset || '',
								sizes: image.sizes || '',
							} )
						}
					/>
					<StyleSelect
						group="image-size"
						label={ __( 'Size', 'osous' ) }
						value={ size }
						onChange={ set( 'size' ) }
					/>
					<StyleSelect
						group="image-fit"
						label={ __( 'Fit', 'osous' ) }
						value={ fit }
						onChange={ set( 'fit' ) }
					/>
					<Toggle
						label={ __( 'Rounded corners', 'osous' ) }
						checked={ rounded }
						onChange={ set( 'rounded' ) }
					/>
					<Toggle
						label={ __( 'Load first', 'osous' ) }
						help={ __(
							'For the one picture at the top of the page: the browser fetches it before anything else.',
							'osous'
						) }
						checked={ priority }
						onChange={ set( 'priority' ) }
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
						group="span"
						label={ __( 'Width', 'osous' ) }
						help={ __(
							'How many of the grid’s columns it takes.',
							'osous'
						) }
						value={ span }
						onChange={ set( 'span' ) }
					/>
					<StyleSelect
						group="tablet-row"
						label={ __( 'Row on a tablet', 'osous' ) }
						value={ tabletRow }
						onChange={ set( 'tabletRow' ) }
					/>
					<Toggle
						label={ __( 'Space below', 'osous' ) }
						checked={ spaceBelow }
						onChange={ set( 'spaceBelow' ) }
					/>
				</PanelBody>
			</InspectorControls>
			{ src ? (
				<img
					{ ...blockProps }
					src={ resolveUrl( src ) }
					srcSet={ resolveSrcset( srcset ) || undefined }
					sizes={ sizes || undefined }
					alt={ alt }
				/>
			) : (
				<div { ...blockProps }>
					<ImageControl
						value={ {} }
						onChange={ ( image ) =>
							setAttributes( {
								src: image.src || '',
								alt: image.alt || '',
							} )
						}
					/>
				</div>
			) }
		</>
	);
}
