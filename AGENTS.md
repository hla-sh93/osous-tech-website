# Working on this project

A guide for whoever picks this up next, human or otherwise. Read it before touching
anything. Most of it is not opinion — it is a list of things that have already gone wrong
here and how they were caught.

Companion document: [README.md](README.md), what the project is in a page. The plan it
was built from and the old local-setup notes were retired on 2026-09-15; this file carries
everything they said that is still true.

---

## 1. What this is

A pixel-accurate rebuild of [osoustaqnia.com](https://www.osoustaqnia.com/) as a WordPress
theme. The live site is a Webflow export whose every page keeps its full markup in one raw
HTML field in the database — unmaintainable, and the thing being replaced.

The rebuild happened in two halves:

- **57 static HTML pages** at the repository root. These are the **reference**. They are not
  a stage that gets thrown away — every check in the project compares the theme's output
  against them.
- **`theme/`** — a classic WordPress theme laid out as a small hand-written theme is: one
  PHP file per page, a single header and footer, the design's sections and cards as
  template parts, the functions split by concern. The shape is SimonPadbury/bst's, chosen
  by the client on 2026-09-23 (§10).

Current state, measured:

```
57 pages · 399 sections (the blog page is a query) · 56 page files, 529 KB
theme:    100 files; 56 pages, 8 sections, 8 parts, 3 loops, 9 function files, no build
database: the pages as WordPress knows them (their addresses), the menus, the posts, the form;
          no page content -- a page's words are in its file
```

---

## 2. The one rule everything else serves

**Correctness here is measured, never eyeballed.** Every claim in this repository has a
command behind it. If you change something and cannot produce a passing check, you have
not finished.

This is not a style preference. Seven real bugs in this project were invisible to a person
looking at the page and obvious to a script comparing it: forty images silently stripped, a
non-breaking space eaten, `u0026` printed inside headings on 51 pages, a pinned heading
engaging 2,000 pixels early, two pages loading the wrong scroll module, a stylesheet that
never loaded at all, a preview that silently 414'd. The block model of 2026-09-16 added
three more, and the move to page files on 2026-09-23 found its own: the first generator
took a card's prose from the migration's reading of it rather than from the page, and
every list inside a purple card lost its `role` and its items.

Before you say a task is done, run the tests in §5 and paste the output.

---

## 3. Where things are

```
*.html                 57 static reference pages — the source of truth
assets/css/            8 stylesheets. Load order is load-bearing (see §6)
assets/js/             6 scroll/behaviour modules
assets/css/contact-form.css   one rule, for one element the reference never had (§5.8)
assets/css/editor.css  the post editor's settled appearance (reveal.js does not run there)
assets/images/         330 files    assets/uploads/  64 blog images
theme/                 the WordPress theme
  style.css              the theme header only; no rules
  functions.php          requires functions/*.php, nothing else
  index.php              the blog: the posts loop
  single.php  archive.php  search.php  404.php  searchform.php
  page.php               a page with no file of its own: the editor's content
  pages/<slug>.php       one file per page, 56 of them (§3, "The pages")
  includes/
    header.php  footer.php  whatsapp.php     the one header, the one footer, the badge
    sections/              the sections the design draws one way, one file each (§3)
    parts/                 the cards, the partner mark, the pinned item
    loops/                 content.php (the post card), content-single.php, content-none.php
  functions/
    templates.php          a page whose slug has a file under pages/ uses it
    markup.php             the helpers the templates share: URLs, images, buttons, inline HTML, prose
    enqueues.php           stylesheets and scripts; a scroll module is asked for by its section
    navbar.php             mega menu and footer menus
    settings.php           Site details in the Customizer
    setup.php              theme supports, the logo, favicon, menu wiring
    blog.php               the post's picture, the pager, the page header for posts and archives
    contact.php            Contact Form 7, made to render as the design (§5.8)
    cleanup.php            what WordPress adds that this design does not want
  theme.json             settings only, no styles: keeps wp-admin's CSS out of the post editor
  languages/osous.pot    every string, for translators — `wp i18n make-pot`
  readme.txt             what somebody installing the theme needs to know
_tools/                 every script and harness. Never shipped in the theme
  extract.py             the reference pages → typed nodes
  gen_blocks.py          typed nodes → the design's components, with named attributes: the reading
  gen_pages.py           that reading → theme/pages/*.php (§3, "How the pages got there")
  seed.py, seed_posts.py, seed_contact.py
                         pages, menus, posts and the contact form, through _migration/*.php
  verify_*.py, verify_*.js, geometry.html, pinned-list.html, run_harness.js
                         the checks (§5)
  verify.py, audit.py    the reference against the live site, and the CSS (§5.0, §5.6)
  env.sh, package.py, handoff-test.sh
                         run it, package it, prove the package (§4, §5.9)
  screenshot.html, save-shot.php
                         theme/screenshot.png, taken from the home page
  local/mu-plugins/      mounted into the local WordPress only: routes wp_mail() to Mailpit
  handoff/               a second, clean stack for proving the package (§5.9)
_migration/             generated WP-CLI scripts and data (gitignored)
dist/                   the handoff package (gitignored, rebuilt on demand)
docker-compose.yml      WordPress + MariaDB + phpMyAdmin + WP-CLI + Mailpit
phpcs.xml.dist          the WordPress Coding Standards, as this theme runs them (§5.7)
composer.json           what installs them; vendor/ is gitignored
package.json            the JavaScript lint, and the Playwright the checks drive Chrome with
.editorconfig           tabs, as WordPress core has it
```

### The pages

A page is a file, and the file reads as the page. `pages/dell-iraq.php` opens with the
header, then the page's sections in order, then the footer:

```php
get_template_part( 'includes/header' );
?>
<main>
	<?php
	get_template_part(
		'includes/sections/page-header',
		null,
		array(
			'title' => 'Dell Partner in Iraq',
			'text'  => 'Certified servers, storage, and enterprise IT solutions …',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Dell Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Dell Technologies partner …</p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-dell.webp',
						'alt'    => 'Dell',
						'srcset' => '…',
						'sizes'  => '…',
					)
				);
				?>
			</div>
		</div>
	</section>
	…
	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Contact Osous Al Taqnia – Dell Partner in Iraq',
			'text'       => 'Need Dell PowerEdge servers, enterprise storage, or …',
			'buttonText' => 'Request Consultation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
```

Three kinds of thing appear in a page file, and nothing else:

| what | how it is written | where its markup lives |
|---|---|---|
| a section the design draws one way — the page header, the call to action, the pinned list, the split section, the hero, the card stack, the industries carousel, the contact section | `get_template_part( 'includes/sections/<name>', null, array( … ) )` with its words as arguments; a section with items takes them as `'items' => array( … )` | `includes/sections/<name>.php`, once |
| a card, a partner mark, a pinned item | `get_template_part( 'includes/parts/<name>', null, array( … ) )` | `includes/parts/<name>.php`, once |
| the layout around them, the headings and paragraphs, the prose | the markup itself, with the design's own classes; a picture through `osous_image()`, a button through `osous_button()`, a link to a page through `home_url()` | the page file |

Every part declares its arguments and their defaults at the top with `wp_parse_args()`,
and prints exactly what the design draws for that component. Prose inside a purple card is
a string argument and goes through `osous_prose()`, which resolves its links; prose
anywhere else is markup in the file. A scroll module is asked for by the section that needs
it (`osous_scroll_module()` in the pinned list, the split section and the card stack;
`osous_enqueue_carousel()` in the carousel; `osous_enqueue_contact_form()` in the contact
section), so a page gets a script because it prints the section the script drives.

The header and the footer are `includes/header.php` and `includes/footer.php`, included by
`get_template_part()` as bst has it, from every template and every page file. The words of
the header and footer that are not menus are Site details (§5.11).

### How the pages got there

`_tools/extract.py` reads each reference page into typed nodes. `_tools/gen_blocks.py`
reads every section as one of the design's components with named attributes — a page
header, a call to action, a card of each kind, and the generic path of section → container
→ grid → column → heading — and **raises on anything it cannot express**. Its names are the
block editor's, from the theme's earlier life as a block theme (§10); the reading is the
same one that proved exact against all 57 pages then. `_tools/gen_pages.py` walks the
nodes with that reading beside it: a component it recognises becomes a part call with the
component's attributes as arguments; everything else is written as the node's own markup,
so it cannot drift. §5.1 proves the result is the reference, element for element.

The generator is the migration. It ran once; from then on a page file is the page, and
somebody changes the words there. Until the theme is handed over, the files are regenerated
from the reference rather than edited by hand, so that the reference stays the proof.

---

## 4. Running it

Docker Desktop must be running.

```bash
docker compose up -d
```

| | |
|---|---|
| The site | http://localhost:8080 |
| Dashboard | http://localhost:8080/wp-admin — `admin` / `admin`, local only |
| phpMyAdmin | http://localhost:8081 |
| Static reference | http://localhost:8080/static/ |
| Mailpit | http://localhost:8025 — every message WordPress sends, locally |

The repository is mounted at `/static` inside the container so the harnesses can measure
both versions from one origin. That mount is a development convenience and has no place on
a server.

```bash
./_tools/env.sh save <name>      # snapshot the database before experimenting
./_tools/env.sh restore <name>
./_tools/env.sh rebuild          # wipe it and build it again from this repo
./_tools/env.sh package          # dist/ — the theme zip, the content, the plugins, the notes (§5.9)
./_tools/handoff-test.sh         # prove dist/ installs on a clean WordPress
```

The page files, after a change to the generator or to a part's arguments:

```bash
python _tools/gen_pages.py               # rewrites theme/pages/*.php from the reference
python _tools/gen_blocks.py              # a report: which kinds of section each page holds
```

The contact form, after an experiment in Contact → Contact Forms:

```bash
python _tools/seed_contact.py
docker compose --profile tools run --rm cli wp eval-file /migration/contact.php
```

The Python tools need `python -m pip install lxml html5lib`. The plugins the contact page
needs -- Contact Form 7 and Simple Cloudflare Turnstile -- are installed by `env.sh rebuild`
from zips the host fetches; see the hazard on the container and wordpress.org.

The coding standards, before every commit (§5.7):

```bash
npm run lint              # JavaScript: the WordPress ESLint rules, over assets/js
./_tools/env.sh phpcs     # PHP: the WordPress Coding Standards over theme/. phpcbf fixes what it can
```

---

## 5. The tests

Run all of them before claiming anything is done. None takes longer than ten minutes.

### 5.0 The reference against the live site — `_tools/verify.py`

The 57 pages were cloned from osoustaqnia.com and checked against it as they were made;
`verify.py` is that check, and it has to tolerate what the live site adds around the
design -- Webflow's scaffolding classes, WP Rocket's rewriting, and since 2026-09-16 the
five class names the export misspells and the clone corrects (`RENAMED`). It needs the live
site and the internet, and it is the one to run if the live site may have changed since the
clone, before anything here is taken as the truth. Every other check compares against the
pages themselves.

### 5.1 Markup — `_tools/verify_wp.py`

```bash
python _tools/verify_wp.py            # all 57 pages
python _tools/verify_wp.py about      # one page
```

Fetches each page from the local WordPress, maps its URLs back to static paths, and
compares `<main>` element by element: tag, every attribute the design uses (`class`,
`href`, `src`, `srcset`, `sizes`, `alt`, `id`, `role`, `aria-*`), and every character of
text. Reports the first three divergences with a path to each.

Two things the Webflow export left behind are settled by name before comparing
(`prune_debris`): an empty attribute-less `<div>` is removed from both sides (eight, inside
pinned lists, where the part prints none), and an empty class-less `<a href="#">` at the
top of a card is read as the card's empty media box (eight; it opens the same flex gap —
see the hazard). On the contact page the static form's hidden message boxes come out of
the reference too.

**Passing looks like:** `0 of 57 pages differ`

### 5.2 Layout — `_tools/geometry.html`

```bash
SLUGS=$(ls *.html | grep -v '^blog\.html$' | sed 's/\.html$//' | tr '\n' ',' | sed 's/,$//')
node _tools/run_harness.js "geometry.html?slugs=$SLUGS"               # headless; exit 1 on any difference
node _tools/run_harness.js "geometry.html?slugs=$SLUGS&w=479&h=900"   # the phone layout
```

Run it this way unless you want to watch it. A harness in a tab that is not on screen
reports faults that are not there — see the hazard on hidden tabs in §7.

Runs in the browser, from the WordPress origin:

```
http://localhost:8080/static/_tools/geometry.html?slugs=about,index,...
...&w=479&h=900        another viewport; the default is 1440x900
```

Loads both versions of each page in frames, waits for fonts, images, Swiper's first
transform and GSAP's timeline to go quiet, then samples until three consecutive readings
agree. Compares section offsets, total page height, and a digest of 33 computed CSS
properties plus the measured left edge and width of every element in `<main>`. It also
checks each side on its own for a pinned section that engages too early — a fault both
copies can share, which no comparison between them would find.

The blog page is left out: it is a query result, and the reference shows nine pages of
posts with a pager where the local database holds twelve posts and shows all of them. Its
shape is checked by §5.1 and its loop by §5.5.

Results land in `window.RESULT`. The default 1440x900 matters: the page header is `90vh`,
so a different height silently changes every offset.

**Passing looks like:** `done at 1440x900 — 0 of 56 pages differ`. At 479x900, 55 of 56:
the contact page reports one `<input>` — the submit button — at `opacity: 1` on the
WordPress side and `0` on the reference. The markup around it is the same on both (§5.1),
and the only thing that runs on one side and not the other is Contact Form 7's script,
which reveals the button early at phone width. It is one element of 45, and it is listed
here so it is not mistaken for a regression. A reveal animation caught mid-flight can also
report one page once; run that page again before believing it.

### 5.3 Scroll behaviour — `_tools/pinned-list.html`

```bash
PINNED=$(grep -l 'list-scroll-section' *.html | sed 's/\.html$//' | tr '\n' ',' | sed 's/,$//')
node _tools/run_harness.js "pinned-list.html?slugs=$PINNED"    # headless; exit 1 if any list is wrong
```

`siem-soc-solutions-iraq` behaves correctly, so its behaviour is the specification: the pin
starts when the heading reaches the middle of the viewport, ends when the last item leaves
it, holds the heading exactly there in between, keeps one item highlighted, and is never
held while its own section is off screen. Scrolls each page through the range in 25px steps
and reports where the pin really engages.

**Passing looks like:** `45 pinned lists checked, 0 wrong`

### 5.4 Chrome — `_tools/verify_chrome.py`

```bash
python _tools/verify_chrome.py                 # a representative handful
python _tools/verify_chrome.py $(ls *.html | sed 's/\.html$//')   # all 57
```

Everything `verify_wp.py` does not look at: the navbar, the footer and the WhatsApp badge.
It matters more since the footer's details became settings — nothing else in the project
would notice if an address or a phone number came out wrong.

**Passing looks like:** `342 el, 9 accepted differences` per page, and `0 of N pages
differ`. Those nine are dead `.menu-list-icon` markup the static pages carry inconsistently
and the theme omits; anything else is a regression.

### 5.5 The blog loop — `_tools/verify_blog.py`

```bash
python _tools/verify_blog.py
python _tools/verify_blog.py --pager      # drops posts_per_page to 4, checks, restores
```

The blog page cannot be compared word for word any more: it is a query result, and
`blog.html` is a snapshot of what production's query returned on the day it was cloned.
`verify_wp.py` compares it by shape; this compares every card against the post it claims to
be showing — title, date, permalink and picture — and exercises the pager.

**Passing looks like:** `0 problems`

### 5.6 CSS completeness — `_tools/audit.py`

Reports source rules that would apply to this markup but have no equivalent here, classes
defined but never used, and duplicate selectors. Needs the live theme's CSS alongside it;
paths are at the top of the file, and `EQUIV` maps the export's misspelt names to the
corrected ones.

**Passing looks like:** zero missing rules, zero duplicates, and two intentionally kept
classes (`is-current`, `justify-end`).

### 5.7 PHP and the coding standards

```bash
docker compose exec wordpress sh -c 'cd /var/www/html/wp-content/themes/osous && for f in $(find . -name "*.php"); do php -l "$f"; done' | grep -v "No syntax errors"
docker compose exec wordpress cat /var/www/html/wp-content/debug.log   # should not exist
npm run lint              # 0 problems
./_tools/env.sh phpcs     # nothing printed, exit 0
```

The last two are the WordPress Coding Standards — the ones WordPress publishes at
developer.wordpress.org/coding-standards — run by the tools WordPress runs them with:
`wp-scripts lint-js` for JavaScript, and PHP_CodeSniffer with the `WordPress` ruleset plus
`PHPCompatibilityWP` (`phpcs.xml.dist`) for PHP. Both come back clean, over the 56 page
files as well, and both have to before a commit.

**The helpers are declared, not ignored.** `osous_class_attr()`, `osous_inline()`,
`osous_srcset()`, `osous_address()`, `osous_aria_current()`, `osous_post_thumb()` and
`osous_copyright()` each escape everything they emit and return markup that is safe to
print. `phpcs.xml.dist` names them in `customAutoEscapedFunctions`, which is the mechanism
WPCS provides for exactly this. The rule this leaves: an echo the sniff flags is fixed by
escaping it, or by routing it through one of those helpers — never by a new ignore — and a
helper joins that list only if it escapes on every path. The theme's `phpcs:ignore`
comments are each for something the sniff cannot know — prose from the theme's own page
files with its links escaped (`osous_prose()`), a tag name chosen from a fixed list, an
`id` escaped on the line above, a plugin's shortcode — and each says so on the same line
after `--`.

### 5.8 The contact form — `_tools/verify_contact.js`

```bash
node _tools/verify_contact.js            # layout, refusal, delivery
node _tools/verify_contact.js --headed
```

Three things, in a real browser against the running stack. **Layout**: ten boxes -- the
panel, the form, every field, the button, the Turnstile reserve -- measured on the static
reference and on `/contact/` at the same size, within 1px. **Refusal**: submitted empty, the
form goes `invalid` and all five fields are flagged. **Delivery**: submitted filled in, the
form goes `sent`, and Mailpit holds a message to the address in Customize → Site details,
with the visitor's address as Reply-To and every typed field in the body. The Turnstile
plugin does its real verification against Cloudflare's always-pass test keys.

`verify_wp.py` covers the page's markup: for the pages in its `CF7` set it takes away, by
name, exactly what Contact Form 7 adds -- the wrapper, the announcement region, the hidden
fields, the span around each control, the response box, the spinner, the state classes and
attributes -- and what remains has to match the reference element for element, as on every
other page. Both sides of a CF7 page are parsed with html5lib rather than lxml alone, for
the reason in the hazard on the empty `<p>`.

### 5.9 The handoff — `_tools/handoff-test.sh`

```bash
./_tools/env.sh package        # dist/: the theme zip, the content, the three plugin zips, the notes
./_tools/handoff-test.sh       # installs exactly that on a WordPress that has never seen this repo
```

The test stands up a second, clean stack (`_tools/handoff/docker-compose.yml`, nothing
mounted but `dist/`), installs WordPress, and does what HANDOFF.txt tells the receiver
to do -- the theme from its zip, the plugins from theirs, the content from its export,
permalinks, the Turnstile keys -- then runs `verify_wp.py` and `verify_chrome.py`
against it on port 8090 and tears it down. It is the difference between "the package
should work" and "the package works": a file missing from the zip, a post type missing
from the export, a menu that did not travel, or a setting the theme was meant to fill
in on arrival all fail here and nowhere else.

### 5.10 The site, by hand

The part no harness can do: the site in a client's hands. Take a snapshot first
(`./_tools/env.sh save before-hands`), then:

- open a page's file under `theme/pages/`, change a heading and a card's text, reload:
  the page shows both, and `verify_wp.py <slug>` reports those two texts **and nothing
  else**; put the file back (`git checkout theme/pages/<slug>.php`)
- copy a call to action from one page file into another, reload, put it back
- add a page under Pages, give it a file copied from a similar page, open it at 1440 and at
  479; delete both
- add that page to a menu (Appearance → Menus); change a phone number in Appearance →
  Customize → Site details; write a post with a featured image
- the console has no errors throughout
- `./_tools/env.sh restore before-hands`, delete what was uploaded, and the reference
  checks are green again

The block editor's version of this run, on 2026-09-23, is what found the empty-paragraph
bug in the purple card; the page files have no such surface, and the run is shorter.

### 5.11 Site details reach the page — `_tools/verify_settings.py`

```bash
python _tools/verify_settings.py
```

Every field in `functions/settings.php`, and the logo from Customize → Site Identity, is
set to a marker nobody would write, the home page is fetched, and each marker has to
appear where that field is printed: the phone in its `tel:` link and its text, the address
with its line breaks, the badge in its `img`, the button's page in both of the header's
buttons. Then everything is put back and the home page fetched again: it has to be byte
for byte what it was before the run.

It exists because a field that is declared, sanitised and saved but never printed passes
every other check here — the defaults reproduce the reference, so `verify_chrome.py` is
content with a footer that ignores its settings. This is the one place a forgotten
`osous_setting()` call shows.

**Passing looks like:** `26 settings checked, 0 problems`

---

## 6. Rules

**The standards run before a commit, and an ignore is not a fix.** `npm run lint` and
`./_tools/env.sh phpcs` are both clean (§5.7). A `phpcs:ignore` is for something the sniff
cannot know, says why on the same line after `--`, and the sniff being wrong is the only
reason.

**Never edit `assets/css/*` or `assets/js/*` to make one page look right.** They are
verified against the original site. A page that renders wrong has wrong *data*. The
exceptions in this project's history were genuine defects affecting every page, and each
was measured before and after across all 57 — the last being the correction of the
export's five misspelt names on 2026-09-16, made in the reference pages and the theme
alike.

**A component prints its markup in one file, and nowhere else.** A section under
`includes/sections/`, a card under `includes/parts/`, the helpers in
`functions/markup.php` for what several of them share — a URL, an image, a button, the
inline allow-list, prose. `functions/blog.php` prints a page header by calling the part.
A second renderer of anything means two things to keep correct.

**A page is its file.** `pages/<slug>.php` holds the page's sections and its words and
nothing that is not that page: the markup of a component belongs in its part. A page that
needs a new kind of section gets a new part under `includes/sections/`, used from the page
file like the others.

**Stylesheet order is load-bearing** and enforced by the dependency chain in
`functions/enqueues.php`: tokens · base · layout · components · navigation · sections ·
utilities · animations. `utilities.css` must stay second-to-last, `animations.css` last. Do
not "simplify" it into a loop over a directory listing.

**Media queries within a file run 991 → 767 → 479.** A tool that reorders them breaks
mobile in a way desktop testing never catches. This has happened here once.

**Never hand-edit generated files.** `_migration/*` comes from `_tools/seed*.py`; until the
theme is handed over, `theme/pages/` comes from `_tools/gen_pages.py`. Change the source.
After handover the page files are the source, and the reference pages become the record of
the migration rather than the check for those pages.

**Do not re-add `webflow.js`.** Every animation is reimplemented in the six modules; the
IX2 runtime would double-bind the scroll triggers.

**Escape at the point of output** — `esc_html`, `esc_attr`, `esc_url`, `wp_kses`. The
allow-list in `osous_inline_html()` is deliberately short: bold, italic, a link, a line
break, and the design's three spans. The page files are the theme's own code and print
their markup as any template does; what they take from elsewhere — a URL, a setting, a
post's title — is escaped where it is printed.

**Three divergences from the live site are deliberate** and must survive. They are
documented in the README: the split-section script that throws on live, the pinned list
measuring before images load, and three nav links that depend on server redirects.

---

## 7. Hazards

Every one of these has already cost time here.

**`active` is in the markup and the script takes it away.** The reference page was
captured with the first item of a pinned list highlighted, so `list-item active` is what
every copy of that section carries; `includes/sections/pinned-list.php` puts it on the
first item. On the live page ScrollTrigger toggles the class from `top center` to `bottom
center`, which means at rest **no** item has it — GSAP removes the one the markup shipped.
Neither side is wrong, and anything comparing the two has to know.

**An empty link opens a gap.** `.card-1` is a flex column with `gap: 1rem`, so any child
at all — the export's bare `<a href="#"></a>` at the top of eight icon-less cards — adds
16px to the card and 32px to the page. "Renders nothing" is not a thing a flex child can
do. Those cards keep their empty media box; the geometry check is what said so.

**A false that reads as empty is dropped.** `card.outline` defaults to true, so "no
outline" is stored as `false` — and a reading that dropped every falsy attribute as
"nothing to say" drew an outline on every card that had none. Only `None` and `""` mean
nothing; `flag()` in `gen_blocks.py` is for the booleans whose default is false.

**Take the words from the page, not from a reading of the page.** The first page generator
built a purple card's prose from `gen_blocks.py`'s core-block reading of it, and every list
inside a card came out without its `role="list"` and with its items as paragraphs. The
reading recognises components; the markup written for anything that is not a part call is
the node's own, taken from the reference. Keep that line: a generator that re-renders what
it read has two things to keep exact.

**WP-CLI `eval-file` runs a script through `eval()`, and a half-megabyte line broke it
silently.** Content inline in a loader, as a nowdoc, came back from `eval()` with nothing
run and nothing said — exit code 0, no output, not even a log line above it. Keep the seeds
small and their data in files beside them.

**WPCS reads a template part's `$args` as a global.** Assigning to `$args` in a part —
`$args = wp_parse_args( $args, … )`, the pattern every tutorial shows — is a
`PrefixAllGlobals` error over the whole theme. The parts parse into `$osous_args`.

**Git Bash rewrites backslashes in a heredoc.** A Python patch typed into a heredoc arrived
with `\\u0634` turned into the character, `\t` turned into a tab, and an assertion that
looked right failing for an hour, twice on 2026-09-23. Anything with a backslash goes in a
file written by the editor, or spells the backslash as `chr(92)`.

**`geometry.html` is 32px out for anyone who is logged in.** The admin bar sets
`html { margin-top: 32px }`, every offset on the WordPress side shifts by exactly that, and
all 57 pages report as broken while nothing has moved. `sample()` measures from the root
element's own top for this reason. If a whole run is off by one constant, look there
before looking at the theme.

**The reference is the live page *before* its scripts ran.** The clone was taken from a
site behind WP Rocket with delayed JavaScript, so it is CF7's server markup: no spinner
after the submit button, no `js` class, no Turnstile iframe. CF7's script inserts
`<span class="wpcf7-spinner">` at load -- hidden until submitting, inline-block, 24px, and
it takes a line: 3px more form than the reference, measured. On the live site that line
appears the first time a visitor touches the page. `assets/css/contact-form.css` takes the
spinner out of the flow; that is the one rule in that file, and why.

**A browser and libxml2 repair CF7's markup differently.** CF7 puts the Turnstile `<div>`
inside a `<p>`. A browser closes the `<p>` early and leaves an empty `<p>` behind -- which
the reference, being a browser's DOM, contains. libxml2 drops the stray `</p>` instead, so
`verify_wp.py` parsed with lxml alone saw 48 elements against 49 about markup that is the
same in every browser. CF7 pages go through html5lib on both sides.

**`wp plugin install <slug>` cannot download inside the container.** WordPress.org is not
reachable from it over TLS ("could not establish a secure connection" in the debug log,
from the update check too), while Packagist and Google are. The host reaches it fine, so
`env.sh rebuild` fetches the plugin zips with curl and installs them from `/migration`.

**PHPMailer refuses `wordpress@localhost`.** The local site URL has no dot in its host, so
the default sender fails validation before anything is sent and CF7 reports every
submission as an error. The local mu-plugin gives it `wordpress@localhost.test`. Only the
`wordpress` and `cli` services mount that plugin; a `wp eval` in a container without the
mount sends nowhere and says so.

**A classic theme with no `theme.json` gets wp-admin's own CSS inside the post editor.**
Core skips its editor reset only `wp_theme_has_theme_json()`, and that reset depends on
`common` and `forms`, so `wp-admin/css/forms.css` reaches into the canvas. The minimal
`theme.json` keeps all of it out. It declares no `styles`, so the front end is untouched.
`osous_enqueue_editor_fonts()` gives that canvas the same two web fonts, for the same
reason: a post drawn in a fallback face reads as very nearly right.

**Translating a string before `init` is a notice since WordPress 6.7.** `register_nav_menus()`
with translated names in `after_setup_theme` was enough to put "translation loading for the
osous domain was triggered too early" in the debug log on every request. The menus are
registered on `init` now. §5.7's "debug.log should not exist" is how this kind of thing
is noticed; read the log before deleting it.

**A tab that is not on screen cannot run the harnesses.** Chrome pauses
`requestAnimationFrame` in a hidden document; ScrollTrigger does its work on rAF and
IntersectionObserver does not fire, so `pinned-list.html` in a background tab — or in the
desktop app's browser pane while the pane is hidden — says every pinned list "never pinned"
and every reveal is still hidden, about code that is fine. `node _tools/run_harness.js`
runs a harness under headless Chrome, which reports itself visible and runs rAF.

**Two different things get called "WordPress-standard".** The WordPress Coding Standards
are what WordPress publishes and what `phpcs.xml.dist` runs: prefixed functions, tabs, Yoda,
escaping on output, `__()` with a text domain. Roots' Sage is the other thing people mean:
Blade templates, an `app/` namespace autoloaded by Composer, Acorn (Laravel) at runtime, a
Vite build, `vendor/` deployed inside the theme, and a web-server rule so `.blade.php` files
are not served as text. It is a different architecture with its own style — PSR, spaces, no
Yoda — that the WordPress sniffs would fail, and a Sage theme is not a zip somebody unpacks
and activates. This theme is written to the first. See §10.

**`docker pull` cut off mid-layer, twice, right after Docker Desktop came up.** `short read:
expected N bytes` and then `lookup registry-1.docker.io: no such host`, while the host's own
network was fine throughout. The WP-CLI image already on disk has PHP 8.3 and reaches
Packagist, so `_tools/env.sh phpcs` installs the standards through it with a `composer.phar`
the host fetches and checks against the published hash — no new image.

**A clean `phpcs -q` prints nothing.** Not "0 errors" — nothing at all. Read the exit code,
or drop `-q` and read the summary.

**`--` inside an XML comment is not a comment.** `phpcs.xml.dist` is XML, and a double
hyphen inside `<!-- -->` makes the whole ruleset invalid; phpcs reports it only as "Ruleset
is not valid".

**`wp_update_post()` unslashes what it is given.** Pass content with backslashes through
unslashed and every backslash is eaten; in the block era that put `u0026` in the middle of
headings on 51 of 57 pages. The seeds pass everything through `wp_slash()`.

**Git Bash rewrites container paths.** `/migration/x.sql` becomes `C:/Program
Files/Git/migration/x.sql` inside the container, and `wp rewrite structure '/%postname%/'`
silently stored `/C:/Program Files/Git/%postname%/` for weeks. Prefix every WP-CLI call
whose argument starts with a slash: `MSYS_NO_PATHCONV=1 docker compose ...`.

**ScrollTrigger measures pins in creation order.** A pin above another one adds height when
it engages, so a pin measured second computes its start against a page that is about to
grow. Every pinned trigger sets `refreshPriority` from its element's document position.
Keep it that way — script load order is not a stable thing to depend on.

**Python's `str.strip()` eats U+00A0.** Several paragraphs open with a non-breaking space.
Strip ASCII whitespace explicitly: `.strip(" \t\r\n\f\v")`.

**`<a>` is not reliably inline.** `<div><a><img></a></div>` is a block wrapper. Treating it
as inline stores the markup as a string, and an output filter then strips the image — 40
images and 5 icons vanished this way. Inline detection must look at descendants, not
children.

**Chrome reports `auto` margins inconsistently.** The same element gives `0px` or
`46.33px` depending on whether layout happens to be clean. Never compare `margin-left` or
`margin-right`; compare measured left edge and width.

**Browsers cache `assets/*.js` hard.** The static pages request them with no version query,
so a stale copy reads exactly like a real difference between the two sides. The harness
re-fetches every shared asset with `cache: 'reload'` first; the theme uses `filemtime()`
for asset versions while `WP_DEBUG` is on.

**`theme/assets` is an empty mount point.** Docker mounts the real `assets/` over it. A zip
of `theme/` alone is a theme with no CSS, no JavaScript and no images, which installs
perfectly and renders nothing. Package with `_tools/package.py`.

**Do not make `body` a flex container.** It would stop the margins between the last section
and the footer collapsing — 64px on every page, and 68px more that escapes above the body
today. The sticky footer in `base.css` exists because it changes no formatting context.

**Fresh browser tabs default to a mobile viewport.** Resize to 1440x900 before measuring
anything.

**WordPress ships its own draft Privacy Policy page** on a slug this site wants. The seed
adopts and publishes it, and empties its boilerplate.

**A rebuild proves nothing until it has run on a wiped database.** `env.sh rebuild` said
it recreated the whole site, and every check agreed — because the posts had been seeded
by hand once and rode along in every snapshot since. The first run from nothing left the
blog with WordPress's *Hello world!* and `/blog/` an empty page: the posts seed had never
been in the script. It is now. `docker compose down -v` first, then believe a rebuild.

**A receiver installs the zip they recognise and skips the notes.** The first receiver of
the handoff uploaded `osous-theme.zip`, activated it, and asked where the other 56 pages
were: the content import, step 3 of HANDOFF.txt, never happened. The theme now holds each
page's file, but WordPress still has to be told the pages exist, so the import is still
step 3, and both notes open with that fact, in English and in Arabic (HANDOFF-ar.txt).

**Docker Desktop will not start after an unclean shutdown.** It quits with "starting
services: initializing Inference manager … remove … dockerInference: The file cannot be
accessed by the system", or the same for the Secrets Engine's `engine.sock`. The cause is
a socket file left behind under `%LOCALAPPDATA%\Docker\run\` or
`%LOCALAPPDATA%\docker-secrets-engine\` that Windows will neither delete nor let Docker
delete. Rename the folder it is in (`run` → `run.stale`, likewise the secrets one) with
Docker stopped, and start Docker again; it recreates both. It has happened three times;
the log that names the file is `%LOCALAPPDATA%\Docker\log\host\com.docker.backend.exe.log`.

---

## 8. What is done

| | |
|---|---|
| Phase 0 | Local environment — Docker, WP-CLI, phpMyAdmin |
| Phase 1 | Theme skeleton, real navbar and footer menus, asset pipeline |
| Phase 2 | All 57 pages rendering from section data, verified |
| Phase 3 | Content in the database as blocks; words, images and section order editable |
| Steps 1–4, 6 | A section library, site settings, repeatable items, style choices, the wider library |
| Phase 4 | The blog is a real loop; 404, search, archives; the contact form is Contact Form 7 |
| Phase 5 | The block model: 35 blocks with named attributes replace the one tree-carrying block; the export's misspelt names corrected (2026-09-16) |
| Phase 6 | One file per page, bst's layout: `pages/`, `includes/`, `functions/`; the blocks retired at the client's request (2026-09-23) |

Tagged: `phase-2`, `phase-3`, `phase-5`, `phase-6`. `git tag -l -n5` shows what each
guarantees; `phase-5` is where the block model lives if anyone ever wants it back.

---

## 9. What is left

**Production.** Real Turnstile keys under Settings → Cloudflare Turnstile; a host that can
send mail (an SMTP plugin); the ~100 real posts — only the first twelve were cloned, and
their bodies were not, so `_tools/seed_posts.py` (run by `env.sh rebuild`) seeds titles,
dates, slugs and pictures, and the text says so until a production import replaces it.
Then WP Rocket on and every harness re-run against production; Rank Math confirmed as the
owner of `<title>`; the server-side redirects for `hikvision-iraq`, `motorola-iraq` and
`endpoint-secuirty-iraq` restored so the nav hrefs can go back to the live URLs.

**WP Rocket lies to measurement.** `content-visibility: auto` makes unscrolled sections
report `height: 0`, and its lazyload leaves images as `data:` placeholders. Neutralise both
before trusting a number; the recipe is in the README.

**Editing pages inside WordPress** is what the page files gave up (§10). If the client
wants it back for some pages, the mechanism is already there: a page without a file shows
the editor's content through `page.php`, and the block model for the design's own sections
is in git at `phase-5`.

**Inventing a layout the design does not have** is still the line not crossed (§10): a
page file carries the design's own classes and nothing else, and it costs the guarantee
that every page can be verified against the reference.

---

## 10. The trade-off to hold on to

There is a line between two ways of building this:

- **Locked** — every page is guaranteed to look like the design. Nobody can break it, and
  nobody can invent anything either.
- **Free**, as a general page builder is — anyone can do anything, including produce pages
  that have nothing to do with this design.

Everything above sits deliberately on the locked side: a page file uses the design's
sections and cards and its own classes, so the result stays coherent and the measurements
keep meaning something. If a future brief asks for the free end of that line, say plainly
what it costs: the pixel-accuracy this whole project is built to guarantee stops being
meaningful the moment anyone can drag anything anywhere.

### Roots / Sage — examined on 2026-09-14, asked for again on 2026-09-16, not adopted

roots.io was proposed as the model of "standard WordPress code" and read in full: Sage,
Bedrock, Acorn, the structure, functionality, deployment and Gutenberg pages. It is a
developer stack — Blade templates, an `app/` namespace autoloaded by Composer, Acorn at
runtime, a Vite build, `vendor/` shipped inside the theme, a server rule for `.blade.php` —
with its own style that the WordPress sniffs would fail, and a Sage theme is not a zip
somebody unpacks and activates. Bedrock for the *hosting* remains a reasonable later choice
on its own; it does not touch the theme. Do not reopen this without a new reason.

### The block model — built on 2026-09-16, retired for pages on 2026-09-23

On 2026-09-16 the owner relayed that their manager had read the code and found it
"generated, not standard": every page one block carrying a JSON tree, patterns of 2,000
characters on one line, Webflow's misspelt class names throughout. The answer was 35
blocks with named attributes, one folder each, WordPress's own blocks for prose, one option
table, the misspellings corrected — every check green, and a hand-run through the editor
that found and fixed one real bug. That is `phase-5`.

On 2026-09-23 the client asked for a different shape again: SimonPadbury/bst — a 2015
Bootstrap 3 starter theme whose whole point is plainness: templates in the root, the header
and footer under `includes/`, loops under `includes/loops/`, `functions.php` requiring the
files under `functions/` — and **one file per page, with a single header and footer**. The
structure was proposed in chat, accepted, and built the same day, with two departures
stated at the time: the page files sit under `pages/` rather than as 56 `page-slug.php`
files in the root (one filter, `functions/templates.php`), and there is no Bootstrap, no
jQuery and no sidebar, because the design has none. `assets/` also stays where it was,
because the reference pages share those exact files through one mount and the checks rest
on that.

What it costs was said plainly and accepted: the words of the 57 pages live in PHP files,
changed in a file rather than in WordPress; a new page is a new file; the editor is for
posts only. What it keeps: every check in §5, the handoff package, the Customizer, the
menus, the blog, the contact form. Do not bring the blocks back for pages without a new
brief; do not add Bootstrap.

---

## 11. If something goes wrong

```bash
git tag -l -n5                        # the restore points, and what each guarantees
git checkout phase-5                  # the block model, as it was last verified
./_tools/env.sh list                  # database snapshots
./_tools/env.sh restore <name>
./_tools/env.sh rebuild               # wipe the database, build it from this repo
```

`rebuild` recreates the entire local site from this repository alone: the pages WordPress
needs to know about and the menus, the posts, the contact form. The pages' words are in
`theme/pages/`, so nothing about them depends on the database, and nothing depends on a
snapshot surviving.
