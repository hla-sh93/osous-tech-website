/**
 * The design's option tables, as the theme hands them to the editor.
 *
 * functions/design.php is the one place the values, their labels and their classes are
 * written down; functions/blocks.php passes them here as `window.osousEditor.design`, so a
 * select control in the sidebar and the class a template prints can never disagree.
 */

/**
 * The editor's settings from the theme: asset paths and the option tables.
 *
 * @return {Object} `{ themeUrl, homeUrl, design }`.
 */
export function editorSettings() {
	return window.osousEditor || { themeUrl: '/', homeUrl: '/', design: {} };
}

/**
 * The options of one group, in the shape SelectControl wants.
 *
 * @param {string} group A key of functions/design.php's table: 'surface', 'spacing', …
 * @return {Array<{value: string, label: string}>} Options.
 */
export function options( group ) {
	return ( editorSettings().design[ group ] || [] ).map( ( option ) => ( {
		value: option.value,
		label: option.label,
	} ) );
}

/**
 * The class a value stands for, or '' for a value the design does not name.
 *
 * @param {string} group A key of the table.
 * @param {string} value The chosen value.
 * @return {string} Class names, space separated.
 */
export function classFor( group, value ) {
	const found = ( editorSettings().design[ group ] || [] ).find(
		( option ) => option.value === String( value ?? '' )
	);
	return found ? found.class : '';
}

/**
 * Class names joined, with the empties left out.
 *
 * @param {...(string|false|null|undefined)} parts Class names.
 * @return {string} The class attribute's value.
 */
export function cx( ...parts ) {
	return parts
		.filter( ( part ) => typeof part === 'string' && part.trim() !== '' )
		.join( ' ' )
		.split( /\s+/ )
		.filter( Boolean )
		.join( ' ' );
}
