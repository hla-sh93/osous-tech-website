/**
 * Check that the block editor's canvas paints what the page paints.
 *
 *     node _tools/verify_editor.js
 *     node _tools/verify_editor.js --all                # every page, not six, and the rest
 *     node _tools/verify_editor.js about contact-us     # just these pages
 *     node _tools/verify_editor.js --patterns           # just the library: sections and starters
 *     node _tools/verify_editor.js --kinds              # just the question a new page is asked
 *     node _tools/verify_editor.js --tools              # just a page's own tools
 *     node _tools/verify_editor.js --build              # a page built from each kind, by hand
 *     node _tools/verify_editor.js --build=service,partner
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
 *
 * The starters under theme/patterns/starters/ are checked with it: a starter is a whole
 * page of its kind, so it is inserted the same way and held against every section of the
 * page it was lifted from.
 *
 * THE KINDS OF PAGE
 *
 * A new page is asked what kind of page it is (theme/blocks/editor/page-kind.js). For
 * every kind the theme names, a new page is opened as a person would open one, the kind
 * is clicked, and the page has to take the kind's template and hold its starter's
 * sections, every block valid -- and WordPress has to accept that page when it is saved,
 * which is where a template it does not know would be refused. Then the two ways of not
 * answering: "Start with an empty page" leaves no template and no blocks, and a page that
 * already exists is not asked at all. The drafts this makes are deleted at the end.
 *
 * A PAGE'S OWN TOOLS
 *
 * What the theme adds round a page, used the way a person uses it. "Duplicate" in the list
 * of pages has to make a draft with the same sections on the same template. On that
 * draft the "Page options" panel has to be there; its WhatsApp switch has to be saved with
 * the page and take the badge off the page as a visitor would see it; and it must not
 * offer to restore an original, because a copy has none. On a page that was installed
 * with the theme, "Restore the original sections" has to bring back every section after
 * some have been removed, and Undo has to return what was there. And a card's link has to
 * be pickable from the list of the site's pages, stored in the form the content keeps.
 * Nothing is saved on any page that existed before the run; the draft is deleted.
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

/* Every page the reference has, but the blog: what the blog prints is the posts. */
const ALL_SLUGS = () => fs.readdirSync(ROOT)
	.filter((name) => name.endsWith('.html') && name !== 'blog.html')
	.map((name) => name.slice(0, -5))
	.sort();

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
	// takes them off on output (functions/markup.php).
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
 * item is one -- the editor edits an item's words in a <div> inside the <li>, and
 * the page's <li> holds the words itself. Only core's: the theme's own list items edit
 * their words in a <div> too, and there the page has that <div>, with the same class.
 * The section library is where this showed; no page in DEFAULT_SLUGS has a list inside
 * a card.
 *
 * Core's Custom HTML block is the other: the canvas draws its markup inside two <div>s
 * of the block's own, and the page prints the markup where the block stands. Four pages
 * carry one -- the export's stray "Block quote" on the two legal pages, an empty list on
 * two partner pages -- and none of them is in DEFAULT_SLUGS either; the legal starter is
 * where it showed.
 */
const TRANSPARENT = ['li.wp-block-list-item > div.block-editor-rich-text__editable',
	'div.block-library-html__edit',
	'div.block-library-html__edit > div.block-editor-inner-content'];

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

/**
 * Can every word and every picture on the page be clicked into, in the canvas?
 *
 * Words the editor can change are `contenteditable` elements, and a picture is changed by
 * clicking its block; neither is any use if a click there lands on something else. Each
 * one is brought to the middle of the canvas and the canvas is asked what is under its
 * centre: the words themselves (or something inside them), or, for a picture, its own
 * block. A decoration laid over a card, or a section drawn behind the editor's own page,
 * takes the click instead -- and the words under it cannot be edited by pointing at them,
 * though every other check here would pass. The page header did exactly that until
 * editor.css put it back in line with the other blocks.
 *
 * What a strip clips out of sight (a row wider than its frame) cannot be pointed at by
 * anybody, and is counted apart: it is reached from the List View instead.
 *
 * @param {import('playwright').Frame} canvas The editor canvas.
 * @return {Promise<{words: number, pictures: number, clipped: number, blocked: Object[]}>}
 */
async function clickable(canvas) {
	return canvas.evaluate(async () => {
		const found = { words: 0, pictures: 0, clipped: 0, blocked: [] };
		const root = document.querySelector('.is-root-container');
		const targets = [
			...[...root.querySelectorAll('[contenteditable="true"]')].map((el) => ({ el, kind: 'words' })),
			...[...root.querySelectorAll('img')].map((el) => ({ el, kind: 'pictures' })),
		];
		for (const { el, kind } of targets) {
			const block = el.closest('[data-block]');
			if (!block || el.closest('[aria-hidden="true"]')) { continue; }
			el.scrollIntoView({ block: 'center', inline: 'center' });
			await new Promise((resolve) => { requestAnimationFrame(() => resolve()); });
			const box = el.getBoundingClientRect();
			if (box.width < 2 || box.height < 2) { continue; }
			const x = box.left + box.width / 2;
			const y = box.top + box.height / 2;
			if (x < 0 || y < 0 || x > window.innerWidth || y > window.innerHeight) {
				found.clipped++;
				continue;
			}
			found[kind]++;
			const hit = document.elementFromPoint(x, y);
			const reached = hit && (kind === 'words' ? el === hit || el.contains(hit) : block.contains(hit));
			if (!reached) {
				found.blocked.push({
					kind,
					block: block.dataset.type,
					what: (kind === 'words' ? el.textContent : el.getAttribute('src').split('/').pop()).trim().slice(0, 40),
					by: hit ? `${hit.tagName.toLowerCase()}${hit.className && typeof hit.className === 'string' ? `.${hit.className.trim().split(/\s+/).join('.')}` : ''}` : 'nothing',
				});
			}
		}
		window.scrollTo(0, 0);
		return found;
	});
}

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
	const reach = await clickable(canvas);

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
	for (const miss of reach.blocked) {
		problems.push(`${miss.kind === 'words' ? 'words' : 'a picture'} in ${miss.block} cannot be clicked into ("${miss.what}"): ${miss.by} takes the click`);
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

	return { slug, sections: left.length, elements, blocks: validity.total, states, spaces, canvasWidth, reach, problems };
}

/* ------------------------------------------------------------ the section library ----- */

/**
 * The library on disk: each file's slug, whether the inserter lists it, and the page and
 * section it was lifted from -- or, for a starter, the page whose every section it is.
 * gen_patterns.py writes all three in the header.
 *
 * @return {Object[]} One entry per file.
 */
function patternFiles() {
	const base = path.join(ROOT, 'theme', 'patterns');
	const found = [];

	for (const group of ['sections', 'starters']) {
		const dir = path.join(base, group);
		if (!fs.existsSync(dir)) { continue; }
		for (const name of fs.readdirSync(dir).filter((file) => file.endsWith('.php')).sort()) {
			const file = `${group}/${name}`;
			const text = fs.readFileSync(path.join(dir, name), 'utf8');
			const field = (key) => (text.match(new RegExp(`^ \\* ${key}: (.+)$`, 'm')) || [])[1];
			const one = text.match(/from ([a-z0-9-]+), section (\d+) of \d+/);
			const all = text.match(/from ([a-z0-9-]+), all (\d+) sections/);
			if (!field('Slug') || !(one || all)) {
				throw new Error(`theme/patterns/${file}: the header names no slug or no source`);
			}

			found.push({
				file,
				name: field('Slug'),
				inserter: field('Inserter') === 'yes',
				page: (one || all)[1],
				// A section pattern is one section of its page; a starter is all of them.
				section: one ? parseInt(one[2], 10) : 0,
				sections: all ? parseInt(all[2], 10) : 1,
			});
		}
	}

	return found;
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
		.forEach((p) => problems.push(`${p.name} is registered but has no file under theme/patterns/`));
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
			const source = sections.get(file.page);
			const right = file.section ? [source[file.section - 1]] : source;
			if (left.length !== file.sections) {
				own.push(`${left.length} sections in the canvas for a pattern of ${file.sections}`);
			} else if (right.length !== left.length || right.some((section) => !section)) {
				own.push(file.section
					? `${file.page} has no section ${file.section}`
					: `${file.page} has ${source.length} sections, the starter ${left.length}`);
			} else {
				left.forEach((section, i) => {
					result.elements += section.length;
					const found = compare(section, right[i],
						file.section ? file.name : `${file.name} section ${i + 1}`, own);
					result.states += found.states;
					result.spaces += found.spaces;
				});
			}
		} catch (error) {
			own.push(String(error.message || error));
		}
		results.push(result);
		console.log(own.length
			? `!! ${own.length} differences`
			: `${String(result.blocks).padStart(3)} blocks, ${String(result.elements).padStart(4)} elements  ok, as ${file.page}`
				+ (file.section ? ` section ${file.section}` : `, all ${file.sections} sections`)
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
				+ `; ${result.reach.words} words, ${result.reach.pictures} pictures clicked into`
				+ (result.reach.clipped ? ` (${result.reach.clipped} clipped from sight)` : '')
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
	console.log('\nthe library, sections and starters:');
	const { results, problems, offered } = await checkPatterns(context, width, height);

	const bad = results.filter((r) => r.problems.length);
	report(bad);
	for (const problem of problems) {
		console.log(`  !! ${problem}`);
	}
	console.log(bad.length || problems.length
		? `\n${bad.length} of ${results.length} patterns differ from what they were lifted from`
			+ (problems.length ? `, and ${problems.length} problems with the library itself` : '')
		: `\nall ${results.length} patterns: registered, ${offered} in the inserter, every block valid, each painted as its source`);

	return bad.length + problems.length;
}

/* ------------------------------------------------------------ the kinds of page ----- */

/**
 * A new page, as somebody opening Pages -> Add New gets it: the editor, and over it the
 * question.
 *
 * WordPress's own welcome guide is switched off first. It is a second modal over the
 * same screen the first time a user ever opens the editor, which on a site that has just
 * been rebuilt is now, and a click meant for the question would land on it.
 *
 * @param {import('playwright').Page} page   A page of the logged-in browser.
 * @param {number}                    width  Window width.
 * @param {number}                    height Window height.
 * @return {Promise<import('playwright').Frame>} The editor canvas.
 */
async function openNewPage(page, width, height) {
	await page.setViewportSize({ width, height });
	await page.goto(`${BASE}/wp-admin/post-new.php?post_type=page`, { waitUntil: 'domcontentloaded' });

	let canvas = null;
	for (let i = 0; i < 200 && !canvas; i++) {
		canvas = page.frames().find((f) => f.name() === 'editor-canvas');
		if (!canvas) { await wait(100); }
	}
	if (!canvas) { throw new Error('the editor canvas never appeared'); }
	await page.evaluate(() => {
		const preferences = window.wp.data.dispatch('core/preferences');
		preferences.set('core/edit-post', 'welcomeGuide', false);
		preferences.set('core', 'welcomeGuide', false);
		window.wp.data.dispatch('core/editor').updateEditorSettings({ autosaveInterval: 86400 });
	});

	return canvas;
}

/** What the editor holds about the page being edited: its id, its template, its blocks. */
async function pageState(page) {
	return page.evaluate(() => {
		const editor = window.wp.data.select('core/editor');
		const templates = editor.getEditorSettings().availableTemplates || {};

		return {
			id: editor.getCurrentPostId(),
			template: editor.getEditedPostAttribute('template') || '',
			saved: editor.getCurrentPostAttribute('template') || '',
			roots: window.wp.data.select('core/block-editor').getBlocks().length,
			templates,
		};
	});
}

/**
 * Every kind, chosen on a new page; then the two ways of not choosing.
 *
 * @param {import('playwright').BrowserContext} context The logged-in browser.
 * @param {number}                              width   Window width.
 * @param {number}                              height  Window height.
 * @return {Promise<number>} How many things were wrong.
 */
async function runKinds(context, width, height) {
	console.log('\nthe kinds of page:');
	const raw = wp('eval', 'echo wp_json_encode( osous_page_kinds_for_editor() );');
	const kinds = JSON.parse(raw.slice(raw.indexOf('[')));
	const starters = new Map(patternFiles().map((file) => [file.name, file]));
	const drafts = [];
	let failed = 0;

	const say = (label, problems, ok) => {
		console.log(`${label.padEnd(28)} ${problems.length ? `!! ${problems.join('; ')}` : ok}`);
		failed += problems.length ? 1 : 0;
	};

	for (const kind of kinds) {
		const problems = [];
		const page = await context.newPage();
		const errors = watchErrors(page);
		let ok = '';
		try {
			const canvas = await openNewPage(page, width, height);
			await page.waitForSelector('.osous-page-kind', { timeout: 20000 });
			const offered = await page.$$eval('.osous-page-kind button strong', (list) => list.map((el) => el.textContent));
			if (offered.join('|') !== kinds.map((k) => k.title).join('|')) {
				problems.push(`the question offers ${offered.join(', ') || 'nothing'}`);
			}
			await page.locator('.osous-page-kind button', { has: page.locator('strong', { hasText: new RegExp(`^${kind.title}$`) }) }).click();
			await page.waitForSelector('.osous-page-kind', { state: 'detached', timeout: 20000 });

			const starter = starters.get(kind.starter);
			if (!starter) {
				problems.push(`no file for its starter ${kind.starter}`);
			} else {
				await canvasSettled(canvas, starter.sections);
			}
			const validity = await blockValidity(page);
			let state = await pageState(page);
			drafts.push(state.id);
			if (state.template !== kind.template) {
				problems.push(`the page's template is "${state.template}", the kind's is ${kind.template}`);
			}
			if (starter && state.roots !== starter.sections) {
				problems.push(`${state.roots} sections on the page, its starter has ${starter.sections}`);
			}
			if (validity.invalid.length) {
				problems.push(`${validity.invalid.length} of ${validity.total} blocks are invalid: ${[...new Set(validity.invalid)].join(', ')}`);
			}
			if (state.templates[kind.template] !== kind.title) {
				problems.push(`the Template panel does not list ${kind.template} as "${kind.title}"`);
			}

			/* Saved as a draft: WordPress checks a page's template against the ones the
			   theme has, and refuses the save when it does not know it. */
			await page.evaluate(async () => {
				window.wp.data.dispatch('core/editor').editPost({ title: 'verify_editor.js, a new page' });
				await window.wp.data.dispatch('core/editor').savePost();
			});
			state = await pageState(page);
			if (state.saved !== kind.template) {
				problems.push(`saved, the page's template is "${state.saved}"`);
			}
			const held = await page.evaluate(() => (window.wp.data.select('core/editor').getCurrentPostAttribute('content') || '').split('<!-- wp:').length - 1);
			if (held !== validity.total) {
				problems.push(`saved, the page holds ${held} blocks of the ${validity.total} laid down`);
			}
			ok = `${String(state.roots).padStart(2)} sections, ${String(validity.total).padStart(3)} blocks  ok, on ${kind.template}`;
		} catch (error) {
			problems.push(String(error.message || error).split('\n')[0]);
		}
		for (const error of errors.slice(0, 3)) {
			problems.push(`editor console: ${error.slice(0, 200)}`);
		}
		await page.close();
		say(kind.title, problems, ok);
	}

	/* Not answering, the first way: an empty page on the default template. */
	{
		const problems = [];
		const page = await context.newPage();
		try {
			await openNewPage(page, width, height);
			await page.waitForSelector('.osous-page-kind', { timeout: 20000 });
			await page.locator('.osous-page-kind button', { hasText: 'Start with an empty page' }).click();
			await page.waitForSelector('.osous-page-kind', { state: 'detached', timeout: 20000 });
			const state = await pageState(page);
			drafts.push(state.id);
			if (state.template || state.roots) {
				problems.push(`template "${state.template}", ${state.roots} blocks`);
			}
		} catch (error) {
			problems.push(String(error.message || error).split('\n')[0]);
		}
		await page.close();
		say('an empty page', problems, 'no template, no blocks  ok');
	}

	/* The second way: a page that already exists is not asked. */
	{
		const problems = [];
		const page = await context.newPage();
		try {
			await openEditor(page, postIdFor('about'), width, height);
			await wait(1500);
			if (await page.$('.osous-page-kind')) {
				problems.push('the question is asked of a page that already has its sections');
			}
		} catch (error) {
			problems.push(String(error.message || error).split('\n')[0]);
		}
		await page.close();
		say('a page that exists', problems, 'not asked  ok');
	}

	const made = drafts.filter((id) => Number.isInteger(id) && id > 0);
	if (made.length) {
		wp('post', 'delete', ...made.map(String), '--force');
	}
	console.log(failed
		? `\n${failed} of ${kinds.length + 2} answers went wrong`
		: `\nall ${kinds.length} kinds: offered to a new page, each sets its template and lays down its starter`);

	return failed;
}

/* ------------------------------------------------------------- a page's own tools ----- */

/** The sidebar's Page tab, open, so the panels in it are in the document. */
async function showPageSidebar(page) {
	await page.evaluate(() => {
		const { dispatch } = window.wp.data;
		try { dispatch('core/edit-post').openGeneralSidebar('edit-post/document'); } catch (error) { /* older name */ }
		try { dispatch('core/interface').enableComplementaryArea('core', 'edit-post/document'); } catch (error) { /* newer name */ }
	});
	await wait(600);
}

/** The "Page options" panel, opened if it was folded. */
async function pageOptions(page) {
	await showPageSidebar(page);
	const toggle = page.locator('.components-panel__body-toggle', { hasText: 'Page options' });
	await toggle.waitFor({ timeout: 15000 });
	if ((await toggle.getAttribute('aria-expanded')) !== 'true') { await toggle.click(); }

	return page.locator('.components-panel__body', { has: toggle });
}

/**
 * Duplicate, the options panel, restoring an original, and the page picker.
 *
 * @param {import('playwright').BrowserContext} context The logged-in browser.
 * @param {number}                              width   Window width.
 * @param {number}                              height  Window height.
 * @return {Promise<number>} How many things were wrong.
 */
async function runTools(context, width, height) {
	console.log('\na page\'s own tools:');
	const source = 'dell-iraq';
	const sourceId = postIdFor(source);
	const drafts = [];
	let failed = 0;

	const say = (label, problems, ok) => {
		console.log(`${label.padEnd(28)} ${problems.length ? `!! ${problems.join('; ')}` : ok}`);
		failed += problems.length ? 1 : 0;
	};
	const firstLine = (error) => String(error.message || error).split('\n')[0];

	/* Duplicate, and the panel on the copy. */
	const page = await context.newPage();
	const errors = watchErrors(page);
	let copyId = 0;
	{
		const problems = [];
		let ok = '';
		try {
			await page.setViewportSize({ width, height });
			await page.goto(`${BASE}/wp-admin/edit.php?post_type=page&s=${encodeURIComponent('Dell Partner in Iraq')}`, { waitUntil: 'domcontentloaded' });
			const link = page.locator(`#post-${sourceId} .row-actions a`, { hasText: 'Duplicate' });
			const href = await link.getAttribute('href', { timeout: 15000 });
			await page.goto(href, { waitUntil: 'domcontentloaded' });
			let canvas = null;
			for (let i = 0; i < 200 && !canvas; i++) {
				canvas = page.frames().find((f) => f.name() === 'editor-canvas');
				if (!canvas) { await wait(100); }
			}
			if (!canvas) { throw new Error('"Duplicate" did not open the copy in the editor'); }
			await canvasSettled(canvas);
			const validity = await blockValidity(page);
			const state = await page.evaluate(() => {
				const editor = window.wp.data.select('core/editor');
				return {
					id: editor.getCurrentPostId(),
					title: editor.getCurrentPostAttribute('title'),
					status: editor.getCurrentPostAttribute('status'),
					template: editor.getCurrentPostAttribute('template'),
					roots: window.wp.data.select('core/block-editor').getBlocks().length,
					asked: !!document.querySelector('.osous-page-kind'),
				};
			});
			copyId = state.id;
			drafts.push(state.id);
			const original = JSON.parse(wp('eval',
				`$p = get_post( ${sourceId} ); echo wp_json_encode( array( 'title' => $p->post_title, 'template' => get_post_meta( $p->ID, '_wp_page_template', true ), 'roots' => count( array_filter( parse_blocks( $p->post_content ), function ( $b ) { return null !== $b['blockName']; } ) ) ) );`));
			if (state.id === sourceId) { problems.push('the editor opened the page itself, not a copy'); }
			if (state.title !== `${original.title} (copy)`) { problems.push(`the copy is called "${state.title}"`); }
			if (state.status !== 'draft') { problems.push(`the copy is ${state.status}, not a draft`); }
			if (state.template !== original.template) { problems.push(`the copy is on "${state.template}", the page on ${original.template}`); }
			if (state.roots !== original.roots) { problems.push(`the copy has ${state.roots} sections, the page ${original.roots}`); }
			if (validity.invalid.length) { problems.push(`${validity.invalid.length} of ${validity.total} blocks are invalid`); }
			if (state.asked) { problems.push('the copy was asked what kind of page it is'); }
			ok = `a draft called "${state.title}", ${state.roots} sections, on ${state.template}  ok`;
		} catch (error) {
			problems.push(firstLine(error));
		}
		say('Duplicate', problems, ok);
	}

	{
		const problems = [];
		try {
			if (!copyId) { throw new Error('there is no copy to look at'); }
			const panel = await pageOptions(page);
			const badge = panel.locator('.components-toggle-control', { hasText: 'WhatsApp badge' }).locator('input[type="checkbox"]');
			if (!(await badge.isChecked())) { problems.push('the WhatsApp switch starts off'); }
			if (await panel.locator('button', { hasText: 'Restore the original sections' }).count()) {
				problems.push('a copy is offered an original to restore');
			}
			await badge.click();
			await page.evaluate(async () => { await window.wp.data.dispatch('core/editor').savePost(); });
			const saved = await page.evaluate(() => window.wp.data.select('core/editor').getCurrentPostAttribute('meta'));
			if (!saved || saved._osous_hide_whatsapp !== true) {
				problems.push(`switched off and saved, the page holds ${JSON.stringify(saved && saved._osous_hide_whatsapp)}`);
			}
			const front = await context.newPage();
			await front.goto(`${BASE}/?page_id=${copyId}&preview=true`, { waitUntil: 'domcontentloaded' });
			const shown = await front.evaluate(() => ({
				badge: !!document.querySelector('a.whatsapp'),
				sections: document.querySelectorAll('main > *').length,
			}));
			await front.close();
			if (shown.badge) { problems.push('the badge is still on the page'); }
			if (!shown.sections) { problems.push('the copy shows no sections to a visitor'); }
		} catch (error) {
			problems.push(firstLine(error));
		}
		for (const error of errors.slice(0, 3)) { problems.push(`editor console: ${error.slice(0, 200)}`); }
		say('Page options', problems, 'the WhatsApp switch is saved with the page and takes the badge off it  ok');
	}
	await page.close();

	/* Restore, and the page picker, on a page installed with the theme. Never saved. */
	const installed = await context.newPage();
	const installedErrors = watchErrors(installed);
	let canvas = null;
	{
		const problems = [];
		let ok = '';
		try {
			canvas = await openEditor(installed, sourceId, width, height);
			await installed.evaluate(() => {
				window.wp.data.dispatch('core/editor').updateEditorSettings({ autosaveInterval: 86400 });
			});
			const before = await installed.evaluate(() => window.wp.data.select('core/block-editor').getBlocks().length);
			await installed.evaluate(() => {
				const editor = window.wp.data.select('core/block-editor');
				window.wp.data.dispatch('core/block-editor').removeBlocks(editor.getBlocks().slice(0, 2).map((b) => b.clientId));
			});
			const cut = await installed.evaluate(() => window.wp.data.select('core/block-editor').getBlocks().length);
			const panel = await pageOptions(installed);
			await panel.locator('button', { hasText: 'Restore the original sections' }).click();
			await installed.locator('.osous-restore-original button', { hasText: /^Restore$/ }).click();
			await installed.waitForSelector('.osous-restore-original', { state: 'detached', timeout: 20000 });
			await canvasSettled(canvas, before);
			const restored = await installed.evaluate(() => window.wp.data.select('core/block-editor').getBlocks().length);
			const validity = await blockValidity(installed);
			if (cut !== before - 2) { problems.push(`removing two sections left ${cut} of ${before}`); }
			if (restored !== before) { problems.push(`restored, the page has ${restored} sections; it was installed with ${before}`); }
			if (validity.invalid.length) { problems.push(`${validity.invalid.length} of ${validity.total} restored blocks are invalid`); }
			await installed.evaluate(() => { window.wp.data.dispatch('core/editor').undo(); });
			await wait(400);
			const undone = await installed.evaluate(() => window.wp.data.select('core/block-editor').getBlocks().length);
			if (undone !== cut) { problems.push(`Undo left ${undone} sections, not the ${cut} that were there`); }
			ok = `${cut} sections back to ${restored}, every block valid, and Undo returns the ${undone}  ok`;
		} catch (error) {
			problems.push(firstLine(error));
		}
		say('Restore the original', problems, ok);
	}

	{
		const problems = [];
		let ok = '';
		try {
			const picked = await installed.evaluate(async () => {
				const { select, dispatch, resolveSelect } = window.wp.data;
				const find = (blocks) => {
					for (const block of blocks) {
						if (block.name === 'osous/card' && block.attributes.media !== 'none') { return block; }
						const inner = find(block.innerBlocks || []);
						if (inner) { return inner; }
					}
					return null;
				};
				const card = find(select('core/block-editor').getBlocks());
				if (!card) { return null; }
				await dispatch('core/block-editor').selectBlock(card.clientId);
				try { dispatch('core/edit-post').openGeneralSidebar('edit-post/block'); } catch (error) { /* older name */ }
				try { dispatch('core/interface').enableComplementaryArea('core', 'edit-post/block'); } catch (error) { /* newer name */ }
				const pages = await resolveSelect('core').getEntityRecords('postType', 'page',
					{ per_page: -1, status: 'publish', orderby: 'title', order: 'asc', _fields: 'id,title,link' });
				const about = pages.find((p) => /\/about\/$/.test(p.link));

				return { clientId: card.clientId, was: card.attributes.link, pages: pages.length, title: about.title.raw };
			});
			if (!picked) { throw new Error(`${source} has no card to pick a link for`); }
			const field = installed.locator('.components-combobox-control', { hasText: 'Icon links to' }).locator('input');
			await field.waitFor({ timeout: 15000 });
			await field.click();
			await field.fill(picked.title);
			await installed.locator('.components-form-token-field__suggestion', { hasText: picked.title }).first().click();
			await wait(300);
			const now = await installed.evaluate((clientId) => window.wp.data.select('core/block-editor').getBlockAttributes(clientId).link, picked.clientId);
			const published = parseInt(wp('eval', 'echo count( get_posts( array( "post_type" => "page", "post_status" => "publish", "numberposts" => -1, "fields" => "ids" ) ) );'), 10);
			if (picked.pages !== published) { problems.push(`the list offers ${picked.pages} pages, the site has ${published}`); }
			if (now !== 'about.html') { problems.push(`picking "${picked.title}" stored "${now}"`); }
			ok = `${picked.pages} pages offered; "${picked.title}" is stored as ${now}  ok`;
		} catch (error) {
			problems.push(firstLine(error));
		}
		for (const error of installedErrors.slice(0, 3)) { problems.push(`editor console: ${error.slice(0, 200)}`); }
		say('A link, picked from the pages', problems, ok);
	}
	await installed.close();

	const made = drafts.filter((id) => Number.isInteger(id) && id > 0 && id !== sourceId);
	if (made.length) {
		wp('post', 'delete', ...made.map(String), '--force');
	}
	console.log(failed
		? `\n${failed} of 4 tools went wrong`
		: '\nall 4 tools: a page is duplicated, its options are saved, its original comes back, its links are picked');

	return failed;
}

/* -------------------------------------------------------- a page built from a kind ----- */

const TEST_PICTURE = path.join(ROOT, '_migration', 'editable', 'osous-test-picture-1.png');
const SHOTS = path.join(ROOT, '_migration', 'check');

/**
 * What a person works on in a page just laid down from a kind: the first words of the
 * opening section, a block's words further down, and a block holding a picture.
 *
 * @param {import('playwright').Page} page The editor page.
 * @return {Promise<Object>} `{ opening, later, picture }`, each `{ clientId, name, key, text }`.
 */
async function buildTargets(page) {
	return page.evaluate(() => {
		const plain = (value) => {
			const holder = document.createElement('div');
			holder.innerHTML = value === undefined || value === null ? '' : String(value);
			return holder.textContent.trim();
		};
		const wordsOf = (block) => {
			for (const key of ['title', 'content', 'text']) {
				const text = plain(block.attributes[key]);
				if (text.length > 3) { return { key, text }; }
			}
			return null;
		};
		const flat = (blocks) => blocks.flatMap((block) => [block, ...flat(block.innerBlocks || [])]);
		const roots = window.wp.data.select('core/block-editor').getBlocks();
		const first = roots.length ? flat([roots[0]]) : [];
		const all = flat(roots);
		const describe = (block) => (block ? { clientId: block.clientId, name: block.name, ...wordsOf(block) } : null);
		// The contact section is drawn by the server; its words are edited in the sidebar.
		const typed = (block) => wordsOf(block) && block.name !== 'osous/contact' && block.name !== 'core/list';
		const picture = all.find((block) => (typeof block.attributes.src === 'string' && block.attributes.src)
			|| ['image', 'icon', 'logo'].some((key) => block.attributes[key] && block.attributes[key].src));

		// A page that opens on the contact section types its first words into the sidebar.
		const contact = roots[0] && roots[0].name === 'osous/contact' && !first.find(typed)
			? { ...describe(roots[0]), key: 'title', text: plain(roots[0].attributes.title), sidebar: 'Heading' }
			: null;

		return {
			opening: contact || describe(first.find(typed)),
			later: describe(all.find((block) => !first.includes(block) && typed(block))),
			picture: picture ? { clientId: picture.clientId, name: picture.name } : null,
		};
	});
}

/** Select a block and type into one of its fields in the sidebar, found by its label. */
async function typeInSidebar(page, target, words) {
	await page.evaluate((id) => {
		const { dispatch } = window.wp.data;
		dispatch('core/block-editor').selectBlock(id);
		try { dispatch('core/edit-post').openGeneralSidebar('edit-post/block'); } catch (error) { /* older name */ }
		try { dispatch('core/interface').enableComplementaryArea('core', 'edit-post/block'); } catch (error) { /* newer name */ }
	}, target.clientId);
	const field = page.locator('.block-editor-block-inspector').getByLabel(target.sidebar, { exact: true }).first();
	await field.waitFor({ timeout: 15000 });
	await field.fill(words);
	await wait(300);

	return page.evaluate(({ id, key }) => String(window.wp.data.select('core/block-editor').getBlockAttributes(id)[key]),
		{ id: target.clientId, key: target.key });
}

/** Click into a block's words in the canvas, select them, and type others over them. */
async function typeOver(page, canvas, target, words) {
	// A heading or a line of text is its own rich text: the block and its words are one element.
	const field = canvas.locator(`[data-block="${target.clientId}"][contenteditable="true"], [data-block="${target.clientId}"] [contenteditable="true"]`)
		.filter({ hasText: target.text.slice(0, 30) }).first();
	await field.scrollIntoViewIfNeeded();
	await field.click();
	await page.keyboard.press('Control+a');
	await page.keyboard.type(words);
	await wait(300);

	return page.evaluate(({ id, key }) => String(window.wp.data.select('core/block-editor').getBlockAttributes(id)[key]),
		{ id: target.clientId, key: target.key });
}

/**
 * A picture from this machine, through the sidebar's "Replace image": the Media Library
 * opens, the file is uploaded from its "Upload files" tab, and "Select" puts it in the block.
 */
async function pictureFromThisMachine(page, target, file) {
	await page.evaluate((id) => {
		const { dispatch } = window.wp.data;
		dispatch('core/block-editor').selectBlock(id);
		try { dispatch('core/edit-post').openGeneralSidebar('edit-post/block'); } catch (error) { /* older name */ }
		try { dispatch('core/interface').enableComplementaryArea('core', 'edit-post/block'); } catch (error) { /* newer name */ }
	}, target.clientId);
	const button = page.locator('.osous-image-control button', { hasText: /^(Replace image|Choose image)$/ }).first();
	await button.waitFor({ timeout: 15000 });
	await button.click();
	const modal = page.locator('.media-modal:visible').first();
	await modal.waitFor({ timeout: 15000 });
	const tab = modal.locator('.media-menu-item, .media-router button', { hasText: 'Upload files' }).first();
	if (await tab.count()) { await tab.click(); }
	const [chooser] = await Promise.all([
		page.waitForEvent('filechooser', { timeout: 15000 }),
		modal.locator('button.browser', { hasText: /Select Files/i }).first().click(),
	]);
	await chooser.setFiles(file);
	// The modal is position: fixed, which has no offsetParent: ask for its boxes instead.
	await page.waitForFunction(() => {
		const open = [...document.querySelectorAll('.media-modal')].find((m) => m.getClientRects().length > 0);
		const select = open && open.querySelector('.media-button-select');
		return select && !select.disabled && !open.querySelector('.attachment.uploading');
	}, null, { timeout: 60000 });
	await modal.locator('.media-button-select').click();
	await wait(500);

	return page.evaluate((id) => JSON.stringify(window.wp.data.select('core/block-editor').getBlockAttributes(id)), target.clientId);
}

/** The Slug row of the sidebar's summary: opened, typed into, closed. */
async function setSlug(page, slug) {
	await showPageSidebar(page);
	const toggle = page.locator('.editor-post-url__panel-toggle');
	await toggle.waitFor({ timeout: 15000 });
	await toggle.click();
	const field = page.locator('.editor-post-url__panel-dialog input[type="text"]').first();
	await field.waitFor({ timeout: 10000 });
	await field.fill(slug);
	await field.press('Tab');
	await page.keyboard.press('Escape');
	await wait(300);

	return page.evaluate(() => window.wp.data.select('core/editor').getEditedPostSlug());
}

/** The Template row of the sidebar's summary: the list of templates, one chosen. */
async function setTemplate(page, template) {
	await showPageSidebar(page);
	const toggle = page.locator('button[aria-label="Template options"]');
	await toggle.waitFor({ timeout: 15000 });
	await toggle.click();
	const list = page.locator('.editor-post-template__classic-theme-dropdown select');
	await list.waitFor({ timeout: 10000 });
	const offered = await list.locator('option').evaluateAll((options) => options.map((o) => o.value));
	await list.selectOption(template);
	await page.keyboard.press('Escape');
	await wait(300);

	return {
		offered,
		now: await page.evaluate(() => window.wp.data.select('core/editor').getEditedPostAttribute('template')),
	};
}

/** Publish, through the top bar's button and the panel it opens. */
async function publishPage(page) {
	await page.locator('.editor-post-publish-panel__toggle, .editor-post-publish-button__button').first().click();
	const confirm = page.locator('.editor-post-publish-panel .editor-post-publish-button');
	try {
		await confirm.first().waitFor({ timeout: 8000 });
		await confirm.first().click();
	} catch (error) { /* published at once: the checks before publishing are switched off */ }
	await page.waitForFunction(() => {
		const editor = window.wp.data.select('core/editor');
		return editor.getCurrentPostAttribute('status') === 'publish' && !editor.isSavingPost();
	}, null, { timeout: 30000 });
	const close = page.locator('.editor-post-publish-panel__header button[aria-label^="Close"]');
	if (await close.count()) { await close.first().click(); }
}

/** Save a published page, through the top bar's Save. */
async function savePage(page) {
	await page.locator('.editor-post-publish-button__button').first().click();
	await page.waitForFunction(() => {
		const editor = window.wp.data.select('core/editor');
		return !editor.isSavingPost() && !editor.isEditedPostDirty();
	}, null, { timeout: 30000 });
}

/** Walk a page top to bottom so every reveal has played, then come back. */
async function walkThrough(page) {
	await page.evaluate(async () => {
		const height = document.documentElement.scrollHeight;
		for (let y = 0; y < height; y += 300) {
			window.scrollTo(0, y);
			await new Promise((r) => { setTimeout(r, 80); });
		}
		window.scrollTo(0, 0);
	});
	await wait(SETTLE_MS);
}

/**
 * The page as a visitor gets it, logged out: what it says, at 1440 and at 479, with a
 * picture of each kept under _migration/check/.
 */
async function asAVisitor(browser, url, expected, name) {
	const visitor = await browser.newContext();
	const front = await visitor.newPage();
	const errors = watchErrors(front);
	const seen = {};
	for (const [width, height] of [[1440, 900], [479, 900]]) {
		await front.setViewportSize({ width, height });
		const response = await front.goto(url, { waitUntil: 'load' });
		await walkThrough(front);
		const shown = await front.evaluate(({ words, picture }) => {
			const main = document.querySelector('main');
			const text = main ? main.textContent.replace(/\s+/g, ' ') : '';
			return {
				title: document.title,
				words: words.filter((w) => !text.includes(w)),
				picture: !picture || [...(main ? main.querySelectorAll('img') : [])].some((img) => img.src.includes(picture)),
				body: document.body.className,
				overflow: document.documentElement.scrollWidth - window.innerWidth,
			};
		}, expected);
		const file = path.join(SHOTS, `build-${name}-${width}.png`);
		await front.screenshot({ path: file, fullPage: true });
		seen[width] = { status: response ? response.status() : 0, ...shown, file };
	}
	await visitor.close();

	return { seen, errors };
}

/**
 * Every kind, made into a page the way the owner makes one, published, looked at, given
 * another template and another address, and deleted.
 *
 * For each kind: Pages -> Add New, the kind chosen from the question, a title typed, the
 * opening words and a later block's words typed over in the canvas, a picture uploaded
 * from this machine through the block's "Replace image", the slug typed in the sidebar,
 * Publish. Then, logged out, the page at its address: the typed words, the uploaded
 * picture, the kind's template on the body, no sideways scroll, at 1440 and at 479. Then,
 * back in the editor, another kind picked from the Template row and the slug changed and
 * saved: the template is on the body, and the old address arrives at the new one in one
 * permanent redirect.
 *
 * @param {import('playwright').BrowserContext} context The logged-in browser.
 * @param {number}                              width   Window width.
 * @param {number}                              height  Window height.
 * @param {string[]}                            wanted  Kind keys; empty for all of them.
 * @return {Promise<number>} How many kinds went wrong.
 */
async function runBuild(context, width, height, wanted) {
	console.log('\na page built from each kind, by hand:');
	const raw = wp('eval', 'echo wp_json_encode( osous_page_kinds_for_editor() );');
	const kinds = JSON.parse(raw.slice(raw.indexOf('[')));
	const starters = new Map(patternFiles().map((file) => [file.name, file]));
	const made = [];
	let failed = 0;

	for (const kind of kinds.filter((k) => !wanted.length || wanted.includes(k.key))) {
		const problems = [];
		const notes = [];
		const page = await context.newPage();
		const errors = watchErrors(page);
		const title = `Test ${kind.title.replace(/ page$/i, '')} page`;
		const slug = `test-${kind.key}-page`;
		const opening = `${kind.title.replace(/ page$/i, '')} page, built in the editor`;
		const later = `Words typed into the new ${kind.key} page by hand.`;
		const typed = [];
		let picture = '';
		let at = 'opening Pages -> Add New';
		try {
			const canvas = await openNewPage(page, width, height);
			await page.waitForSelector('.osous-page-kind', { timeout: 20000 });
			await page.locator('.osous-page-kind button', { has: page.locator('strong', { hasText: new RegExp(`^${kind.title}$`) }) }).click();
			await page.waitForSelector('.osous-page-kind', { state: 'detached', timeout: 20000 });
			const starter = starters.get(kind.starter);
			await canvasSettled(canvas, starter ? starter.sections : null);

			at = 'typing the title';
			const titleField = canvas.locator('.editor-post-title__input').first();
			await titleField.click();
			await page.keyboard.type(title);

			at = 'typing over the opening words';
			const targets = await buildTargets(page);
			if (!targets.opening) { throw new Error('the starter has no words to type over'); }
			const typedOpening = targets.opening.sidebar
				? await typeInSidebar(page, targets.opening, opening)
				: await typeOver(page, canvas, targets.opening, opening);
			if (!typedOpening.includes(opening)) { problems.push(`typed into ${targets.opening.name}, it holds "${typedOpening.slice(0, 60)}"`); }
			typed.push(opening);
			if (targets.later) {
				at = `typing over the words of ${targets.later.name}`;
				const typedLater = await typeOver(page, canvas, targets.later, later);
				if (!typedLater.includes(later)) { problems.push(`typed into ${targets.later.name}, it holds "${typedLater.slice(0, 60)}"`); }
				typed.push(later);
			} else {
				notes.push('one section only');
			}
			if (targets.picture) {
				at = `uploading a picture into ${targets.picture.name}`;
				const held = await pictureFromThisMachine(page, targets.picture, TEST_PICTURE);
				const match = held.match(/https?:[^"]*osous-test-picture[^"]*/);
				if (!match) { problems.push(`the uploaded picture is not in ${targets.picture.name}: ${held.slice(0, 120)}`); }
				picture = match ? path.basename(match[0]).replace(/\.png$/, '') : '';
			} else {
				notes.push('no picture in this kind\'s starter');
			}
			at = 'typing the slug';
			const slugNow = await setSlug(page, slug);
			if (slugNow !== slug) { problems.push(`the slug field holds "${slugNow}"`); }

			at = 'publishing';
			await publishPage(page);
			const state = await page.evaluate(() => {
				const editor = window.wp.data.select('core/editor');
				return { id: editor.getCurrentPostId(), link: editor.getPermalink(), template: editor.getCurrentPostAttribute('template') };
			});
			made.push(state.id);
			const validity = await blockValidity(page);
			if (validity.invalid.length) { problems.push(`${validity.invalid.length} of ${validity.total} blocks are invalid after publishing`); }
			if (state.template !== kind.template) { problems.push(`published on "${state.template}", the kind's is ${kind.template}`); }
			if (!state.link.endsWith(`/${slug}/`)) { problems.push(`published at ${state.link}`); }

			at = 'looking at the page as a visitor';
			const stem = path.basename(kind.template, '.php');
			const look = await asAVisitor(context.browser(), state.link, { words: typed, picture }, kind.key);
			for (const [w, seen] of Object.entries(look.seen)) {
				if (seen.status !== 200) { problems.push(`at ${w} the page answers ${seen.status}`); }
				if (seen.words.length) { problems.push(`at ${w} the page does not say: ${seen.words.join(' / ')}`); }
				if (!seen.picture) { problems.push(`at ${w} the uploaded picture is not on the page`); }
				if (!seen.body.includes(`page-template-${stem}`)) { problems.push(`at ${w} the body is not on ${stem}`); }
				if (!seen.title.includes(title)) { problems.push(`at ${w} the document title is "${seen.title}"`); }
				if (seen.overflow > 0) { problems.push(`at ${w} the page scrolls sideways by ${seen.overflow}px`); }
			}
			for (const error of look.errors.slice(0, 3)) { problems.push(`the page's console: ${error.slice(0, 160)}`); }

			/* The same page, given another kind and another address in the sidebar. */
			at = 'changing the template';
			const next = kinds[(kinds.indexOf(kind) + 1) % kinds.length];
			const swapped = await setTemplate(page, next.template);
			const listed = kinds.every((k) => swapped.offered.includes(k.template));
			if (!listed) { problems.push(`the Template row offers ${swapped.offered.join(', ')}`); }
			if (swapped.now !== next.template) { problems.push(`picked ${next.template}, the page holds "${swapped.now}"`); }
			at = 'changing the slug and saving';
			const renamed = await setSlug(page, `${slug}-renamed`);
			await savePage(page);
			const after = await page.evaluate(() => window.wp.data.select('core/editor').getPermalink());
			const moved = await fetch(after);
			const body = await moved.text();
			const old = await fetch(state.link, { redirect: 'manual' });
			const nextStem = path.basename(next.template, '.php');
			if (renamed !== `${slug}-renamed` || moved.status !== 200) { problems.push(`renamed, the page answers ${moved.status} at ${after}`); }
			if (!new RegExp(`<body[^>]*page-template-${nextStem}`).test(body)) { problems.push(`moved to ${next.title}, the body does not say ${nextStem}`); }
			if (old.status !== 301 || old.headers.get('location') !== after) {
				problems.push(`the first address answers ${old.status} ${old.headers.get('location') || ''}`);
			}
			notes.unshift(`${validity.total} blocks, published on ${stem}, then ${nextStem} and /${slug}-renamed/ (old address 301)`);
		} catch (error) {
			problems.push(`${at}: ${String(error.message || error).split('\n')[0]}`);
			await page.screenshot({ path: path.join(SHOTS, `build-${kind.key}-failed.png`) }).catch(() => {});
			const id = await page.evaluate(() => window.wp.data.select('core/editor').getCurrentPostId()).catch(() => 0);
			if (id && !made.includes(id)) { made.push(id); }
		}
		for (const error of errors.slice(0, 3)) { problems.push(`editor console: ${error.slice(0, 200)}`); }
		await page.close();
		console.log(`${kind.title.padEnd(16)} ${problems.length ? `!! ${problems.join('; ')}` : `${notes.join('; ')}  ok`}`);
		failed += problems.length ? 1 : 0;
	}

	const pages = made.filter((id) => Number.isInteger(id) && id > 0);
	if (pages.length) { wp('post', 'delete', ...pages.map(String), '--force'); }
	const uploads = wp('post', 'list', '--post_type=attachment', '--s=osous-test-picture', '--field=ID', '--format=ids').split(/\s+/).filter(Boolean);
	if (uploads.length) { wp('post', 'delete', ...uploads, '--force'); }
	console.log(failed
		? `\n${failed} kinds went wrong`
		: '\nevery kind: made into a page, its words and picture changed, published, moved to another kind and another address');

	return failed;
}

async function main() {
	const argv = process.argv.slice(2);
	const headed = argv.includes('--headed');
	const widthArg = argv.find((a) => a.startsWith('--width='));
	const width = widthArg ? parseInt(widthArg.split('=')[1], 10) : 1440;
	const height = 1000;
	const slugs = argv.filter((a) => !a.startsWith('--'));

	/* Named pages, or --pages, check only the pages; --patterns only the library; --kinds
	   only the question a new page is asked; --tools only a page's own tools; nothing named
	   checks all four. */
	/* --build is never part of the default run: it publishes pages and uploads a picture,
	   and deletes them again, which a check of the canvas has no business doing. */
	const buildArg = argv.find((a) => a === '--build' || a.startsWith('--build='));
	const only = ['--pages', '--patterns', '--kinds', '--tools'].filter((flag) => argv.includes(flag));
	const doPages = slugs.length > 0 || (!only.length && !buildArg) || only.includes('--pages');
	const doPatterns = !slugs.length && ((!only.length && !buildArg) || only.includes('--patterns'));
	const doKinds = !slugs.length && ((!only.length && !buildArg) || only.includes('--kinds'));
	const doTools = !slugs.length && ((!only.length && !buildArg) || only.includes('--tools'));
	const pages = slugs.length ? slugs : (argv.includes('--all') ? ALL_SLUGS() : DEFAULT_SLUGS);

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
	if (doKinds) { failed += await runKinds(context, width, height); }
	if (doTools) { failed += await runTools(context, width, height); }
	if (buildArg) {
		const wanted = buildArg.includes('=') ? buildArg.split('=')[1].split(',').filter(Boolean) : [];
		failed += await runBuild(context, width, height, wanted);
	}

	await browser.close();
	process.exit(failed ? 1 : 0);
}

main().catch((error) => {
	console.error(error);
	process.exit(1);
});
