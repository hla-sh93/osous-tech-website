/**
 * A button, in the editor: the label is typed in place, the link set in the sidebar.
 */
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { Arrow, LinkField, StyleSelect, Toggle } from '../shared/controls';
import { classFor, cx } from '../shared/design';
import { resolveUrl } from '../shared/url';

const LABEL = {
	primary: 't-r-md c-on-primary',
	outline: 't-r-md',
	text: 't-r-md c-on-secondary link',
	neutral: 't-r-md',
};

const ARROW = {
	outline: 'icon-24 c-on-neutral',
	text: 'icon-24 c-neutral-active',
};

export default function Edit( { attributes, setAttributes } ) {
	const { text, url, style, narrow } = attributes;
	const kind = LABEL[ style ] ? style : 'primary';

	const blockProps = useBlockProps( {
		className: narrow ? '_w-max-200' : classFor( 'button', kind ),
	} );

	const link = (
		<a
			href={ resolveUrl( url ) }
			onClick={ ( event ) => event.preventDefault() }
			{ ...( narrow
				? { className: classFor( 'button', kind ) }
				: blockProps ) }
		>
			<RichText
				tagName="div"
				className={ LABEL[ kind ] }
				value={ text }
				onChange={ ( value ) => setAttributes( { text: value } ) }
				allowedFormats={ [] }
				placeholder={ __( 'Button…', 'osous' ) }
			/>
			{ ARROW[ kind ] && (
				<div className={ cx( ARROW[ kind ] ) }>
					<Arrow />
				</div>
			) }
		</a>
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Button', 'osous' ) }>
					<LinkField
						value={ url }
						onChange={ ( value ) =>
							setAttributes( { url: value } )
						}
					/>
					<StyleSelect
						group="button"
						label={ __( 'Style', 'osous' ) }
						value={ kind }
						onChange={ ( value ) =>
							setAttributes( { style: value } )
						}
					/>
					<Toggle
						label={ __( 'Keep to its own width', 'osous' ) }
						help={ __(
							'Otherwise it stretches across the column it sits in.',
							'osous'
						) }
						checked={ narrow }
						onChange={ ( value ) =>
							setAttributes( { narrow: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			{ narrow ? <div { ...blockProps }>{ link }</div> : link }
		</>
	);
}
