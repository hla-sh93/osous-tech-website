/**
 * "Page options", in the editor's sidebar.
 *
 * What a page carries besides its blocks (functions/pages.php): whether the WhatsApp
 * badge floats over it, the other language versions a search engine should know about,
 * and -- for a page that was installed from a file under pages/ -- a way back to its
 * original sections. All of it is the page's own meta, saved with the page.
 *
 * The kind of page is not here: that is the page's template, and WordPress's own Template
 * row, in the panel above this one, shows it and changes it.
 */
import apiFetch from '@wordpress/api-fetch';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { parse } from '@wordpress/blocks';
import {
	Button,
	Modal,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import {
	PluginDocumentSettingPanel,
	store as editorStore,
} from '@wordpress/editor';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { store as noticesStore } from '@wordpress/notices';
import { registerPlugin } from '@wordpress/plugins';

/**
 * The panel.
 *
 * @return {Element|null} The panel, on a page; nothing on a post.
 */
function PagePanel() {
	const [ asking, setAsking ] = useState( false );
	const [ busy, setBusy ] = useState( false );
	const { isPage, meta } = useSelect( ( select ) => {
		const editor = select( editorStore );
		return {
			isPage: editor.getCurrentPostType() === 'page',
			meta: editor.getEditedPostAttribute( 'meta' ) || {},
		};
	}, [] );
	const { editPost } = useDispatch( editorStore );
	const { resetBlocks } = useDispatch( blockEditorStore );
	const { createSuccessNotice, createErrorNotice } =
		useDispatch( noticesStore );

	if ( ! isPage ) {
		return null;
	}

	const setMeta = ( key, value ) => editPost( { meta: { [ key ]: value } } );
	const original = meta._osous_original || '';

	const restore = async () => {
		setBusy( true );
		try {
			const found = await apiFetch( {
				path: `/osous/v1/original/${ original }`,
			} );
			resetBlocks( parse( found.content ) );
			createSuccessNotice(
				__(
					'The original sections are back. Nothing is saved until you save the page, and Undo returns what was here.',
					'osous'
				),
				{ type: 'snackbar' }
			);
		} catch {
			createErrorNotice(
				__(
					'The original sections could not be read from the theme.',
					'osous'
				),
				{ type: 'snackbar' }
			);
		}
		setBusy( false );
		setAsking( false );
	};

	return (
		<PluginDocumentSettingPanel
			name="osous-page-options"
			title={ __( 'Page options', 'osous' ) }
		>
			<ToggleControl
				__nextHasNoMarginBottom
				label={ __( 'WhatsApp badge', 'osous' ) }
				help={ __(
					'The floating button that opens a WhatsApp chat. On every page unless switched off here.',
					'osous'
				) }
				checked={ ! meta._osous_hide_whatsapp }
				onChange={ ( shown ) =>
					setMeta( '_osous_hide_whatsapp', ! shown )
				}
			/>
			<TextareaControl
				__nextHasNoMarginBottom
				label={ __( 'Other language versions', 'osous' ) }
				help={ __(
					'For search engines: one per line, a language code and the address of this page in that language, as in "en-SY https://example.com/sy". Leave empty if there is none.',
					'osous'
				) }
				rows={ 3 }
				value={ meta._osous_alternates || '' }
				onChange={ ( value ) => setMeta( '_osous_alternates', value ) }
			/>
			{ original && (
				<>
					<p style={ { marginTop: '16px' } }>
						{ __(
							'This page was installed with the theme. Its original sections can be put back at any time.',
							'osous'
						) }
					</p>
					<Button
						__next40pxDefaultSize
						variant="secondary"
						onClick={ () => setAsking( true ) }
					>
						{ __( 'Restore the original sections', 'osous' ) }
					</Button>
				</>
			) }
			{ asking && (
				<Modal
					title={ __( 'Restore the original sections?', 'osous' ) }
					onRequestClose={ () => setAsking( false ) }
					size="small"
					className="osous-restore-original"
				>
					<p>
						{ __(
							'Everything on this page is replaced by the sections it was installed with. Nothing is saved until you save the page, and Undo returns what is here now.',
							'osous'
						) }
					</p>
					<div style={ { display: 'flex', gap: '8px' } }>
						<Button
							__next40pxDefaultSize
							variant="primary"
							isBusy={ busy }
							disabled={ busy }
							onClick={ restore }
						>
							{ __( 'Restore', 'osous' ) }
						</Button>
						<Button
							__next40pxDefaultSize
							variant="tertiary"
							disabled={ busy }
							onClick={ () => setAsking( false ) }
						>
							{ __( 'Cancel', 'osous' ) }
						</Button>
					</div>
				</Modal>
			) }
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'osous-page-panel', { render: PagePanel } );
