/**
 * Check that the block editor's canvas paints what the page paints.
 *
 *     node _tools/verify_editor.js
 *     node _tools/verify_editor.js about contact-us     # just these pages
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
 */
'use strict';

const { execFileSync } = require('child_process');
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
	'.swiper-container'];

/*
 * Classes a script writes onto the page at runtime. None of them is in the markup either
 * side saved, and none of them runs in the canvas, so they are noise here -- the styles
 * they carry are compared regardless, because computed style is what is compared.
 */
const RUNTIME_CLASSES = ['is-revealed', 'swiper-initialized', 'swiper-horizontal',
	'swiper-vertical', 'swiper-backface-hidden', 'swiper-android', 'swiper-ios',
	'swiper-pointer-events', 'swiper-watch-progress'];

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
function digestSource({ side, props, intentional, runtime }) {
	/* ScrollTrigger wraps everything it pins in a `.pin-spacer` of its own making, and
	   moves the pinned element's margins onto it -- the element keeps its place in the
	   flow through the spacer while it is held still. The spacer is scaffolding, not
	   design, and it exists only where GSAP runs: walk straight through it, and read the
	   margins off the spacer, which is where the element's own margins went. */
	const unwrap = (el) => (el.classList.contains('pin-spacer') && el.firstElementChild
		? { el: unwrap(el.firstElementChild).el, box: el } : { el, box: el });

	const roots = (side === 'page'
		? Array.from(document.querySelector('main').children).map(unwrap)
		: Array.from(document.querySelectorAll('[data-type="osous/section"]'))
			.map((block) => {
				const inner = block.querySelector('.components-disabled > div');
				return inner && inner.firstElementChild && { el: inner.firstElementChild, box: inner.firstElementChild };
			})
	).filter((entry) => entry && entry.el);

	const classOf = (el) => (el.getAttribute('class') || '').split(/\s+/)
		.filter((name) => name && !runtime.includes(name))
		.sort().join(' ');

	return roots.map((root) => {
		const out = [];
		(function walk({ el, box }, depth) {
			const own = getComputedStyle(el);
			const outer = getComputedStyle(box);
			const skip = intentional.some((sel) => el.matches(sel));
			out.push({
				tag: el.tagName,
				cls: classOf(el),
				depth,
				skip,
				w: Math.round(el.getBoundingClientRect().width),
				props: props.map((p) => (p.startsWith('margin-') ? outer : own).getPropertyValue(p)),
			});
			if (skip) { return; }
			for (const child of el.children) { walk(unwrap(child), depth + 1); }
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
		problems.push(`${label}: ${editor.length} elements in the editor, ${page.length} on the page`);
		return;
	}
	editor.forEach((e, i) => {
		const p = page[i];
		const where = `${label} ${nameOf(e)}`;
		if (e.tag !== p.tag || e.cls !== p.cls || e.depth !== p.depth) {
			problems.push(`${where}: the page has ${nameOf(p)} here`);
			return;
		}
		if (e.skip) { return; }
		if (Math.abs(e.w - p.w) > TOLERANCE) {
			problems.push(`${where}: width ${e.w} in the editor, ${p.w} on the page`);
		}
		PROPS.forEach((prop, k) => {
			if (!same(e.props[k], p.props[k])) {
				problems.push(`${where}: ${prop} is ${e.props[k]} in the editor and ${p.props[k]} on the page`);
			}
		});
	});
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
 *
 * A block is not ready when it appears: ServerSideRender fetches its markup over REST, so
 * wait for the count to stop moving and for every block to hold something.
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

	let stable = 0;
	let last = -1;
	for (let i = 0; i < 200 && stable < 3; i++) {
		const rendered = await canvas.evaluate(() => {
			const blocks = Array.from(document.querySelectorAll('[data-type="osous/section"]'));
			const done = blocks.filter((b) => {
				const inner = b.querySelector('.components-disabled > div');
				const root = inner && inner.firstElementChild;
				/*
				 * A block shows `<Placeholder>` while ServerSideRender is still fetching,
				 * and a placeholder is an element like any other -- so "has rendered
				 * something" believed the editor a second and a half too early, and the
				 * whole page came back as nine sections of four elements each. It is the
				 * *section* that has to be there.
				 */
				return root && !root.classList.contains('components-placeholder');
			});
			return [blocks.length, done.length];
		}).catch(() => [0, -1]);

		stable = (rendered[1] === last && rendered[1] === rendered[0] && rendered[1] > 0)
			? stable + 1 : 0;
		last = rendered[1];
		await wait(150);
	}
	await fontsReady(canvas);
	await wait(SETTLE_MS);

	return canvas;
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
	await wait(SETTLE_MS);
}

/* --------------------------------------------------------------------------- run ----- */

async function check(context, slug, width, height) {
	const id = postIdFor(slug);
	if (!id) { throw new Error(`no page with the slug ${slug}`); }

	const editorPage = await context.newPage();
	const canvas = await openEditor(editorPage, id, width, height);

	/* Both sides are measured at the width a section actually gets, so they resolve the
	   same percentages. Not the canvas window and not the wrapper: the window may carry a
	   scrollbar and the wrapper carries the editor's own gutter, and a section is inside
	   both. Ask a section how wide it is. */
	const canvasWidth = await canvas.evaluate(() => {
		const block = document.querySelector('[data-type="osous/section"] .components-disabled > div');
		return Math.round(block.firstElementChild.getBoundingClientRect().width);
	});
	const left = await stableDigest(canvas, { side: 'editor', props: PROPS, intentional: INTENTIONAL, runtime: RUNTIME_CLASSES });

	const frontPage = await context.newPage();
	await openPage(frontPage, slug, canvasWidth, height);
	const right = await stableDigest(frontPage, { side: 'page', props: PROPS, intentional: INTENTIONAL, runtime: RUNTIME_CLASSES });

	await editorPage.close();
	await frontPage.close();

	const problems = [];
	let elements = 0;
	if (left.length !== right.length) {
		problems.push(`${left.length} sections in the editor, ${right.length} on the page`);
	} else {
		left.forEach((section, i) => {
			elements += section.length;
			compare(section, right[i], `section ${i + 1}`, problems);
		});
	}

	return { slug, sections: left.length, elements, canvasWidth, problems };
}

async function main() {
	const argv = process.argv.slice(2);
	const headed = argv.includes('--headed');
	const widthArg = argv.find((a) => a.startsWith('--width='));
	const width = widthArg ? parseInt(widthArg.split('=')[1], 10) : 1440;
	const height = 1000;
	const slugs = argv.filter((a) => !a.startsWith('--'));

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
			: `${String(result.sections).padStart(2)} sections, ${String(result.elements).padStart(4)} elements  ok at ${result.canvasWidth}px`);
	}

	await browser.close();

	const bad = results.filter((r) => r.problems.length);
	for (const result of bad) {
		console.log(`\n${result.slug}:`);
		for (const problem of result.problems.slice(0, 40)) {
			console.log(`  !! ${problem}`);
		}
		if (result.problems.length > 40) {
			console.log(`  … and ${result.problems.length - 40} more`);
		}
	}

	console.log(bad.length
		? `\n${bad.length} of ${results.length} pages differ from the page they edit`
		: `\nall ${results.length} pages: the canvas paints what the page paints`);

	process.exit(bad.length ? 1 : 0);
}

main().catch((error) => {
	console.error(error);
	process.exit(1);
});
