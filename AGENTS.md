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
- **`theme/`** — a classic WordPress theme with its own blocks, rendering the same pages
  from the blocks in the database, with everything on them editable in the block editor.
  It is laid out the way bst lays a theme out — templates in the root, `functions/`,
  `includes/` — with a template per kind of page and a file per page under `pages/`,
  because that is the structure the client asked for; and nothing a page says is in
  those files' keeping, because that is what version 3 got wrong (§10).

Current state, measured:

```
57 pages · 399 sections (the blog page is a query) · 3,434 blocks on 56 pages
  3,033 of the theme's 35 kinds, 401 of WordPress's own (paragraphs, lists, headings)
  19 classes carried in "Additional CSS class(es)", none of them layout
  7 kinds of page; every page on its kind's template, with its own search title and description
theme:    243 files; 35 blocks, 40 files of markup under includes/, 7 templates,
          23 section patterns, 7 starters, 56 page originals, one 72 KB editor bundle
database: 488 KB of block content
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
never loaded at all, a preview that silently 414'd. The rebuild of the content model on
2026-09-16 added three more: a false `outline` dropped as "empty", a card's empty link that
turned out to open a 16px flex gap, and an editor template lock that rewrote six list items
to two.

Before you say a task is done, run the tests in §5 and paste the output.

---

## 3. Where things are

```
*.html                 57 static reference pages — the source of truth
assets/css/            8 stylesheets. Load order is load-bearing (see §6)
assets/js/             6 scroll/behaviour modules
assets/css/contact-form.css   one rule, for one element the reference never had (§5.12)
assets/css/editor.css  the canvas's settled appearance, since no script runs there
assets/images/         330 files    assets/uploads/  64 blog images
theme/                 the WordPress theme, in bst's shape: templates in the root,
                       functions/ for the PHP, includes/ for the markup
  header.php, footer.php the one header and the one footer
  page.php, home.php, single.php, archive.php, search.php, 404.php, index.php
  templates/             one template per kind of page: page-home.php … page-legal.php
  functions/design.php   THE option tables: every choice a block offers, and its class
  functions/markup.php   the helpers the templates share: URLs, images, buttons, inline HTML
  functions/links.php    pages by identity: a link follows its page, an old address arrives
  functions/blocks.php   registration, the editor bundle, the block category, what a page may hold
  functions/patterns.php registers the pattern library (core only scans patterns/ itself)
  functions/templates.php the kinds of page: each one's template, starter and title
  functions/pages.php    what a page carries besides its blocks: its options, its
                         original, and "Duplicate" in the list of pages
  functions/seo.php      hreflang — the one line of the <head> no SEO plugin writes
  functions/enqueue.php  stylesheets and scripts — scripts follow blocks, into synced
                         patterns too (osous_has_block)
  functions/nav.php      mega menu and footer menus
  functions/contact.php  Contact Form 7, made to render as the design (§5.12)
  functions/setup.php    theme supports, the logo, favicon, menu wiring
  functions/settings.php Site details: the Customizer's fields and their helpers (§5.14)
  functions/cleanup.php  what WordPress adds that this design does not want
  functions/blog.php     the post picture, the pager, the page header for posts and archives
  includes/              the markup: one file per thing the design draws
    layout/                section, container, grid, column, section-heading, stack, group, panel
    elements/              heading, text, image, button, divider, spacer, prose, list, list-item
    parts/                 the five cards, the feature card, the partner mark, the pinned item
    sections/              page-header, call-to-action, pinned-list, split-section, hero, …
    loops/                 a page's blocks, a post in a grid, a post's body, the empty state
    whatsapp.php           the floating badge
  blocks/<name>/         one folder per block (§3, "The blocks")
    block.json             what it is, what it stores, what may go inside it, and which
                           file under includes/ prints it (its `render`)
    edit.js                how it is edited; compiled into build/index.js
  blocks/shared/         the controls, the option tables and URL helpers the edit.js files share
  blocks/editor/         what the editor has that is not a block: the question a new
                         page is asked (page-kind.js), and "Page options" (page-panel.js)
  blocks/index.js        registers every block with the editor
  build/                 the editor bundle — committed on purpose, the zip needs no build
  patterns/sections/     the section library, generated: readable block markup
  patterns/starters/     a whole page of each kind, generated: what a new page begins from
  pages/                 every page's original, generated: one file per page, its sections
                         in order as blocks
  theme.json             settings only, no styles. It is what keeps wp-admin's
                         CSS out of the editor canvas and makes a section full
                         width there. Adding `styles` would change the front end
  languages/osous.pot    every string, for translators — `wp i18n make-pot`
  readme.txt             what somebody installing the theme needs to know
_tools/                 every script and harness. Never shipped in the theme
  extract.py             the reference pages → typed nodes, the migration's reading of them
  gen_blocks.py          typed nodes → blocks: _migration/blocks.json, its loader, and theme/pages/
  gen_patterns.py        the same conversion: one section of each kind into patterns/sections/,
                         one page of each kind into patterns/starters/
  kinds.py, refpage.py   the kinds of page and which each reference page is; a page's
                         title and what its <head> says
  seed.py, seed_posts.py, seed_contact.py
                         pages, menus, posts and the contact form, through _migration/*.php
  verify_*.py, verify_*.js, geometry.html, pinned-list.html, run_harness.js
                         the checks (§5)
  verify.py, audit.py    the reference against the live site, and the CSS (§5.0, §5.6)
  check.sh               every check in §5 that needs only the running site, in one run
  env.sh, package.py, handoff-test.sh
                         run it, package it, prove the package (§4, §5.13)
  screenshot.html, save-shot.php
                         theme/screenshot.png, taken from the home page
  local/mu-plugins/      mounted into the local WordPress only: routes wp_mail() to Mailpit
  handoff/               a second, clean stack for proving the package (§5.13)
_migration/             generated WP-CLI scripts and data (gitignored)
dist/                   the handoff package (gitignored, rebuilt on demand)
docker-compose.yml      WordPress + MariaDB + phpMyAdmin + WP-CLI + Mailpit
phpcs.xml.dist          the WordPress Coding Standards, as this theme runs them (§5.7)
composer.json           what installs them; vendor/ is gitignored
.editorconfig           tabs, as WordPress core has it
```

### The blocks

A page is a list of blocks. Every block is a folder under `blocks/` — what it is and how
it is edited — and a file under `includes/`, which is the markup it prints: `block.json`
names that file as its `render`. The layout blocks hold other
blocks; the content blocks hold words and pictures; the cards are fixed shapes with
optional parts; the whole-section blocks are the designs that are one thing.

| block | what it is | holds |
|---|---|---|
| `section` | the band across the page: spacing, background, height, anchor | container, group |
| `container` | the box at the design's width: width, fit, colour, border, shadow, backdrop | anything |
| `grid` | 1–5 across, gap, sideways scroll | cards, columns, anything |
| `column` | one cell: vertical position, span, phone placement | anything |
| `section-heading` | the `.heading` area, centred or not | heading, text, … |
| `stack` | `.content`: children one under the other | anything |
| `group` | a wrapper: its blocks stacked or side by side, placed in its grid | anything |
| `panel` | `.modal` or `.card`, coloured, with a backdrop | anything |
| `heading` | h1–h6 with a size, colour, weight and alignment | — |
| `text` | a paragraph or a label in one of the design's text styles | — |
| `image` | a picture or an icon at one of the design's sizes | — |
| `button` | a link drawn as one of the four button styles | — |
| `divider`, `spacer` | a rule; empty space | — |
| `prose` | `.rich-text-block`, written in core's paragraph, heading, list, html blocks | core blocks |
| `list`, `list-item` | a bulleted or ticked list | list items |
| `card`, `numbered-card`, `highlight-card`, `photo-card`, `overlay-card` | the five card designs (`card-1` … `card-5`) | prose, in the purple card |
| `feature-card`, `partner`, `pinned-item` | the items of the split section, the partner strip, the pinned list | — |
| `page-header`, `call-to-action`, `pinned-list` + `pinned-items`, `split-section`, `hero`, `card-stack`, `industries-carousel`, `partners`, `contact` | whole sections | their own items |

Every choice a block offers is a word — `surface: "neutral"`, `spacing: "tall"`,
`columns: 3` — and `functions/design.php` is the one place a word becomes a class. The templates
read it, the editor is handed it (`window.osousEditor.design`), and §5.9 proves every class
in it against the stylesheets. Where a block sits in its grid, which row it takes on a
tablet or a phone, whether a group stacks its blocks or sets them side by side — all words
too (`place`, `span`, `tabletRow`, `direction`). What the reference carries that no word
covers — a page's own `custom-section-1`, one `col-right`, one `bg-img-15` — rides in
WordPress's own `className` ("Additional CSS class(es)" in the Advanced panel), and §5.10
counts it: 19 classes, on 7 kinds of block, none of them something a new block would need.

The reading of a page in the code editor:

```
<!-- wp:osous/section {"background":"dark-pattern"} -->
<!-- wp:osous/container -->
<!-- wp:osous/section-heading {"align":"center"} -->
<!-- wp:osous/heading {"content":"Dell Solutions Offered by Osous Al Taqnia","tone":"on-secondary","align":"center"} /-->
<!-- /wp:osous/section-heading -->
<!-- wp:osous/grid {"columns":3} -->
<!-- wp:osous/numbered-card {"title":"Dell PowerEdge Servers"} -->
<!-- wp:paragraph --><p>Osous Al Taqnia supplies the full range of Dell PowerEdge servers…</p><!-- /wp:paragraph -->
<!-- /wp:osous/numbered-card -->
…
```

### The kinds of page

The site has seven kinds of page, and a kind is three things with one name
(`functions/templates.php`):

| kind | pages | template | what a new one starts as |
|---|---|---|---|
| Home page | 1 | `templates/page-home.php` | `patterns/starters/home.php` |
| About page | 1 | `templates/page-about.php` | `patterns/starters/about.php` |
| Solution page | 27 | `templates/page-solution.php` | `patterns/starters/solution.php` |
| Partner page | 19 | `templates/page-partner.php` | `patterns/starters/partner.php` |
| Service page | 5 | `templates/page-service.php` | `patterns/starters/service.php` |
| Contact page | 1 | `templates/page-contact.php` | `patterns/starters/contact.php` |
| Legal page | 2 | `templates/page-legal.php` | `patterns/starters/legal.php` |

The template is WordPress's own page template: a file with a `Template Name`, offered in
the editor's Template panel, stored on the page as `_wp_page_template`. **Nothing looks a
page up by its slug**, so a page keeps its kind whatever its address becomes. All seven
print the same frame — the header, the page's blocks, the footer — because what makes a
partner page a partner page is its sections, and those are the page's own content; the
file is where a part every page of the kind must carry would go.

A page opened for the first time is asked what kind it is
(`blocks/editor/page-kind.js`). The answer sets the template and lays down the kind's
starter: every section of a typical page of that kind, in order, with real words to
replace. "Start with an empty page" leaves the default template, `page.php`.

`theme/pages/<slug>.php` is every page's original: the blocks it was built from, one
file a page, indented to be read. WordPress holds the copy that is edited; the page
remembers which file is its original in `_osous_original`, which survives a change of
slug. §5.15 holds the files, the pages and the kinds together.

### What a page carries besides its blocks

All of it is the page's own meta, by the page's id, edited in the editor's sidebar
(`functions/pages.php`, `blocks/editor/page-panel.js`):

- **Page options → WhatsApp badge.** On every page unless the page switches it off.
- **Page options → Other language versions.** One per line, `en-SY https://…`; printed
  as hreflang (`functions/seo.php`).
- **Page options → Restore the original sections.** For a page that names an original:
  the editor asks `GET /wp-json/osous/v1/original/<key>` for the file's blocks and lays
  them down. Nothing is saved until the page is; Undo takes it back.
- **The header over a dark opening** is not an option: a page whose first block is the
  hero gets the navbar's dark form, front page or not (`osous_page_opens_dark()`).
- **Duplicate**, under each page in Pages: a draft with the same sections, template and
  options. Not its address, its original, or what a search engine is told about it.

And two things every page can now hold: a link picked from the list of the site's pages
(`LinkField` in `blocks/shared/controls.js`; stored as `about.html`, resolved by
`functions/links.php`), and a **synced pattern** — a section kept once under Patterns and
placed on many pages. `core/block` is allowed on pages for that, and the scroll scripts
follow a section into one (`osous_has_block()`).

### What a search engine is told

The `<head>` of the site being replaced is Rank Math's work: a title and a description
per page, a canonical address, a robots line, Open Graph and Twitter tags, and schema for
the company. It is Rank Math's here too. The theme declares `title-tag`, calls
`wp_head()`, and prints **no title, description or canonical of its own** — two of any of
those is worse than none. The words come from fields on each page (the Rank Math box in
the editor); the seed fills `rank_math_title` and `rank_math_description` from each
reference page's own `<title>` and description, and they travel with the content export.
The company's details — name, logo, address, hours, profiles — are Rank Math's settings
(Titles & Meta → Local SEO), which the client brings from the live site with Rank Math's
own export.

The one line no SEO plugin writes is hreflang, and that is the theme's
(`functions/seo.php`): every page names itself in the site's language (Site details →
Search engines, `en-US`) and as `x-default`, and lists the versions entered under its own
Page options. An empty language prints none, which is how to hand hreflang to a
translation plugin. §5.18 reads every page's `<head>`.

### How the content got there

`_tools/extract.py` reads each reference page into typed nodes (that vocabulary is now
internal to the migration). `_tools/gen_blocks.py` converts every section into blocks by
matching the shapes it knows — a page header, a call to action, a card of each kind, and
the generic path of section → container → grid → column → heading — and **raises on
anything it cannot express**. It writes `_migration/blocks.json` and a loader; §5.1 proves
the result is the reference, element for element, and writes the same blocks a second
time as `theme/pages/`. `_tools/gen_patterns.py` runs the same conversion on one typical
section of each kind and on one typical page of each kind, and writes the patterns and
the starters. `_tools/seed.py` gives every page its kind's template (`_tools/kinds.py`
says which page is which, from the site's own menu).

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
./_tools/env.sh package          # dist/ — the theme zip, the content, the plugins, the notes (§5.13)
./_tools/handoff-test.sh         # prove dist/ installs on a clean WordPress
```

Rebuilding the content after an editor experiment, or after a change to the conversion:

```bash
python _tools/gen_blocks.py              # also rewrites theme/pages/
docker compose --profile tools run --rm cli wp eval-file /migration/blocks.php
python _tools/gen_patterns.py            # the library and the starters, from the same conversion
```

The contact form, after an experiment in Contact → Contact Forms:

```bash
python _tools/seed_contact.py
docker compose --profile tools run --rm cli wp eval-file /migration/contact.php
```

The Python tools need `python -m pip install lxml html5lib`. The plugins the contact page
needs -- Contact Form 7 and Simple Cloudflare Turnstile -- and the SEO plugin, Rank Math,
are installed by `env.sh rebuild` from zips the host fetches; see the hazard on the
container and wordpress.org.

The editor bundle, after any change under `theme/blocks/`:

```bash
npm install && npm run build
```

The coding standards, before every commit (§5.7):

```bash
npm run lint              # JavaScript: the WordPress ESLint rules, over the blocks and assets/js
./_tools/env.sh phpcs     # PHP: the WordPress Coding Standards over theme/. phpcbf fixes what it can
```

---

## 5. The tests

Run all of them before claiming anything is done.

```bash
./_tools/check.sh              # every check below that needs only the running site
./_tools/check.sh --quick      # without the editor and the three browser harnesses
./_tools/check.sh seo slugs    # only the ones named
python _tools/report.py        # the last run, a row a page: _migration/check/report.md
```

`check.sh` runs each check the way its section says to and passes it on the sentence that
section gives as "Passing looks like", one line a check; what each printed is kept under
`_migration/check/`. All of it takes about an hour, most of that the editor opened on
every page and the three harnesses that drive a browser through every page; `--quick`
about eight minutes. It reads
the site that is running. The proof that counts is from nothing:
`./_tools/env.sh rebuild`, then `./_tools/check.sh`, then `./_tools/env.sh package` and
`./_tools/handoff-test.sh`. Run it with nothing else going on (see the hazard on a busy
machine). §5.0 and §5.6 need the live site and are not in it; §5.11 is done by hand.

`report.py` reads what the checks left under `_migration/check/` and writes one table, a
row a page and a column a question — the markup, 1440, 479, the pinned lists, the editor,
what was clicked into, the fields changed, search and social, the template, the slug —
with the date of every log under it. A cell is a tick only where a check named the page
and passed it. It measures nothing itself; it is how a run is shown page by page, which is
how the owner asks for it.

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
pinned lists, where an empty block child has no height), and an empty class-less
`<a href="#">` at the top of a card is read as the card's empty media box (eight; it opens
the same flex gap — see the hazard). On the contact page the static form's hidden message
boxes come out of the reference too.

One thing the SEO plugin adds is taken off the WordPress side, by name
(`strip_seo_plugin`): Rank Math gives every link that leaves the site `rel="noopener"`, on
three links across three pages, and the reference has none (see the hazard). A `target`
is not accepted.

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
WordPress side and still waiting for its reveal on the reference (`static=<reveal>`: it is
below the fold at that width). The markup around it is the same on both (§5.1), and the
only thing that runs on one side and not the other is Contact Form 7's script, which
shows the button early at phone width. It predates the block model, it is one element of
45, and it is listed here so it is not mistaken for a regression. `check.sh` passes the
phone layout on exactly that and nothing else.

**A reveal that is off screen is left out, by name.** An element is revealed the first
time it crosses the viewport and stays revealed. One that ends up just under the fold may
have been above it for a moment while the pictures over it were still arriving — on the
side where they arrived later — and is then revealed on one side and waiting on the other,
with both pages laid out the same to the pixel. At phone width that reported a heading at
`opacity: 0` against `1` on `disaster-recovery-iraq` in about half the runs. So for an
element off screen at rest, whether its reveal has played is not compared: its opacity,
its animation and its transform are written `<reveal>` on both sides. A page where the
two sides disagree says so next to its verdict — `match  (1 off-screen reveal played on
one side only)` — and an element in the viewport is compared in full, which is what would
show `reveal.js` not loading.

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

### 5.7 PHP, the build, and the coding standards

```bash
docker compose exec wordpress sh -c 'cd /var/www/html/wp-content/themes/osous && for f in $(find . -name "*.php"); do php -l "$f"; done' | grep -v "No syntax errors"
docker compose exec wordpress cat /var/www/html/wp-content/debug.log   # should not exist
npm run build
npm run lint              # 0 problems
./_tools/env.sh phpcs     # nothing printed, exit 0
```

The last two are the WordPress Coding Standards — the ones WordPress publishes at
developer.wordpress.org/coding-standards — run by the tools WordPress runs them with:
`wp-scripts lint-js` for JavaScript, and PHP_CodeSniffer with the `WordPress` ruleset plus
`PHPCompatibilityWP` (`phpcs.xml.dist`) for PHP. Both come back clean, and both have to
before a commit. `npm run lint:fix` formats the blocks' JavaScript (Prettier, the WordPress
configuration) and touches nothing else.

**The helpers are declared, not ignored.** `osous_class_attr()`, `osous_inline()`,
`osous_srcset()`, `osous_address()`, `osous_aria_current()` and `osous_post_thumb()` each
escape everything they emit and return markup that is safe to print. `phpcs.xml.dist` names
them in `customAutoEscapedFunctions`, which is the mechanism WPCS provides for exactly this.
The rule this leaves: an echo the sniff flags is fixed by escaping it, or by routing it
through one of those helpers — never by a new ignore — and a helper joins that list only if
it escapes on every path. The theme's `phpcs:ignore` comments are each for something the
sniff cannot know — a container printing its inner blocks (`osous_inner()`), a tag name
chosen from a fixed list, an `id` escaped on the line above, a plugin's shortcode — and
each says so on the same line after `--`.

### 5.8 The editor canvas — `_tools/verify_editor.js`

```bash
node _tools/verify_editor.js                    # six pages, the library, the kinds of page
node _tools/verify_editor.js --all              # every page instead of six; check.sh runs this
node _tools/verify_editor.js about contact      # just these pages
node _tools/verify_editor.js --patterns         # just the library: sections and starters
node _tools/verify_editor.js --kinds            # just the question a new page is asked
node _tools/verify_editor.js --tools            # just a page's own tools
node _tools/verify_editor.js --build            # a page of every kind made by hand, published, deleted
node _tools/verify_editor.js --headed           # watch it happen
```

Compares what the editor's canvas paints against what the page paints: every element of
every section, by tag, class and 28 computed properties, at the width a section actually
gets in the canvas. It logs in by handing the browser a cookie WP-CLI mints, so there is
nothing to configure and no password anywhere. It also asks the editor whether every block
on the page is valid — the prose blocks are core's, saved by the migration rather than by
the editor, and an invalid one would be recovered by rewriting the content — and it prints
any script error the editor throws, because a block that fails to load reads as a missing
section otherwise.

It exists because nothing else here looks at the editor, and the editor is where the site
gets edited. Every block draws itself in the canvas — its `edit.js` prints the same markup
its file under `includes/` prints, with the words editable in place — and this is the check that
keeps the two the same. The one block drawn by the server is the contact section, whose
form only Contact Form 7 can render.

Then, on the same canvas, every word and every picture is pointed at. Each editable text
and each image is brought to the middle of the canvas and the canvas is asked what is under
its centre: the words themselves, or, for a picture, its own block. Anything else there
takes the click, and the words under it cannot be edited by pointing at them, however well
they are painted. The first run over every page, on 2026-10-01, found four such things,
all of the canvas and none of the front end; `assets/css/editor.css` answers each, and the
hazard "The canvas is not the page" in §7 says what they were. What a strip clips out of
sight is counted apart, as clipped from sight: nobody can point at it, and the List View
reaches it.

Then the section library, the same way. Every pattern in `theme/patterns/sections/` is a real
section lifted from a real page — `gen_patterns.py` names which in the file's header —
so each one is inserted into an editor as the inserter would insert it, and held against
that section on that page: every block it parses to has to be registered and valid, the
inserter has to offer exactly the files marked `Inserter: yes`, and the canvas has to paint
the inserted pattern as the page paints the section. Nothing is saved; autosave is pushed
out of reach first. This is what catches a pattern drifting from the blocks — an attribute
renamed, a parent changed — which nothing else would report until somebody clicked +.
It also reaches sections the six pages do not have: the first run found that core's list
item edits its words in a `<div>` the page does not have (see `TRANSPARENT`).

Five kinds of difference are not faults and are handled by name rather than by loosening
the comparison. `RUNTIME_CLASSES` are classes a script or the editor decides — `is-revealed`,
`wp-block`, the selection states, and core's own `wp-block-paragraph` on prose that
`osous_prose_markup()` takes off on output — ignored when elements are matched up; every
`wp-block-*` class is the editor's. `INTENTIONAL` are the subtrees `editor.css` reshapes
because GSAP does not run in the canvas. `RUNTIME_STATES` is the narrow case that also
repaints: only `active`, and where the two sides disagree about it that element **and its
subtree** have their styles left alone. `TRANSPARENT` is the editor wrapper that holds what
the page holds directly in its parent — core's list item edits its words in a `<div>` inside
the `<li>` — walked through rather than counted, as GSAP's pin-spacer is. And a width over
by at most one space, on text the editor lays out `white-space: pre-wrap` where the page
collapses it, is a hanging space at a line wrap (see the hazard). The counts are printed
as `(3 mid-scroll)` and `(2 hanging spaces)`, never silent — if either is ever large,
something has been excused that should not have been.

The seven starters are in the same run: a starter is a whole page of its kind, so it is
inserted the same way and held against every section of the page it was lifted from.

Then the kinds of page, as a person meets them. For each kind a new page is opened
(Pages → Add New), the question "What kind of page is this?" has to be there offering
exactly the kinds the theme names, the kind is clicked, and the page has to take the
kind's template and hold its starter's sections with every block valid. The page is then
saved as a draft, because that is where WordPress refuses a template it does not know.
"Start with an empty page" has to leave no template and no blocks, and a page that
already exists must not be asked. The drafts are deleted afterwards.

Last, a page's own tools, used the way a person uses them. "Duplicate" in the list of
pages has to open a draft called "… (copy)" with the same sections on the same template,
and the copy must not be asked what kind of page it is. On that draft the "Page options"
panel has to be there, must not offer an original to restore, and its WhatsApp switch,
clicked and saved, has to be in the page's meta and take the badge off the page as a
visitor gets it. On a page installed with the theme, two sections are removed, "Restore
the original sections" is clicked and confirmed, and every section has to be back with
every block valid — and Undo has to return the page with the two missing. Then a card is
selected and its link picked from the list of pages by title: the list has to offer every
published page, and what is stored has to be `about.html`. The draft is deleted; nothing
is saved on a page that existed before.

Last and only when asked for, `--build`: the owner's first week, for every kind. Pages →
Add New; the kind chosen from the question; a title typed; the opening words and a later
block's words typed over in the canvas (the contact section's in its sidebar, which is
where they are edited); a picture uploaded from this machine through the block's "Replace
image" and the Media Library's "Upload files"; the slug typed in the sidebar's Slug row;
Publish. Then, logged out, the page at its address at 1440 and at 479: the typed words, the
uploaded picture, the kind's template on the body, the title in the document's title, no
sideways scroll, a clean console — and a picture of each kept as
`_migration/check/build-<kind>-<width>.png`. Then, back in the editor, another kind picked
from the Template row and the slug changed again, saved: the new template is on the body
and the first address is one 301 to the new one. The pages and the upload are deleted at
the end. It is never part of the default run, because it publishes.

**Passing looks like:** `all 6 pages: the canvas paints what the page paints` (`all 56
pages` with `--all`, each line also saying how many words and pictures were clicked into),
then `all 30 patterns: registered, 21 in the inserter, every block valid, each painted as
its source`, then `all 7 kinds: offered to a new page, each sets its template and lays
down its starter`, then `all 4 tools: a page is duplicated, its options are saved, its
original comes back, its links are picked`; and from `--build`, `every kind: made into a
page, its words and picture changed, published, moved to another kind and another address`

### 5.9 The editor's choices — `_tools/verify_presets.py`

```bash
python _tools/verify_presets.py
```

Every option in `functions/design.php` writes a class, and every one of those is a promise that
the design has a rule for it. This reads the tables through WordPress and checks each
against every selector in `assets/css`: an option of several classes has to be carried by
one selector holding them all (`.section.overflow-hidden.bg-3` paints nothing if either
half is missing) or each has to be a utility with a rule of its own. It also checks that
the copy of those tables in `_tools/gen_blocks.py`, which the migration reads the reference
pages with, still says what `functions/design.php` says.

Nothing else catches this. The other checks read the 57 pages as they are, and an option
nobody has picked yet is on none of them.

**Passing looks like:** `0 problems`

### 5.10 Is the content what the blocks can show? — `_tools/verify_coverage.py`

```bash
python _tools/verify_coverage.py
python _tools/verify_coverage.py --list     # every leftover class, page by page
```

Walks every block of every page **as WordPress holds it** and checks that every block is
one the theme registers, that every choice is one the design names, and reports what is
left over: the classes carried in "Additional CSS class(es)". Those are editable, but they
are also the measure of how much of the site the named attributes do not yet describe, so
they are counted by block and by class. The first two fail the run; the third is a report.

With named attributes, "is everything editable?" is answered by construction — a card's
title is its `title`, and the editor offers a control for every attribute. What can still
go wrong is a block or a value the theme does not know, and that is what this looks for.

**Passing looks like:** `0 problems`, and a residue list that only shrinks.

### 5.11 The editor, by hand

The part no harness can do: the site in a client's hands. Take a snapshot first
(`./_tools/env.sh save before-hands`), then, logged in and through the admin only:

- open a page, change a heading and a card's text, save: the front end shows both, and
  `verify_wp.py <slug>` reports those two texts **and nothing else**
- insert a section from + → Patterns → Osous sections, change its words, save
- replace a card's icon from the Media Library, save
- make a new page from three patterns, edit its words, publish; look at it at 1440 and
  at 479
- add that page to a menu (Appearance → Menus); change a phone number in Appearance →
  Customize → Site details; write a post with a featured image
- the console has no errors throughout
- `./_tools/env.sh restore before-hands`, delete what was uploaded, and the reference
  checks are green again

Since version 4, five more, each a thing a client does in their first week:

- Pages → Add New: the question is there; choose a kind, change its title and two
  lines, publish; look at it at 1440 and at 479
- change a published page's slug in the sidebar (the address under the title), save:
  the old address arrives at the new one, and a page that linked to it links to the new
  address
- Page options: switch the WhatsApp badge off, save, look; switch it back
- the Rank Math box: change the page's search title, save: the page's `<title>` is it
- select a section → ⋮ → Create pattern → Synced; place it on a second page; change a
  word in it under Patterns: both pages change

Done on 2026-09-23: every step passed, and the run found what the harnesses could not —
a purple card that had merely been clicked came back with an empty paragraph saved inside
it (the template hazard in §7). Two things that are not faults: the Patterns tab says "No
results" for a second while the library loads, and a picture chosen from the Media Library
is stored as its full URL, as core's image block stores one.

Done again on 2026-09-30 for the five version-4 steps, on a snapshot that was restored
afterwards. A Partner page was made from the question, given its own title and two
changed lines, published, and looked at at 1440 and at 479. `dell-iraq` was renamed `dell`
in the editor: the old address was a 301 to the new one, the eight pages that link to it
printed the new one, and it kept its template. A title and a description typed into the
Rank Math box were the page's `<title>` and description. A call to action was made a
synced pattern on one page, placed on a second, changed once under Patterns, and read
the new words on both. The console stayed clean. It found nothing the harnesses had not:
they had already met Rank Math's silence and its link filter (§7).

### 5.12 The contact form — `_tools/verify_contact.js`

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

### 5.13 The handoff — `_tools/handoff-test.sh`

```bash
./_tools/env.sh package        # dist/: the theme zip, the content, the four plugin zips, the notes
./_tools/handoff-test.sh       # installs exactly that on a WordPress that has never seen this repo
```

The test stands up a second, clean stack (`_tools/handoff/docker-compose.yml`, nothing
mounted but `dist/`), installs WordPress, and does what HANDOFF.txt tells the receiver
to do -- the theme from its zip, the plugins from theirs, the content from its export,
permalinks, the Turnstile keys -- then runs `verify_wp.py`, `verify_chrome.py` and
`verify_seo.py` against it on port 8090, counts the pages that arrived on their kind's
template, and tears it down. It is the difference between "the package
should work" and "the package works": a file missing from the zip, a post type missing
from the export, a menu that did not travel, or a setting the theme was meant to fill
in on arrival all fail here and nowhere else.

### 5.14 Site details reach the page — `_tools/verify_settings.py`

```bash
python _tools/verify_settings.py
```

Every field in `functions/settings.php`, and the logo from Customize → Site Identity, is set to
a marker nobody would write, the home page is fetched, and each marker has to appear where
that field is printed: the phone in its `tel:` link and its text, the address with its
line breaks, the badge in its `img`, the button's page in both of the header's buttons.
Then everything is put back and the home page fetched again: it has to be byte for byte
what it was before the run.

It exists because a field that is declared, sanitised and saved but never printed passes
every other check here — the defaults reproduce the reference, so `verify_chrome.py` is
content with a footer that ignores its settings. This is the one place a forgotten
`osous_setting()` call shows.

**Passing looks like:** `27 settings checked, 0 problems`

### 5.15 The kinds of page — `_tools/verify_templates.py`

```bash
python _tools/verify_templates.py
```

A kind is written down in three places that have to agree: `_tools/kinds.py`, which the
migration reads; `functions/templates.php`, which WordPress and the editor read; and the
files themselves, `templates/page-<kind>.php` with its `Template Name` and
`patterns/starters/<kind>.php` with its slug. This asks WordPress what it has and holds
it against the other two: the same kinds in the same order; exactly those templates
offered for a page, with "Create custom template" off; every starter registered, kept
out of the inserter, for pages, made of blocks the theme registers, and lifted from a
page of its own kind. Then the pages: every one of the 57 on its kind's template, and
every file under `theme/pages/` the same blocks with the same attributes as the page
WordPress holds.

Nothing else looks at any of it. The pages render the same on every template, so a page
on the wrong one — or a page file that has drifted from its page — passes every other
check here. It has been seen to fail: a page moved to the legal template and given one
changed word reports both, by name.

**Passing looks like:** `57 of 57 pages on their kind's template; 56 of 56 page files the
same as the pages` and `0 problems`

### 5.16 Changing a page's address — `_tools/verify_slugs.py`

```bash
python _tools/verify_slugs.py
```

Renames the page most other pages link to, through the same `wp_update_post()` the
editor's save runs, and looks at everything that used to break. The page has to answer
at its new address with the same sections on the same template; its old address has to
be one permanent redirect to the new one; every page that links to it has to print the
new address and not the old, in its content and in the menu. Renamed a second time, both
old addresses have to go straight to the newest. Given its first slug back, it must not
redirect to itself, the two in-between addresses still have to arrive, and it must list
exactly those two as old. Moved under another page, its links have to follow it there and
its old address arrive. Then the page is put back -- slug, parent, the old slugs it
collected -- and it and a page that links to it are held against the reference.

Nothing else changes a slug. Every other check reads the site as the migration left it,
with every page under the name it was cloned with, so a theme that finds a page, a
template or a link by slug passes all of them and breaks the first time a client tidies
an address. That is exactly what happened to version 3 (§7).

**Passing looks like:** `22 checks, 0 problems`

### 5.17 What a page decides for itself — `_tools/verify_pages.py`

```bash
python _tools/verify_pages.py
```

Four things no reference page exercises — every one of the 57 has the badge, only the
front page opens on a hero, none holds a synced pattern — so nothing else would notice
any of them breaking. The WhatsApp badge, switched off on one page, is gone from that
page and still on another. A scratch page made from the Home starter, which is not the
front page, gets the dark navbar and the dark body, and brings the card stack's and the
carousel's scripts with it. A scratch page holding nothing but a synced pattern of a
pinned list prints the section and loads `list-scroll.js`. And the originals' route
answers someone who may edit pages with the page's own blocks, refuses a visitor, finds
nothing for a key that is no file, and is no route at all for a key that tries to leave
`pages/`. The scratch pages and the pattern are deleted and the badge put back.

**Passing looks like:** `0 problems`

### 5.18 What every page tells a search engine — `_tools/verify_seo.py`

```bash
python _tools/verify_seo.py
python _tools/verify_seo.py about dell-iraq     # just these pages
```

Fetches every page and reads its `<head>`: one `<title>`, and it is the reference
page's; one description, and it is the reference page's; one canonical address, the
page's own; one robots line that lets the page be indexed and followed; Open Graph saying
the same title, description and address, and a Twitter card; schema that parses as JSON;
hreflang naming the page once in the site's language and once as the default.

"One" is half the point. A theme and a plugin that both print a title or a description is
the fault this is most likely to catch, and no other check reads the `<head>` at all —
`verify_wp.py` is `<main>`, `verify_chrome.py` the rest of the `<body>`. It needs Rank
Math active, which `env.sh rebuild` sees to, and `handoff-test.sh` runs it against the
clean install to prove the titles travelled with the content.

**Passing looks like:** `57 pages, 0 problems`

### 5.19 Everything on a page is the dashboard's to change — `_tools/verify_editable.py`

```bash
python _tools/verify_editable.py                     # every page, all four parts
python _tools/verify_editable.py --part=blocks       # one part: blocks, seo, templates, slugs
python _tools/verify_editable.py about contact       # just these pages
```

The owner's test of 2026-10-01, put to every page: every word, picture, link, colour and
background, and what the page tells a search engine and a share, is changed from
WordPress, and nothing is fixed. Four parts, each changing the site the way the editor or
Rank Math's box changes it, each reading every page back as a visitor gets it, each putting
back what it changed:

- **blocks.** Every field of every block of every page is saved as a marker — the empty
  ones and the ones left at their default too, which the saved block does not hold at
  all: words, alt texts, pictures (as an address in the uploads, which is how the Media
  Library stores one), links; a card's picture is switched on so that its picture and its
  link show; every colour and background choice goes back to plain. Each page's `<main>`
  has to print every marker and hold no word, picture or link that is not one. The contact
  form's labels are Contact Form 7's, and have to be in that form's settings. What stays
  drawn with every choice cleared is reported as fixed by its component; on 2026-10-01
  that was the home page's hero picture (`bg-img-6`, in `includes/sections/hero.php`) and
  its industries section's (`bg-3`, in `industries-carousel.php`), the Azure page's
  `bg-img-15` carried in "Additional CSS class(es)", and the list bullets and ticks, which
  are the list's style.
- **seo.** Rank Math's fields on every page set to markers: title, description, the
  Facebook and the Twitter title, description and picture, the Twitter card, canonical.
  The two pictures are uploaded and set by id, as Rank Math's own picker sets them: set by
  address alone, Rank Math prints no picture at all. Then title and description alone,
  with robots set to noindex — apart, because Rank Math leaves the canonical out of a page
  that is not indexed — and the social tags have to follow the title and the description.
  On the blog, WordPress's posts page, Rank Math has Twitter repeat the Facebook fields and
  reads no Twitter field of its own; the check holds the blog to exactly that.
- **templates.** Every page is moved to another kind through `wp_update_post()`, which is
  how the Template row saves: accepted, the body says the new template, `<main>` is the
  same. A template the theme does not have is refused.
- **slugs.** Every page is renamed at once: each answers at its new address with the same
  `<main>`, its old address is one permanent redirect there, and no link on any page
  points to an old address. Then every slug, and every old slug, is put back.

Every change is written down under `_migration/editable/` before it is made, and put back
at the end of its part even when the part fails; a run that dies half way leaves those
files, and the next run puts them back before it starts. The Turnstile widget in the
contact form takes a new random id on every request, so `<main>` is compared with those
ids taken out.

**Passing looks like:** `57 pages, 0 problems`

---

## 6. Rules

**The repository is public.** `origin` is `github.com/hla-sh93/osous-tech-website`, pushed
for the first time on 2026-09-30 so that no version lives on one machine only, and GitHub
gives that account public repositories and no private ones. So nothing that is not meant
for everyone goes into a commit: no production database, no export of the live site's
posts, no real Turnstile or SMTP key, no licence key. `_migration/` and `dist/` are
ignored for that reason and stay ignored. What the history already holds was looked at
before the first push: no key and no token; one old `osous-tech-website.zip` (added in
17bee80, deleted in 1aeab2a) with a copy of the local test database, whose only account is
the local `admin`.

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

**A block prints its markup in one file under `includes/`, and nowhere else.** Its
`block.json` names that file as its `render` (`file:../../includes/sections/hero.php`), so
there is no `render.php` beside it and nothing to keep in step. `functions/markup.php`
holds what several of those files share — a URL, an image, a button, the inline allow-list —
and `functions/blog.php` renders a page header by calling the block. A second renderer of
anything means two things to keep correct.

**Every choice is a word from `functions/design.php`.** A new option is a new entry there, with
its class and its label; the template reads it with `osous_option()`, the editor lists it
from the same table, and §5.9 proves it against the stylesheets. Not a class string in a
template, not a colour picker, not free CSS.

**A script follows its block through `osous_has_block()`, not `has_block()`.** Core's
function reads the page's content and stops; a synced pattern is one line there and its
section is in another post, so a pinned list placed that way would arrive without the
script that pins it.

**The theme prints no SEO tag but hreflang.** Title, description, canonical, robots, Open
Graph, Twitter and schema are the SEO plugin's, edited on the page and in the plugin's
settings. A `<meta name="description">` in `header.php` would be a second one on every
page the day the plugin is active — which is every day.

**Stylesheet order is load-bearing** and enforced by the dependency chain in
`functions/enqueue.php`: tokens · base · layout · components · navigation · sections · utilities ·
animations. `utilities.css` must stay second-to-last, `animations.css` last. Do not
"simplify" it into a loop over a directory listing.

**Media queries within a file run 991 → 767 → 479.** A tool that reorders them breaks
mobile in a way desktop testing never catches. This has happened here once.

**Never hand-edit generated files.** `_migration/*` comes from `_tools/gen_*.py` and
`_tools/seed*.py`; `theme/patterns/sections/` and `theme/patterns/starters/` from
`gen_patterns.py`; `theme/pages/` from `gen_blocks.py`; `theme/build/` from
`npm run build`. Change the source.

**Do not re-add `webflow.js`.** Every animation is reimplemented in the six modules; the
IX2 runtime would double-bind the scroll triggers.

**A template per kind of page, never per page, and never chosen by a slug.** The seven
under `templates/` are the kinds (§3); a page that needs something new needs a new block
or a new pattern, not a new template — otherwise the 57-page problem comes straight back.
A template found by the page's slug is the version-3 fault: rename the page and it has no
template. WordPress keeps the choice on the page; leave it there.

**A page is known by what it is, never by its slug.** A slug is a field an editor may
change on any day. The template is on the page (`_wp_page_template`), the menus hold ids,
Site details holds the button's page as an id, and a link in the content — stored as the
static site's own `about.html`, which is what keeps the content readable — is resolved by
`functions/links.php` to the page that has that path now or had it before. So: no
`get_page_by_path()` and no `home_url( '/slug/' )` for a page anywhere in the theme; ask
`osous_page_id()` and `osous_page_url()`. §5.16 renames a page to prove it.

**Escape at the point of output** — `esc_html`, `esc_attr`, `esc_url`, `wp_kses`. The
allow-list in `osous_inline_html()` is deliberately short and matches what the editor's
toolbar can produce: bold, italic, a link, a line break, and the design's three spans.

**Three divergences from the live site are deliberate** and must survive. They are
documented in the README: the split-section script that throws on live, the pinned list
measuring before images load, and three nav links that depend on server redirects.

---

## 7. Hazards

Every one of these has already cost time here.

**`active` is in the markup and the script takes it away.** The reference page was
captured with the first item of a pinned list highlighted, so `list-item active` is what
every copy of that section carries; `includes/sections/pinned-items.php` puts it on the first item.
On the live page ScrollTrigger toggles the class from `top center` to `bottom center`,
which means at rest **no** item has it — GSAP removes the one the markup shipped. Neither
side is wrong, and anything comparing the two has to know: see `RUNTIME_STATES`.

**`templateLock: 'all'` rewrites the content.** With that lock the editor *synchronises*
a block's inner blocks with its template on every load: the pinned list's six items came
back as the template's two, and the column's paragraph vanished, before anybody had saved.
A locked shape wants `templateLock: 'insert'`, and every InnerBlocks inside it wants
`templateLock: false` of its own, or it inherits the lock and nothing can be added.

**An empty link opens a gap.** `.card-1` is a flex column with `gap: 1rem`, so any child
at all — the export's bare `<a href="#"></a>` at the top of eight icon-less cards — adds
16px to the card and 32px to the page. "Renders nothing" is not a thing a flex child can
do. Those cards keep their empty media box; the geometry check is what said so.

**A false that reads as empty is dropped.** `card.outline` defaults to true, so "no
outline" is stored as `false` — and a migration that dropped every falsy attribute as
"nothing to say" drew an outline on every card that had none. Only `None` and `""` mean
nothing; `flag()` in `gen_blocks.py` is for the booleans whose default is false.

**WP-CLI `eval-file` runs a script through `eval()`, and a half-megabyte line broke it
silently.** The content inline in the loader, as a nowdoc, came back from `eval()` with
nothing run and nothing said — exit code 0, no output, not even a log line above it.
The content is in `_migration/blocks.json`, read by a loader of forty lines.

**Since WordPress 7, core's `wp-block-*` classes are added on render, not saved.** A
paragraph, a heading and a list save as plain `<p>`, `<h3>` and `<ul>`, and a filter adds
`wp-block-paragraph` and the rest on output. Markup saved *with* the class is what the
editor no longer writes and would flag; markup rendered *without* it is what the design
has. `osous_prose_markup()` takes the classes off and puts the design's `role="list"` back.

**`white-space: pre-wrap` hangs a space off the line.** The editor sets it inline on every
editable, so a space typed at a line's end stays visible. At a line wrap that space is
counted in the element's width — a `thin` span reads 6px wider in the canvas than on the
page, and a centred heading 5px. Nothing moves. Do not turn the rule off: without it
Chrome inserts a non-breaking space for every trailing space typed, and those get saved.
`verify_editor.js` counts these by name.

**`get_theme_file_uri( '/' )` has no trailing slash.** `themeUrl + 'assets/…'` became
`osousassets/…` and every icon in the canvas was a 404. `trailingslashit()`.

**The block editor cannot be put in an iframe.** `post.php?action=edit` sends
`Document-Isolation-Policy: isolate-and-credentialless`, which puts it in its own agent
cluster: `contentDocument` is `null` even from the same origin. `/wp-admin/` and
`wp-login.php` frame and read fine, which makes this look like a login problem for about
an hour. Anything that has to read the editor drives a real page — that is why
`verify_editor.js` is Playwright and not another harness in `_tools/*.html`.

**The canvas clamps every block to 840px.** Gutenberg's content width, right for a column
of prose and wrong for a full-bleed section — and the design's media queries still read
the canvas viewport, so the editor was laying out the *desktop* design inside a
tablet-width box. `theme/theme.json` sets `layout.contentSize: "100%"`, which is the only
thing that file is there for. Do not re-solve this in `editor.css`; it was, and the two
fixes then overlapped.

**A classic theme with no `theme.json` gets wp-admin's own CSS inside the canvas.** Core
skips its editor reset only `wp_theme_has_theme_json()`, and that reset depends on
`common` and `forms` — so `wp-admin/css/forms.css` styles the contact form's inputs in the
editor, at `input[type="text"]` (0,1,1), which outranks the design's `.input` (0,1,0). The
minimal `theme.json` keeps all of it out. It declares no `styles`, so the front end is
untouched.

**Nothing gives the canvas a web font.** `add_editor_style()` takes local files; Inter and
Sora come from Google. Without them every heading in the editor is drawn in a fallback
face — which reads as *very nearly* right, and is worse than obviously wrong, because type
is what somebody is judging when they choose a heading. `osous_enqueue_editor_fonts()`
hangs the same URL on `enqueue_block_assets`, which is the hook that runs inside the
iframe. Measured, it was three pixels of heading width.

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

**`wpcf7_enqueue_styles()` enqueues a handle CF7 registers somewhere else.** In its own
`wp_enqueue_scripts` callback -- which never runs while the editor canvas collects its
assets, so the call does nothing there and the canvas paints CF7's hidden fieldset as a
box. `functions/contact.php` registers the handle itself when it finds it missing.

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

**`wp-scripts lint-js --fix <path>` fixed more than the path.** Given one file, it rewrote
every JavaScript file under `assets/js/` and `_tools/` as well. `npm run lint:fix` names the
one place it may touch; read `git status` afterwards regardless.

**A clean `phpcs -q` prints nothing.** Not "0 errors" — nothing at all. Read the exit code,
or drop `-q` and read the summary.

**`--` inside an XML comment is not a comment.** `phpcs.xml.dist` is XML, and a double
hyphen inside `<!-- -->` makes the whole ruleset invalid; phpcs reports it only as "Ruleset
is not valid".

**`wp_update_post()` unslashes what it is given.** Block delimiters carry `\u` escapes so
the JSON cannot end the HTML comment it sits in. Pass that through unslashed and every
backslash is eaten, and the site renders `u0026` in the middle of its headings. 51 of 57
pages. Always `wp_update_post( wp_slash( ... ) )`.

**Git Bash rewrites container paths.** `/migration/x.sql` becomes `C:/Program
Files/Git/migration/x.sql` inside the container, and `wp rewrite structure '/%postname%/'`
silently stored `/C:/Program Files/Git/%postname%/` for weeks. Prefix every WP-CLI call
whose argument starts with a slash: `MSYS_NO_PATHCONV=1 docker compose ...`.

**`ServerSideRender` puts attributes in the query string.** Long enough and Apache answers
414, and the preview never arrives — silently. The contact block, the one that uses it,
passes `httpMethod="POST"`.

**`the_content` rewrites finished markup.** `wptexturize` changes quotes and dashes inside
every heading; `wpautop` wraps loose text. Both are removed for pages in
`osous_unfilter_page_content()`. Do not put them back.

**ScrollTrigger measures pins in creation order.** A pin above another one adds height when
it engages, so a pin measured second computes its start against a page that is about to
grow. Every pinned trigger sets `refreshPriority` from its element's document position.
Keep it that way — script load order is not a stable thing to depend on.

**Python's `str.strip()` eats U+00A0.** Several paragraphs open with a non-breaking space.
Strip ASCII whitespace explicitly: `.strip(" \t\r\n\f\v")`.

**`<a>` is not reliably inline.** `<div><a><img></a></div>` is a block wrapper. Treating it
as inline stores the markup as a string, and the output filter then strips the image — 40
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

**Replacing an image must clear `srcset` and `sizes`.** They describe the file being
replaced. Leaving them serves the old picture at most widths. `ImageControl` drops both.

**Do not make `body` a flex container.** It would stop the margins between the last section
and the footer collapsing — 64px on every page, and 68px more that escapes above the body
today. The sticky footer in `base.css` exists because it changes no formatting context.

**Fresh browser tabs default to a mobile viewport.** Resize to 1440x900 before measuring
anything.

**A scan of pattern files is cached.** Core caches its scan of `theme/patterns/` per
theme — it had once been populated as empty before the directory existed, so nothing
registered and nothing said why — and `functions/patterns.php`, which registers the
sub-folders core does not look in, keeps its own list in a transient named for the theme's
version. Both are read fresh while `WP_DEVELOPMENT_MODE` is `theme`, which it is locally
and after `env.sh rebuild`; on a server, a pattern file added without a version bump
appears when that transient expires, a day later, or when it is deleted.

**WordPress ships its own draft Privacy Policy page** on a slug this site wants. The seed
adopts and publishes it, and empties its boilerplate.

**A rebuild proves nothing until it has run on a wiped database.** `env.sh rebuild` said
it recreated the whole site, and every check agreed — because the posts had been seeded
by hand once and rode along in every snapshot since. The first run from nothing left the
blog with WordPress's *Hello world!* and `/blog/` an empty page: the posts seed had never
been in the script. It is now. `docker compose down -v` first, then believe a rebuild.

**A receiver installs the zip they recognise and skips the notes.** The first receiver of
the handoff uploaded `osous-theme.zip`, activated it, and asked where the other 56 pages
were: the content import, step 3 of HANDOFF.txt, never happened. Both notes now open with
that one fact, in English and in Arabic (HANDOFF-ar.txt). If it happens again, the answer
is an import the theme runs itself, not more prose.

**WordPress keeps a post's old slugs, and deliberately not a page's.** Rename a post and
its old address redirects for ever; rename a page and the old address is a 404.
`wp_check_for_changed_slugs()` returns early for a hierarchical post type and
`wp_old_slug_redirect()` refuses one by name, so neither half of the mechanism exists for
pages. On top of that, version 3 found each page's file by its slug, so a renamed page
lost its template and printed nothing, and both versions pasted a stored `dell-iraq.html`
onto the site's address. A client changed one slug in the editor and reported the page
broken, on 2026-09-29. `functions/links.php` records a published page's old slugs in the
same meta key core uses for posts, resolves every stored link through them, and sends the
old address on; §5.16 is the check that would have caught it.

**The live site's permalinks have no trailing slash.** Its canonical addresses read
`https://www.osoustaqnia.com/about`. The local site and the handoff use `/%postname%/`.
A page's address is therefore never assembled by hand: `osous_page_url()` adds what
`user_trailingslashit()` says the site uses, so the same content links correctly on both.
Before this, every internal link on a no-slash site would have cost a redirect.

**Rank Math prints nothing until its first screen is answered.** Installed and active,
with a title in every page's field, the `<head>` was still WordPress's own: Rank Math's
front end does not load while `is_invalid_registration()` is true, and that is true until
an account is connected or "Skip" is pressed in its wizard (`rank_math_registration_skip`).
Closing the wizard is not pressing Skip. `env.sh rebuild` and `handoff-test.sh` set the
option; HANDOFF.txt tells the receiver to do one or the other. §5.18 is what notices.

**Rank Math's link settings reach the pages now, and never did on the live site.** The
live pages are one raw HTML field printed by the old theme; these are blocks printed
through `the_content`, which Rank Math filters. Its default "Open External Links in New
Tab/Window" put `target="_blank"` on three links the design prints plain -- `env.sh
rebuild` and `handoff-test.sh` switch it off and the notes say to -- and it adds
`rel="noopener"` to every outside link whatever the settings say, which `verify_wp.py`
accepts by name. Nobody knows what the live site has that setting at: it has never had
anything to act on there. Look at it again after the live settings are imported.

**A busy machine makes the scroll harnesses lie.** `pinned-list.html` reported one list
of 45 wrong while 213 files were being staged in another shell, and `geometry.html` five
pages of 56 while `npm install` ran; each was clean the moment it ran alone. They measure
where a pin engages and where a reveal has got to, frame by frame, and a starved browser
misses frames. Run them with nothing else going on, and re-run alone before believing a
failure that appeared alongside other work.

**Docker Desktop will not start after an unclean shutdown.** It quits with "starting
services: initializing Inference manager … remove … dockerInference: The file cannot be
accessed by the system", or the same for the Secrets Engine's `engine.sock`. The cause is
a socket file left behind under `%LOCALAPPDATA%\Docker\run\` or
`%LOCALAPPDATA%\docker-secrets-engine\` that Windows will neither delete nor let Docker
delete. Rename the folder it is in (`run` → `run.stale`, likewise the secrets one) with
Docker stopped, and start Docker again; it recreates both. Two of these in a row cost
half an hour on 2026-09-17. The log that names the file is
`%LOCALAPPDATA%\Docker\log\host\com.docker.backend.exe.log`.

**A template on an optional InnerBlocks area fires on every selection.** The purple card's
prose area is drawn only while the card is selected, and it carried
`template: [ [ 'core/paragraph' ] ]` — so drawing it inserted a paragraph, and every card a
person merely clicked was saved with an empty `<p>` and a prose box printed around it.
Templates are for areas that always hold something; an optional area gets `defaultBlock`
with `directInsert`, so the "+" inserts and nothing else does. Found on 2026-09-23 by
editing as the client would, not by any harness: `verify_editor.js` never selects a card.

**An impossible version range hides until the next fresh install.** `package.json` asked
for `@wordpress/data` ^12 and `@wordpress/rich-text` ^9 — versions npm has never had; the
newest were 10.56 and 7.56. Nothing noticed, because both were already installed as
dependencies of `@wordpress/block-editor`, and `npm run build` only ever ran against that
`node_modules`. The first `npm install` from the manifest, on 2026-09-29, failed with
`ETARGET`. Pin to what the packages you already use depend on (`^10.55.0`, `^7.55.0`), and
after changing `package.json` run `npm install` rather than trusting the folder on disk.

**Git Bash rewrites backslashes in a heredoc.** A Python patch typed into a heredoc
arrived with `\\u0634` turned into the character and `\t` into a tab, and an assertion
that looked right failed for an hour, twice on 2026-09-23. Anything with a backslash goes
in a file written by the editor, or spells the backslash as `chr(92)`.

**A check that passes on the absence of faults has to prove it ran.** On 2026-10-01
Docker stopped in the middle of `check.sh`. The phone layout's harness timed out with an
empty log, found no differences in it, and `check.sh` printed `ok`: its sentence said only
that nothing differed. It now also needs the harness's own `done at 479x900`, as the
desktop check always needed `done at 1440x900`. The pinned lists caught the same outage
honestly, because their sentence counts what was checked (`45 pinned lists checked`). A
check whose pass sentence has no count of what it looked at can pass on nothing.

**The canvas is not the page: what the design draws behind, or pins.** On 2026-10-01 the
click check in §5.8 found four things that the canvas painted correctly and nobody could
click into, all answered in `assets/css/editor.css` and none in the front end's sheets.
The page header is `z-index: -1`, under the navbar on the front end and, in the canvas,
under the editor's own page: its eyebrow, title and text took no click on 55 pages. It is
`auto` in the canvas, and the post's title is lifted over its first 100px, as the navbar
is. The card stack is a 500px frame in a section one screen high; laid out one card under
another for editing, its cards spilled over the sections before and after it, which took
their clicks. Both are `auto` in the canvas — the heights the design gives the stack itself
below 991px. The split section's heading is laid over its cards (`.misc`, absolute), and
over a column of cards it lay under the first card's words, which are drawn on top: on
three pages whose first card is long it took no click. It comes first in the canvas, above
the cards, as the design lays it out below 991px. And a page that opens on the hero is
drawn on the dark body
(`c-surface-secondary-900`), which the canvas does not have, so the home page's white words
were white on white while being edited; the canvas's page takes the colour when the first
block is the hero. The canvas check compares the paint inside sections, never what is on
top of what, nor the body behind them, and leaves the pinned sections' insides alone, so it
passed all four.

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
| Phase 6 | One file per page in bst's shape, built at the client's request (2026-09-23); set aside on 2026-09-29 because the pages could no longer be edited in WordPress. Kept whole on the branch `file-per-page` (§10) |

| Phase 7 | Version 4 (2026-09-30): the file structure and the editor, both. The blocks' markup under `includes/`, a template per kind of page, a starter per kind and an original per page; a page known by what it is, so its slug can change; page options, Duplicate, synced patterns, links picked from the pages; each page's search title and description carried into Rank Math's fields, and hreflang from the theme. Merged into `main` on 2026-09-30 at the owner's word (§10) |
| 0.4.1 | Every page put to the owner's test (2026-10-01): every field of every page changed from the dashboard and seen on the page, its search and social fields, another template, another address, and a page of every kind built by hand in the editor (§5.8, §5.19). Four places where the editor drew a page correctly and took no click, fixed in `editor.css` (§7) |

Tagged: `phase-2`, `phase-3`, `phase-5`, `phase-6`, `phase-7`. `git tag -l -n5` shows what
each guarantees. `main` is version 4 since 2026-09-30, when the owner asked for
`version-4` to be merged into it; it was a fast-forward, so `phase-7` is a commit of `main`
and the branch `version-4` is kept where it was merged. `phase-6` is on the branch
`file-per-page`, not on `main`. Version 0.2.1, the block model before the file structure,
is the commit `c9b7a97`.

---

## 9. What is left

**The live site is not an empty WordPress, and the package is proven only on one.**
Measured on 2026-10-01 through the live site's public REST API (`/wp-json/wp/v2/pages`,
`/posts`): its WordPress already holds every one of the 57 pages at the same address, plus
`hikvision-iraq` and `motorola-iraq`, all 59 with empty content and the default template,
because the old theme draws them; and 104 posts. WordPress Importer 0.9.6 takes an item
for one it already has only when the title and the date both match
(`post_exists( $title, '', $date, $type )`). No page does (the live ones are dated
2025-12-07 to 2025-12-15), and the seeded posts are dated at midnight where the live ones
carry a time (`cybersecurity-compliance-iraq`: 17:04:07). So on the live site, or on a
copy of it, activating the theme shows every address empty, and importing
`osous-content.xml` adds all 57 pages again at `<slug>-2` and the twelve sample posts
beside the real ones. The way on is the install step below, run in place.

**Production.** Real Turnstile keys under Settings → Cloudflare Turnstile; a host that can
send mail (an SMTP plugin); the real posts — only the first twelve were cloned, and their
bodies were not, so `_tools/seed_posts.py` (run by `env.sh rebuild`) seeds titles, dates,
slugs and pictures, and the text says so. They are for an empty install: the live
WordPress has the 104 real posts already, and the samples must never reach it.
Then WP Rocket on and every harness re-run against production; the
server-side redirects for `hikvision-iraq`, `motorola-iraq` and `endpoint-secuirty-iraq`
restored so the nav hrefs can go back to the live URLs.

**Rank Math, on production.** The package carries the free plugin and each page's title
and description. The company's details that the live site publishes as schema — a Place,
a ProfessionalService with its address, hours and profiles — are Rank Math's settings and
arrive with Rank Math's own export from the live site (Status & Tools → Import & Export);
installed in place on the live WordPress (above), they are there already, and so is PRO.
The live site runs Rank Math PRO; the per-page schema PRO adds (Service, Offer) needs PRO
installed here. Two of Rank Math's defaults are worth a look on a fresh install: it turns
date archives off, and it opens external links in a new tab. The live home page names a
Syrian version of itself (`hreflang en-SY`, `/sy`), which is not among the 57 pages: if it
is kept, it goes in the home page's Page options.

**The content import is a step somebody can skip, and the live site cannot use it.**
The pages arrive with `osous-content.xml`, and the first receiver skipped it. Every page's
original is in the theme now (`theme/pages/`), with its title and its template in the
file's header, so an "install the site's pages" button in the theme is a day's work: each
page filled where its address already is, keeping its id, its Rank Math fields and its
history, or made where it is not; the menus from the same reading of the navbar the seed
does; the front page and the blog page set; posts never touched. It makes the import
unnecessary for everything but the sample posts and the contact form, and it is the only
way onto the live WordPress (above). The next thing to build: recommended to the owner on
2026-10-01, waiting on their word.

**WP Rocket lies to measurement.** `content-visibility: auto` makes unscrolled sections
report `height: 0`, and its lazyload leaves images as `data:` placeholders. Neutralise both
before trusting a number; the recipe is in the README.

**The residue.** 19 classes ride in "Additional CSS class(es)" (§5.10 lists them), down
from 144 on 2026-09-23, when the grid placements, the rows per breakpoint and the flex
wrappers became named options. What is left is four classes with no stylesheet rule at
all (`custom-section-1`, `custom-1`, `c-on-surface-primary`, `a-l-sb`) and four one-offs
(`col-right`, `microsoft_dynamics_icon`, a container's `h-75`, a section's `bg-img-15`).
None is worth an option: the dead ones could be dropped from the reference pages and the
content together, the one-offs are one page each.

**Colours and backgrounds, as Elementor has them.** Asked for by the owner on 2026-10-01
along with everything else on a page. Measured on every page by §5.19: every background a
section, a container or a panel has is chosen from the design's own — eight section
backgrounds, nineteen pictures and patterns for a panel — and every colour of words and
surfaces from the design's few; none is typed in, and no picture is uploaded for a
background. Three pictures offer no choice at all: the home page's hero, its industries
section, and the Azure page's class. A picture field on the section, the panel and the
hero, taking a picture from the Media Library in place of the design's own, would answer
the request without leaving the design's colours (§10). Not built; reported to the owner.

**Inventing a layout the design does not have** is still the line not crossed (§10). It
would need core blocks bound to the design tokens through `theme.json` `styles`, and it
costs the guarantee that every page can be verified against the reference.

---

## 10. The trade-off to hold on to

There is a line between two ways of building this:

- **Locked** — every page is guaranteed to look like the design. Nobody can break it, and
  nobody can invent anything either.
- **Free**, as a general page builder is — anyone can do anything, including produce pages
  that have nothing to do with this design.

Everything above sits deliberately in the middle: real building blocks and real choices,
but every choice drawn from the design's own vocabulary, so the result stays coherent and
the measurements keep meaning something. That is why a section offers "background: light
grey" and not a colour picker.

If a future brief asks for the free end of that line, say plainly what it costs: the
pixel-accuracy this whole project is built to guarantee stops being meaningful the moment
anyone can drag anything anywhere.

### Roots / Sage — examined on 2026-09-14, asked for again on 2026-09-16, not adopted

roots.io was proposed as the model of "standard WordPress code" and read in full: Sage,
Bedrock, Acorn, the structure, functionality, deployment and Gutenberg pages. It is a
developer stack — Blade templates, an `app/` namespace autoloaded by Composer, Acorn at
runtime, a Vite build, `vendor/` shipped inside the theme, a server rule for `.blade.php` —
with its own style that the WordPress sniffs would fail, and a Sage theme is not a zip
somebody unpacks and activates.

On 2026-09-16 the owner asked for it again, and then relayed the real requirement: their
manager had read the code and found it "generated, not standard". What the manager was
seeing was not the PHP, which already passed WordPress's own standards, but the content
model — every page one `osous/section` block carrying a JSON tree with single-letter keys,
patterns of 2,000 characters on one line, and Webflow's misspelt class names throughout.
A Sage port would have changed the wrapper and left all of that in place. The owner chose
the rebuild that answers the complaint: one block per design component, each a folder with
`block.json`, `render.php` and `edit.js`, named attributes, WordPress's own blocks for
prose, one option table, the misspellings corrected — delivered with every check in §5
green. Bedrock for the *hosting* remains a reasonable later choice on its own; it does not
touch the theme. Do not reopen this without a new reason.

### One file per page — built on 2026-09-23, set aside on 2026-09-29

On 2026-09-23 the client asked for SimonPadbury/bst's structure with one PHP file per page
and a single header and footer. It was built the same day and passed every check:
`theme/pages/<slug>.php` for the 56 pages, `includes/` for the header, the footer, the
sections, the cards and the loops, `functions/` for the rest, the blocks removed. It is
kept whole on the branch `file-per-page`, tagged `phase-6` at its verified state, and its
0.3.0 package is in `_migration/kept/`.

What it cost, said before it was built and felt after: with a page's words in its PHP
file, none of the 56 pages could be edited in WordPress, and opening one in the editor
showed an empty page whose changes went nowhere. On 2026-09-29 the owner asked for the
block version back without losing the other, and `main` was reverted to it (6a7b218).

(Superseded on 2026-09-30 by version 4, below, which keeps both without the fields.)
If the client asks for the file structure again, the answer that keeps both is the
classic-theme one: the page files keep the structure, and the words and pictures move
into fields on each page, edited in WordPress with Secure Custom Fields — free, maintained
by WordPress.org, the continuation of ACF, with the repeater fields the cards need. It
was proposed on 2026-09-29 and not chosen; it would start from the `file-per-page` branch.
Worth knowing when that conversation comes back: bst itself edits pages in WordPress —
its `page.php` prints the editor's content. It was the one-file-per-page rule, not bst,
that moved the words into code.

### Version 4 — the files and the editor, asked for on 2026-09-29, built on 2026-09-30

Once `main` was the block model again the owner asked for both at once: version 3's
structure, "without ever cancelling editability"; control over everything through
WordPress "as if I were using Elementor"; a template for each page, chosen when the page
is made; a slug that can be changed without the page breaking, which a client had just
reported; and the `<head>` the live site prints for search engines, manageable the same
way. Their example file was a classic template, `Template Name: HomePage`, with its words
written into it — "only for the ordering and organisation".

One idea answers all five: **the file is for the kind, the editor is for the content, and
the id is the identity.**

- *The file is for the kind.* bst's shape — `functions/`, `includes/`, templates in the
  root — with the blocks' markup moved under `includes/` and a template for each of the
  seven kinds of page. What version 3 had as one file per page is `theme/pages/`: the same
  56 files, as the blocks each page was installed with rather than as the page itself.
- *The editor is for the content.* Nothing a page says is in a file's keeping. A new page
  is asked its kind and starts as that kind's sections; a page's options, its original and
  a copy of it are in the editor; a link is picked from the pages; a section can be kept
  once and placed on many pages.
- *The id is the identity.* The template is on the page, the menus and the settings hold
  ids, and a stored link is resolved to the page that has that address now or had it.

What was weighed and left out, so it is not proposed again without a new reason:

- **Ids inside the content's links.** The plan said links would be stored by id. An id
  does not survive the content import — the importer gives a page a new one on any site
  that is not empty — so the link stays the readable `about.html` and is resolved through
  the page's own history of slugs, which travels with the page as post meta.
- **The brand's colours as a palette in `theme.json`.** It would put a colour control on
  every core block that bypasses `functions/design.php`, where a colour is a named choice
  the stylesheets are checked against (§5.9). The theme's own text blocks already offer
  the design's tones.
- **The call to action as one shared section.** It reads differently on 41 of the 52
  pages that have one. Synced patterns are allowed on pages for the sections that really
  are shared; none of the 57 pages was converted to one.
- **Fields instead of blocks** (the Secure Custom Fields middle path proposed on
  2026-09-29). Fields are a form beside the page; the owner asked for the page itself.
- **Hiding the header's button per page.** The navbar is laid out around it.

It was built on the branch `version-4`, tagged `phase-7`, and became `main` on 2026-09-30
when the owner said to merge it.

---

## 11. If something goes wrong

```bash
git push origin --all                 # after every verified commit: the remote is the copy
git push origin --tags                # that survives this machine
git tag -l -n5                        # the restore points, and what each guarantees
git checkout phase-3                  # the code as it was last verified
git checkout file-per-page            # the one-file-per-page version, set aside on 2026-09-29
git checkout c9b7a97                  # version 0.2.1: the block model before version 4
./_tools/env.sh list                  # database snapshots
./_tools/env.sh restore <name>
./_tools/env.sh rebuild               # wipe the database, build it from this repo
```

`rebuild` recreates the entire local site from this repository alone: the pages and
menus, the posts, the contact form, and every page's blocks. Nothing depends on a
snapshot surviving.

The push is the owner's to run, or to allow: an assistant's session may refuse to send
the repository out of the machine, and did on 2026-09-30. A commit that has not been
pushed is a version that can still be lost, so say so when handing work over.
