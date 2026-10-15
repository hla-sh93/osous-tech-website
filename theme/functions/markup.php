<?php
/**
 * Small helpers the block templates share.
 *
 * Each block prints its own markup in its file under includes/. What those files have in
 * common is here: turning a stored URL into a real one, printing an image or a button
 * the way the design draws them, and letting through the little inline markup an author
 * may put inside a heading or a paragraph.
 *
 * Every function that returns markup escapes everything it emits, and phpcs.xml.dist
 * lists those by name so a template may print their result directly.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline markup an author may put inside a heading, paragraph or label.
 *
 * Anything else is stripped on output. The list matches what the editor's toolbar can
 * produce -- bold, italic, a link, a line break, and the design's three span styles --
 * and nothing more, because this is the only place editor-supplied HTML reaches the page.
 *
 * @return array<string,array<string,bool>>
 */
function osous_inline_html() {
	return array(
		'strong' => array( 'class' => true ),
		'em'     => array(),
		'span'   => array( 'class' => true ),
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
 * Inline content, filtered to the tags an author is allowed to use.
 *
 * @param string $html Stored inline HTML.
 * @return string Safe to print.
 */
function osous_inline( $html ) {
	return wp_kses( (string) $html, osous_inline_html() );
}

/**
 * Rewrite a stored URL into a real one.
 *
 * Block attributes keep the static site's own paths -- `assets/images/x.svg`,
 * `about.html`, `#inquiry` -- which makes the content readable, diffable and independent
 * of where WordPress happens to be installed. Resolving them here means the same content
 * works on localhost and in production without a search-and-replace.
 *
 * A page's path is resolved to the page, not pasted onto the site's address: `about.html`
 * is the page that has that address now, or the one that had it before it was renamed
 * (functions/links.php), printed as that page's address today and with the trailing
 * slash the site's permalinks use. So a link survives its page's slug being changed.
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
	// `about.html` -> the About page, wherever it is now; `blog/page/2.html` -> /blog/page/2/.
	if ( substr( $url, -5 ) === '.html' ) {
		$path = substr( $url, 0, -5 );
		$page = osous_page_id( $path );

		return $page ? osous_page_url( $page ) : home_url( user_trailingslashit( '/' . $path ) );
	}

	return $url;
}

/**
 * Rewrite every candidate in a srcset.
 *
 * @param string $srcset Stored srcset.
 * @return string Escaped, safe to print.
 */
function osous_srcset( $srcset ) {
	$out = array();
	foreach ( explode( ',', (string) $srcset ) as $candidate ) {
		$candidate = trim( $candidate );
		if ( '' === $candidate ) {
			continue;
		}
		$parts = preg_split( '/\s+/', $candidate, 2 );
		$out[] = esc_url( osous_url( $parts[0] ) ) . ( isset( $parts[1] ) ? ' ' . esc_attr( $parts[1] ) : '' );
	}

	return implode( ', ', $out );
}

/**
 * A class attribute from any number of class strings, or nothing when they are all empty.
 *
 * @param string ...$parts Class names, space separated; empties are skipped.
 * @return string ` class="…"`, escaped, or ''.
 */
function osous_class_attr( ...$parts ) {
	$names = array();
	foreach ( $parts as $part ) {
		foreach ( explode( ' ', (string) $part ) as $name ) {
			if ( '' !== $name ) {
				$names[] = $name;
			}
		}
	}

	return $names ? ' class="' . esc_attr( implode( ' ', $names ) ) . '"' : '';
}

/**
 * The classes that draw a block's own background picture, or '' when it has none.
 *
 * A section, a container, a panel, the hero and the industries carousel can each carry a
 * picture chosen from the Media Library (`picture`), darkened by one of the design's two
 * shades (`pictureShade`). `has-picture` lays it over whatever background the design
 * draws there (assets/css/sections.css), so the design's choices still apply when the
 * picture is removed. blocks/shared/controls.js draws the same in the editor.
 *
 * @param array $attributes The block's attributes.
 * @return string Class names.
 */
function osous_picture_class( $attributes ) {
	if ( empty( $attributes['picture'] ) ) {
		return '';
	}

	return trim( 'has-picture ' . osous_option( 'shade', isset( $attributes['pictureShade'] ) ? $attributes['pictureShade'] : '' ) );
}

/**
 * The style attribute that names a block's own background picture, or ''.
 *
 * The address goes to the stylesheet as a custom property, so the stylesheet decides how
 * it is drawn -- covering the box, under a shade -- and the attribute carries nothing but
 * the address.
 *
 * @param array $attributes The block's attributes.
 * @return string ` style="…"`, escaped, or ''.
 */
function osous_picture_style( $attributes ) {
	$url = empty( $attributes['picture'] ) ? '' : esc_url_raw( osous_url( $attributes['picture'] ) );
	if ( '' === $url ) {
		return '';
	}

	return ' style="' . esc_attr( '--osous-picture: url("' . $url . '")' ) . '"';
}

/**
 * The heading tag for a level, and nothing that is not one.
 *
 * @param int|string $level 1 to 6.
 * @return string `h1` … `h6`.
 */
function osous_heading_tag( $level ) {
	$level = (int) $level;

	return 'h' . ( $level >= 1 && $level <= 6 ? $level : 2 );
}

/**
 * Print an image.
 *
 * @param array  $image   `src`, and optionally `alt`, `srcset`, `sizes`, `priority`.
 * @param string $classes Class names for it.
 */
function osous_image( array $image, $classes = '' ) {
	if ( empty( $image['src'] ) ) {
		return;
	}
	echo '<img src="' . esc_url( osous_url( $image['src'] ) ) . '"';
	if ( ! empty( $image['srcset'] ) ) {
		echo ' srcset="' . osous_srcset( $image['srcset'] ) . '"';
	}
	if ( ! empty( $image['sizes'] ) ) {
		echo ' sizes="' . esc_attr( $image['sizes'] ) . '"';
	}
	// Always, even when empty: an image with no alt attribute is read aloud by its file name.
	echo ' alt="' . esc_attr( isset( $image['alt'] ) ? $image['alt'] : '' ) . '"';
	if ( ! empty( $image['priority'] ) ) {
		echo ' fetchpriority="high"';
	}
	echo osous_class_attr( $classes ) . '>';
}

/**
 * The one icon the design draws: the arrow inside outlined and text buttons.
 *
 * @param string $name Icon name. Only `arrow` exists.
 */
function osous_icon( $name = 'arrow' ) {
	if ( 'arrow' !== $name ) {
		return;
	}
	echo '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.6822 18.7468L21.4651 12M21.4651 12L15.6822 5.25323M21.4651 12L3.7085 12" stroke="currentColor" stroke-width="1.5"/></svg>';
}

/**
 * Print a button: a link with the design's label inside it, and an arrow where the style
 * has one.
 *
 * @param string $text    The label; inline markup allowed.
 * @param string $url     Where it goes.
 * @param string $style   One of osous_design()['button']: primary, outline, text, neutral.
 * @param string $classes Extra classes on the link itself.
 */
function osous_button( $text, $url, $style = 'primary', $classes = '' ) {
	$style = osous_option( 'button', $style ) ? $style : 'primary';

	$label = 't-r-md c-on-primary';
	$arrow = '';
	if ( 'outline' === $style ) {
		$label = 't-r-md';
		$arrow = 'icon-24 c-on-neutral';
	} elseif ( 'text' === $style ) {
		$label = 't-r-md c-on-secondary link';
		$arrow = 'icon-24 c-neutral-active';
	} elseif ( 'neutral' === $style ) {
		$label = 't-r-md';
	}

	echo '<a href="' . esc_url( osous_url( $url ) ) . '"' . osous_class_attr( osous_option( 'button', $style ), $classes ) . '>';
	echo '<div' . osous_class_attr( $label ) . '>' . osous_inline( $text ) . '</div>';
	if ( $arrow ) {
		echo '<div' . osous_class_attr( $arrow ) . '>';
		osous_icon();
		echo '</div>';
	}
	echo '</a>';
}

/**
 * Print the blocks inside a block.
 *
 * `$content` is what WordPress hands a container's template: its inner blocks, already
 * rendered by their own templates, each of which escaped what it printed. There is
 * nothing left to escape, and escaping it again would strip the SVG arrows out of the
 * buttons. This is the one place that prints it, so the sniff is answered once.
 *
 * @param string $content Rendered inner blocks.
 */
function osous_inner( $content ) {
	echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered blocks, escaped by their own templates; see above.
}

/**
 * Print the numbered badge a card can carry: "01" in a circle.
 *
 * @param string $number The figure shown; nothing is printed for an empty one.
 */
function osous_number( $number ) {
	if ( '' === trim( (string) $number ) ) {
		return;
	}
	echo '<div class="number"><div class="h3 number-style-1">' . osous_inline( $number ) . '</div><div class="number-shape"></div></div>';
}

/**
 * Prose, as the design marks it up.
 *
 * Paragraphs, lists and headings inside a prose area are WordPress's own blocks, and
 * WordPress writes its own class on two of them. The design's stylesheets never mention
 * those classes, and its lists carry `role="list"` instead -- the attribute that keeps a
 * list announced as a list once its bullets are styled away. So the block's output is
 * put back into the design's shape here, once, rather than every page carrying markup
 * the design has no use for.
 *
 * @param string $content The rendered inner blocks.
 * @return string Safe to print: it is the blocks' own output with two strings replaced.
 */
function osous_prose_markup( $content ) {
	return str_replace(
		array( '<ul class="wp-block-list">', '<ol class="wp-block-list">', ' class="wp-block-heading"', ' class="wp-block-paragraph"' ),
		array( '<ul role="list">', '<ol role="list">', '', '' ),
		(string) $content
	);
}

/**
 * Whether inner content has anything to show.
 *
 * The editor leaves an empty paragraph behind more easily than it seems -- a click into
 * a card's prose area and away again is enough -- and a prose box printed around nothing
 * is a box the design does not have. Words count, and so does anything drawn without
 * words: a picture, a rule, an embed.
 *
 * @param string $content Rendered inner blocks.
 * @return bool
 */
function osous_has_prose( $content ) {
	$content = (string) $content;

	return '' !== trim( wp_strip_all_tags( $content ) )
		|| (bool) preg_match( '/<(img|hr|iframe|video|audio|svg|figure)\b/i', $content );
}
