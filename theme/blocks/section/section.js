/**
 * The editor interface for an Osous section.
 *
 * A section is a tree of typed nodes (see inc/sections.php). The shape of that tree is
 * the design and belongs to the theme; the words and the pictures hanging off it belong
 * to whoever is editing. So this walks the tree, finds every leaf a person could sensibly
 * change, and offers one control for each -- while the preview is rendered by the very
 * same PHP that renders the front end, so what the editor shows is what the page is.
 *
 * Sections are reordered by dragging them in the list view, and a new one is made by
 * duplicating an existing one. The block is not in the inserter on purpose: an empty
 * section has no shape and would render nothing at all.
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
	Button,
	PanelBody,
	Placeholder,
	TextControl,
	TextareaControl,
} from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import { __, sprintf } from '@wordpress/i18n';

import metadata from './block.json';

/** Node types whose `x` is text a person would edit. */
const TEXT_TYPES = {
	h: __( 'Heading', 'osous' ),
	p: __( 'Paragraph', 'osous' ),
	txt: __( 'Text', 'osous' ),
	rich: __( 'Rich text', 'osous' ),
	li: __( 'List item', 'osous' ),
};

/**
 * Classes that mark a repeated thing, so fields can be grouped under "Card 3" rather than
 * listed as forty unlabelled boxes.
 */
const GROUPS = [
	[ 'card-1', __( 'Card', 'osous' ) ],
	[ 'card-2', __( 'Card', 'osous' ) ],
	[ 'card-4', __( 'Card', 'osous' ) ],
	[ 'card-5', __( 'Card', 'osous' ) ],
	[ 'feature-card', __( 'Feature', 'osous' ) ],
	[ 'partner-card', __( 'Partner', 'osous' ) ],
	[ 'list-item', __( 'List item', 'osous' ) ],
	[ 'blog-card', __( 'Post', 'osous' ) ],
	[ 'cell', __( 'Column', 'osous' ) ],
];

function classesOf( node ) {
	return ( node.c || '' ).split( ' ' ).filter( Boolean );
}

function groupNameFor( node ) {
	const classes = classesOf( node );
	for ( const [ marker, name ] of GROUPS ) {
		if ( classes.includes( marker ) ) {
			return name;
		}
	}
	return null;
}

/**
 * Every editable leaf in a section, in reading order.
 *
 * @param {Array} nodes Section nodes.
 * @return {Array} Objects of { path, kind, node, group }.
 */
function collectFields( nodes ) {
	const fields = [];
	const counters = {};

	const walk = ( list, path, group ) => {
		list.forEach( ( node, index ) => {
			const here = [ ...path, index ];
			let mine = group;

			const name = groupNameFor( node );
			if ( name ) {
				counters[ name ] = ( counters[ name ] || 0 ) + 1;
				mine = sprintf( '%s %d', name, counters[ name ] );
			}

			if ( TEXT_TYPES[ node.t ] && ( node.x || '' ).trim() !== '' ) {
				fields.push( { path: here, kind: 'text', node, group: mine } );
			} else if ( node.t === 'img' ) {
				fields.push( { path: here, kind: 'image', node, group: mine } );
			} else if ( node.t === 'a' && node.href ) {
				fields.push( { path: here, kind: 'link', node, group: mine } );
			}

			if ( Array.isArray( node.k ) ) {
				walk( node.k, here, mine );
			}
		} );
	};

	walk( nodes, [], null );
	return fields;
}

/**
 * Return a copy of the tree with one node's properties changed.
 *
 * @param {Array}  nodes   Section nodes.
 * @param {Array}  path    Child indices from the root.
 * @param {Object} changes Properties to merge into that node.
 * @return {Array} A new tree.
 */
function withNode( nodes, path, changes ) {
	const [ index, ...rest ] = path;

	return nodes.map( ( node, i ) => {
		if ( i !== index ) {
			return node;
		}
		if ( rest.length === 0 ) {
			return { ...node, ...changes };
		}
		return { ...node, k: withNode( node.k || [], rest, changes ) };
	} );
}

/** A short, recognisable name for one field. */
function labelFor( field ) {
	if ( field.kind === 'image' ) {
		return __( 'Image', 'osous' );
	}
	if ( field.kind === 'link' ) {
		return __( 'Link', 'osous' );
	}
	return TEXT_TYPES[ field.node.t ] || __( 'Text', 'osous' );
}

function TextField( { field, onChange } ) {
	const value = field.node.x || '';
	const hasMarkup = /<[a-z]/i.test( value );

	return (
		<TextareaControl
			__nextHasNoMarginBottom
			label={ labelFor( field ) }
			help={
				hasMarkup
					? __( 'This one carries formatting tags. Keep them to keep the styling.', 'osous' )
					: undefined
			}
			value={ value }
			rows={ value.length > 120 ? 5 : 2 }
			onChange={ ( next ) => onChange( { x: next } ) }
		/>
	);
}

function ImageField( { field, onChange } ) {
	const { src, alt } = field.node;

	return (
		<div className="osous-field-image">
			{ src && <img src={ src } alt="" style={ { maxWidth: '100%', display: 'block' } } /> }
			<MediaUploadCheck>
				<MediaUpload
					allowedTypes={ [ 'image' ] }
					onSelect={ ( media ) =>
						/*
						 * srcset and sizes describe the file being replaced, not the new one.
						 * Leaving them would keep serving the old picture at most widths, which
						 * is the kind of bug nobody finds until a phone is held up in a meeting.
						 */
						onChange( {
							src: media.url,
							srcset: undefined,
							sizes: undefined,
							alt: media.alt || alt || '',
						} )
					}
					render={ ( { open } ) => (
						<Button variant="secondary" onClick={ open } style={ { marginTop: '8px' } }>
							{ src ? __( 'Replace image', 'osous' ) : __( 'Choose image', 'osous' ) }
						</Button>
					) }
				/>
			</MediaUploadCheck>
			<TextControl
				__nextHasNoMarginBottom
				label={ __( 'Alt text', 'osous' ) }
				help={ __( 'What the picture shows, for people who cannot see it.', 'osous' ) }
				value={ alt || '' }
				onChange={ ( next ) => onChange( { alt: next } ) }
			/>
		</div>
	);
}

function LinkField( { field, onChange } ) {
	return (
		<TextControl
			__nextHasNoMarginBottom
			label={ labelFor( field ) }
			value={ field.node.href || '' }
			onChange={ ( next ) => onChange( { href: next } ) }
		/>
	);
}

const CONTROLS = { text: TextField, image: ImageField, link: LinkField };

function Edit( { attributes, setAttributes } ) {
	const { nodes = [], label } = attributes;
	const fields = collectFields( nodes );

	// Keep the grouping the walk produced, in the order it found things.
	const groups = [];
	fields.forEach( ( field ) => {
		const name = field.group || __( 'Content', 'osous' );
		const last = groups[ groups.length - 1 ];
		if ( last && last.name === name ) {
			last.fields.push( field );
		} else {
			groups.push( { name, fields: [ field ] } );
		}
	} );

	const update = ( path ) => ( changes ) =>
		setAttributes( { nodes: withNode( nodes, path, changes ) } );

	return (
		<Fragment>
			<InspectorControls>
				{ groups.length === 0 && (
					<PanelBody title={ __( 'Nothing to edit', 'osous' ) }>
						<p>
							{ __(
								'This section has no words or pictures of its own — it is a divider or a spacer.',
								'osous'
							) }
						</p>
					</PanelBody>
				) }
				{ groups.map( ( group, i ) => (
					<PanelBody key={ i } title={ group.name } initialOpen={ i === 0 }>
						{ group.fields.map( ( field ) => {
							const Control = CONTROLS[ field.kind ];
							return (
								<Control
									key={ field.path.join( '-' ) }
									field={ field }
									onChange={ update( field.path ) }
								/>
							);
						} ) }
					</PanelBody>
				) ) }
			</InspectorControls>

			{ nodes.length === 0 ? (
				<Placeholder label={ label || __( 'Section', 'osous' ) }>
					{ __(
						'Empty. Duplicate a section that already has the shape you want, then change its words.',
						'osous'
					) }
				</Placeholder>
			) : (
				<ServerSideRender block={ metadata.name } attributes={ attributes } />
			) }
		</Fragment>
	);
}

registerBlockType( metadata.name, {
	...metadata,
	edit: Edit,
	save: () => null,
	// The list view says "Page header" and "Pinned list" rather than "Section" forty times.
	__experimentalLabel: ( attributes ) => attributes.label || undefined,
} );
