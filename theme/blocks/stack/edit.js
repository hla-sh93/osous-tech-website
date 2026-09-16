/**
 * A stack, in the editor.
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps( { className: 'content' } );
	const innerProps = useInnerBlocksProps( blockProps );

	return <div { ...innerProps } />;
}
