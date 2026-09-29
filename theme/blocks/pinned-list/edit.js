/**
 * A pinned list, in the editor: the heading column and the items, side by side.
 *
 * The canvas does not scroll-pin anything, so the two columns simply sit next to each
 * other here, which is what there is to edit.
 *
 * The two halves are locked against insertion and removal, not `all`: with `all` the
 * editor resynchronises what is inside them to the template on every load, and a list
 * of six items would come back as the template's two.
 */
import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const TEMPLATE = [
	[ 'osous/column', {}, [ [ 'osous/heading' ] ] ],
	[
		'osous/pinned-items',
		{},
		[ [ 'osous/pinned-item' ], [ 'osous/pinned-item' ] ],
	],
];

export default function Edit( { attributes, setAttributes } ) {
	const Scroll = attributes.tagName === 'div' ? 'div' : 'section';

	const blockProps = useBlockProps( {
		className: 'section list-scroll-section',
	} );
	const innerProps = useInnerBlocksProps(
		{ className: 'grid-2' },
		{ template: TEMPLATE, templateLock: 'insert' }
	);

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Markup', 'osous' ) }
					initialOpen={ false }
				>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Inner element', 'osous' ) }
						value={ Scroll }
						options={ [
							{ value: 'section', label: 'section' },
							{ value: 'div', label: 'div' },
						] }
						onChange={ ( value ) =>
							setAttributes( { tagName: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="container">
					<Scroll className="list-scroll">
						<div { ...innerProps } />
					</Scroll>
				</div>
			</section>
		</>
	);
}
