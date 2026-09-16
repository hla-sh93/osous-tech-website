/**
 * The page header, in the editor.
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { FORMATS, OptionalText } from '../shared/controls';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { eyebrow, title, text } = attributes;

	const blockProps = useBlockProps( { className: 'section header' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<section { ...blockProps }>
			<div className="container h-100">
				<div className="header-contents">
					<OptionalText
						tagName="div"
						className="t-r-md c-on-neutral-subtle"
						value={ eyebrow }
						onChange={ set( 'eyebrow' ) }
						placeholder={ __(
							'Small line above the title…',
							'osous'
						) }
						show={ isSelected }
					/>
					{ ( eyebrow || isSelected ) && (
						<div className="_w-max-200">
							<div className="divider gradient-1"></div>
						</div>
					) }
					<div>
						<RichText
							tagName="h1"
							className="h2 a-c"
							value={ title }
							onChange={ set( 'title' ) }
							allowedFormats={ FORMATS }
							placeholder={ __( 'Page title…', 'osous' ) }
						/>
						<OptionalText
							tagName="p"
							className="p-r-sm c-on-neutral a-c"
							value={ text }
							onChange={ set( 'text' ) }
							placeholder={ __(
								'A line under the title…',
								'osous'
							) }
							show={ isSelected }
						/>
					</div>
				</div>
			</div>
		</section>
	);
}
