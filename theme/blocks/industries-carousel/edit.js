/**
 * The industries carousel, in the editor.
 *
 * Swiper does not run here; the slides sit in a row and scroll with the canvas.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { FORMATS, PictureControl, pictureProps } from '../shared/controls';
import { cx } from '../shared/design';
import { resolveUrl } from '../shared/url';

export default function Edit( { attributes, setAttributes } ) {
	const { title, text, picture, pictureShade } = attributes;
	const drawn = pictureProps( picture, pictureShade );

	const blockProps = useBlockProps( {
		className: cx( 'section overflow-hidden bg-3', drawn.className ),
		style: drawn.style,
	} );
	const innerProps = useInnerBlocksProps(
		{ className: 'flex-h swiper-wrapper' },
		{ template: [ [ 'osous/photo-card' ] ] }
	);
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Background', 'osous' ) }>
					<PictureControl
						picture={ picture }
						shade={ pictureShade }
						onChange={ setAttributes }
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="container swiper-container">
					<div className="flex-h a-sb-t">
						<div className="heading">
							<RichText
								tagName="h2"
								className="h2 c-on-secondary"
								value={ title }
								onChange={ set( 'title' ) }
								allowedFormats={ FORMATS }
								placeholder={ __( 'Heading…', 'osous' ) }
							/>
							<RichText
								tagName="p"
								className="p c-on-secondary"
								value={ text }
								onChange={ set( 'text' ) }
								allowedFormats={ FORMATS }
								placeholder={ __( 'Text…', 'osous' ) }
							/>
						</div>
						<div className="button-wrapper">
							<div className="hide-on-tablet">
								<div className="flex-h">
									<button
										type="button"
										className="btn outline swiper-button-prev"
										aria-label={ __(
											'Previous slide',
											'osous'
										) }
									>
										<img
											src={ resolveUrl(
												'assets/images/icon-arrow-left.svg'
											) }
											alt=""
											className="icon-24"
										/>
									</button>
									<button
										type="button"
										className="btn outline swiper-button-next"
										aria-label={ __(
											'Next slide',
											'osous'
										) }
									>
										<img
											src={ resolveUrl(
												'assets/images/icon-arrow-right.svg'
											) }
											alt=""
											className="icon-24"
										/>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div { ...innerProps } />
				</div>
			</section>
		</>
	);
}
