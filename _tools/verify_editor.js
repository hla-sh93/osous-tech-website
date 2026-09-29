/**
 * Check that the block editor's canvas paints what the page paints.
 *
 *     node _tools/verify_editor.js
 *     node _tools/verify_editor.js about contact-us     # just these pages
 *     node _tools/verify_editor.js --patterns           # just the section library
 *     node _tools/verify_editor.js --headed             # watch it happen
 *     node _tools/verify_editor.js --width=1440
 *
 * WHY THIS EXISTS
 *
 * The editor is where the site gets edited, so the canvas has to show the design.
 * Somebody choosing a background, or dropping a section onto a page, is choosing by eye;
 * a canvas that lies about colour, type or layout turns every one of those choices into
 * guesswork. Nothing else in this suite looks at the editor at all -- verify_wp.py and
 * geometry.html both read the front end -- so the canvas rendering every section at
 * Gutenberg's 840px content width, which is what it did until this check existed, passed
 * all of them. It was a desktop layout in a tablet-width box: neither of the two layouts
 * this site has.
 *
 * WHY IT IS NOT AN HTML HARNESS LIKE THE OTHERS
 *
 * geometry.html measures two pages by framing them. That cannot work here: the block
 * editor screen sends `Document-Isolation-Policy: isolate-and-credentialless`, which puts
 * it in its own agent cluster, so an embedding page cannot reach its document even from
 * the same origin. `/wp-admin/` frames and reads fine; `post.php?action=edit` never will.
 * Driving two real pages is the only way in.
 *
 * Playwright is already here, as a dependency of @wordpress/scripts, and it drives the
 * Chrome that is already installed -- `channel: 'chrome'` downloads nothing.
 *
 * HOW IT LOGS IN
 *
 * WP-CLI mints a login cookie and the browser is handed it. No password is typed, none is
 * stored here, and nothing has to be configured before the first run.
 *
 * WHAT IS COMPARED, AND WHAT IS NOT
 *
 * Every element of every section, by tag, class and the paint properties in PROPS.
 * Lengths are compared with a 2px tolerance: the canvas is a scrollbar's width narrower
 * than the window beside it and every percentage lands a pixel out.
 *
 * Three things are excluded on purpose, each by name rather than by loosening the
 * comparison:
 *
 *   - `is-revealed`, the class reveal.js adds on scroll. assets/css/editor.css gives
 *     those elements the settled appearance directly, so the computed styles match even
 *     though the class does not.
 *   - The subtrees editor.css reshapes on purpose -- the pinned split section, the
 *     overlapping card stack, the marquee. GSAP does not run in the canvas, so these are
 *     laid out plainly there by design. See INTENTIONAL.
 *   - The carousel, which Swiper drives with inline transforms on the front end and
 *     leaves alone in the canvas.
 *
 * THE SECTION LIBRARY
 *
 * theme/patterns/ is checked the same way. Each pattern is a real section lifted from a
 * real page -- gen_patterns.py names which one in the file's header -- so there is a page
 * to hold it against. Every pattern is inserted into the editor as the inserter would
 * insert it; every block it parses to has to be registered and valid; the inserter has to
 * offer exactly the ones whose files say so; and the canvas has to paint the inserted
 * pattern as the page paints that section. Without this a pattern could drift from the
 * blocks -- an attribute renamed, a parent changed -- and nothing would say so until
 * somebody clicked +. The default run checks the six pages and then the library.
 */
'use strict';

const { execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const ROOT = path.dirname(__dirname);
const BASE = process.env.OSOUS_WP || 'http://localhost:8080';

const TOLERANCE = 2;      // px; the canvas is a scrollbar narrower than the page
const SETTLE_MS = 700;

/* Six pages between them carrying every kind of section the site has: the hero and the
   carousel, the pinned list, the card stack, the numbered and photo cards, the partner
   marquee and the contact form. */
const DEFAULT_SLUGS = ['index', 'about', 'contact', 'siem-soc-solutions-iraq',
	'managed-it-services-iraq', 'next-generation-firewall-iraq'];

/* Colour, type, spacing, alignment, decoration, and the box the element occupies.
   `position` and `transform` are absent because the pinned sections are excluded by name
   anyway, and including them would only re-flag those. */
const PROPS = ['display', 'color', 'background-color', 'background-image',
	'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing',
	'text-align', 'text-transform', 'text-decoration-line',
	'margin-top', 'margin-bottom', 'padding-top', 'padding-right', 'padding-bottom',
	'padding-left', 'border-top-width', 'border-radius', 'border-color',
	'flex-direction', 'flex-wrap', 'align-items', 'justify-content', 'gap',
	'grid-template-columns', 'opacity'];

const INTENTIONAL = ['.split-section', '.card-overlapping-item', '.infinite-carousel',
	'.swiper-container',
	// The box the design reserves for Turnstile. The plugin fills it with its own
	// scaffolding and Cloudflare's script builds an iframe and a token field inside that,
	// none of which runs in the canvas. The box itself is compared; its contents are not.
	'.cf7-cf-turnstile'];

/*
 * Classes a script decides at runtime. None of them is a choice anybody made in the editor,
 * and none of the scripts runs in the canvas.
 *
 * `active` is the odd one and the instructive one: it is in the saved markup, on the first
 * item of a pinned list, because that is the state the reference page was captured in. On
 * the live page ScrollTrigger *removes* it -- the highlight only holds while the item is
 * crossing the middle of the window -- so at rest the page has no highlighted item and the
 * canvas, which cannot scroll, still shows one. Both are right for where they are.
 *
 * All of them are ignored when elements are matched up by name.
 */
const RUNTIME_CLASSES = ['is-revealed', 'active', 'swiper-initialized', 'swiper-horizontal',
	'swiper-vertical', 'swiper-backface-hidden', 'swiper-android', 'swiper-ios',
	'swiper-pointer-events', 'swiper-watch-progress',
	// Contact Form 7 swaps `no-js` for `js` on its wrapper once its script has run.
	'no-js', 'js',
	// The editor's own: every block wrapper, every area that holds blocks, every text
	// being edited in place, and the selection states. None of them is on the page.
	'wp-block', 'block-editor-block-list__block', 'block-editor-block-list__layout',
	'block-editor-inner-blocks', 'is-selected', 'has-child-selected', 'is-highlighted',
	'is-hovered', 'is-dragging', 'is-multi-selected', 'rich-text',
	'block-editor-rich-text__editable', 'is-editing',
	// Core's own classes on the prose blocks. The editor gives a paragraph, a heading, a
	// list and its items each one; the page never has them, because osous_prose_markup()
	// takes them off on output (inc/markup.php).
	'wp-block-paragraph', 'wp-block-heading', 'wp-block-list', 'wp-block-list-item'];

/*
 * The subset that also changes what the element looks like, and cannot be reconciled.
 *
 * Only `active`. `.list-item.active` repaints three properties, and which item holds it
 * depends on where the page is scrolled, so an element that has it on one side and not on
 * the other has to have its styles left alone -- comparing them would compare the two
 * states rather than the design.
 *
 * `is-revealed` is deliberately NOT here even though the canvas never has it: editor.css
 * gives those elements the design's own settled appearance, so their styles *do* match and
 * must go on being compared. Putting it here excused 36 elements a page and quietly turned
 * this into a check of almost nothing.
 */
const RUNTIME_STATES = ['active'];

/*
 * Elements a script inserts. None of them is in the saved markup on either side, and only
 * the page runs the script, so they are left out of the digest altogether -- unlike the
 * pin-spacer, which wraps a real element and is walked through rather than dropped.
 *
 * `.wpcf7-spinner` is the one Contact Form 7 adds after its submit button at load: an
 * inline-block, hidden until the form is submitting, that still takes a line.
 */
const RUNTIME_ELEMENTS = ['.wpcf7-spinner',
	// The editor's "+" button inside an empty or selected container.
	'.block-list-appender', '.block-editor-inserter'];

/*
 * Editor wrappers with nothing of their own to paint: what they hold, the page holds
 * directly in their parent, so they are walked through rather than counted. Core's list
 * item is the one -- the editor edits an item's words in a <div> inside the <li>, and
 * the page's <li> holds the words itself. Only core's: the theme's own list items edit
 * their words in a <div> too, and there the page has that <div>, with the same class.
 * The section library is where this showed; no page in DEFAULT_SLUGS has a list inside
 * a card.
 */
const TRANSPARENT = ['li.wp-block-list-item > div.block-editor-rich-text__editable'];

/* ------------------------------------------------------------------------ WP-CLI ----- */

function wp(...args) {
	const command = ['compose', '--profile', 'tools', 'run', '--rm', 'cli', 'wp', ...args];
	const out = execFileSync('docker', command, {
		cwd: ROOT,
		encoding: 'utf8',
		// Git Bash rewrites anything that looks like a path on its way into the container.
		env: { ...process.env, MSYS_NO_PATHCONV: '1' },
		stdio: ['ignore', 'pipe', 'ignore'],
	});

	return out.trim();
}

/**
 * Login cookies for user 1, minted by WordPress itself.
 *
 * Two of them, and both are needed. `auth_redirect()` guards wp-admin with the *auth*
 * cookie -- over plain http, `secure_auth` over https -- and the rest of WordPress reads
 * the `logged_in` one. A browser carrying only the second lands on wp-login.php.
 */
function authCookies() {
	const raw = wp('eval',
		'$e = time() + 3600;' +
		'echo AUTH_COOKIE . "\t" . wp_generate_auth_cookie( 1, $e, "auth" ) . "\n";' +
		'echo LOGGED_IN_COOKIE . "\t" . wp_generate_auth_cookie( 1, $e, "logged_in" );');

	const url = new URL(BASE);
	const cookies = raw.split('\n')
		.map((line) => line.trim().split('\t'))
		.filter((pair) => pair.length === 2 && pair[0] && pair[1])
		.map(([name, value]) => ({
			name,
			value,
			domain: url.hostname,
			path: '/',
			httpOnly: true,
			secure: false,
		}));

	if (cookies.length !== 2) {
		throw new Error('WP-CLI did not return both login cookies');
	}

	return cookies;
}

function postIdFor(slug) {
	const id = wp('post', 'list', '--post_type=page', `--name=${slug}`, '--field=ID', '--format=ids');

	return parseInt(id.split(/\s+/)[0], 10);
}

/* --------------------------------------------------------------------- measuring ----- */

/*
 * Runs inside the page. One entry per element, depth first, so two digests line up index
 * for index; an excluded element is recorded and its children are not walked.
 */
function digestSource({ side, props, intentional, runtime, states, inserted, transparent }) {
	/* ScrollTrigger wraps everything it pins in a `.pin-spacer` of its own making, and
	   moves the pinned element's margins onto it -- the element keeps its place in the
	   flow through the spacer while it is held still. The spacer is scaffolding, not
	   design, and it exists only where GSAP runs: walk straight through it, and read the
	   margins off the spacer, which is where the element's own margins went. */
	const unwrap = (el) => (el.classList.contains('pin-spacer') && el.firstElementChild
		? { el: unwrap(el.firstElementChild).el, box: el } : { el, box: el });

	/*
	 * In the canvas each top-level block's wrapper *is* its section element -- the block
	 * draws itself. The one exception is the contact block, which the server draws
	 * (its form is Contact Form 7's): there the section sits inside the wrapper, under
	 * the disabled area ServerSideRender puts it in.
	 */
	const roots = (side === 'page'
		? Array.from(document.querySelector('main').children).map(unwrap)
		: Array.from(document.querySelectorAll('.is-root-container > [data-block]'))
			.map((block) => {
				const rendered = block.querySelector('.components-disabled > div');
				const el = rendered ? rendered.firstElementChild : block;
				return el && { el, box: el };
			})
	).filter((entry) => entry && entry.el);

	const names = (el) => (el.getAttribute('class') || '').split(/\s+/).filter(Boolean);
	/* `wp-block-osous-card` and its kin: the class the editor gives every block wrapper
	   after the block's name. The theme's templates print none, and no stylesheet here
	   mentions one -- verify_wp.py would flag it on the page if one ever appeared. */
	const classOf = (el) => names(el)
		.filter((n) => !runtime.includes(n) && !/^wp-block-/.test(n)).sort().join(' ');
	const stateOf = (el) => names(el).filter((n) => states.includes(n)).sort().join(' ');

	return roots.map((root) => {
		const out = [];
		(function walk({ el, box }, depth) {
			// Nothing to paint: a plugin's inline script is in the canvas, enqueued on the page.
			if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEMPLATE'].includes(el.tagName)) {
				return;
			}
			if (inserted.some((sel) => el.matches(sel))) {
				return;
			}
			const own = getComputedStyle(el);
			const outer = getComputedStyle(box);
			const skip = intentional.some((sel) => el.matches(sel));
			out.push({
				tag: el.tagName,
				cls: classOf(el),
				state: stateOf(el),
				depth,
				skip,
				w: Math.round(el.getBoundingClientRect().width),
				/* For the hanging-space rule in compare(): how this element breaks lines,
				   how big its type is, and whether its words could wrap at a space. */
				ws: own.getPropertyValue('white-space'),
				fs: parseFloat(own.getPropertyValue('font-size')) || 0,
				sp: /\s/.test(el.textContent),
				props: props.map((p) => (p.startsWith('margin-') ? outer : own).getPropertyValue(p)),
			});
			if (skip) { return; }
			/* A transparent wrapper's children stand where it stands. */
			const children = (node) => Array.from(node.children)
				.flatMap((child) => (transparent.some((sel) => child.matches(sel)) ? children(child) : [child]));
			for (const child of children(el)) { walk(unwrap(child), depth + 1); }
		})(root, 0);

		return out;
	});
}

/**
 * Two values are the same when they read the same, or when they differ only in numbers
 * within TOLERANCE of each other -- `repeat(3, 429.33px)` against `repeat(3, 430px)`. The
 * text around the numbers still has to match exactly, so a grid that lost a track, or a
 * colour that changed, is never forgiven.
 */
function same(a, b) {
	if (a === b) { return true; }
	const NUM = /-?\d*\.?\d+/g;
	if (a.replace(NUM, '#') !== b.replace(NUM, '#')) { return false; }
	const left = a.match(NUM) || [];
	const right = b.match(NUM) || [];

	return left.every((n, i) => Math.abs(parseFloat(n) - parseFloat(right[i])) <= TOLERANCE);
}

function nameOf(entry) {
	return entry.tag.toLowerCase() + (entry.cls ? `.${entry.cls.split(' ').join('.')}` : '');
}

function compare(editor, page, label, problems) {
	if (editor.length !== page.length) {
		/* Say where the two stop agreeing, not only that they do: a count alone sends
		   whoever reads it back to the browser to find out which element it was. */
		let at = 0;
		while (at < editor.length && at < page.length
			&& editor[at].tag === page[at].tag && editor[at].cls === page[at].cls
			&& editor[at].depth === page[at].depth) {
			at++;
		}
		const here = (list) => (at < list.length ? nameOf(list[at]) : 'nothing');
		problems.push(`${label}: ${editor.length} elements in the editor, ${page.length} on the page; `
			+ `they part at #${at}: editor has ${here(editor)}, page has ${here(page)}`);
		return { states: 0, spaces: 0 };
	}
	let states = 0;
	let spaces = 0;
	let inState = null;   // depth of the element whose state differs, while inside it

	editor.forEach((e, i) => {
		const p = page[i];
		const where = `${label} ${nameOf(e)}`;
		if (e.tag !== p.tag || e.cls !== p.cls || e.depth !== p.depth) {
			problems.push(`${where}: the page has ${nameOf(p)} here`);
			return;
		}
		if (inState !== null && e.depth > inState) {
			states++;
			return;
		}
		inState = null;
		if (e.skip) { return; }
		/*
		 * One side carries a runtime state class the other does not -- a highlighted list
		 * item against the same item at rest. Comparing their styles would compare the two
		 * states rather than the design that draws them.
		 *
		 * The subtree goes with it: `.list-item.active` sets `color`, and every heading and
		 * paragraph under it inherits that. Skipping only the element itself left its two
		 * children reporting white against #333, which is the same one difference counted
		 * three times.
		 */
		if (e.state !== p.state) {
			states++;
			inState = e.depth;
			return;
		}
		if (Math.abs(e.w - p.w) > TOLERANCE) {
			/*
			 * Text being edited in place is laid out `white-space: pre-wrap` -- the editor
			 * sets that inline on every editable, so that a space typed at the end of a
			 * line stays visible -- and in pre-wrap a space at a line wrap hangs off the
			 * line and is counted in the element's width. The page collapses that space.
			 * Nothing moves: the words wrap at the same place either way. So a width that
			 * is over by at most one space, on text the editor wraps this way and the page
			 * does not, is that space and no fault; it is counted and printed as
			 * "(n hanging spaces)" rather than silently forgiven.
			 */
			const hanging = e.sp && e.ws.startsWith('pre') && !p.ws.startsWith('pre')
				&& e.w > p.w && e.w - p.w <= 0.35 * e.fs + TOLERANCE;
			if (hanging) {
				spaces++;
			} else {
				problems.push(`${where}: width ${e.w} in the editor, ${p.w} on the page`);
			}
		}
		PROPS.forEach((prop, k) => {
			if (!same(e.props[k], p.props[k])) {
				problems.push(`${where}: ${prop} is ${e.props[k]} in the editor and ${p.props[k]} on the page`);
			}
		});
	});

	return { states, spaces };
}

/* ----------------------------------------------------------------------- loading ----- */

const wait = (ms) => new Promise((resolve) => { setTimeout(resolve, ms); });

/**
 * Wait for the web fonts.
 *
 * Text measured before Inter and Sora arrive is measured in a fallback face, and a
 * heading comes out thirteen to thirty pixels narrower than the same heading a moment
 * later. That is the same difference this check was written to catch when the canvas
 * really had no fonts, so it has to be waited out rather than tolerated.
 *
 * @param {import('playwright').Frame|import('playwright').Page} target Frame or page.
 */
async function fontsReady(target) {
	await target.evaluate(() => document.fonts && document.fonts.ready).catch(() => {});
}

/**
 * Wait for GSAP to stop moving, and for the pinned list to fall back to its first item.
 *
 * The page has to be scrolled from top to bottom before it is read, because reveal.js only
 * reveals what has gone past -- and scrolling is exactly what drives the pinned list's
 * `.active` highlight from one item to the next. Read a moment too early on the way back
 * and the page reports item four highlighted where the editor, which has never scrolled,
 * shows item one: white text against the page's dark, and four differences that are a
 * stopwatch rather than a fault.
 *
 * @param {import('playwright').Page} page The front page.
 */
async function scrollSettled(page) {
	await page.evaluate(async () => {
		const idle = () => !window.gsap || !window.gsap.globalTimeline
			.getChildren(true, true, false).some((tween) => tween.isActive());

		/* Quiet twice, a moment apart: a ScrollTrigger refresh can re-run the code that
		   starts these tweens, so the timeline goes still and then moves again. */
		let quiet = 0;
		for (let i = 0; i < 80 && quiet < 2; i++) {
			quiet = idle() ? quiet + 1 : 0;
			await new Promise((r) => { setTimeout(r, quiet === 1 ? 250 : 50); });
		}
	}).catch(() => {});
}

/**
 * Read a digest, and read it again until two readings in a row agree.
 *
 * Every other harness here does this, for the same reason: one reading of a page that is
 * still settling is true for an instant and different from the next one.
 *
 * @param {import('playwright').Frame|import('playwright').Page} target Frame or page.
 * @param {Object} args Arguments for `digestSource`.
 * @return {Array} The digest.
 */
async function stableDigest(target, args) {
	let previous = null;
	for (let i = 0; i < 5; i++) {
		const reading = await target.evaluate(digestSource, args);
		const text = JSON.stringify(reading);
		if (previous === text) {
			return reading;
		}
		previous = text;
		await wait(300);
	}

	throw new Error('the page never stopped moving');
}

/**
 * Size the window so the *content* is exactly this wide.
 *
 * Headless Chrome draws no scrollbar and headed Chrome draws a 15px one, so asking for a
 * viewport is not the same as asking for a content width. Measure, correct, measure again.
 */
async function fitContentWidth(page, target, height) {
	let viewport = target;
	for (let i = 0; i < 3; i++) {
		await page.setViewportSize({ width: viewport, height });
		const actual = await page.evaluate(() => document.documentElement.clientWidth);
		if (actual === target) { return; }
		viewport += target - actual;
	}
}

/**
 * The editor canvas, once every block has rendered.
 */
async function openEditor(page, id, width, height) {
	await page.setViewportSize({ width, height });
	await page.goto(`${BASE}/wp-admin/post.php?post=${id}&action=edit`, { waitUntil: 'domcontentloaded' });

	let canvas = null;
	for (let i = 0; i < 200 && !canvas; i++) {
		canvas = page.frames().find((f) => f.name() === 'editor-canvas');
		if (!canvas) { await wait(100); }
	}
	if (!canvas) { throw new Error('the editor canvas never appeared'); }
	await canvasSettled(canvas);

	return canvas;
}

/**
 * Wait until the canvas holds every block, rendered.
 *
 * A block is not ready when it appears: ServerSideRender fetches its markup over REST, so
 * wait for the count to stop moving and for every block to hold something. Given a count,
 * wait for that many too: after resetBlocks() the blocks it replaced are still drawn for
 * a frame or two.
 *
 * @param {import('playwright').Frame} canvas   The editor canvas.
 * @param {?number}                   expected How many top-level blocks there should be.
 */
async function canvasSettled(canvas, expected = null) {
	let stable = 0;
	let last = -1;
	for (let i = 0; i < 200 && stable < 3; i++) {
		const rendered = await canvas.evaluate(() => {
			const blocks = Array.from(document.querySelectorAll('.is-root-container > [data-block]'));
			const done = blocks.filter((b) => {
				/*
				 * The contact block is drawn by the server and shows `<Placeholder>` while
				 * ServerSideRender is still fetching -- and a placeholder is an element like
				 * any other, so "has rendered something" once believed the editor a second
				 * and a half too early. It is the *section* that has to be there. Every
				 * other block draws itself and is there as soon as it exists.
				 */
				const inner = b.querySelector('.components-disabled > div');
				if (!inner) {
					return !b.querySelector('.components-placeholder, .components-spinner');
				}
				const root = inner.firstElementChild;
				return root && !root.classList.contains('components-placeholder');
			});
			return [blocks.length, done.length];
		}).catch(() => [0, -1]);

		stable = (rendered[1] === last && rendered[1] === rendered[0] && rendered[1] > 0
			&& (expected === null || rendered[0] === expected))
			? stable + 1 : 0;
		last = rendered[1];
		await wait(150);
	}
	await fontsReady(canvas);
	await wait(SETTLE_MS);
}

/**
 * Every block on the page, and whether the editor accepted each one.
 *
 * A block whose saved markup is not what its save() would write is "invalid": the editor
 * shows a warning in its place and offers to recover it, and a person who clicks through
 * has their content rewritten. The prose blocks are core's own, saved by this theme's
 * migration rather than by the editor, so this is where a wrong guess about how core
 * saves a list would show.
 *
 * @param {import('playwright').Page} page The editor page.
 * @return {Promise<{total: number, invalid: string[]}>} Counts, and the invalid ones by name.
 */
async function blockValidity(page) {
	return page.evaluate(() => {
		const invalid = [];
		let total = 0;
		const walk = (blocks) => blocks.forEach((block) => {
			total++;
			if (block.isValid === false) { invalid.push(block.name); }
			walk(block.innerBlocks || []);
		});
		walk(window.wp.data.select('core/block-editor').getBlocks());
		return { total, invalid };
	});
}

/**
 * The front page, with everything revealed.
 *
 * reveal.js only reveals what has been scrolled past, so a page read from the top reports
 * opacity 0 on everything below the fold. Walk the whole page, then come back.
 */
async function openPage(page, slug, contentWidth, height) {
	await page.goto(`${BASE}/${slug}/`, { waitUntil: 'load' });
	await fitContentWidth(page, contentWidth, height);
	await fontsReady(page);
	await wait(SETTLE_MS);
	await page.evaluate(async (step) => {
		const height = document.documentElement.scrollHeight;
		for (let y = 0; y < height; y += step) {
			window.scrollTo(0, y);
			await new Promise((r) => { setTimeout(r, 60); });
		}
		window.scrollTo(0, 0);
	}, Math.round(height / 2));
	await scrollSettled(page);
	await wait(SETTLE_MS);
}

/* --------------------------------------------------------------------------- run ----- */

/**
 * Everything the editor page says went wrong, as it happens.
 *
 * A script error in the editor leaves blocks undrawn, and an undrawn block reads as a
 * missing section. Say what broke rather than only that something did.
 *
 * @param {import('playwright').Page} page The editor page.
 * @return {string[]} The list the errors land in.
 */
function watchErrors(page) {
	const errors = [];
	page.on('pageerror', (error) => errors.push(String(error.message || error)));
	page.on('console', (message) => {
		if (message.type() === 'error' && !/Failed to load resource/.test(message.text())) {
			errors.push(message.text());
		}
	});
	page.on('response', (response) => {
		if (response.status() >= 400) { errors.push(`${response.status()} ${response.url()}`); }
	});

	return errors;
}

const digestArgs = (side) => ({ side, props: PROPS, intentional: INTENTIONAL,
	runtime: RUNTIME_CLASSES, states: RUNTIME_STATES, inserted: RUNTIME_ELEMENTS,
	transparent: TRANSPARENT });

async function check(context, slug, width, height) {
	const id = postIdFor(slug);
	if (!id) { throw new Error(`no page with the slug ${slug}`); }

	const editorPage = await context.newPage();
	const errors = watchErrors(editorPage);
	const canvas = await openEditor(editorPage, id, width, height);
	const validity = await blockValidity(editorPage);

	/* Both sides are measured at the width a section actually gets, so they resolve the
	   same percentages. Not the canvas window and not the wrapper: the window may carry a
	   scrollbar and the wrapper carries the editor's own gutter, and a section is inside
	   both. Ask a section how wide it is. */
	const canvasWidth = await canvas.evaluate(() => {
		const block = document.querySelector('.is-root-container > [data-block]');
		return Math.round(block.getBoundingClientRect().width);
	});
	const left = await stableDigest(canvas, digestArgs('editor'));

	const frontPage = await context.newPage();
	await openPage(frontPage, slug, canvasWidth, height);
	const right = await stableDigest(frontPage, digestArgs('page'));

	await editorPage.close();
	await frontPage.close();

	const problems = [];
	let elements = 0;
	let states = 0;
	let spaces = 0;
	for (const error of errors.slice(0, 5)) {
		problems.push(`editor console: ${error.slice(0, 300)}`);
	}
	if (validity.invalid.length) {
		problems.push(`${validity.invalid.length} of ${validity.total} blocks are invalid in the editor: `
			+ [...new Set(validity.invalid)].join(', '));
	}
	if (left.length !== right.length) {
		problems.push(`${left.length} sections in the editor, ${right.length} on the page`);
	} else {
		left.forEach((section, i) => {
			elements += section.length;
			const found = compare(section, right[i], `section ${i + 1}`, problems);
			states += found.states;
			spaces += found.spaces;
		});
	}

	return { slug, sections: left.length, elements, blocks: validity.total, states, spaces, canvasWidth, problems };
}

/* ------------------------------------------------------------ the section library ----- */

/**
 * The library on disk: each file's slug, whether the inserter lists it, and the page and
 * section it was lifted from. gen_patterns.py writes all three in the header.
 *
 * @return {Object[]} One entry per file.
 */
function patternFiles() {
	const dir = path.join(ROOT, 'theme', 'patterns');

	return fs.readdirSync(dir).filter((file) => file.endsWith('.php')).sort().map((file) => {
		const text = fs.readFileSync(path.join(dir, file), 'utf8');
		const field = (name) => (text.match(new RegExp(`^ \\* ${name}: (.+)$`, 'm')) || [])[1];
		const source = text.match(/from ([a-z0-9-]+), section (\d+) of \d+/);
		if (!field('Slug') || !source) {
			throw new Error(`theme/patterns/${file}: the header names no slug or no source section`);
		}

		return {
			file,
			name: field('Slug'),
			inserter: field('Inserter') === 'yes',
			page: source[1],
			section: parseInt(source[2], 10),
		};
	});
}

/**
 * The library as the editor has it: this theme's registered patterns, with their content,
 * and the names the inserter offers at the top level of a page.
 *
 * @param {import('playwright').Page} page The editor page.
 * @return {Promise<{registered: Object[], offered: ?string[]}>} Both lists.
 */
async function registeredPatterns(page) {
	return page.evaluate(async () => {
		const all = await window.wp.data.resolveSelect('core').getBlockPatterns();
		const editor = window.wp.data.select('core/block-editor');
		const allowed = editor.__experimentalGetAllowedPatterns || editor.getAllowedPatterns;
		const ours = (list) => list.filter((p) => p.name.startsWith('osous/'));

		return {
			registered: ours(all).map((p) => ({ name: p.name, content: p.content })),
			offered: allowed ? ours(allowed()).map((p) => p.name) : null,
		};
	});
}

/**
 * Put one pattern on the canvas in place of whatever is there, as the inserter would.
 *
 * @param {import('playwright').Page}  page    The editor page.
 * @param {import('playwright').Frame} canvas  Its canvas.
 * @param {string}                     content The pattern's block markup.
 * @return {Promise<{roots: number, unknown: string[]}>} What it parsed to.
 */
async function insertPattern(page, canvas, content) {
	const parsed = await page.evaluate((markup) => {
		const { blocks, data } = window.wp;
		const list = blocks.parse(markup);
		const unknown = [];
		const walk = (items) => items.forEach((block) => {
			if (block.name === 'core/missing' || !blocks.getBlockType(block.name)) {
				unknown.push((block.attributes && block.attributes.originalName) || block.name);
			}
			walk(block.innerBlocks || []);
		});
		walk(list);
		data.dispatch('core/block-editor').resetBlocks(list);

		return { roots: list.length, unknown };
	}, content);
	await canvasSettled(canvas, parsed.roots);

	return parsed;
}

/**
 * Every pattern, inserted, and held against the section it was lifted from.
 *
 * One editor is opened and never saved: each pattern replaces the blocks on its canvas in
 * turn. Autosave is pushed out of reach first, so the page the editor was opened on gets
 * no autosave revision holding a pattern.
 *
 * @param {import('playwright').BrowserContext} context The logged-in browser.
 * @param {number}                              width   Window width.
 * @param {number}                              height  Window height.
 * @return {Promise<{results: Object[], problems: string[], offered: number}>} What was found.
 */
async function checkPatterns(context, width, height) {
	const files = patternFiles();
	const problems = [];

	const editorPage = await context.newPage();
	const errors = watchErrors(editorPage);
	const canvas = await openEditor(editorPage, postIdFor('contact'), width, height);
	await editorPage.evaluate(() => {
		window.wp.data.dispatch('core/editor').updateEditorSettings({ autosaveInterval: 86400 });
	});

	const { registered, offered } = await registeredPatterns(editorPage);
	const byName = new Map(registered.map((p) => [p.name, p]));
	files.filter((f) => !byName.has(f.name))
		.forEach((f) => problems.push(`theme/patterns/${f.file} is not registered as ${f.name}`));
	registered.filter((p) => !files.some((f) => f.name === p.name))
		.forEach((p) => problems.push(`${p.name} is registered but has no file in theme/patterns/`));
	if (offered) {
		const listed = files.filter((f) => f.inserter).map((f) => f.name);
		const missing = listed.filter((n) => !offered.includes(n));
		const extra = offered.filter((n) => !listed.includes(n));
		if (missing.length || extra.length) {
			problems.push(`the inserter offers ${offered.length} patterns and the files list ${listed.length}`
				+ (missing.length ? `; not offered: ${missing.join(', ')}` : '')
				+ (extra.length ? `; offered though marked "Inserter: no": ${extra.join(', ')}` : ''));
		}
	} else {
		problems.push('the editor has no selector for the patterns the inserter offers');
	}

	const frontPage = await context.newPage();
	const sections = new Map();   // page slug -> its digest, one entry per section
	const results = [];
	let canvasWidth = 0;
	for (const file of files.filter((f) => byName.has(f.name))) {
		process.stdout.write(`${file.name.padEnd(28)} `);
		const own = [];
		const result = { ...file, blocks: 0, elements: 0, states: 0, spaces: 0, problems: own };
		try {
			const parsed = await insertPattern(editorPage, canvas, byName.get(file.name).content);
			if (parsed.unknown.length) {
				own.push(`blocks the editor does not know: ${[...new Set(parsed.unknown)].join(', ')}`);
			}
			const validity = await blockValidity(editorPage);
			result.blocks = validity.total;
			if (validity.invalid.length) {
				own.push(`${validity.invalid.length} of ${validity.total} blocks are invalid: `
					+ [...new Set(validity.invalid)].join(', '));
			}
			canvasWidth = canvasWidth || await canvas.evaluate(() => Math.round(
				document.querySelector('.is-root-container > [data-block]').getBoundingClientRect().width));
			const left = await stableDigest(canvas, digestArgs('editor'));
			if (!sections.has(file.page)) {
				await openPage(frontPage, file.page, canvasWidth, height);
				sections.set(file.page, await stableDigest(frontPage, digestArgs('page')));
			}
			const right = sections.get(file.page)[file.section - 1];
			if (left.length !== 1) {
				own.push(`${left.length} sections in the canvas for one pattern`);
			} else if (!right) {
				own.push(`${file.page} has no section ${file.section}`);
			} else {
				result.elements = left[0].length;
				Object.assign(result, compare(left[0], right, file.name, own));
			}
		} catch (error) {
			own.push(String(error.message || error));
		}
		results.push(result);
		console.log(own.length
			? `!! ${own.length} differences`
			: `${String(result.blocks).padStart(3)} blocks, ${String(result.elements).padStart(4)} elements  ok, as ${file.page} section ${file.section}`
				+ (result.states ? `  (${result.states} mid-scroll)` : '')
				+ (result.spaces ? `  (${result.spaces} hanging spaces)` : ''));
	}

	await editorPage.close();
	await frontPage.close();
	for (const error of errors.slice(0, 5)) {
		problems.push(`editor console: ${error.slice(0, 300)}`);
	}

	return { results, problems, offered: offered ? offered.length : 0 };
}

/* -------------------------------------------------------------------------- main ----- */

function report(bad) {
	for (const result of bad) {
		console.log(`\n${result.name || result.slug}:`);
		for (const problem of result.problems.slice(0, 40)) {
			console.log(`  !! ${problem}`);
		}
		if (result.problems.length > 40) {
			console.log(`  … and ${result.problems.length - 40} more`);
		}
	}
}

async function runPages(context, pages, width, height) {
	const results = [];
	for (const slug of pages) {
		process.stdout.write(`${slug.padEnd(28)} `);
		let result;
		try {
			result = await check(context, slug, width, height);
		} catch (error) {
			result = { slug, sections: 0, elements: 0, problems: [String(error.message || error)] };
		}
		results.push(result);
		console.log(result.problems.length
			? `${String(result.sections).padStart(2)} sections  !! ${result.problems.length} differences`
			: `${String(result.sections).padStart(2)} sections, ${String(result.blocks).padStart(3)} blocks, ${String(result.elements).padStart(4)} elements  ok at ${result.canvasWidth}px`
				+ (result.states ? `  (${result.states} mid-scroll)` : '')
				+ (result.spaces ? `  (${result.spaces} hanging spaces)` : ''));
	}

	const bad = results.filter((r) => r.problems.length);
	report(bad);
	console.log(bad.length
		? `\n${bad.length} of ${results.length} pages differ from the page they edit`
		: `\nall ${results.length} pages: the canvas paints what the page paints`);

	return bad.length;
}

async function runPatterns(context, width, height) {
	console.log('\nthe section library:');
	const { results, problems, offered } = await checkPatterns(context, width, height);

	const bad = results.filter((r) => r.problems.length);
	report(bad);
	for (const problem of problems) {
		console.log(`  !! ${problem}`);
	}
	console.log(bad.length || problems.length
		? `\n${bad.length} of ${results.length} patterns differ from the section they were lifted from`
			+ (problems.length ? `, and ${problems.length} problems with the library itself` : '')
		: `\nall ${results.length} patterns: registered, ${offered} in the inserter, every block valid, each painted as its section`);

	return bad.length + problems.length;
}

async function main() {
	const argv = process.argv.slice(2);
	const headed = argv.includes('--headed');
	const widthArg = argv.find((a) => a.startsWith('--width='));
	const width = widthArg ? parseInt(widthArg.split('=')[1], 10) : 1440;
	const height = 1000;
	const slugs = argv.filter((a) => !a.startsWith('--'));

	/* Named pages, or --pages, check only the pages; --patterns only the library; nothing
	   named checks both. */
	const doPages = !argv.includes('--patterns');
	const doPatterns = !argv.includes('--pages') && !slugs.length;
	const pages = slugs.length ? slugs : DEFAULT_SLUGS;

	let browser;
	try {
		browser = await chromium.launch({ channel: 'chrome', headless: !headed });
	} catch (error) {
		console.error('Could not start Chrome. Playwright drives the Chrome already on this');
		console.error('machine rather than downloading its own; install Google Chrome, or run');
		console.error('`npx playwright install chromium` and drop `channel` from launch().');
		throw error;
	}

	const context = await browser.newContext({ viewport: { width, height } });
	await context.addCookies( authCookies() );

	let failed = 0;
	if (doPages) { failed += await runPages(context, pages, width, height); }
	if (doPatterns) { failed += await runPatterns(context, width, height); }

	await browser.close();
	process.exit(failed ? 1 : 0);
}

main().catch((error) => {
	console.error(error);
	process.exit(1);
});
