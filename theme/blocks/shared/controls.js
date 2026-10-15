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
	ComboboxControl,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { classFor, cx, editorSettings, options } from './design';
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
 * A page's address in the form the content stores it: its path with `.html`, as the
 * static site named its pages, and `index.html` for the front page.
 *
 * @param {string} link The page's permalink.
 * @return {string} `about.html`, `solutions/backup.html`, `index.html`.
 */
function storedPath( link ) {
	const { homeUrl } = editorSettings();
	const path = String( link || '' )
		.replace( homeUrl, '' )
		.replace( /^\/+|\/+$/g, '' );

	return path === '' ? 'index.html' : path + '.html';
}

/**
 * Where a link goes.
 *
 * Two ways in, one value out. A page of this site is picked from the list, by its title;
 * anything else -- an anchor (`#inquiry`), a phone number, another site -- is typed. What
 * is stored either way is the static site's own form of an address (`about.html`), which
 * keeps the content readable and independent of where WordPress is installed. It is not
 * tied to the page's slug of the day: functions/links.php resolves it to the page that
 * has that address now or had it before, so a link made here follows its page when the
 * page is renamed.
 *
 * @param {Object}   props          Component props.
 * @param {string}   [props.label]  The control's label.
 * @param {string}   props.value    The current URL.
 * @param {Function} props.onChange Called with the new URL.
 * @return {Element} The controls.
 */
export function LinkField( { label, value, onChange } ) {
	const pages = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'postType', 'page', {
				per_page: -1,
				status: 'publish',
				orderby: 'title',
				order: 'asc',
				_fields: 'id,title,link',
			} ),
		[]
	);
	const choices = ( pages || [] ).map( ( page ) => ( {
		value: storedPath( page.link ),
		label:
			page.title?.raw || page.title?.rendered || storedPath( page.link ),
	} ) );
	const chosen = choices.some( ( choice ) => choice.value === value )
		? value
		: null;

	return (
		<>
			<ComboboxControl
				__nextHasNoMarginBottom
				__next40pxDefaultSize
				label={ label || __( 'Link', 'osous' ) }
				help={ __(
					'A page of this site. The link follows the page if its address is changed later.',
					'osous'
				) }
				value={ chosen }
				options={ choices }
				onChange={ ( path ) => onChange( path || '' ) }
			/>
			<Field
				label={ __( 'Or an address', 'osous' ) }
				help={ __(
					'An anchor (#inquiry), a phone number (tel:…), or a full address on another site.',
					'osous'
				) }
				value={ value }
				onChange={ onChange }
			/>
		</>
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
 * A picture of the site's own behind a block: for the sidebar.
 *
 * The design's backgrounds are a list to choose from; this is the one place a picture
 * that is not the design's can go behind a section, a container, a panel or a whole
 * section like the hero. It covers the box in place of whatever the design draws there,
 * and can be darkened with one of the design's own two shades so words stay readable.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.picture  The picture's URL, or ''.
 * @param {string}   props.shade    A value of the design's `shade` table.
 * @param {Function} props.onChange Called with the attributes that change.
 * @return {Element} The control.
 */
export function PictureControl( { picture, shade, onChange } ) {
	return (
		<div className="osous-picture-control">
			<p className="components-base-control__label">
				{ __( 'Background picture', 'osous' ) }
			</p>
			{ picture && (
				<img
					src={ resolveUrl( picture ) }
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
					onSelect={ ( media ) => onChange( { picture: media.url } ) }
					render={ ( { open } ) => (
						<Button
							variant="secondary"
							onClick={ open }
							style={ { marginBottom: '8px' } }
						>
							{ picture
								? __( 'Replace picture', 'osous' )
								: __( 'Choose picture', 'osous' ) }
						</Button>
					) }
				/>
			</MediaUploadCheck>
			{ picture && (
				<Button
					variant="tertiary"
					isDestructive
					onClick={ () => onChange( { picture: '' } ) }
					style={ { marginBottom: '8px', marginLeft: '8px' } }
				>
					{ __( 'Remove', 'osous' ) }
				</Button>
			) }
			<p className="components-base-control__help">
				{ __(
					'Covers the whole box, in place of the background chosen above.',
					'osous'
				) }
			</p>
			{ picture && (
				<StyleSelect
					group="shade"
					label={ __( 'Darken the picture', 'osous' ) }
					help={ __(
						'So that light words on it stay readable.',
						'osous'
					) }
					value={ shade }
					onChange={ ( value ) =>
						onChange( { pictureShade: value } )
					}
				/>
			) }
		</div>
	);
}

/**
 * The class and the style that draw a block's picture, as functions/markup.php's
 * osous_picture_class() and osous_picture_style() print them.
 *
 * @param {string} picture The picture's URL, or ''.
 * @param {string} shade   A value of the design's `shade` table.
 * @return {{className: string, style: (Object|undefined)}} Props for the block's element.
 */
export function pictureProps( picture, shade ) {
	if ( ! picture ) {
		return { className: '', style: undefined };
	}
	return {
		className: cx( 'has-picture', classFor( 'shade', shade ) ),
		style: { '--osous-picture': `url("${ resolveUrl( picture ) }")` },
	};
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
