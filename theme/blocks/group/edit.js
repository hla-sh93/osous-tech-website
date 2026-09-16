/**
 * A group, in the editor.
 *
 * Its classes come from the Advanced panel's "Additional CSS class(es)" field: this is
 * the one block whose whole point is carrying the design's layout classes as they are.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const TAGS = [
	{ value: 'div', label: 'div' },
	{ value: 'section', label: 'section' },
	{ value: 'span', label: 'span' },
	{ value: 'p', label: 'p' },
];

export default function Edit( { attributes, setAttributes } ) {
	const Tag = TAGS.some( ( tag ) => tag.value === attributes.tagName )
		? attributes.tagName
		: 'div';

	const blockProps = useBlockProps();
	const innerProps = useInnerBlocksProps( blockProps );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Markup', 'osous' ) }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'HTML element', 'osous' ) }
						value={ Tag }
						options={ TAGS }
						onChange={ ( value ) =>
							setAttributes( { tagName: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<Tag { ...innerProps } />
		</>
	);
}
