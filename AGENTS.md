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

Current state, measured:

```
57 pages · 399 sections (the blog page is a query) · 3,434 blocks on 56 pages
  3,033 of the theme's 35 kinds, 401 of WordPress's own (paragraphs, lists, headings)
  144 classes carried in "Additional CSS class(es)", 38 generic groups
theme:    162 files; 35 blocks, 23 patterns, one 63 KB editor bundle
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
theme/                 the WordPress theme
  blocks/<name>/         one folder per block (§3, "The blocks")
    block.json             what it is, what it stores, what may go inside it
    render.php             the markup it prints on the page
    edit.js                how it is edited; compiled into build/index.js
  blocks/shared/         the controls, the option tables and URL helpers the edit.js files share
  blocks/index.js        registers every block with the editor
  build/                 the editor bundle — committed on purpose, the zip needs no build
  inc/design.php         THE option tables: every choice a block offers, and its class
  inc/markup.php         the helpers the templates share: URLs, images, buttons, inline HTML
  inc/blocks.php         registration, the editor bundle, the block category, what a page may hold
  inc/enqueue.php        stylesheets and scripts — scripts follow blocks (has_block)
  inc/nav.php            mega menu and footer menus
  inc/contact.php        Contact Form 7, made to render as the design (§5.12)
  inc/setup.php          theme supports, favicon, menu wiring
  inc/cleanup.php        what WordPress adds that this design does not want
  inc/blog.php           the blog card, the pager, the page header for posts and archives
  patterns/              the section library, generated: readable block markup
  theme.json             settings only, no styles. It is what keeps wp-admin's
                         CSS out of the editor canvas and makes a section full
                         width there. Adding `styles` would change the front end
  languages/osous.pot    every string, for translators — `wp i18n make-pot`
  readme.txt             what somebody installing the theme needs to know
_tools/                 every script and harness. Never shipped in the theme
  extract.py             the reference pages → typed nodes, the migration's reading of them
  gen_blocks.py          typed nodes → blocks: _migration/blocks.json and its loader
  gen_patterns.py        the same conversion, one section of each kind, into theme/patterns/
  seed.py, seed_posts.py, seed_contact.py
                         pages, menus, posts and the contact form, through _migration/*.php
  verify_*.py, verify_*.js, geometry.html, pinned-list.html, run_harness.js
                         the checks (§5)
  verify.py, audit.py    the reference against the live site, and the CSS (§5.0, §5.6)
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

A page is a list of blocks, and every block is a folder. The layout blocks hold other
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
| `group` | a plain wrapper carrying the design's layout classes | anything |
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
`columns: 3` — and `inc/design.php` is the one place a word becomes a class. The templates
read it, the editor is handed it (`window.osousEditor.design`), and §5.9 proves every class
in it against the stylesheets. What the reference carries that no word covers — `span-2`,
`row-tablet-3`, `hflex` — rides in WordPress's own `className` ("Additional CSS class(es)"
in the Advanced panel), and §5.10 counts it.

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

### How the content got there

`_tools/extract.py` reads each reference page into typed nodes (that vocabulary is now
internal to the migration). `_tools/gen_blocks.py` converts every section into blocks by
matching the shapes it knows — a page header, a call to action, a card of each kind, and
the generic path of section → container → grid → column → heading — and **raises on
anything it cannot express**. It writes `_migration/blocks.json` and a loader; §5.1 proves
the result is the reference, element for element. `_tools/gen_patterns.py` runs the same
conversion on one typical section of each kind and writes the patterns.

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
python _tools/gen_blocks.py
docker compose --profile tools run --rm cli wp eval-file /migration/blocks.php
python _tools/gen_patterns.py            # the library, from the same conversion
```

The contact form, after an experiment in Contact → Contact Forms:

```bash
python _tools/seed_contact.py
docker compose --profile tools run --rm cli wp eval-file /migration/contact.php
```

The Python tools need `python -m pip install lxml html5lib`. The plugins the contact page
needs -- Contact Form 7 and Simple Cloudflare Turnstile -- are installed by `env.sh rebuild`
from zips the host fetches; see the hazard on the container and wordpress.org.

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
pinned lists, where an empty block child has no height), and an empty class-less
`<a href="#">` at the top of a card is read as the card's empty media box (eight; it opens
the same flex gap — see the hazard). On the contact page the static form's hidden message
boxes come out of the reference too.

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
which reveals the button early at phone width. It predates the block model, it is one
element of 45, and it is listed here so it is not mistaken for a regression.

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
node _tools/verify_editor.js                    # six pages, then the section library
node _tools/verify_editor.js about contact      # just these pages
node _tools/verify_editor.js --patterns         # just the library
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
its `render.php` prints, with the words editable in place — and this is the check that
keeps the two the same. The one block drawn by the server is the contact section, whose
form only Contact Form 7 can render.

Then the section library, the same way. Every pattern in `theme/patterns/` is a real
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

**Passing looks like:** `all 6 pages: the canvas paints what the page paints` and
`all 23 patterns: registered, 21 in the inserter, every block valid, each painted as its
section`

### 5.9 The editor's choices — `_tools/verify_presets.py`

```bash
python _tools/verify_presets.py
```

Every option in `inc/design.php` writes a class, and every one of those is a promise that
the design has a rule for it. This reads the tables through WordPress and checks each
against every selector in `assets/css`: an option of several classes has to be carried by
one selector holding them all (`.section.overflow-hidden.bg-3` paints nothing if either
half is missing) or each has to be a utility with a rule of its own. It also checks that
the copy of those tables in `_tools/gen_blocks.py`, which the migration reads the reference
pages with, still says what `inc/design.php` says.

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

Done on 2026-09-23: every step passed, and the run found what the harnesses could not —
a purple card that had merely been clicked came back with an empty paragraph saved inside
it (the template hazard in §7). Two things that are not faults: the Patterns tab says "No
results" for a second while the library loads, and a picture chosen from the Media Library
is stored as its full URL, as core's image block stores one.

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

**A block prints its markup in its own `render.php`, and nowhere else.** `inc/markup.php`
holds what several templates share — a URL, an image, a button, the inline allow-list — and
`inc/blog.php` renders a page header by calling the block. A second renderer of anything
means two things to keep correct.

**Every choice is a word from `inc/design.php`.** A new option is a new entry there, with
its class and its label; the template reads it with `osous_option()`, the editor lists it
from the same table, and §5.9 proves it against the stylesheets. Not a class string in a
template, not a colour picker, not free CSS.

**Stylesheet order is load-bearing** and enforced by the dependency chain in
`inc/enqueue.php`: tokens · base · layout · components · navigation · sections · utilities ·
animations. `utilities.css` must stay second-to-last, `animations.css` last. Do not
"simplify" it into a loop over a directory listing.

**Media queries within a file run 991 → 767 → 479.** A tool that reorders them breaks
mobile in a way desktop testing never catches. This has happened here once.

**Never hand-edit generated files.** `_migration/*` comes from `_tools/gen_*.py` and
`_tools/seed*.py`; `theme/patterns/` from `gen_patterns.py`; `theme/build/` from
`npm run build`. Change the source.

**Do not re-add `webflow.js`.** Every animation is reimplemented in the six modules; the
IX2 runtime would double-bind the scroll triggers.

**No per-page templates.** A page that needs something new needs a new block or a new
pattern, not a new template — otherwise the 57-page problem comes straight back.

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
every copy of that section carries; `pinned-items/render.php` puts it on the first item.
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
box. `inc/contact.php` registers the handle itself when it finds it missing.

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

**WordPress caches its scan of `theme/patterns/` per theme.** The cache had been populated
as empty before the directory existed, so nothing registered and nothing said why.
`WP_DEVELOPMENT_MODE` is set to `theme` locally and by `env.sh rebuild`; if patterns still
fail to appear, clear the theme's pattern cache rather than guessing.

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

Tagged: `phase-2`, `phase-3`, `phase-5`. `git tag -l -n5` shows what each guarantees.

---

## 9. What is left

**Production.** Real Turnstile keys under Settings → Cloudflare Turnstile; a host that can
send mail (an SMTP plugin); the ~100 real posts — only the first twelve were cloned, and
their bodies were not, so `_tools/seed_posts.py` (run by `env.sh rebuild`) seeds titles,
dates, slugs and pictures, and the text says so until a production import replaces it.
Then WP Rocket on and every harness re-run against production; Rank Math confirmed as the
owner of `<title>`; the
server-side redirects for `hikvision-iraq`, `motorola-iraq` and `endpoint-secuirty-iraq`
restored so the nav hrefs can go back to the live URLs.

**WP Rocket lies to measurement.** `content-visibility: auto` makes unscrolled sections
report `height: 0`, and its lazyload leaves images as `data:` placeholders. Neutralise both
before trusting a number; the recipe is in the README.

**The residue.** 144 classes ride in "Additional CSS class(es)" (§5.10 lists them): the
grid placements Webflow wrote per breakpoint, the flex wrappers, one page's own backdrop.
Each is a candidate for a named option when a real need for it appears in the editor, and
none is worth inventing an option for before then.

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

---

## 11. If something goes wrong

```bash
git tag -l -n5                        # the restore points, and what each guarantees
git checkout phase-3                  # the code as it was last verified
./_tools/env.sh list                  # database snapshots
./_tools/env.sh restore <name>
./_tools/env.sh rebuild               # wipe the database, build it from this repo
```

`rebuild` recreates the entire local site from this repository alone: the pages and
menus, the posts, the contact form, and every page's blocks. Nothing depends on a
snapshot surviving.
