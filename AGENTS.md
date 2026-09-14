# Working on this project

A guide for whoever picks this up next, human or otherwise. Read it before touching
anything. Most of it is not opinion — it is a list of things that have already gone wrong
here and how they were caught.

Companion documents: [README.md](README.md) for what the project is,
[docs/wordpress-theme-plan.md](docs/wordpress-theme-plan.md) for why it is built this way,
[docs/local-setup.md](docs/local-setup.md) for the local environment.

---

## 1. What this is

A pixel-accurate rebuild of [osoustaqnia.com](https://www.osoustaqnia.com/) as a WordPress
theme. The live site is a Webflow export whose every page keeps its full markup in one raw
HTML field in the database — unmaintainable, and the thing being replaced.

The rebuild happened in two halves:

- **57 static HTML pages** at the repository root. These are the **reference**. They are not
  a stage that gets thrown away — every check in the project compares the theme's output
  against them.
- **`theme/`** — a classic WordPress theme that renders the same 57 pages from data in the
  database, with the words, pictures and section order editable in the admin.

Current state, measured:

```
57 pages · 401 sections · 7,085 nodes
2,380 text fields · 391 images · 478 links, all editable
theme:    18 files, 284 KB, zero content
database: 605 KB of block content
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
never loaded at all, a preview that silently 414'd.

Before you say a task is done, run the tests in §5 and paste the output.

---

## 3. Where things are

```
*.html                 57 static reference pages — the source of truth
assets/css/            8 stylesheets. Load order is load-bearing (see §6)
assets/js/             6 scroll/behaviour modules
assets/images/         330 files    assets/uploads/  64 blog images
theme/                 the WordPress theme
  inc/sections.php       the ONLY file that emits section markup
  inc/blocks.php         the one block type, and what may sit beside it
  inc/enqueue.php        stylesheets and scripts
  inc/nav.php            mega menu and footer menus
  inc/setup.php          theme supports, favicon, menu wiring
  inc/cleanup.php        what WordPress adds that this design does not want
  blocks/section/        block.json + the editor's source
  build/                 compiled editor JS — committed on purpose
  theme.json             settings only, no styles. It is what keeps wp-admin's
                         CSS out of the editor canvas and makes a section full
                         width there. Adding `styles` would change the front end
  archive.php            category, tag, author and date archives
  languages/osous.pot    every string, for translators — `wp i18n make-pot`
  readme.txt             what somebody installing the theme needs to know
_tools/                 every script and harness. Never shipped in the theme
_migration/             generated WP-CLI scripts (gitignored)
dist/                   the handoff package (gitignored, rebuilt on demand)
docker-compose.yml      WordPress + MariaDB + phpMyAdmin + WP-CLI
phpcs.xml.dist          the WordPress Coding Standards, as this theme runs them (§5.7)
composer.json           what installs them; vendor/ is gitignored
.editorconfig           tabs, as WordPress core has it
```

### The data model

A page is an ordered list of **sections**; a section is a tree of **typed nodes**. Fifteen
types, and `_tools/extract.py` refuses to parse anything it cannot express in them — which
is what makes it impossible for a page to lose content quietly.

| type | what it is | fields |
|---|---|---|
| `box` | a wrapper element with children | `c`, `k`, `g` |
| `txt` | an element whose content is inline only | `c`, `x` |
| `h` | h1–h6 | `g`, `c`, `x` |
| `p` | a paragraph | `c`, `x` |
| `rich` | prose the editor owns whole | `c`, `x` |
| `a` | a link | `href`, `c`, `x` or `k` |
| `btn` | a button | `c`, `x` or `k` |
| `img` | an image | `src`, `srcset`, `sizes`, `alt`, `c` |
| `icon` | the one icon in the design | `n` |
| `list` / `li` | a styled ul/ol and its items | `g`, `c`, `k` |
| `form` / `input` / `textarea` / `br` | the contact form | various |

`c` = class, `k` = children, `x` = inline HTML, `g` = tag name.

Each top-level section is one `osous/section` block. Its `nodes` attribute holds the tree;
its `render_callback` is `osous_render_nodes()`; its editor walks the tree and offers a
control per editable leaf.

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

The repository is mounted at `/static` inside the container so the harnesses can measure
both versions from one origin. That mount is a development convenience and has no place on
a server.

```bash
./_tools/env.sh save <name>      # snapshot the database before experimenting
./_tools/env.sh restore <name>
./_tools/env.sh rebuild          # wipe it and build it again from this repo
./_tools/env.sh package          # dist/ — the zip, the content export, the notes
```

Rebuilding the content after an editor experiment:

```bash
python _tools/gen_blocks.py
docker compose --profile tools run --rm cli wp eval-file /migration/blocks.php
```

Editor JavaScript, after any change under `theme/blocks/`:

```bash
npm install && npm run build
```

The coding standards, before every commit (§5.7):

```bash
npm run lint              # JavaScript: the WordPress ESLint rules, over the editor and assets/js
./_tools/env.sh phpcs     # PHP: the WordPress Coding Standards over theme/. phpcbf fixes what it can
```

---

## 5. The tests

Run all of them before claiming anything is done. None takes longer than ten minutes.

### 5.1 Markup — `_tools/verify_wp.py`

```bash
python _tools/verify_wp.py            # all 57 pages
python _tools/verify_wp.py about      # one page
```

Fetches each page from the local WordPress, maps its URLs back to static paths, and
compares `<main>` element by element: tag, every attribute the design uses (`class`,
`href`, `src`, `srcset`, `sizes`, `alt`, `id`, `role`, `aria-*`), and every character of
text. Reports the first three divergences with a path to each.

**Passing looks like:** `0 of 57 pages differ`

### 5.2 Layout — `_tools/geometry.html`

```bash
node _tools/run_harness.js "geometry.html?slugs=about,index"    # headless; exit 1 on any difference
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

Results land in `window.RESULT`. The default 1440x900 matters: the page header is `90vh`,
so a different height silently changes every offset.

**Passing looks like:** `done at 1440x900 — 0 of 57 pages differ`

**Also run it at 479x900.** Both viewports were clean at the last check.

### 5.3 Scroll behaviour — `_tools/pinned-list.html`

```bash
node _tools/run_harness.js "pinned-list.html?slugs=siem-soc-solutions-iraq"    # headless; exit 1 if any list is wrong
```

```
http://localhost:8080/static/_tools/pinned-list.html?slugs=<the 45 pages with a pinned list>
...&base=/static/      check the static reference instead
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
paths are at the top of the file.

**Passing looks like:** zero missing rules, zero duplicates, and two intentionally kept
classes (`is-current`, `justify-end`).

### 5.7 PHP, the build, and the coding standards

```bash
docker compose exec wordpress sh -c 'cd /var/www/html/wp-content/themes/osous && for f in $(find . -name "*.php"); do php -l "$f"; done' | grep -v "No syntax errors"
docker compose exec wordpress cat /var/www/html/wp-content/debug.log   # should not exist
npm run build
npm run lint              # 0 problems
./_tools/env.sh phpcs     # nothing printed, exit 0 — over all 44 files
```

The last two are the WordPress Coding Standards — the ones WordPress publishes at
developer.wordpress.org/coding-standards — run by the tools WordPress runs them with:
`wp-scripts lint-js` for JavaScript, and PHP_CodeSniffer with the `WordPress` ruleset plus
`PHPCompatibilityWP` (`phpcs.xml.dist`) for PHP. Both come back clean, and both have to
before a commit.

They were first run on 2026-09-14. PHP: 54 findings in 30 files — 24 were one missing
`@package` tag repeated through the generated patterns, 22 were whitespace and alignment
that `phpcbf` fixed by itself, and 8 were real: a comparison the wrong way round, a
parameter named `$list`, a comment without its blank line, and five echoes of the theme's
own output helpers that the sniff could not know were safe. JavaScript: 85, of which 78
were Prettier formatting and the rest missing types in JSDoc.

**The helpers are declared, not ignored.** `osous_attrs()`, `osous_class()`, `osous_inline()`,
`osous_address()`, `osous_aria_current()`, `osous_srcset()` and `osous_post_thumb()` each escape
everything they emit and return markup that is safe to print. `phpcs.xml.dist` names them in
`customAutoEscapedFunctions`, which is the mechanism WPCS provides for exactly this. The
24 `phpcs:ignore` comments that used to sit on those echoes are gone, so the sniff now
checks those lines instead of skipping them. The rule this leaves: an echo the sniff flags
is fixed by escaping it, or by routing it through one of those helpers — never by a new
ignore — and a helper joins that list only if it escapes on every path.

### 5.8 The editor canvas — `_tools/verify_editor.js`

```bash
node _tools/verify_editor.js                    # six pages, every kind of section
node _tools/verify_editor.js about contact      # just these
node _tools/verify_editor.js --headed           # watch it happen
```

Compares what the editor's canvas paints against what the page paints: every element of
every section, by tag, class and 28 computed properties, at the width a section actually
gets in the canvas. It logs in by handing the browser a cookie WP-CLI mints, so there is
nothing to configure and no password anywhere.

It exists because nothing else here looks at the editor, and the editor is where the site
gets edited. Somebody choosing a background or dropping in a section is choosing by eye,
and until this check was written the canvas was laying every section out at Gutenberg's
840px content width while the design's media queries read the 1440px canvas — a desktop
layout in a tablet-width box, passing every other test in this file.

It drives real pages rather than framing them, because the block editor cannot be framed
at all: see the hazard below.

Three kinds of difference are not faults and are handled by name rather than by loosening
the comparison. `RUNTIME_CLASSES` are classes a script decides — ignored when elements are
matched up. `INTENTIONAL` are the subtrees `editor.css` reshapes because GSAP does not run
in the canvas. `RUNTIME_STATES` is the narrow case that also repaints: only `active`, and
where the two sides disagree about it that element **and its subtree** have their styles
left alone, because `.list-item.active` sets `color` and everything under it inherits.
The count is printed as `(3 mid-scroll)`, never silent — if that number is ever large,
something has been excused that should not have been.

### 5.9 The editor's choices — `_tools/verify_presets.py`

```bash
python _tools/verify_presets.py
```

Every option in the Appearance panel writes a class onto the section, and every one of
those is a promise that the design has a rule for it. This reads `GRIDS`, `BACKGROUNDS`
and `SPACING` out of `section.js` -- rather than restating them, so the two cannot drift
-- and checks each against every selector in `assets/css`. A preset of two classes has to
be carried by one selector holding both: `.section.overflow-hidden.bg-3` paints nothing
if either half is missing.

Nothing else catches this. The other checks read the 57 pages as they are, and an option
nobody has picked yet is on none of them.

### 5.10 Is everything editable? — `_tools/verify_coverage.py`

```bash
python _tools/verify_coverage.py
python _tools/verify_coverage.py --list     # every gap, page by page
```

Walks every node of every section of every page **as WordPress holds it** and sorts each
one into three piles: something a control reaches, something with nothing to edit, and
something a person would want to change that nothing reaches. The third pile is the point,
and it has to stay empty.

It is easy to open the editor, see fields, and believe a page is editable. A heading
nobody can reach looks exactly like a heading somebody can. The only way to know is to
count what is on the pages and check each thing against the controls that exist — 7,131
nodes, 401 sections, 57 pages.

The rules come out of `section.js`: TEXT_TYPES, GROUPS, the four preset lists, the regex
`collectPanels()` uses, and which node types get a placeholder. Restating any of them here
would mean the audit could pass while the editor disagreed with it.

Everything in the middle pile is there by name and for a reason, never swept in:
`painted layer` is the empty `txt` nodes the design uses as a border or a fill,
`the one icon` is all 57 icons — `osous_icon()` draws `arrow` and nothing else, so there
is no second one to choose — `card effect` is `.card-4.bg-blur`, the frosted pane that is
what that card *is* rather than a colour anybody picks, and `carousel arrow` is the two
Swiper buttons. If any of those ever gains words, it moves to the third pile by itself.

### 5.11 The editor, by hand

The part no harness can do. Log in, open a page, and confirm:

- every block is `osous/section` and none is invalid
- every preview request returns 200 (watch the network panel, not the page — a failed
  preview looks exactly like one that has not loaded yet)
- the console has no errors
- changing a field and saving changes the front end **and nothing else**:

```js
// in the editor console
wp.data.select('core/block-editor').getBlocks().map(b => [b.name, b.isValid, b.attributes.label])
```

---

## 6. Rules

**The standards run before a commit, and an ignore is not a fix.** `npm run lint` and
`./_tools/env.sh phpcs` are both clean (§5.7). The theme carries two `phpcs:ignore` comments,
both for the Google Fonts URL having no version, and each says why on the same line after
`--`. That is the shape any new one must have, and the sniff being wrong is the only reason.

**Never edit `assets/css/*` or `assets/js/*` to make one page look right.** They are
verified against the original site. A page that renders wrong has wrong *data*. The two
exceptions in this project's history were both genuine design defects affecting every page,
and both were measured before and after across all 57.

**`inc/sections.php` is the only file that may emit section markup.** Everything else
passes data to it. A second renderer means two things to keep correct.

**Stylesheet order is load-bearing** and enforced by the dependency chain in
`inc/enqueue.php`: tokens · base · layout · components · navigation · sections · utilities ·
animations. `utilities.css` must stay second-to-last, `animations.css` last. Do not
"simplify" it into a loop over a directory listing.

**Media queries within a file run 991 → 767 → 479.** A tool that reorders them breaks
mobile in a way desktop testing never catches. This has happened here once.

**Never hand-edit generated files.** `_migration/*.php` comes from `_tools/gen_*.py`. Change
the generator.

**Do not re-add `webflow.js`.** Every animation is reimplemented in the six modules; the
IX2 runtime would double-bind the scroll triggers.

**No per-page templates.** A page that needs something new needs a new section, not a new
template — otherwise the 57-page problem comes straight back.

**Escape at the point of output** — `esc_html`, `esc_attr`, `esc_url`, `wp_kses`. The
allow-list in `osous_inline_html()` is deliberately short and matches exactly what
`extract.py` can produce.

**Three divergences from the live site are deliberate** and must survive. They are
documented in the README: the split-section script that throws on live, the pinned list
measuring before images load, and three nav links that depend on server redirects.

---

## 7. Hazards

Every one of these has already cost time here.

**`active` is in the markup and the script takes it away.** The reference page was
captured with the first item of a pinned list highlighted, so `list-item active` is what
every copy of that section carries. On the live page ScrollTrigger toggles the class from
`top center` to `bottom center`, which means at rest **no** item has it — GSAP removes the
one the markup shipped. The editor, which cannot scroll, still shows it. Neither side is
wrong, and anything comparing the two has to know: see `RUNTIME_STATES`.

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
untouched: `geometry.html` says 0 of 6 before and after.

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

**A tab that is not on screen cannot run the harnesses.** Chrome pauses
`requestAnimationFrame` in a hidden document; ScrollTrigger does its work on rAF and
IntersectionObserver does not fire, so `pinned-list.html` in a background tab — or in the
desktop app's browser pane while the pane is hidden — says every pinned list "never pinned"
and every reveal is still hidden, about code that is fine. It said exactly that twice on
2026-09-14, in two different browsers, and `document.visibilityState` read `hidden` both
times. `node _tools/run_harness.js` runs a harness under headless Chrome, which reports
itself visible and runs rAF; both verdicts in §5.2 and §5.3 were obtained that way.

**Two different things get called "WordPress-standard".** The WordPress Coding Standards
are what WordPress publishes and what `phpcs.xml.dist` runs: prefixed functions, tabs, Yoda,
escaping on output, `__()` with a text domain. Roots' Sage is the other thing people mean:
Blade templates, an `app/` namespace autoloaded by Composer, Acorn (Laravel) at runtime, a
Vite build, `vendor/` deployed inside the theme, and a web-server rule so `.blade.php` files
are not served as text. It is a different architecture with its own style — PSR, spaces, no
Yoda — that the WordPress sniffs would fail, and a Sage theme is not a zip somebody unpacks
and activates. This theme is written to the first. Moving it to the second is a rewrite of
the theme's structure, not a cleanup of its code.

**`docker pull` cut off mid-layer, twice, right after Docker Desktop came up.** `short read:
expected N bytes` and then `lookup registry-1.docker.io: no such host`, while the host's own
network was fine throughout. The WP-CLI image already on disk has PHP 8.3 and reaches
Packagist, so `_tools/env.sh phpcs` installs the standards through it with a `composer.phar`
the host fetches and checks against the published hash — no new image.

**`wp-scripts lint-js --fix <path>` fixed more than the path.** Given one file, it rewrote
every JavaScript file under `assets/js/` and `_tools/` as well. `npm run lint:fix` names the
two places it may touch; read `git status` afterwards regardless.

**A clean `phpcs -q` prints nothing.** Not "0 errors" — nothing at all. Read the exit code,
or drop `-q` and read the summary.

**`--` inside an XML comment is not a comment.** `phpcs.xml.dist` is XML, and a double
hyphen inside `<!-- -->` makes the whole ruleset invalid; phpcs reports it only as "Ruleset
is not valid".

**`wp_update_post()` unslashes what it is given.** `serialize_blocks()` escapes every `&`,
`<`, `>`, `'` and `"` in the block JSON as a `\u` sequence so the HTML comment stays a
comment. Pass that through unslashed and every backslash is eaten, and the site renders
`u0026` in the middle of its headings. 51 of 57 pages. Always `wp_update_post( wp_slash(
... ) )`.

**Git Bash rewrites container paths.** `/migration/x.sql` becomes `C:/Program
Files/Git/migration/x.sql` inside the container, and `wp rewrite structure '/%postname%/'`
silently stored `/C:/Program Files/Git/%postname%/` for weeks. Prefix every WP-CLI call
whose argument starts with a slash: `MSYS_NO_PATHCONV=1 docker compose ...`.

**`ServerSideRender` puts attributes in the query string.** A section's tree is kilobytes
of JSON, Apache answers 414, and the preview never arrives — silently. Use
`httpMethod="POST"`.

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
perfectly and renders nothing. Package with `_tools/package.py`, which also checks that all
353 referenced images are in the archive.

**Replacing an image must clear `srcset` and `sizes`.** They describe the file being
replaced. Leaving them serves the old picture at most widths.

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

---

## 8. What is done

| | |
|---|---|
| Phase 0 | Local environment — Docker, WP-CLI, phpMyAdmin |
| Phase 1 | Theme skeleton, real navbar and footer menus, asset pipeline |
| Phase 2 | All 57 pages rendering from section data, verified |
| Phase 3 | Content in the database as blocks; words, images and section order editable |
| Step 1 | A section library — 24 patterns, 18 in the inserter. A new page can be built |
| Step 2 | Site settings — the footer's details in Appearance → Customize → Site details |
| Step 3 | Repeatable items — cards, columns and list items can be added, moved, removed |
| Step 4 | Style choices — background, spacing and the bottom margin, per section |
| Phase 4, part | The blog is a real loop; 404, search and the search form exist |

Tagged: `phase-2`, `phase-3`. `git tag -l -n5` shows what each guarantees.

At `phase-3`, all three harnesses report zero differences.

---

## 9. What is left

The site is *editable*. It is not yet *buildable* — nobody can make a new page, add a card,
or change the footer's phone number. That is the work below, in the order it earns its keep.

### ~~Step 1 — Section library~~ ✅ done

`theme/patterns/` holds 24 patterns generated by `_tools/gen_patterns.py`. Regenerate them
after changing the reference pages. Six are registered but kept out of the inserter: the
home-page one-offs, the blog index and the contact form.

### ~~Step 2 — Site settings~~ ✅ done

`theme/inc/settings.php`. One table, `osous_settings()`, drives the Customizer panel and
the defaults both. Read a value with `osous_setting( 'key' )`.

### Step 1 — Section library (~2 days) — superseded, kept for the reasoning

**Problem.** `theme/blocks/section/block.json` sets `supports.inserter: false`, and
`osous_allowed_blocks()` permits only `osous/section` on pages. A new page therefore has
**nothing to insert**. That is a defect, not a design choice: duplication only works inside
a page that already has sections.

**Do.** Register each of the ~20 archetypes as a block pattern (`register_block_pattern`),
each one an `osous/section` block pre-filled with a representative tree and placeholder
text. Generate them from the real pages — `_tools/gen_blocks.py` already labels every
section, so pick the cleanest instance of each:

```
Columns 84 · Page header 55 · Call to action 52 · Pinned list 45 · Section 33
Numbered cards 33 · Cards 32 · Rich text 17 · Highlight cards 16 · Photo cards 10
Intro panel 8 · Bullet list 5 · Split section 3 · Partner logos 2
Blog list 1 · Contact form 1 · Tick list 1 · Hero 1 · Card stack 1 · Industries carousel 1
```

`Section` is the label nothing else matched — 33 of them, and worth looking at before
building patterns, because some are probably archetypes the labelling missed.

Put them in a dedicated pattern category so the inserter shows this design's sections and
nothing else. Leave `inserter: false` on the block itself — the pattern is the way in.

**Passes when.** A new empty page can be built from the inserter alone; the result renders
correctly; `verify_wp.py` still reports zero on the existing 57.

### Step 2 — Site settings (~1 day) — superseded, kept for the reasoning

**Problem.** These exist only in `theme/footer.php` and `theme/functions.php` and cannot be
changed by anyone: two office addresses, four phone numbers, `info@osoustaqnia.com`, the
Facebook and LinkedIn URLs, the Google Maps embed, two ISO badges, and the WhatsApp number
in `OSOUS_WHATSAPP`.

**Do.** A Customizer panel (`customize_register`) with a section per group, or a small
options page. Replace the hardcoded values in `footer.php` and
`template-parts/chrome/whatsapp.php` with `get_theme_mod()` calls that fall back to today's
values, so nothing changes until somebody edits it.

**Passes when.** Every value above is editable in the admin, and `verify_wp.py` still
reports zero with the defaults untouched. Note that `verify_wp.py` only checks `<main>` —
compare the footer separately, the way §5.6 describes.

### ~~Step 3 — Repeatable items~~ ✅ done

Each repeated group in the sidebar has move, duplicate and remove. A copy is stripped of
the classes in `NOT_INHERITED` (`theme/blocks/section/section.js`): Webflow's grid
placements and the pinned list's `active`. Two cells both claiming `span-2` fold the grid,
and nobody would connect that to having pressed duplicate.

### Step 3 — Repeatable items (~2 days) — superseded, kept for the reasoning

**Problem.** A section's tree is fixed. You can edit four cards; you cannot make five, or
delete one, or reorder them.

**Do.** In the block editor, find repeatable containers (a `box` whose children share a
class: `card-1`, `card-2`, `card-4`, `card-5`, `feature-card`, `partner-card`,
`list-item`, `li`, `cell`) and offer **add** (deep-clone the last sibling, blank its text),
**remove**, and **reorder** in the sidebar panel that already groups them as `Card 1`,
`Card 2`, …

Watch the grid: some sections place children with `#w-node`-derived utility classes
(`span-2`, `col-right`, `media-first`, `row-tablet-3`). A cloned card inherits the class of
the one it was cloned from, which may be wrong in a new position. Test a clone at 1440 and
479.

**Passes when.** Adding, removing and reordering a card in each of the five card types
renders correctly at both viewports.

### ~~Step 4 — Style choices~~ ✅ done

`BACKGROUNDS` and `SPACING` in `theme/blocks/section/section.js`. Both lists come from
counting what the 401 section roots carry; extend them from that evidence, not from
imagination. The controls own only the classes they name — everything else on the root is
carried through, which is what keeps `header`, `list-scroll-section` and `split-section`
safe.

### ~~Step 6 — Widen the library~~ ✅ done

The archetypes were never the constraint; **one instance of each** was. `Cards` is on the
site at two, three and four across, `Columns` at two, three and four, `Numbered cards` the
same -- and the inserter offered one of each, so getting a four-across row meant inserting
a three and duplicating a card.

- **Columns**, in the Appearance panel: `GRIDS` in `section.js`, one control per grid in
  the section, `grid-1` to `grid-5`. It owns the count and nothing else — `grid-to-carousel`,
  `carousel-in-mobile` and the `gap-*` classes ride on the same nodes and are carried
  through. That one control is what turns 21 patterns into the layouts the site actually
  has.
- **Two more backgrounds and one more spacing**, from the same count of the 401 roots that
  produced the first six. `bg-img-15` is deliberately still out: it paints one page's own
  photograph, and it is content rather than a backdrop.
- **Panel**, one control per `.container`, `.container-full`, `.modal` or `.card`: `SURFACES` in
  `section.js`. Count the 401 sections and 43 carry a colour on the root while **144 carry
  one on a panel inside**, 133 of those on a container — so a Background control that only
  reached the root reached under a quarter of the colours on the site. That is how a grey
  band could sit in plain sight with the panel insisting the section was white. Both were
  true: the section is white, the panel on it is grey. The patterned backgrounds are not
  offered here, because every one of their rules is written `.section.bg-img-…` and would
  do nothing on a panel.
- **Hero, Card stack and Industries carousel** are in the inserter now. They were held out
  as one-offs on the reasoning that a shape appearing once belongs to the page it is on.
  That was the wrong test: all three are ordinary markup, and the scroll modules that drive
  them are enqueued from the classes on the page rather than its slug, so they work
  wherever they land. 21 patterns listed, 3 still held back — `Blog list` and `Contact form`
  because WordPress itself replaces them, and `Section` because it is the catch-all.

- **Link text, and form placeholders.** `verify_coverage.py` found seven links carrying
  their own words -- most keep them in a `txt` child -- with a field for where they go and
  none for what they say, because `collectGroups()` chose between the two with an `else
  if`. And the six fields of the contact form had no field for their placeholder, which is
  the only copy that page shows before anybody types.

What is still not possible, and is the next real decision: inventing a layout the design
does not have. That needs core blocks bound to the design tokens through `theme.json`
`styles`, and it costs the guarantee that every page can be verified against the reference.

### Step 4 — Style choices (~2 days) — superseded, kept for the reasoning

**Problem.** No control over a section's background, spacing or alignment.

**Do.** Expose the design's own vocabulary as a small set of choices on the section block —
not free CSS, not a colour picker. From `tokens.css` and `sections.css`:

- background: `c-surface-white`, `c-surface-neutral`, `c-surface-primary`,
  `c-surface-secondary`, and the `bg-img-*` family
- vertical rhythm: `section`, `full`, `top-tight`, `p-y-0`, `p-b-0`, `m-b-0`
- alignment: `center`, `a-c`

Each maps to a class on the section's root node, so every combination is one the stylesheets
already define and has therefore already been verified.

**Passes when.** Each choice changes the rendered class and nothing else; the 57 existing
pages are untouched.

### Step 5 — Inline editing (~3–4 days, optional)

**Problem.** Text is edited in the sidebar rather than on the page.

**Do.** This is the real work, because the preview is server-rendered. Either replace
`ServerSideRender` with a client-side renderer that mirrors `inc/sections.php` — which means
two renderers to keep in step, and the reason it was avoided — or overlay `RichText` on the
rendered output and map edits back to node paths.

**Weigh it honestly before starting.** It is the biggest comfort gain and the largest risk
to the guarantee that the editor shows exactly what the page is.

### Phase 4 — Blog, forms, search, 404 — mostly done

Done: `home.php`, `single.php`, the pager, `404.php`, `search.php`, `searchform.php`,
`archive.php` (category, tag, author and date, sharing the blog grid), and twelve seeded
posts. `page_for_posts` is set now, so `/blog/` is the posts index. `languages/osous.pot`
holds all 176 strings, the editor's included — `wp_set_script_translations()` wires the
bundle to it — and `readme.txt` says what an installer needs to know.

Still to do, and it needs a decision:

**The contact form.** Contact Form 7 is installed and active. Wiring the form means
accepting what CF7 wraps around it, measured on this install:

```
div.wpcf7            the outer wrapper, with an id
div.screen-reader-response   + p[role=status] + ul
form.wpcf7-form      action="/#wpcf7-…", novalidate, data-status
fieldset.hidden-fields-container   + six hidden inputs
span.wpcf7-form-control-wrap       one around every control
div.wpcf7-response-output
```

Roughly seventeen elements the reference does not have, so `/contact/` stops matching
`contact.html` element for element. The design's own classes can be kept — CF7 takes
`class:input class:t-m-md` in the tag and adds them alongside its own — but the wrappers
cannot be removed.

`.input` is `display: block; width: 100%`, and CF7 puts an inline `span` around it.
**Measure whether that changes the layout before assuming either way.** If it does,
`.wpcf7-form-control-wrap { display: contents }` is a new rule for an element the reference
never had, so it cannot regress anything.

Then: a shortcode node type so the contact page's section can hold the form, a
`verify_contact.py` along the lines of `verify_blog.py`, and Turnstile, which needs keys
that only production has.

**Also still open:** the ~100 real posts. Only the first twelve were cloned, and their
bodies were not. `_tools/seed_posts.py` seeds titles, dates, slugs and pictures; the text
says so until a production import replaces it.

### Phase 5 — Production (~2 days)

Turn WP Rocket on and re-verify. Confirm Rank Math owns the `<title>`. Restore the
server-side redirects for `hikvision-iraq`, `motorola-iraq` and `endpoint-secuirty-iraq` so
the nav hrefs can go back to the live URLs. Re-run every harness against production.

**WP Rocket lies to measurement.** `content-visibility: auto` makes unscrolled sections
report `height: 0`, and its lazyload leaves images as `data:` placeholders. Neutralise both
before trusting a number; the recipe is in the README.

---

## 10. The trade-off to hold on to

There is a line between two ways of building this:

- **Locked** — every page is guaranteed to look like the design. Nobody can break it, and
  nobody can invent anything either.
- **Free**, as a general page builder is — anyone can do anything, including produce pages
  that have nothing to do with this design.

Everything above sits deliberately in the middle: real building blocks and real choices,
but every choice drawn from the design's own vocabulary, so the result stays coherent and
the measurements keep meaning something. That is why Step 4 offers "background: neutral"
and not a colour picker.

If a future brief asks for the free end of that line, say plainly what it costs: the
pixel-accuracy this whole project is built to guarantee stops being meaningful the moment
anyone can drag anything anywhere.

---

## 11. If something goes wrong

```bash
git tag -l -n5                        # the restore points, and what each guarantees
git checkout phase-3                  # the code as it was last verified
./_tools/env.sh list                  # database snapshots
./_tools/env.sh restore <name>
./_tools/env.sh rebuild               # wipe the database, build it from this repo
```

`rebuild` followed by `gen_blocks.py` + `wp eval-file /migration/blocks.php` recreates the
entire local site from this repository alone. Nothing depends on a snapshot surviving.
