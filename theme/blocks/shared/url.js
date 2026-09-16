/**
 * Stored URLs into real ones, the way inc/markup.php's osous_url() does it.
 *
 * The blocks keep the static site's own paths -- `assets/images/x.svg`, `about.html` --
 * so the content stays readable and works wherever WordPress is installed. The canvas
 * has to show the same picture the page shows, so it resolves them the same way.
 */
import { editorSettings } from './design';

/**
 * A stored URL as the browser can fetch it.
 *
 * @param {string} url `assets/…`, `slug.html`, an anchor, or an absolute URL.
 * @return {string} The resolved URL.
 */
export function resolveUrl( url ) {
	const value = String( url || '' );
	const { themeUrl, homeUrl } = editorSettings();

	if ( value === '' || value.startsWith( '#' ) ) {
		return value;
	}
	if ( /^(https?:|mailto:|tel:|\/\/)/i.test( value ) ) {
		return value;
	}
	if ( value.startsWith( 'assets/' ) ) {
		return themeUrl + value;
	}
	if ( value === 'index.html' ) {
		return homeUrl;
	}
	if ( value.endsWith( '.html' ) ) {
		return homeUrl + value.slice( 0, -5 ) + '/';
	}
	return value;
}

/**
 * Every candidate of a srcset resolved.
 *
 * @param {string} srcset The stored srcset.
 * @return {string} The resolved srcset, or '' for none.
 */
export function resolveSrcset( srcset ) {
	return String( srcset || '' )
		.split( ',' )
		.map( ( candidate ) => candidate.trim() )
		.filter( Boolean )
		.map( ( candidate ) => {
			const [ url, ...rest ] = candidate.split( /\s+/ );
			return [ resolveUrl( url ), ...rest ].join( ' ' );
		} )
		.join( ', ' );
}
