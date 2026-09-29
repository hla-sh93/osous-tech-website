/**
 * The controls the blocks share: a select bound to one of the design's option tables,
 * an image picker, a link field, and the pieces of markup several blocks draw.
 */
import {
	MediaUpload,
	MediaUploadCheck,
	RichText,
} from '@wordpress/block-editor';
import {
	Button,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { options } from './design';
import { resolveSrcset, resolveUrl } from './url';

/** The inline formatting a heading, a paragraph or a label may carry. */
export const FORMATS = [
	'core/bold',
	'core/italic',
	'core/link',
	'core/superscript',
	'core/subscript',
	'osous/highlight',
	'osous/thin',
	'osous/underline',
];

/**
 * A select control whose options are one group of the design's table.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.group    The table's key.
 * @param {string}   props.label    The control's label.
 * @param {string}   [props.help]   A line of help under it.
 * @param {string}   props.value    The current value.
 * @param {Function} props.onChange Called with the new value.
 * @return {Element} The control.
 */
export function StyleSelect( { group, label, help, value, onChange } ) {
	return (
		<SelectControl
			__nextHasNoMarginBottom
			__next40pxDefaultSize
			label={ label }
			help={ help }
			value={ value ?? '' }
			options={ options( group ) }
			onChange={ onChange }
		/>
	);
}

/**
 * A yes/no control.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.label    The control's label.
 * @param {string}   [props.help]   A line of help under it.
 * @param {boolean}  props.checked  The current value.
 * @param {Function} props.onChange Called with the new value.
 * @return {Element} The control.
 */
export function Toggle( { label, help, checked, onChange } ) {
	return (
		<ToggleControl
			__nextHasNoMarginBottom
			label={ label }
			help={ help }
			checked={ !! checked }
			onChange={ onChange }
		/>
	);
}

/**
 * A single-line text field.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.label    The control's label.
 * @param {string}   [props.help]   A line of help under it.
 * @param {string}   props.value    The current value.
 * @param {Function} props.onChange Called with the new value.
 * @return {Element} The control.
 */
export function Field( { label, help, value, onChange } ) {
	return (
		<TextControl
			__nextHasNoMarginBottom
			__next40pxDefaultSize
			label={ label }
			help={ help }
			value={ value ?? '' }
			onChange={ onChange }
		/>
	);
}

/**
 * Where a link goes.
 *
 * A plain field rather than a link picker: the site's links are the static paths the
 * content was cloned with (`about.html`), anchors (`#inquiry`) and phone numbers, and a
 * picker would rewrite them into absolute URLs that only work on one install.
 *
 * @param {Object}   props          Component props.
 * @param {string}   [props.label]  The control's label.
 * @param {string}   props.value    The current URL.
 * @param {Function} props.onChange Called with the new URL.
 * @return {Element} The control.
 */
export function LinkField( { label, value, onChange } ) {
	return (
		<Field
			label={ label || __( 'Link', 'osous' ) }
			help={ __(
				'A page of this site as its file name (about.html), an anchor (#inquiry), or a full address.',
				'osous'
			) }
			value={ value }
			onChange={ onChange }
		/>
	);
}

/**
 * An image picker with its alt text: for the sidebar.
 *
 * `srcset` and `sizes` describe the file being replaced, not the new one; leaving them
 * would keep serving the old picture at most widths, which is the kind of bug nobody
 * finds until a phone is held up in a meeting. Choosing a new file drops both.
 *
 * @param {Object}   props          Component props.
 * @param {string}   [props.label]  The control's label.
 * @param {Object}   props.value    `{ src, alt, srcset, sizes }`.
 * @param {Function} props.onChange Called with the new image object.
 * @return {Element} The control.
 */
export function ImageControl( { label, value, onChange } ) {
	const image = value || {};

	return (
		<div className="osous-image-control">
			{ label && (
				<p className="components-base-control__label">{ label }</p>
			) }
			{ image.src && (
				<img
					src={ resolveUrl( image.src ) }
					alt=""
					style={ {
						maxWidth: '100%',
						maxHeight: '120px',
						display: 'block',
						marginBottom: '8px',
					} }
				/>
			) }
			<MediaUploadCheck>
				<MediaUpload
					allowedTypes={ [ 'image' ] }
					onSelect={ ( media ) =>
						onChange( {
							src: media.url,
							alt: media.alt || image.alt || '',
						} )
					}
					render={ ( { open } ) => (
						<Button
							variant="secondary"
							onClick={ open }
							style={ { marginBottom: '8px' } }
						>
							{ image.src
								? __( 'Replace image', 'osous' )
								: __( 'Choose image', 'osous' ) }
						</Button>
					) }
				/>
			</MediaUploadCheck>
			{ image.src && (
				<Button
					variant="tertiary"
					isDestructive
					onClick={ () => onChange( {} ) }
					style={ { marginBottom: '8px', marginLeft: '8px' } }
				>
					{ __( 'Remove', 'osous' ) }
				</Button>
			) }
			<Field
				label={ __( 'Alt text', 'osous' ) }
				help={ __(
					'What the picture shows, for people who cannot see it.',
					'osous'
				) }
				value={ image.alt }
				onChange={ ( alt ) => onChange( { ...image, alt } ) }
			/>
		</div>
	);
}

/**
 * An image as the page draws it.
 *
 * @param {Object} props             Component props.
 * @param {Object} props.image       `{ src, alt, srcset, sizes }`.
 * @param {string} [props.className] Classes.
 * @return {Element|null} The image, or nothing when there is no file.
 */
export function Img( { image, className } ) {
	const picture = image || {};
	if ( ! picture.src ) {
		return null;
	}
	return (
		<img
			src={ resolveUrl( picture.src ) }
			srcSet={ resolveSrcset( picture.srcset ) || undefined }
			sizes={ picture.sizes || undefined }
			alt={ picture.alt || '' }
			className={ className || undefined }
		/>
	);
}

/**
 * The one icon the design draws: the arrow in outlined and text buttons.
 *
 * @return {Element} The SVG.
 */
export function Arrow() {
	return (
		<svg
			width="24"
			height="24"
			viewBox="0 0 24 24"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<path
				d="M15.6822 18.7468L21.4651 12M21.4651 12L15.6822 5.25323M21.4651 12L3.7085 12"
				stroke="currentColor"
				strokeWidth="1.5"
			/>
		</svg>
	);
}

/**
 * A link in the canvas: it looks and lays out like the real one, and does not navigate.
 *
 * @param {Object} props             Component props.
 * @param {string} props.href        The stored URL.
 * @param {string} [props.className] Classes.
 * @param {*}      props.children    Contents.
 * @return {Element} The anchor.
 */
export function Link( { href, className, children } ) {
	return (
		<a
			href={ resolveUrl( href || '#' ) }
			className={ className || undefined }
			onClick={ ( event ) => event.preventDefault() }
		>
			{ children }
		</a>
	);
}

/**
 * The numbered badge a card can carry, "01" in a circle, editable in place.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.value    The figure.
 * @param {Function} props.onChange Called with the new figure.
 * @param {boolean}  props.show     Whether to draw it when empty (the card is selected).
 * @return {Element|null} The badge.
 */
export function NumberBadge( { value, onChange, show } ) {
	if ( ! value && ! show ) {
		return null;
	}
	return (
		<div className="number">
			<RichText
				tagName="div"
				className="h3 number-style-1"
				value={ value }
				onChange={ onChange }
				allowedFormats={ [] }
				placeholder="01"
			/>
			<div className="number-shape"></div>
		</div>
	);
}

/**
 * A line of editable text that exists on the page only when it has words.
 *
 * Unselected, an empty one is not drawn at all, so the canvas holds exactly the
 * elements the page holds. Selected, it appears with a placeholder to type into.
 *
 * @param {Object}   props             Component props.
 * @param {string}   props.tagName     The element.
 * @param {string}   [props.className] Classes.
 * @param {string}   props.value       The words.
 * @param {Function} props.onChange    Called with the new words.
 * @param {string}   props.placeholder What to show while empty.
 * @param {boolean}  props.show        Draw it even when empty.
 * @param {Array}    [props.formats]   Allowed inline formats; the design's by default.
 * @return {Element|null} The text.
 */
export function OptionalText( {
	tagName,
	className,
	value,
	onChange,
	placeholder,
	show,
	formats,
} ) {
	if ( ! value && ! show ) {
		return null;
	}
	return (
		<RichText
			tagName={ tagName }
			className={ className || undefined }
			value={ value || '' }
			onChange={ onChange }
			allowedFormats={ formats || FORMATS }
			placeholder={ placeholder }
		/>
	);
}
