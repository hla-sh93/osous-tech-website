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
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	Button,
	Disabled,
	PanelBody,
	Placeholder,
	SelectControl,
	TextControl,
	TextareaControl,
	ToggleControl,
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
	// Most buttons keep their words in a `txt` child, but seven links on the site carry
	// their own -- and those had a field for where they go and none for what they say.
	a: __( 'Link text', 'osous' ),
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
	'self-center',
	'self-start',
	'justify-start',
	'justify-center',
	'justify-end',
	'place-center',
	'span-2',
	'span-3',
	'span-4',
	'col-right',
	'media-first',
	'self-center-tablet',
	'self-end-tablet',
	'row-tablet-1',
	'row-tablet-2',
	'row-tablet-3',
	'row-tablet-3-4',
	'row-tablet-4',
	'row-tablet-5',
	'span-mobile-2',
	'row-mobile-4',
	'row-mobile-5',
	'active',
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

/**
 * A deep copy with the classes a sibling must not inherit taken out.
 *
 * @param {Object} node Section node.
 * @return {Object} The copy.
 */
function cloneForSibling( node ) {
	const copied = JSON.parse( JSON.stringify( node ) );
	if ( copied.c ) {
		const kept = classesOf( copied ).filter(
			( c ) => ! NOT_INHERITED.includes( c )
		);
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
			current = {
				name: __( 'Content', 'osous' ),
				container: null,
				index: -1,
				fields: [],
			};
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
				const siblings = list.filter(
					( sibling ) => groupNameFor( sibling ) === name
				);
				current = {
					name: sprintf( '%1$s %2$d', name, counters[ name ] ),
					base: name,
					container: path,
					index,
					repeatable: siblings.length > 1,
					fields: [],
				};
				groups.push( current );
			}

			/*
			 * Not a chain of alternatives. A link can want two fields at once -- one for
			 * the words it shows, one for where it goes -- and `else if` gave it only the
			 * second, so seven links on the site had an address nobody could read and a
			 * label nobody could change.
			 */
			if ( TEXT_TYPES[ node.t ] && ( node.x || '' ).trim() !== '' ) {
				push( { path: here, kind: 'text', node } );
			}
			if ( node.t === 'img' ) {
				push( { path: here, kind: 'image', node } );
			}
			if ( node.t === 'a' && node.href ) {
				push( { path: here, kind: 'link', node } );
			}
			if ( node.t === 'input' || node.t === 'textarea' ) {
				push( { path: here, kind: 'placeholder', node } );
			}
			if ( node.t === 'sc' ) {
				push( { path: here, kind: 'shortcode', node } );
			}

			if ( Array.isArray( node.k ) ) {
				walk( node.k, here );
			}
		} );
	};

	walk( nodes, [] );

	// A group with nothing in it has nothing to show and nothing to edit.
	return groups.filter(
		( group ) => group.fields.length > 0 || group.repeatable
	);
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
			k:
				rest.length === 0
					? change( children )
					: withChildren( children, rest, change ),
		};
	} );
}

/**
 * A short, recognisable name for one field.
 *
 * @param {Object} field One of the fields collectGroups() found.
 * @return {string} The label.
 */
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
					? __(
							'This one carries formatting tags. Keep them to keep the styling.',
							'osous'
						)
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
			{ src && (
				<img
					src={ src }
					alt=""
					style={ { maxWidth: '100%', display: 'block' } }
				/>
			) }
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
						<Button
							variant="secondary"
							onClick={ open }
							style={ { marginTop: '8px' } }
						>
							{ src
								? __( 'Replace image', 'osous' )
								: __( 'Choose image', 'osous' ) }
						</Button>
					) }
				/>
			</MediaUploadCheck>
			<TextControl
				__nextHasNoMarginBottom
				label={ __( 'Alt text', 'osous' ) }
				help={ __(
					'What the picture shows, for people who cannot see it.',
					'osous'
				) }
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

/**
 * The words inside an empty form field.
 *
 * A placeholder is the only text a contact form shows before anybody types, and it was
 * the one piece of copy on that page with no field at all.
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.field    The field, from collectGroups().
 * @param {Function} props.onChange Called with the node properties to change.
 * @return {Element} The control.
 */
function PlaceholderField( { field, onChange } ) {
	return (
		<TextControl
			__nextHasNoMarginBottom
			label={ __( 'Placeholder', 'osous' ) }
			help={ field.node[ 'aria-label' ] || '' }
			value={ field.node.placeholder || '' }
			onChange={ ( next ) => onChange( { placeholder: next } ) }
		/>
	);
}

/**
 * A plugin's form or widget, sitting in the section as the one line that names it.
 *
 * The contact form is Contact Form 7: its fields, its messages and who receives the mail
 * are edited under Contact → Contact Forms, and the section only says which form goes
 * here. The shortcode is still a field, so a second form can take the first one's place.
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.field    The field, from collectGroups().
 * @param {Function} props.onChange Called with the node properties to change.
 * @return {Element} The control.
 */
function ShortcodeField( { field, onChange } ) {
	const isContactForm = /^\s*\[contact-form-7\b/.test( field.node.x || '' );

	return (
		<TextControl
			__nextHasNoMarginBottom
			label={
				isContactForm
					? __( 'Contact form', 'osous' )
					: __( 'Shortcode', 'osous' )
			}
			help={
				isContactForm
					? __(
							'The fields, the messages and who receives the mail are edited under Contact → Contact Forms. Only which form sits here is edited from this box.',
							'osous'
						)
					: __( 'One shortcode, and nothing else.', 'osous' )
			}
			value={ field.node.x || '' }
			onChange={ ( next ) => onChange( { x: next } ) }
		/>
	);
}

const CONTROLS = {
	text: TextField,
	image: ImageField,
	link: LinkField,
	placeholder: PlaceholderField,
	shortcode: ShortcodeField,
};

/**
 * What a section can look like, in the design's own vocabulary.
 *
 * Not a colour picker and not free CSS: every option is a combination that already exists
 * on the site, which is why each one is already verified. The presets pair the surface
 * with its pattern on purpose -- `bg-img-1` is a texture drawn for the dark panel and
 * belongs on nothing else.
 *
 * The lists come from counting what the 401 section roots actually carry, so nothing here
 * is invented and nothing real is missing.
 */
const BACKGROUNDS = [
	{ value: 'white', label: __( 'White', 'osous' ), classes: [] },
	{
		value: 'neutral',
		label: __( 'Light grey', 'osous' ),
		classes: [ 'c-surface-neutral' ],
	},
	{
		value: 'panel',
		label: __( 'White panel', 'osous' ),
		classes: [ 'c-surface-white' ],
	},
	{
		value: 'dark',
		label: __( 'Dark blue', 'osous' ),
		classes: [ 'c-surface-secondary' ],
	},
	{
		value: 'dark-1',
		label: __( 'Dark blue, pattern', 'osous' ),
		classes: [ 'c-surface-secondary', 'bg-img-1' ],
	},
	{
		value: 'dark-2',
		label: __( 'Dark blue, second pattern', 'osous' ),
		classes: [ 'c-surface-secondary', 'bg-img-2' ],
	},
	{
		value: 'photo-1',
		label: __( 'Photo backdrop', 'osous' ),
		classes: [ 'bg-img-5' ],
	},
	{
		/* `overflow-hidden` is not decoration here: the rule that paints this one is
		   `.section.overflow-hidden.bg-3`, so the pair travels together or neither works. */
		value: 'photo-2',
		label: __( 'Photo backdrop, second', 'osous' ),
		classes: [ 'overflow-hidden', 'bg-3' ],
	},
];

/*
 * `bg-img-15` is deliberately not here. It is the only other section background on the
 * site, and what it paints is one page's own photograph -- the Azure page's -- behind a
 * dark scrim. The others are backdrops drawn for any section; that one is content, and
 * offering it would put a picture of a data centre behind somebody's contact details.
 */

const SPACING = [
	{ value: 'normal', label: __( 'Normal', 'osous' ), classes: [] },
	{ value: 'tall', label: __( 'Tall', 'osous' ), classes: [ 'full' ] },
	{ value: 'slim', label: __( 'Slim', 'osous' ), classes: [ 'p-y-1' ] },
	{
		value: 'tight-top',
		label: __( 'Tight at the top', 'osous' ),
		classes: [ 'top-tight' ],
	},
	{
		value: 'none',
		label: __( 'No padding', 'osous' ),
		classes: [ 'padding-none' ],
	},
	{
		value: 'flat',
		label: __( 'No padding above or below', 'osous' ),
		classes: [ 'p-y-0' ],
	},
	{
		value: 'no-bottom',
		label: __( 'No padding below', 'osous' ),
		classes: [ 'p-b-0' ],
	},
];

/**
 * How many across a grid lays its cells out.
 *
 * The five the design has, and no more: `.grid-1` to `.grid-5` are the only ones
 * `layout.css` defines, each with its own behaviour at 991px, 767px and 479px. This is
 * the one choice that turns a section shape into several -- the same row of cards is a
 * different section at two across and at four -- and it is why one pattern per archetype
 * was never going to be enough on its own.
 *
 * `grid-to-carousel`, `carousel-in-mobile` and the `gap-*` classes sit on some of these
 * same nodes and are not listed here on purpose: they are modifiers, not counts, and
 * `withGrid()` carries them through untouched.
 */
const GRIDS = [
	{ value: 'grid-1', label: __( 'One column', 'osous' ) },
	{ value: 'grid-2', label: __( 'Two columns', 'osous' ) },
	{ value: 'grid-3', label: __( 'Three columns', 'osous' ) },
	{ value: 'grid-4', label: __( 'Four columns', 'osous' ) },
	{ value: 'grid-5', label: __( 'Five columns', 'osous' ) },
];

const GRID_NAMES = GRIDS.map( ( grid ) => grid.value );

/**
 * Every grid in a section, innermost last, with the path to each.
 *
 * Most sections have one. A few have a grid inside a grid, and each gets its own control
 * rather than the first one silently standing for both.
 *
 * @param {Array} nodes Section nodes.
 * @param {Array} path  Path to `nodes`, for recursion.
 * @return {Array} `{ path, current, classes }` for each grid found.
 */
function collectGrids( nodes, path = [] ) {
	const found = [];

	nodes.forEach( ( node, index ) => {
		const here = [ ...path, index ];
		const classes = classesOf( node );
		const current = classes.find( ( name ) => GRID_NAMES.includes( name ) );

		if ( current ) {
			found.push( { path: here, current, classes } );
		}
		found.push( ...collectGrids( node.k || [], here ) );
	} );

	return found;
}

/**
 * Swap one grid's count for another, leaving every other class on the node alone.
 *
 * @param {string[]} classes The node's classes.
 * @param {string}   from    The grid class it has.
 * @param {string}   to      The grid class it should have.
 * @return {string} The new class attribute.
 */
function withGrid( classes, from, to ) {
	return classes.map( ( name ) => ( name === from ? to : name ) ).join( ' ' );
}

/**
 * Surfaces that paint on any node, not only on a section root.
 *
 * Counting the 401 sections settles why this list has to exist: 43 of them carry a colour
 * on the root, and 144 more carry one on a panel *inside* the section -- 133 of those on a
 * `.container` or `.container-full`. A Background control that only reached the root
 * reached fewer than a quarter of the colours on the site, which is why a grey panel could
 * sit in plain sight with the panel insisting the section was white. Both are true: the
 * section is white and the panel on it is grey.
 *
 * The patterned backgrounds in BACKGROUNDS are deliberately not offered here. Every one of
 * their rules is written `.section.bg-img-...`, so on a panel they would do nothing at all.
 * `components.css` declares `.c-surface-*` unscoped, so these five paint wherever they land.
 */
const SURFACES = [
	{ value: 'none', label: __( 'None', 'osous' ), classes: [] },
	{
		value: 'white',
		label: __( 'White', 'osous' ),
		classes: [ 'c-surface-white' ],
	},
	{
		value: 'neutral',
		label: __( 'Light grey', 'osous' ),
		classes: [ 'c-surface-neutral' ],
	},
	{
		value: 'primary',
		label: __( 'Purple', 'osous' ),
		classes: [ 'c-surface-primary' ],
	},
	{
		value: 'dark',
		label: __( 'Dark blue', 'osous' ),
		classes: [ 'c-surface-secondary' ],
	},
];

/**
 * Every panel in a section: the wrappers a surface can sit on.
 *
 * @param {Array} nodes Section nodes.
 * @param {Array} path  Path to `nodes`, for recursion.
 * @return {Array} `{ path, classes }` for each panel found.
 */
function collectPanels( nodes, path = [] ) {
	const found = [];

	nodes.forEach( ( node, index ) => {
		const here = [ ...path, index ];
		const classes = classesOf( node );

		/*
		 * `container` and `container-full` are the bands; `modal` is the panel lifted over
		 * a picture, and `card` the plain one. Counting the sections found twelve modals
		 * and three cards carrying a colour that nothing could reach. `card-1` … `card-5`
		 * are deliberately not here: those are card *types*, and their fill comes from a
		 * painted child rather than a surface class on the card.
		 */
		if (
			classes.some( ( name ) =>
				/^(container(-|$)|modal$|card$)/.test( name )
			)
		) {
			found.push( { path: here, classes } );
		}
		found.push( ...collectPanels( node.k || [], here ) );
	} );

	return found;
}

const AS_BUILT = '__as_built__';

/**
 * Which preset a section is currently using, or null when it is something else.
 *
 * @param {string[]} classes The node's classes.
 * @param {Array}    presets One of the preset lists above.
 * @return {?string} The preset's value, or null.
 */
function presetFor( classes, presets ) {
	const managed = presets.flatMap( ( preset ) => preset.classes );
	const mine = classes.filter( ( name ) => managed.includes( name ) ).sort();

	const found = presets.find(
		( preset ) =>
			[ ...preset.classes ].sort().join( ' ' ) === mine.join( ' ' )
	);
	return found ? found.value : null;
}

/**
 * Swap one preset's classes for another's, leaving everything else alone.
 *
 * @param {string[]} classes The node's classes.
 * @param {Array}    presets One of the preset lists above.
 * @param {string}   value   The preset to apply.
 * @return {string[]} The new classes.
 */
function withPreset( classes, presets, value ) {
	const managed = presets.flatMap( ( preset ) => preset.classes );
	const kept = classes.filter( ( name ) => ! managed.includes( name ) );
	const chosen = presets.find( ( preset ) => preset.value === value );

	return chosen ? [ ...kept, ...chosen.classes ] : classes;
}

/**
 * Background, spacing and the bottom margin of the section itself.
 *
 * Everything works on the root node's class, and every class the presets do not own is
 * carried through untouched -- `header`, `list-scroll-section`, `split-section` and the
 * rest say what a section *is* and are not anybody's to change from here.
 *
 * @param {Object}   props          Component props.
 * @param {Array}    props.nodes    Section nodes.
 * @param {Function} props.setNodes Replaces them.
 * @return {Element} The panel.
 */
function Appearance( { nodes, setNodes } ) {
	const root = nodes[ 0 ] || {};
	const classes = classesOf( root );

	const background = presetFor( classes, BACKGROUNDS );
	const spacing = presetFor( classes, SPACING );
	const setClasses = ( next ) =>
		setNodes( withNode( nodes, [ 0 ], { c: next.join( ' ' ) } ) );

	const options = ( presets, current ) => {
		const list = presets.map( ( preset ) => ( {
			value: preset.value,
			label: preset.label,
		} ) );
		if ( current === null ) {
			list.unshift( {
				value: AS_BUILT,
				label: __( 'As built', 'osous' ),
			} );
		}
		return list;
	};

	const grids = collectGrids( nodes );
	const panels = collectPanels( nodes );

	return (
		<PanelBody title={ __( 'Appearance', 'osous' ) } initialOpen={ false }>
			{ grids.map( ( grid, index ) => (
				<SelectControl
					key={ grid.path.join( '-' ) }
					__nextHasNoMarginBottom
					label={
						grids.length > 1
							? sprintf(
									/* translators: %d: which grid in the section, counting from one. */
									__( 'Columns (grid %d)', 'osous' ),
									index + 1
								)
							: __( 'Columns', 'osous' )
					}
					help={ __(
						'How many across on a wide screen. They stack on a phone either way.',
						'osous'
					) }
					value={ grid.current }
					options={ GRIDS }
					onChange={ ( value ) =>
						setNodes(
							withNode( nodes, grid.path, {
								c: withGrid(
									grid.classes,
									grid.current,
									value
								),
							} )
						)
					}
				/>
			) ) }
			<SelectControl
				__nextHasNoMarginBottom
				label={ __( 'Background', 'osous' ) }
				value={ background === null ? AS_BUILT : background }
				options={ options( BACKGROUNDS, background ) }
				onChange={ ( value ) =>
					value !== AS_BUILT &&
					setClasses( withPreset( classes, BACKGROUNDS, value ) )
				}
			/>
			<SelectControl
				__nextHasNoMarginBottom
				label={ __( 'Spacing', 'osous' ) }
				help={ __(
					'How much room the section takes above and below its content.',
					'osous'
				) }
				value={ spacing === null ? AS_BUILT : spacing }
				options={ options( SPACING, spacing ) }
				onChange={ ( value ) =>
					value !== AS_BUILT &&
					setClasses( withPreset( classes, SPACING, value ) )
				}
			/>
			{ panels.map( ( panel, index ) => {
				const current = presetFor( panel.classes, SURFACES );

				return (
					<SelectControl
						key={ panel.path.join( '-' ) }
						__nextHasNoMarginBottom
						label={
							panels.length > 1
								? sprintf(
										/* translators: %d: which panel in the section, counting from one. */
										__( 'Panel %d', 'osous' ),
										index + 1
									)
								: __( 'Panel', 'osous' )
						}
						help={ __(
							'The band inside the section. It can be a different colour from the section itself.',
							'osous'
						) }
						value={ current === null ? AS_BUILT : current }
						options={ options( SURFACES, current ) }
						onChange={ ( value ) =>
							value !== AS_BUILT &&
							setNodes(
								withNode( nodes, panel.path, {
									c: withPreset(
										panel.classes,
										SURFACES,
										value
									).join( ' ' ),
								} )
							)
						}
					/>
				);
			} ) }
			<ToggleControl
				__nextHasNoMarginBottom
				label={ __( 'Join the section below', 'osous' ) }
				help={ __(
					'Removes the gap underneath, so the two read as one band.',
					'osous'
				) }
				checked={ classes.includes( 'm-b-0' ) }
				onChange={ ( on ) =>
					setClasses(
						on
							? [ ...classes, 'm-b-0' ]
							: classes.filter( ( name ) => name !== 'm-b-0' )
					)
				}
			/>
		</PanelBody>
	);
}

/**
 * The children of the container at `path`; the root array when the path is empty.
 *
 * @param {Array} nodes Section nodes.
 * @param {Array} path  Child indices from the root.
 * @return {Array} The children.
 */
function childrenAt( nodes, path ) {
	return path.reduce( ( list, index ) => list[ index ].k || [], nodes );
}

/**
 * Move, copy and remove for one repeated item.
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.group    The group, from collectGroups().
 * @param {Array}    props.nodes    Section nodes.
 * @param {Function} props.setNodes Replaces them.
 * @return {Element} The row of buttons.
 */
function GroupActions( { group, nodes, setNodes } ) {
	const siblings = childrenAt( nodes, group.container );

	// Positions of the items of this kind. A divider or a spacer can sit among them, so
	// "the one above" means the previous card, not the previous element.
	const kin = siblings
		.map( ( node, index ) =>
			groupNameFor( node ) === group.base ? index : -1
		)
		.filter( ( index ) => index !== -1 );
	const position = kin.indexOf( group.index );

	const apply = ( change ) =>
		setNodes( withChildren( nodes, group.container, change ) );

	const move = ( offset ) => {
		const target = kin[ position + offset ];
		if ( target === undefined ) {
			return;
		}
		apply( ( list ) => {
			const next = [ ...list ];
			[ next[ group.index ], next[ target ] ] = [
				next[ target ],
				next[ group.index ],
			];
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
				onClick={ () =>
					apply( ( list ) =>
						list.filter( ( _, i ) => i !== group.index )
					)
				}
			/>
		</div>
	);
}

function Edit( { attributes, setAttributes } ) {
	const { nodes = [], label } = attributes;
	const groups = collectGroups( nodes );
	const blockProps = useBlockProps();

	const setNodes = ( next ) => setAttributes( { nodes: next } );
	const update = ( path ) => ( changes ) =>
		setNodes( withNode( nodes, path, changes ) );

	return (
		<Fragment>
			<InspectorControls>
				{ nodes.length > 0 && (
					<Appearance nodes={ nodes } setNodes={ setNodes } />
				) }
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
					<PanelBody
						key={ i }
						title={ group.name }
						initialOpen={ i === 0 }
					>
						{ group.repeatable && (
							<GroupActions
								group={ group }
								nodes={ nodes }
								setNodes={ setNodes }
							/>
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

			<div { ...blockProps }>
				{ nodes.length === 0 ? (
					<Placeholder label={ label || __( 'Section', 'osous' ) }>
						{ __(
							'Empty. Insert a section from the pattern library, or duplicate one that already has the shape you want.',
							'osous'
						) }
					</Placeholder>
				) : (
					/*
					 * Disabled, so a click on a card selects the section instead of following
					 * the link inside it. Without it the rendered preview swallows every
					 * click and the only way to select anything is the list view.
					 *
					 * POST, not GET. ServerSideRender puts the attributes in the query string
					 * by default, and a section's node tree is a few kilobytes of JSON --
					 * long enough that Apache answers 414 and the preview silently never
					 * arrives.
					 */
					<Disabled>
						<ServerSideRender
							block={ metadata.name }
							attributes={ attributes }
							httpMethod="POST"
						/>
					</Disabled>
				) }
			</div>
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
