/**
 * The partner strip, in the editor.
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps( { className: 'partner-cards-wrapper' } );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'osous/partner' ] ],
	} );

	return <div { ...innerProps } />;
}
