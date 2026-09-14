/**
 * Run one of the browser harnesses headless and print what it found.
 *
 *     node _tools/run_harness.js "geometry.html?slugs=about,index"
 *     node _tools/run_harness.js "pinned-list.html?slugs=siem-soc-solutions-iraq"
 *
 * Exit code 0 when the harness reports nothing wrong, 1 otherwise.
 *
 * WHY THIS EXISTS
 *
 * The harnesses under _tools/*.html measure pages in frames, and they need a browser. A
 * tab that is not on screen is not that browser: Chrome pauses requestAnimationFrame in a
 * hidden document, ScrollTrigger does its work on rAF, so a pinned heading in a background
 * tab never pins -- and the harness reports a fault that is not there. That happened twice
 * on the same afternoon: once in the desktop app's browser pane while the pane was hidden,
 * once in Chrome while another window was in front. Both runs said "never pinned" about
 * code that was fine.
 *
 * Headless Chrome reports itself visible and runs rAF, so under it the harness sees what
 * a reader sees, and the verdict does not depend on which window happens to be on top.
 * Playwright is already here as a dependency of @wordpress/scripts and drives the Chrome
 * already installed; nothing is downloaded.
 */
'use strict';

const { chromium } = require( 'playwright' );

const BASE = process.env.OSOUS_WP || 'http://localhost:8080';
const TIMEOUT_MS = 10 * 60 * 1000;

async function main() {
	const target = process.argv[ 2 ];
	if ( ! target ) {
		console.error( 'usage: node _tools/run_harness.js "<harness>.html?slugs=..."' );
		process.exit( 2 );
	}

	const url = `${ BASE }/static/_tools/${ target }`;
	const browser = await chromium.launch( { channel: 'chrome', headless: true } );
	const page = await browser.newPage( { viewport: { width: 1440, height: 900 } } );

	page.on( 'pageerror', ( error ) => console.error( 'page error:', error.message ) );

	await page.goto( url, { waitUntil: 'load' } );

	/* Both harnesses write their verdict into #status and start it with "done". */
	await page.waitForFunction(
		() => /^done/.test( ( document.getElementById( 'status' ) || {} ).textContent || '' ),
		null,
		{ timeout: TIMEOUT_MS, polling: 1000 }
	);

	const result = await page.evaluate( () => ( {
		visibility: document.visibilityState,
		status: document.getElementById( 'status' ).textContent,
		rows: Array.from( document.querySelectorAll( '#out tbody tr' ) ).map( ( row ) =>
			Array.from( row.cells ).map( ( cell ) => cell.textContent.trim() ).join( ' | ' )
		),
	} ) );

	await browser.close();

	console.log( `${ target }  (document ${ result.visibility })` );
	for ( const row of result.rows ) {
		console.log( '  ' + row );
	}
	console.log( result.status );

	/* "0 of 6 pages differ", "3 pinned lists checked, 0 wrong" -- the same two shapes. */
	const clean = /(^|\D)0 (of \d+ pages differ|wrong)/.test( result.status );
	process.exit( clean ? 0 : 1 );
}

main().catch( ( error ) => {
	console.error( error.message || error );
	process.exit( 1 );
} );
