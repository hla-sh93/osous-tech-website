/**
 * Check the contact form end to end: the layout, the validation, the mail.
 *
 *     node _tools/verify_contact.js
 *     node _tools/verify_contact.js --headed      # watch it happen
 *
 * verify_wp.py proves the form's markup is the design's once Contact Form 7's scaffolding
 * is taken away. This proves the rest: that the scaffolding changes no pixel of the
 * layout, that the form refuses what it should, and that a message typed into it arrives
 * -- in the local mail catcher, with every field in it and the right addresses on it.
 *
 * LAYOUT   The static reference page and the WordPress page are opened at the same size
 *          and the boxes of the panel, the form, every field, the button and the Turnstile
 *          reserve are compared. CF7 wraps each control in an inline <span>; this is where
 *          "does the span move anything" is answered with a number rather than a guess.
 *
 * REFUSAL  Submitted empty, in the browser: five fields are flagged and nothing is sent.
 *
 * DELIVERY Submitted filled in, in the browser: the form reports it sent, and Mailpit
 *          (docker-compose.yml) holds a message to the address in Customize → Site
 *          details, with the visitor's address as Reply-To and every field in the body.
 *          The Turnstile widget runs with Cloudflare's always-pass test keys, so the real
 *          plugin does the real verification and passes.
 */
'use strict';

const { chromium } = require( 'playwright' );

const BASE = process.env.OSOUS_WP || 'http://localhost:8080';
const MAILPIT = process.env.OSOUS_MAILPIT || 'http://localhost:8025';
const TOLERANCE = 1; // px

const BOXES = [
	'.modal.form-panel',
	'.form-block',
	'.form-block form',
	'#name',
	'#company',
	'#email',
	'#mobile',
	'#message',
	'.btn.form',
	'.cf7-cf-turnstile',
	// Not the static form's own "Thank you" and "Oops" boxes: both are `display: none`,
	// and Contact Form 7 answers with a response box of its own, so the theme does not
	// print them. `_tools/verify_wp.py` takes them out of the reference for the same reason.
];

const wait = ( ms ) => new Promise( ( resolve ) => { setTimeout( resolve, ms ); } );

/*
 * Nothing is measured until the page has settled. The design reveals headings, fields and
 * buttons on scroll with a short animation; a field read while it is still sliding into
 * place reports a top a few pixels off, and the two pages are never mid-animation at the
 * same instant. So: scroll everything into view, come back, and wait for every animation
 * on the page to finish.
 */
async function settle( page ) {
	await page.evaluate( () => document.fonts && document.fonts.ready );
	await page.evaluate( async () => {
		window.scrollTo( 0, document.documentElement.scrollHeight );
		await new Promise( ( r ) => { setTimeout( r, 300 ); } );
		window.scrollTo( 0, 0 );
		await Promise.all( document.getAnimations().map( ( a ) => a.finished.catch( () => {} ) ) );
	} );
	await wait( 300 );
}

async function boxes( page ) {
	await settle( page );

	return page.evaluate( ( selectors ) => {
		const out = {};
		for ( const selector of selectors ) {
			const el = document.querySelector( selector );
			if ( ! el ) {
				out[ selector ] = null;
				continue;
			}
			const r = el.getBoundingClientRect();
			out[ selector ] = [ r.left, r.top + window.scrollY, r.width, r.height ].map( Math.round );
		}
		return out;
	}, BOXES );
}

function compareBoxes( wp, ref, problems ) {
	for ( const selector of BOXES ) {
		const a = wp[ selector ];
		const b = ref[ selector ];
		if ( ! a || ! b ) {
			problems.push( `${ selector }: ${ a ? 'missing on the reference' : 'missing on WordPress' }` );
			continue;
		}
		const off = [ 'left', 'top', 'width', 'height' ].filter( ( _, i ) => Math.abs( a[ i ] - b[ i ] ) > TOLERANCE );
		if ( off.length ) {
			problems.push( `${ selector }: ${ off.join( ', ' ) } differ -- wp ${ a.join( 'x' ) } vs reference ${ b.join( 'x' ) }` );
		}
	}
}

async function mailpitMessages() {
	const list = await fetch( `${ MAILPIT }/api/v1/messages` ).then( ( r ) => r.json() );
	return list.messages || [];
}

async function main() {
	const headed = process.argv.includes( '--headed' );
	const problems = [];
	const stamp = Date.now().toString( 36 );
	const visitor = {
		name: `Verify ${ stamp }`,
		company: 'Osous QA',
		email: `verify-${ stamp }@example.com`,
		mobile: '+964 750 000 0000',
		message: `Typed by _tools/verify_contact.js at ${ new Date().toISOString() }.`,
	};

	const browser = await chromium.launch( { channel: 'chrome', headless: ! headed } );
	const context = await browser.newContext( { viewport: { width: 1440, height: 900 } } );

	/* ---------------------------------------------------------------- layout ----- */
	const ref = await context.newPage();
	await ref.goto( `${ BASE }/static/contact.html`, { waitUntil: 'load' } );
	const refBoxes = await boxes( ref );
	await ref.close();

	const page = await context.newPage();
	await page.goto( `${ BASE }/contact/`, { waitUntil: 'load' } );
	const wpBoxes = await boxes( page );
	compareBoxes( wpBoxes, refBoxes, problems );
	const layoutProblems = problems.length;

	/* ---------------------------------------------------------------- refusal ---- */
	const form = page.locator( '.form-block form' );
	await page.locator( '.btn.form' ).click( { force: true } );
	await page.waitForFunction(
		() => /\b(invalid|sent|failed|aborted|spam)\b/.test( document.querySelector( '.form-block form' ).className ),
		null,
		{ timeout: 20000 }
	).catch( () => {} );
	const emptyState = await form.evaluate( ( el ) => el.className );
	const tips = await page.locator( '.wpcf7-not-valid-tip' ).count();
	if ( ! /\binvalid\b/.test( emptyState ) ) {
		problems.push( `submitted empty, the form is "${ emptyState }" rather than invalid` );
	}
	if ( tips !== 5 ) {
		problems.push( `submitted empty, ${ tips } fields are flagged; expected all 5` );
	}

	/* --------------------------------------------------------------- delivery ---- */
	const before = ( await mailpitMessages() ).length;
	await page.fill( '#name', visitor.name );
	await page.fill( '#company', visitor.company );
	await page.fill( '#email', visitor.email );
	await page.fill( '#mobile', visitor.mobile );
	await page.fill( '#message', visitor.message );

	/* Turnstile enables the button once its (test-key) challenge passes. */
	await page.waitForFunction(
		() => {
			const token = document.querySelector( 'input[name="cf-turnstile-response"]' );
			return token && token.value.length > 0;
		},
		null,
		{ timeout: 30000 }
	).catch( () => problems.push( 'Turnstile never produced a token -- is the widget rendering, and can the browser reach challenges.cloudflare.com?' ) );

	await page.locator( '.btn.form' ).click( { force: true } );
	await page.waitForFunction(
		() => /\b(sent|failed|aborted|spam)\b/.test( document.querySelector( '.form-block form' ).className ),
		null,
		{ timeout: 30000 }
	).catch( () => {} );
	const sentState = await form.evaluate( ( el ) => el.className );
	const response = await page.locator( '.wpcf7-response-output' ).innerText().catch( () => '' );
	if ( ! /\bsent\b/.test( sentState ) ) {
		problems.push( `submitted filled in, the form is "${ sentState }" -- it said: "${ response.trim() }"` );
	}

	let delivered = null;
	for ( let i = 0; i < 20 && ! delivered; i++ ) {
		const messages = await mailpitMessages();
		delivered = messages.find( ( m ) => ( m.Subject || '' ).includes( visitor.name ) );
		if ( ! delivered ) {
			await wait( 500 );
		}
	}
	if ( ! delivered ) {
		problems.push( `no message with "${ visitor.name }" in its subject reached Mailpit (${ before } there before)` );
	} else {
		const detail = await fetch( `${ MAILPIT }/api/v1/message/${ delivered.ID }` ).then( ( r ) => r.json() );
		const to = ( detail.To || [] ).map( ( a ) => a.Address ).join( ', ' );
		const replyTo = ( detail.ReplyTo || [] ).map( ( a ) => a.Address ).join( ', ' );
		const body = detail.Text || '';
		const expectedTo = await page.evaluate( () => {
			const link = document.querySelector( 'a[href^="mailto:"]' );
			return link ? link.getAttribute( 'href' ).replace( 'mailto:', '' ) : '';
		} );
		if ( expectedTo && to !== expectedTo ) {
			problems.push( `mail went to "${ to }", the site's address is "${ expectedTo }"` );
		}
		if ( replyTo !== visitor.email ) {
			problems.push( `Reply-To is "${ replyTo }", the visitor typed "${ visitor.email }"` );
		}
		for ( const [ field, value ] of Object.entries( visitor ) ) {
			if ( ! body.includes( value ) ) {
				problems.push( `the ${ field } typed into the form is not in the mail` );
			}
		}
	}

	await browser.close();

	console.log( `layout   : ${ BOXES.length } boxes measured, ${ layoutProblems ? layoutProblems + ' differ' : 'all within ' + TOLERANCE + 'px of the reference' }` );
	console.log( `refusal  : empty submission -> ${ /\binvalid\b/.test( emptyState ) ? 'refused' : 'NOT refused' }, ${ tips } fields flagged` );
	console.log( `delivery : ${ /\bsent\b/.test( sentState ) ? 'form reports sent' : 'form did NOT send' }; ${ delivered ? 'message in Mailpit: ' + delivered.Subject : 'nothing in Mailpit' }` );

	if ( problems.length ) {
		console.log( '' );
		for ( const problem of problems ) {
			console.log( '  !! ' + problem );
		}
	}
	console.log( `\n${ problems.length } problems` );
	process.exit( problems.length ? 1 : 0 );
}

main().catch( ( error ) => {
	console.error( error.message || error );
	process.exit( 1 );
} );
