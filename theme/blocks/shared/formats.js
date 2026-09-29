/**
 * The design's three inline styles, as buttons on the text toolbar.
 *
 * The reference pages mark words inside headings with `<span class="highlight">`,
 * `<span class="thin">` and `<span class="underline">`; registering each as a format is
 * what lets the editor keep them when a heading is edited, and offer them for new words.
 */
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';

const STYLES = [
	{
		name: 'highlight',
		title: __( 'Highlight', 'osous' ),
		icon: 'admin-appearance',
	},
	{ name: 'thin', title: __( 'Thin', 'osous' ), icon: 'editor-textcolor' },
	{
		name: 'underline',
		title: __( 'Underline', 'osous' ),
		icon: 'editor-underline',
	},
];

STYLES.forEach( ( style ) => {
	const type = `osous/${ style.name }`;

	registerFormatType( type, {
		title: style.title,
		tagName: 'span',
		className: style.name,
		edit: ( { isActive, value, onChange } ) => (
			<RichTextToolbarButton
				icon={ style.icon }
				title={ style.title }
				isActive={ isActive }
				onClick={ () => onChange( toggleFormat( value, { type } ) ) }
			/>
		),
	} );
} );
