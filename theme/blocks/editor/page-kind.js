/**
 * The kind of page, asked once, when a page is new.
 *
 * A page that has just been created holds nothing and has no template. Before anything
 * else it is asked what kind of page it is -- a partner page, a solution page, the
 * contact page -- and the answer does two things: it sets the page's template, which is
 * how WordPress remembers the kind from then on (the Template panel shows it and can
 * change it), and it lays down the kind's starter, every section of a typical page of
 * that kind, in order, ready to have its words changed.
 *
 * The kinds are functions/templates.php's table, handed over as
 * `window.osousEditor.kinds`; the starters are the patterns under patterns/starters/.
 * Nothing is asked of a page that already holds something, and closing the question
 * leaves an empty page on the default template.
 */
import { store as blockEditorStore } from '@wordpress/block-editor';
import { parse } from '@wordpress/blocks';
import { Button, Modal } from '@wordpress/components';
import { store as coreStore } from '@wordpress/core-data';
import { useDispatch, useRegistry, useSelect } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';

import { editorSettings } from '../shared/design';

/** A kind in the list: its title over its description, the whole row a button. */
const ROW = {
	display: 'block',
	width: '100%',
	height: 'auto',
	padding: '12px 16px',
	textAlign: 'start',
	whiteSpace: 'normal',
};

/**
 * The question, as a modal over a new page.
 *
 * @return {Element|null} The modal, or nothing once the page is no longer new.
 */
function PageKindPicker() {
	const [ dismissed, setDismissed ] = useState( false );
	const [ busy, setBusy ] = useState( false );
	const registry = useRegistry();
	const isNewPage = useSelect( ( select ) => {
		const editor = select( editorStore );
		return (
			editor.getCurrentPostType() === 'page' && editor.isCleanNewPost()
		);
	}, [] );
	const { editPost } = useDispatch( editorStore );
	const { resetBlocks } = useDispatch( blockEditorStore );

	const kinds = editorSettings().kinds || [];
	if ( ! isNewPage || dismissed || kinds.length === 0 ) {
		return null;
	}

	/*
	 * The starter is read when a kind is chosen rather than up front: the library is one
	 * request the editor makes anyway, and resolveSelect waits for it if it is still on
	 * its way.
	 */
	const choose = async ( kind ) => {
		setBusy( true );
		const patterns = await registry
			.resolveSelect( coreStore )
			.getBlockPatterns();
		const starter = ( patterns || [] ).find(
			( pattern ) => pattern.name === kind.starter
		);
		editPost( { template: kind.template } );
		if ( starter ) {
			resetBlocks( parse( starter.content ) );
		}
		setDismissed( true );
	};

	return (
		<Modal
			title={ __( 'What kind of page is this?', 'osous' ) }
			onRequestClose={ () => setDismissed( true ) }
			size="medium"
			className="osous-page-kind"
		>
			<p>
				{ __(
					'The page starts with the sections a page of that kind has, in order, and takes that kind as its template. Every word, picture and section can be changed afterwards.',
					'osous'
				) }
			</p>
			<div style={ { display: 'grid', gap: '8px' } }>
				{ kinds.map( ( kind ) => (
					<Button
						key={ kind.key }
						variant="secondary"
						disabled={ busy }
						style={ ROW }
						onClick={ () => choose( kind ) }
					>
						<strong style={ { display: 'block' } }>
							{ kind.title }
						</strong>
						<span
							style={ { display: 'block', fontWeight: 'normal' } }
						>
							{ kind.description }
						</span>
					</Button>
				) ) }
			</div>
			<p style={ { marginBottom: 0 } }>
				<Button
					__next40pxDefaultSize
					variant="tertiary"
					disabled={ busy }
					onClick={ () => setDismissed( true ) }
				>
					{ __( 'Start with an empty page', 'osous' ) }
				</Button>
			</p>
		</Modal>
	);
}

registerPlugin( 'osous-page-kind', { render: PageKindPicker } );
