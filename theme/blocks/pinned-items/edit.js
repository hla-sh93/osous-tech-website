/**
 * The items of a pinned list, in the editor.
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps( { className: 'list-items-wrapper' } );
	const innerProps = useInnerBlocksProps( blockProps, {
		template: [ [ 'osous/pinned-item' ] ],
		// Its own lock, or it inherits the pinned list's and no item could be added.
		templateLock: false,
	} );

	return <div { ...innerProps } />;
}
