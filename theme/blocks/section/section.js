/**
 * The editor interface for an Osous section.
 *
 * A section is a tree of typed nodes (see inc/sections.php). The shape of that tree is
 * the design and belongs to the theme; the words and the pictures hanging off it belong
 * to whoever is editing. So this walks the tree, finds every leaf a person could sensibly
 * change, and offers one control for each -- while the preview is rendered by the very
 * same PHP that renders the front end, so what the editor shows is what the page is.
 *
 * Where a section repeats something -- cards, list items, columns -- the group also gets
 * move, duplicate and remove, so the number of cards is not fixed by whichever page the
 * section was lifted from.
 *
 * Sections themselves are reordered by dragging them in the list view, and a new one
 * comes from the pattern library. The block is not in the inserter on purpose: an empty
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
 * listed as forty unlabelled boxes -- and so the group can be moved, copied or removed.
 */
const GROUPS = [
	[ 'card-1', __( 'Card', 'osous' ) ],
	[ 'card-2', __( 'Card', 'osous' ) ],
	[ 'card-3', __( 'Card', 'osous' ) ],
	[ 'card-4', __( 'Card', 'osous' ) ],
	[ 'card-5', __( 'Card', 'osous' ) ],
	[ 'feature-card', __( 'Feature', 'osous' ) ],
	[ 'partner-card', __( 'Partner', 'osous' ) ],
	[ 'partner-card-logo', __( 'Partner', 'osous' ) ],
	[ 'list-item', __( 'List item', 'osous' ) ],
	[ 'blog-card', __( 'Post', 'osous' ) ],
	[ 'cell', __( 'Column', 'osous' ) ],
];

/**
 * Classes a copy must not inherit.
 *
 * The placement ones come from Webflow's generated grid rules (see layout.css): they say
 * "this item spans two columns" or "this one jumps to the top on tablet". A copy that
 * inherited `span-2` would claim two columns alongside the original claiming the same two,
 * and the grid would fold. Stripping them leaves a plain item, which is always valid
 * wherever it lands. `active` is the pinned list's runtime highlight and belongs to one
 * item at a time.
 */
const NOT_INHERITED = [
	'self-center', 'self-start', 'justify-start', 'justify-center', 'justify-end',
	'place-center', 'span-2', 'span-3', 'span-4', 'col-right', 'media-first',
	'self-center-tablet', 'self-end-tablet', 'row-tablet-1', 'row-tablet-2',
	'row-tablet-3', 'row-tablet-3-4', 'row-tablet-4', 'row-tablet-5',
	'span-mobile-2', 'row-mobile-4', 'row-mobile-5', 'active',
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
	if ( node.t === 'li' ) {
		return __( 'List item', 'osous' );
	}
	return null;
}

/** A deep copy with the classes a sibling must not inherit taken out. */
function cloneForSibling( node ) {
	const copied = JSON.parse( JSON.stringify( node ) );
	if ( copied.c ) {
		const kept = classesOf( copied ).filter( ( c ) => ! NOT_INHERITED.includes( c ) );
		copied.c = kept.join( ' ' );
	}
	return copied;
}

/**
 * Every editable leaf in a section, gathered into the groups it belongs to.
 *
 * @param {Array} nodes Section nodes.
 * @return {Array} Groups of { name, container, index, repeatable, fields }.
 */
function collectGroups( nodes ) {
	const groups = [];
	const counters = {};
	let current = null;

	const push = ( field ) => {
		if ( ! current ) {
			current = { name: __( 'Content', 'osous' ), container: null, index: -1, fields: [] };
			groups.push( current );
		}
		current.fields.push( field );
	};

	const walk = ( list, path ) => {
		list.forEach( ( node, index ) => {
			const here = [ ...path, index ];
			const name = groupNameFor( node );

			if ( name ) {
				counters[ name ] = ( counters[ name ] || 0 ) + 1;
				// A group is repeatable when it has siblings of the same kind to sit among.
				const siblings = list.filter( ( sibling ) => groupNameFor( sibling ) === name );
				current = {
					name: sprintf( '%s %d', name, counters[ name ] ),
					base: name,
					container: path,
					index,
					repeatable: siblings.length > 1,
					fields: [],
				};
				groups.push( current );
			}

			if ( TEXT_TYPES[ node.t ] && ( node.x || '' ).trim() !== '' ) {
				push( { path: here, kind: 'text', node } );
			} else if ( node.t === 'img' ) {
				push( { path: here, kind: 'image', node } );
			} else if ( node.t === 'a' && node.href ) {
				push( { path: here, kind: 'link', node } );
			}

			if ( Array.isArray( node.k ) ) {
				walk( node.k, here );
			}
		} );
	};

	walk( nodes, [] );

	// A group with nothing in it has nothing to show and nothing to edit.
	return groups.filter( ( group ) => group.fields.length > 0 || group.repeatable );
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

/**
 * Return a copy of the tree with one container's children replaced.
 *
 * @param {Array}    nodes     Section nodes.
 * @param {Array}    container Path to the container, empty for the root.
 * @param {Function} change    Given the children, returns the new children.
 * @return {Array} A new tree.
 */
function withChildren( nodes, container, change ) {
	if ( container.length === 0 ) {
		return change( nodes );
	}
	const [ index, ...rest ] = container;

	return nodes.map( ( node, i ) => {
		if ( i !== index ) {
			return node;
		}
		const children = node.k || [];
		return {
			...node,
			k: rest.length === 0 ? change( children ) : withChildren( children, rest, change ),
		};
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

/** The children of the container at `path`; the root array when the path is empty. */
function childrenAt( nodes, path ) {
	return path.reduce( ( list, index ) => list[ index ].k || [], nodes );
}

/** Move, copy and remove for one repeated item. */
function GroupActions( { group, nodes, setNodes } ) {
	const siblings = childrenAt( nodes, group.container );

	// Positions of the items of this kind. A divider or a spacer can sit among them, so
	// "the one above" means the previous card, not the previous element.
	const kin = siblings
		.map( ( node, index ) => ( groupNameFor( node ) === group.base ? index : -1 ) )
		.filter( ( index ) => index !== -1 );
	const position = kin.indexOf( group.index );

	const apply = ( change ) => setNodes( withChildren( nodes, group.container, change ) );

	const move = ( offset ) => {
		const target = kin[ position + offset ];
		if ( target === undefined ) {
			return;
		}
		apply( ( list ) => {
			const next = [ ...list ];
			[ next[ group.index ], next[ target ] ] = [ next[ target ], next[ group.index ] ];
			return next;
		} );
	};

	return (
		<div
			className="osous-group-actions"
			style={ { display: 'flex', gap: '4px', marginBottom: '12px' } }
		>
			<Button
				size="small"
				icon="arrow-up-alt2"
				label={ __( 'Move up', 'osous' ) }
				disabled={ position <= 0 }
				onClick={ () => move( -1 ) }
			/>
			<Button
				size="small"
				icon="arrow-down-alt2"
				label={ __( 'Move down', 'osous' ) }
				disabled={ position < 0 || position >= kin.length - 1 }
				onClick={ () => move( 1 ) }
			/>
			<Button
				size="small"
				icon="admin-page"
				label={ __( 'Duplicate', 'osous' ) }
				onClick={ () =>
					apply( ( list ) => [
						...list.slice( 0, group.index + 1 ),
						cloneForSibling( list[ group.index ] ),
						...list.slice( group.index + 1 ),
					] )
				}
			/>
			<Button
				size="small"
				isDestructive
				icon="trash"
				label={ __( 'Remove', 'osous' ) }
				disabled={ kin.length <= 1 }
				onClick={ () => apply( ( list ) => list.filter( ( _, i ) => i !== group.index ) ) }
			/>
		</div>
	);
}

function Edit( { attributes, setAttributes } ) {
	const { nodes = [], label } = attributes;
	const groups = collectGroups( nodes );

	const setNodes = ( next ) => setAttributes( { nodes: next } );
	const update = ( path ) => ( changes ) => setNodes( withNode( nodes, path, changes ) );

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
						{ group.repeatable && (
							<GroupActions group={ group } nodes={ nodes } setNodes={ setNodes } />
						) }
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
						'Empty. Insert a section from the pattern library, or duplicate one that already has the shape you want.',
						'osous'
					) }
				</Placeholder>
			) : (
				/*
				 * POST, not GET. ServerSideRender puts the attributes in the query string by
				 * default, and a section's node tree is a few kilobytes of JSON -- long
				 * enough that Apache answers 414 and the preview silently never arrives.
				 */
				<ServerSideRender
					block={ metadata.name }
					attributes={ attributes }
					httpMethod="POST"
				/>
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
