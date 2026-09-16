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
	const { src, alt, srcset, sizes, priority, size, fit, rounded } =
		attributes;

	const blockProps = useBlockProps( {
		className: cx(
			classFor( 'image-size', size ),
			classFor( 'image-fit', fit ),
			rounded && 'radii-container'
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
