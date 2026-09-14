<?php
/**
 * The section renderer.
 *
 * A page is an ordered tree of typed nodes, and this file is the only thing that turns
 * those nodes into markup. Every class name, every wrapper, every attribute the design
 * needs is described by data; nothing here invents structure and nothing outside here
 * emits it.
 *
 * The node types are deliberately few. `_tools/extract.py` refuses to parse anything it
 * cannot express in them, so the vocabulary below is exhaustive by construction rather
 * than by hope.
 *
 *   box   a wrapper element with children            k
 *   txt   an element whose content is inline only     x
 *   h     h1-h6                                       g, x
 *   p     a paragraph                                 x
 *   rich  a block of prose the editor owns whole      x
 *   a     a link                                      href, x or k
 *   btn   a button                                    x or k
 *   img   an image                                    src, srcset, sizes, alt
 *   icon  the one icon in the design                  n
 *   list  a styled ul/ol                              g, k
 *   li    one of its items                            x or k
 *   form / input / textarea / br   a form drawn as markup
 *   sc    one shortcode: a plugin's form or widget     x
 *
 * Phase 3 keeps every one of these functions. A dynamic block's render_callback hands
 * its attributes to exactly this renderer, so the markup proven correct now is the
 * markup that ships.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline markup an author may put inside a heading, paragraph or link.
 *
 * Anything else is stripped on output. This is the only place editor-supplied HTML
 * reaches the page, so the list stays short on purpose.
 *
 * @return array<string,array<string,bool>>
 */
function osous_inline_html() {
	return array(
		'strong' => array( 'class' => true ),
		'span'   => array( 'class' => true ),
		'em'     => array(),
		'sup'    => array(),
		'sub'    => array(),
		'br'     => array(),
		'a'      => array(
			'href'   => true,
			'class'  => true,
			'target' => true,
			'rel'    => true,
		),
	);
}

/**
 * Rewrite a stored URL into a real one.
 *
 * The section data keeps the static site's own paths, which makes it readable, diffable
 * and independent of where WordPress happens to be installed. Resolving them here means
 * the same data works on localhost and in production without a search-and-replace.
 *
 * @param string $url Stored URL: `assets/…`, `slug.html`, an anchor, or an absolute URL.
 * @return string
 */
function osous_url( $url ) {
	$url = (string) $url;

	if ( '' === $url || '#' === $url[0] ) {
		return $url;
	}
	if ( preg_match( '#^(https?:|mailto:|tel:|//)#i', $url ) ) {
		return $url;
	}
	if ( 0 === strpos( $url, 'assets/' ) ) {
		return get_theme_file_uri( $url );
	}
	if ( 'index.html' === $url ) {
		return home_url( '/' );
	}
	// `blog/page/2.html` -> /blog/page/2/, `about.html` -> /about/.
	if ( substr( $url, -5 ) === '.html' ) {
		return home_url( '/' . substr( $url, 0, -5 ) . '/' );
	}

	return $url;
}

/**
 * Rewrite every candidate in a srcset.
 *
 * @param string $srcset Stored srcset.
 * @return string
 */
function osous_srcset( $srcset ) {
	$out = array();
	foreach ( explode( ',', (string) $srcset ) as $candidate ) {
		$candidate = trim( $candidate );
		if ( '' === $candidate ) {
			continue;
		}
		$parts = preg_split( '/\s+/', $candidate, 2 );
		$out[] = esc_url( osous_url( $parts[0] ) ) . ( isset( $parts[1] ) ? ' ' . $parts[1] : '' );
	}

	return implode( ', ', $out );
}

/**
 * Print the attributes a node carries, in a fixed order.
 *
 * @param array         $node  Node data.
 * @param array<string> $names Attribute names to consider, in output order.
 * @return string
 */
function osous_attrs( array $node, array $names ) {
	$url_attrs = array( 'href', 'src', 'action' );
	$out       = '';

	foreach ( $names as $name ) {
		if ( ! isset( $node[ $name ] ) ) {
			continue;
		}
		$value = $node[ $name ];

		if ( true === $value ) {
			$out .= ' ' . $name;
			continue;
		}
		if ( '' === $value && 'alt' !== $name && 'value' !== $name ) {
			continue;
		}
		if ( in_array( $name, $url_attrs, true ) ) {
			$value = esc_url( osous_url( $value ) );
		} elseif ( 'srcset' === $name ) {
			$value = osous_srcset( $value );
		} else {
			$value = esc_attr( $value );
		}
		$out .= ' ' . $name . '="' . $value . '"';
	}

	return $out;
}

/**
 * The class attribute, or nothing when a node has no classes.
 *
 * @param array $node Node data.
 * @return string
 */
function osous_class( array $node ) {
	$class = isset( $node['c'] ) ? trim( $node['c'] ) : '';

	return '' === $class ? '' : ' class="' . esc_attr( $class ) . '"';
}

/**
 * Inline content, filtered to the tags an author is allowed to use.
 *
 * @param array $node Node data.
 * @return string
 */
function osous_inline( array $node ) {
	return isset( $node['x'] ) ? wp_kses( $node['x'], osous_inline_html() ) : '';
}

/**
 * The one icon the design draws: the arrow inside outline buttons.
 *
 * @param string $name Icon name.
 */
function osous_icon( $name ) {
	if ( 'arrow' !== $name ) {
		return;
	}
	?>
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.6822 18.7468L21.4651 12M21.4651 12L15.6822 5.25323M21.4651 12L3.7085 12" stroke="currentColor" stroke-width="1.5"/></svg>
	<?php
}

/**
 * Render a list of nodes.
 *
 * @param array $nodes Node data.
 */
function osous_render_nodes( $nodes ) {
	if ( ! is_array( $nodes ) ) {
		return;
	}
	foreach ( $nodes as $node ) {
		if ( is_array( $node ) ) {
			osous_render_node( $node );
		}
	}
}

/**
 * Render one node.
 *
 * @param array $node Node data.
 */
function osous_render_node( array $node ) {
	$type = isset( $node['t'] ) ? $node['t'] : '';

	switch ( $type ) {

		case 'box':
			$tag = isset( $node['g'] ) ? $node['g'] : 'div';
			echo '<' . tag_escape( $tag ) . osous_class( $node )
				. osous_attrs( $node, array( 'id', 'role', 'aria-hidden', 'data-paged', 'data-query', 'fs-list-element', 'fs-list-load', 'udy-collection' ) ) . '>';
			osous_render_nodes( isset( $node['k'] ) ? $node['k'] : array() );
			echo '</' . tag_escape( $tag ) . '>';
			break;

		case 'txt':
			$tag = isset( $node['g'] ) ? $node['g'] : 'div';
			echo '<' . tag_escape( $tag ) . osous_class( $node ) . '>' . osous_inline( $node ) . '</' . tag_escape( $tag ) . '>';
			break;

		case 'h':
			$tag = isset( $node['g'] ) ? $node['g'] : 'h2';
			echo '<' . tag_escape( $tag ) . osous_class( $node ) . '>' . osous_inline( $node ) . '</' . tag_escape( $tag ) . '>';
			break;

		case 'p':
			echo '<p' . osous_class( $node ) . '>' . osous_inline( $node ) . '</p>';
			break;

		case 'rich':
			$tag = isset( $node['g'] ) ? $node['g'] : 'div';
			echo '<' . tag_escape( $tag ) . osous_class( $node ) . '>'
				. wp_kses_post( isset( $node['x'] ) ? $node['x'] : '' )
				. '</' . tag_escape( $tag ) . '>';
			break;

		case 'a':
			echo '<a' . osous_attrs( $node, array( 'href' ) ) . osous_class( $node )
				. osous_attrs( $node, array( 'target', 'rel', 'aria-label', 'aria-current' ) ) . '>';
			if ( isset( $node['k'] ) ) {
				osous_render_nodes( $node['k'] );
			} else {
				echo osous_inline( $node );
			}
			echo '</a>';
			break;

		case 'btn':
			echo '<button' . osous_attrs( $node, array( 'type' ) ) . osous_class( $node )
				. osous_attrs( $node, array( 'aria-label' ) ) . '>';
			if ( isset( $node['k'] ) ) {
				osous_render_nodes( $node['k'] );
			} else {
				echo osous_inline( $node );
			}
			echo '</button>';
			break;

		case 'img':
			echo '<img' . osous_attrs( $node, array( 'src', 'srcset', 'sizes', 'alt', 'width', 'height', 'fetchpriority' ) ) . osous_class( $node ) . '>';
			break;

		case 'icon':
			osous_icon( isset( $node['n'] ) ? $node['n'] : '' );
			break;

		case 'list':
			$tag = isset( $node['g'] ) ? $node['g'] : 'ul';
			echo '<' . tag_escape( $tag ) . osous_attrs( $node, array( 'role' ) ) . osous_class( $node ) . '>';
			osous_render_nodes( isset( $node['k'] ) ? $node['k'] : array() );
			echo '</' . tag_escape( $tag ) . '>';
			break;

		case 'li':
			echo '<li' . osous_class( $node ) . '>';
			if ( isset( $node['k'] ) ) {
				osous_render_nodes( $node['k'] );
			} else {
				echo osous_inline( $node );
			}
			echo '</li>';
			break;

		case 'form':
			echo '<form' . osous_class( $node ) . osous_attrs( $node, array( 'method', 'action', 'aria-label' ) ) . '>';
			osous_render_nodes( isset( $node['k'] ) ? $node['k'] : array() );
			echo '</form>';
			break;

		case 'input':
			echo '<input' . osous_class( $node )
				. osous_attrs( $node, array( 'id', 'name', 'type', 'size', 'maxlength', 'placeholder', 'aria-label', 'value', 'required' ) ) . '>';
			break;

		case 'textarea':
			echo '<textarea' . osous_class( $node )
				. osous_attrs( $node, array( 'id', 'name', 'cols', 'rows', 'maxlength', 'placeholder', 'aria-label', 'required' ) ) . '></textarea>';
			break;

		case 'br':
			echo '<br>';
			break;

		case 'sc':
			/*
			 * Exactly one shortcode and nothing else. The whole string has to match the
			 * shortcode pattern, rather than being handed to do_shortcode() with whatever
			 * else it holds, so an author who can edit a page cannot slip markup past the
			 * renderer inside a node that is meant to be a plugin's placeholder. The contact
			 * form is Contact Form 7, and this is how it sits inside a section
			 * (see inc/contact.php).
			 */
			$code = isset( $node['x'] ) ? trim( (string) $node['x'] ) : '';
			if ( '' !== $code && preg_match( '/^' . get_shortcode_regex() . '$/s', $code ) ) {
				echo do_shortcode( $code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the plugin's own markup, printed as core's shortcode block prints it.
			}
			break;
	}
}

/**
 * The section data for a page.
 *
 * The page's own blocks, which is where the content lives.
 *
 * Nothing renders from this -- the blocks render themselves. It is here because
 * inc/enqueue.php has to know which behaviours a page asks for before the loop starts,
 * and a page gets the pinned-list module because it contains a pinned list.
 *
 * @param int $post_id Page ID. Defaults to the queried page.
 * @return array Top-level nodes, in order.
 */
function osous_page_sections( $post_id = 0 ) {
	static $cache = array();

	$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();
	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}

	$sections = array();
	$content  = $post_id ? get_post_field( 'post_content', $post_id ) : '';

	if ( $content && has_blocks( $content ) ) {
		foreach ( parse_blocks( $content ) as $block ) {
			if ( 'osous/section' !== $block['blockName'] || empty( $block['attrs']['nodes'] ) ) {
				continue;
			}
			foreach ( (array) $block['attrs']['nodes'] as $node ) {
				$sections[] = $node;
			}
		}
	}

	$cache[ $post_id ] = $sections;

	return $sections;
}

/**
 * Which behaviours a page's sections ask for.
 *
 * Scripts follow the markup rather than a hand-kept list of slugs, so a section moved
 * from one page to another brings its behaviour with it. The static site keeps such a
 * list, and it had already drifted: two pages carrying a split section loaded the
 * pinned-list module instead.
 *
 * @param array $nodes Section data.
 * @return array<string,bool> Keyed by class name, e.g. `list-scroll`.
 */
function osous_section_features( $nodes ) {
	$found = array();

	$scan = function ( $items ) use ( &$scan, &$found ) {
		foreach ( (array) $items as $node ) {
			if ( ! is_array( $node ) ) {
				continue;
			}
			foreach ( explode( ' ', isset( $node['c'] ) ? $node['c'] : '' ) as $class ) {
				if ( '' !== $class ) {
					$found[ $class ] = true;
				}
			}
			if ( isset( $node['k'] ) ) {
				$scan( $node['k'] );
			}
		}
	};
	$scan( $nodes );

	return $found;
}

/**
 * Render a whole page's sections inside `<main>`.
 *
 * @param array $nodes Section data.
 */
function osous_render_main( $nodes ) {
	echo '<main>';
	osous_render_nodes( $nodes );
	echo '</main>';
}
