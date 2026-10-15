/**
 * Appearance → Site pages, clicked the way a person clicks it. Run by verify_install.py.
 *
 *   node _tools/install_by_button.js [--headed]
 *
 * The browser is logged in with a cookie WP-CLI mints (no password typed), then: the
 * Dashboard, where the theme says pages are missing; its link to Appearance → Site pages;
 * the table of what installing will do; Install the pages; the report it comes back with;
 * and the Dashboard again, where the notice must be gone. Each screen is kept as
 * _migration/check/button-NN-*.png. Nothing is checked here beyond what the screens say:
 * the last line printed is what they said, as JSON, and verify_install.py judges it
 * against what WordPress then holds.
 */
const { execFileSync } = require('child_process');
const path = require('path');
const { chromium } = require('playwright');

const ROOT = path.resolve(__dirname, '..');
const BASE = process.env.OSOUS_WP || 'http://localhost:8080';
const SHOTS = path.join(ROOT, '_migration', 'check');
const headed = process.argv.includes('--headed');

function wp(...args) {
	return execFileSync(
		'docker',
		['compose', '--profile', 'tools', 'run', '--rm', '-T', 'cli', 'wp', ...args],
		{
			cwd: ROOT,
			encoding: 'utf8',
			// Git Bash rewrites anything that looks like a path on its way into the container.
			env: { ...process.env, MSYS_NO_PATHCONV: '1' },
			stdio: ['ignore', 'pipe', 'ignore'],
		}
	).trim();
}

function authCookies() {
	const raw = wp(
		'eval',
		'$e = time() + 3600;' +
			'echo AUTH_COOKIE . "\t" . wp_generate_auth_cookie( 1, $e, "auth" ) . "\n";' +
			'echo LOGGED_IN_COOKIE . "\t" . wp_generate_auth_cookie( 1, $e, "logged_in" );'
	);
	const url = new URL(BASE);
	const cookies = raw
		.split('\n')
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

let shot = 0;
async function keep(page, name) {
	shot += 1;
	const file = path.join(SHOTS, `button-${String(shot).padStart(2, '0')}-${name}.png`);
	await page.screenshot({ path: file, fullPage: false });
	return path.relative(ROOT, file).replace(/\\/g, '/');
}

async function counts(rows) {
	const out = {};
	for (const action of await rows.evaluateAll((trs) => trs.map((tr) => tr.dataset.action))) {
		out[action] = (out[action] || 0) + 1;
	}
	return out;
}

(async () => {
	const said = { errors: [] };
	const browser = await chromium.launch({ channel: 'chrome', headless: !headed, slowMo: headed ? 250 : 0 });
	const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
	await context.addCookies(authCookies());
	const page = await context.newPage();
	page.on('pageerror', (error) => said.errors.push(String(error)));

	try {
		// The first admin screen of a freshly built site checks wordpress.org for updates,
		// which this machine's container cannot reach: it waits out each request first.
		await page.goto(`${BASE}/wp-admin/`, { timeout: 120000 });
		const notice = page.locator('#osous-install-notice');
		said.noticeBefore = (await notice.count()) ? (await notice.innerText()).replace(/\s+/g, ' ').trim() : '';
		console.log(`Dashboard: ${said.noticeBefore || '(no notice)'}`);
		console.log(`  ${await keep(page, 'dashboard-before')}`);

		await notice.getByRole('link').click();
		await page.waitForSelector('#osous-install-plan');
		said.plan = await counts(page.locator('#osous-install-plan tbody tr'));
		console.log(`Appearance -> Site pages: ${JSON.stringify(said.plan)}`);
		console.log(`  ${await keep(page, 'plan')}`);

		// The button installs in rounds of a few seconds, each its own request, and the
		// screen between them sends the next by itself; the report is on the last.
		said.rounds = 0;
		page.on('request', (request) => {
			if (/[?&]installing=1/.test(request.url()) && request.method() === 'GET') {
				said.rounds += 1;
			}
		});
		const started = Date.now();
		await page.locator('#osous-install-submit').click({ noWaitAfter: true });
		await page.waitForSelector('#osous-install-report', { timeout: 300000 });
		said.seconds = Math.round((Date.now() - started) / 1000);
		said.rounds += 1;
		said.report = {};
		for (const item of await page.locator('#osous-install-report li[data-action]').evaluateAll((lis) =>
			lis.map((li) => [li.dataset.action, li.innerText])
		)) {
			said.report[item[0]] = Number((item[1].match(/(\d+)\s*$/) || [0, 0])[1]);
		}
		said.reportText = (await page.locator('#osous-install-report').innerText()).replace(/\s+/g, ' ').trim();
		said.planAfter = await counts(page.locator('#osous-install-plan tbody tr'));
		console.log(`Install the pages: ${said.rounds} rounds in ${said.seconds}s`);
		console.log(`  ${said.reportText}`);
		console.log(`  ${await keep(page, 'report')}`);

		await page.goto(`${BASE}/wp-admin/`);
		said.noticeAfter = (await page.locator('#osous-install-notice').count())
			? (await page.locator('#osous-install-notice').innerText()).trim()
			: '';
		console.log(`Dashboard: ${said.noticeAfter || '(no notice)'}`);
		console.log(`  ${await keep(page, 'dashboard-after')}`);
	} catch (error) {
		said.errors.push(String(error));
		console.log(`  ${await keep(page, 'failed')}`);
	} finally {
		await browser.close();
	}
	console.log('OSOUS-JSON' + JSON.stringify(said));
})();
