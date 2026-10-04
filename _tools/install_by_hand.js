/**
 * Remove the theme from WordPress and install it again from its zip -- in the dashboard, by hand.
 *
 *     ./_tools/env.sh installed                    # first: the theme a folder of the site's own
 *     node _tools/install_by_hand.js               # remove it, then install dist/'s osous-theme.zip
 *     node _tools/install_by_hand.js --install     # only the install: the theme is already gone
 *     node _tools/install_by_hand.js --headed      # in a Chrome window on the screen
 *     node _tools/install_by_hand.js --zip=path/to/osous-theme.zip
 *
 * WHAT IT DOES
 *
 * What a person at the computer does, click for click: Appearance -> Themes, Activate on
 * Twenty Twenty-Five (WordPress never deletes the active theme), Theme Details on Osous,
 * Delete, OK; then Add New Theme -> Upload Theme -> Choose File -> the osous-theme.zip out
 * of the handoff package -> Install Now -> Activate; then the home page, as a visitor gets
 * it. Every screen is kept as _migration/check/install-NN-*.png, and what WordPress said
 * on each is printed.
 *
 * WP-CLI is used only to log the browser in (a minted cookie, no password typed) and to
 * look at the folder on the server after each step. Everything that changes the site goes
 * through the dashboard, so it meets what a person meets: the upload limit, the confirm
 * box, the folder WordPress refuses to overwrite.
 *
 * IT REFUSES THE DEVELOPMENT SITE
 *
 * In development (docker-compose.yml alone) wp-content/themes/osous is this repository's
 * theme/ folder, mounted, and WordPress deleting the theme deletes theme/. The script
 * looks at the server's mounts first and stops if the theme folder is one of them. On the
 * installed stack (`env.sh installed`, _tools/installed/docker-compose.yml on top) the
 * theme is an ordinary folder in the site's own volume, as on a host. AGENTS.md §5.20.
 */
const { execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright');

const ROOT = path.resolve(__dirname, '..');
const BASE = process.env.OSOUS_WP || 'http://localhost:8080';
const SHOTS = path.join(ROOT, '_migration', 'check');
const THEME_DIR = '/var/www/html/wp-content/themes/osous';
const OTHER = 'twentytwentyfive';

const argv = process.argv.slice(2);
const headed = argv.includes('--headed');
const installOnly = argv.includes('--install');
const zipArg = (argv.find((a) => a.startsWith('--zip=')) || '').slice(6);

function docker(args, input) {
	return execFileSync('docker', args, {
		cwd: ROOT,
		encoding: 'utf8',
		input,
		// Git Bash rewrites anything that looks like a path on its way into the container.
		env: { ...process.env, MSYS_NO_PATHCONV: '1' },
		stdio: [input ? 'pipe' : 'ignore', 'pipe', 'ignore'],
	}).trim();
}

function wp(...args) {
	return docker(['compose', '--profile', 'tools', 'run', '--rm', '-T', 'cli', 'wp', ...args]);
}

/** A shell line on the server itself -- the container that answers the browser. */
function server(line) {
	return docker(['compose', 'exec', '-T', 'wordpress', 'sh', '-c', line]);
}

function authCookies() {
	const raw = wp('eval',
		'$e = time() + 3600;' +
		'echo AUTH_COOKIE . "\t" . wp_generate_auth_cookie( 1, $e, "auth" ) . "\n";' +
		'echo LOGGED_IN_COOKIE . "\t" . wp_generate_auth_cookie( 1, $e, "logged_in" );');
	const url = new URL(BASE);
	const cookies = raw.split('\n')
		.map((line) => line.trim().split('\t'))
		.filter((pair) => pair.length === 2 && pair[0] && pair[1])
		.map(([name, value]) => ({ name, value, domain: url.hostname, path: '/', httpOnly: true, secure: false }));
	if (cookies.length !== 2) {
		throw new Error('WP-CLI did not return both login cookies');
	}
	return cookies;
}

/**
 * The file a person would pick: osous-theme.zip, out of the handoff package they unzipped.
 */
function themeZip() {
	if (zipArg) {
		return path.resolve(zipArg);
	}
	const dist = path.join(ROOT, 'dist');
	const bundle = fs.existsSync(dist) && fs.readdirSync(dist).find((name) => /^osous-handoff-.+\.zip$/.test(name));
	if (!bundle) {
		throw new Error('no dist/osous-handoff-<version>.zip -- run ./_tools/env.sh package first');
	}
	const out = path.join(ROOT, '_migration', 'manual-install');
	execFileSync('python', ['-c', [
		'import os, sys, zipfile',
		'z = zipfile.ZipFile(sys.argv[1])',
		'z.extractall(sys.argv[2])',
	].join('\n'), path.join(dist, bundle), out]);
	return path.join(out, bundle.replace(/\.zip$/, ''), 'osous-theme.zip');
}

let shot = 0;
async function keep(page, name) {
	shot += 1;
	const file = path.join(SHOTS, `install-${String(shot).padStart(2, '0')}-${name}.png`);
	await page.screenshot({ path: file });
	return path.relative(ROOT, file).replace(/\\/g, '/');
}

/** What WordPress said on the screen: its notices, or the upload page's own lines. */
async function said(page) {
	// Not WordPress's own "x.y.z is available" line, which is about WordPress, not this.
	const lines = await page.locator('#wpbody-content .notice:not(.update-nag), #wpbody-content .updated, #wpbody-content .error, .wrap > p, .wp-die-message')
		.filter({ visible: true }).allInnerTexts().catch(() => []);
	return lines.map((text) => text.replace(/\s+/g, ' ').trim()).filter(Boolean).join(' | ');
}

function folder() {
	return server(`if [ -d ${THEME_DIR} ]; then echo "$(find ${THEME_DIR} -type f | wc -l) files, version $(grep -m1 '^Version:' ${THEME_DIR}/style.css | tr -d '\r' | sed 's/^Version: *//')"; else echo gone; fi`);
}

async function remove(page) {
	await page.goto(`${BASE}/wp-admin/themes.php`);
	const active = (await page.locator('.theme.active').getAttribute('data-slug')) || '';
	console.log(`Appearance -> Themes: the active theme is ${active}; on the server, ${folder()}`);
	console.log(`  ${await keep(page, 'themes-before')}`);

	if (active === 'osous') {
		// The card's own Activate button shows on hover, as it does for a mouse.
		const card = page.locator(`.theme[data-slug="${OTHER}"]`);
		await card.hover();
		await Promise.all([
			page.waitForURL(/themes\.php\?activated=true/, { waitUntil: 'domcontentloaded' }),
			card.locator('.theme-actions a.activate').click(),
		]);
		console.log(`Activate on Twenty Twenty-Five: ${await said(page)}`);
		console.log(`  ${await keep(page, 'other-theme-active')}`);
	}

	await page.locator('.theme[data-slug="osous"]').hover();
	await page.locator('.theme[data-slug="osous"] .more-details').click();
	const overlay = page.locator('.theme-overlay');
	await overlay.locator('a.delete-theme').waitFor();
	console.log(`  ${await keep(page, 'theme-details')}`);

	let question = '';
	page.once('dialog', async (dialog) => {
		question = dialog.message().replace(/\s+/g, ' ');
		await dialog.accept();
	});
	await overlay.locator('a.delete-theme').click();
	await page.locator('.theme[data-slug="osous"]').waitFor({ state: 'detached', timeout: 60000 });
	console.log(`Theme Details -> Delete: WordPress asked "${question}" -> OK`);
	console.log(`  ${await keep(page, 'theme-deleted')}`);

	const left = folder();
	console.log(`on the server: ${left}`);
	if (left !== 'gone') {
		throw new Error('the dashboard said the theme was deleted, but its folder is still on the server');
	}
}

async function install(page, zip) {
	await page.goto(`${BASE}/wp-admin/theme-install.php`);
	await page.locator('.upload-view-toggle').click();
	const form = page.locator('form.wp-upload-form');
	await form.waitFor();

	// Choose File, and the file from this computer -- through the browser's own file chooser.
	const [chooser] = await Promise.all([
		page.waitForEvent('filechooser'),
		form.locator('#themezip').click(),
	]);
	await chooser.setFiles(zip);
	await form.locator('#install-theme-submit:not([disabled])').waitFor();
	const size = (fs.statSync(zip).size / 1024 / 1024).toFixed(1);
	console.log(`Add New Theme -> Upload Theme -> Choose File: ${path.relative(ROOT, zip).replace(/\\/g, '/')} (${size} MB)`);
	console.log(`  ${await keep(page, 'upload-chosen')}`);

	await Promise.all([
		page.waitForURL(/update\.php\?action=upload-theme/, { waitUntil: 'commit', timeout: 180000 }),
		form.locator('#install-theme-submit').click(),
	]);
	await page.waitForLoadState('domcontentloaded', { timeout: 180000 });
	const answer = await said(page) || (await page.locator('body').innerText()).replace(/\s+/g, ' ').slice(0, 300);
	console.log(`Install Now: ${answer}`);
	console.log(`  ${await keep(page, 'upload-result')}`);

	const activate = page.locator('a[href*="action=activate"][href*="stylesheet=osous"]');
	if (!(await activate.count())) {
		console.log(`on the server: ${folder()}`);
		throw new Error('WordPress did not install the theme (see what it said above)');
	}
	console.log(`on the server: ${folder()}`);

	await Promise.all([
		page.waitForURL(/themes\.php\?activated=true/, { waitUntil: 'domcontentloaded' }),
		activate.first().click(),
	]);
	const active = await page.locator('.theme.active').getAttribute('data-slug');
	const version = await page.evaluate(() => {
		const themes = (window._wpThemeSettings && window._wpThemeSettings.themes) || [];
		const osous = themes.find((theme) => theme.id === 'osous');
		return osous ? osous.version : '';
	});
	console.log(`Activate: ${await said(page)} -- the active theme is ${active} ${version}`);
	console.log(`  ${await keep(page, 'theme-active')}`);
	if (active !== 'osous') {
		throw new Error('Osous is not the active theme after Activate');
	}
	return version;
}

async function visit(page) {
	const response = await page.goto(`${BASE}/`);
	await page.waitForLoadState('networkidle').catch(() => {});
	const title = await page.title();
	console.log(`the home page: ${response.status()} "${title}"`);
	console.log(`  ${await keep(page, 'home-page')}`);
	return response.status();
}

async function main() {
	const mounts = server(`mount | grep -c ' ${THEME_DIR}' || true`);
	if (mounts !== '0') {
		console.error(`${THEME_DIR} is mounted from this repository: deleting the theme from the`);
		console.error('dashboard would delete theme/ here. Give the site a theme of its own first:');
		console.error('  ./_tools/env.sh installed');
		process.exit(2);
	}

	const zip = themeZip();
	if (!fs.existsSync(zip)) {
		throw new Error(`no ${zip}`);
	}
	fs.mkdirSync(SHOTS, { recursive: true });
	for (const old of fs.readdirSync(SHOTS).filter((name) => /^install-\d+-.+\.png$/.test(name))) {
		fs.unlinkSync(path.join(SHOTS, old));
	}

	const browser = await chromium.launch({ channel: 'chrome', headless: !headed, slowMo: headed ? 350 : 0 });
	const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
	await context.addCookies(authCookies());
	const page = await context.newPage();

	try {
		if (!installOnly) {
			await remove(page);
		}
		const version = await install(page, zip);
		const status = await visit(page);
		if (status !== 200) {
			throw new Error(`the home page answered ${status}`);
		}
		console.log(`\nremoved and installed by hand from ${path.basename(zip)}: Osous ${version} active, the home page 200`);
	} catch (error) {
		console.log(`  ${await keep(page, 'stopped-here')}`);
		console.error(`\nSTOPPED: ${error.message}`);
		process.exitCode = 1;
	} finally {
		await browser.close();
	}
}

main().catch((error) => {
	console.error(error);
	process.exit(1);
});
