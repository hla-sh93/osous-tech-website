# Osous Al Taqnia — static site

> Picking this up to work on it? Read **[AGENTS.md](AGENTS.md)** first — the rules,
> the hazards, the tests that have to pass, and what is left to build.

A hand-built static clone of [osoustaqnia.com](https://www.osoustaqnia.com/), in plain
HTML, CSS and JavaScript. The live site is a Webflow export running inside WordPress and
served through WP Rocket; this rebuilds the same output from a small, readable stylesheet
set instead of Webflow's generated one. It is the base for the WordPress theme.

## Layout

```
*.html            57 pages, flat at the root (matching the live URL structure)
assets/css/       8 stylesheets, loaded in this order:
                  tokens · base · layout · components · navigation · sections · utilities · animations
assets/js/        navigation · reveal · overlapping-cards · industries-carousel
                  list-scroll · split-section
assets/images/    theme artwork
assets/uploads/   blog post images (mirrors WordPress's wp-content/uploads)
theme/            the WordPress theme built from all of the above
_tools/           build and verification scripts (not part of the site)
_migration/       generated WP-CLI scripts (not part of the site)
docker-compose.yml  local WordPress, MariaDB and phpMyAdmin
```

The static pages are the **reference**, not a stage that gets thrown away: every check in
this project compares the theme's output against them.

Stylesheet order matters: `utilities.css` must stay second-to-last so its single-purpose
classes win, and `animations.css` last. Media queries within a file run 991 → 767 → 479.

## Running it locally

Any static file server:

```bash
python -m http.server 5175
```

`.claude/launch.json` defines the same thing for the editor's preview pane.

Note that browsers cache the stylesheets aggressively on `localhost`; if a CSS edit does
not appear, serve on a different port rather than reloading.

## The WordPress theme

`theme/` is a classic theme that renders these 57 pages from data instead of from 57 files
of markup. The plan and its reasoning are in [docs/wordpress-theme-plan.md](docs/wordpress-theme-plan.md);
the local environment is in [docs/local-setup.md](docs/local-setup.md).

```
theme/
  header.php  footer.php  page.php  index.php
  inc/setup.php       theme supports, menus, favicon, resource hints
  inc/enqueue.php     the eight stylesheets and the scroll modules
  inc/nav.php         the mega menu and footer menus, from real WordPress menus
  inc/sections.php    the section renderer
  inc/blocks.php      the one block, and what it is allowed to sit beside
  inc/cleanup.php     what WordPress adds that this design does not want
  blocks/section/     block.json and the editor's source
  build/              the editor's compiled JavaScript (committed)
  template-parts/chrome/whatsapp.php
```

**A page is a tree of typed nodes.** `_tools/extract.py` reads each static page's `<main>`
and expresses it in fifteen node types — `box`, `txt`, `h`, `p`, `rich`, `a`, `btn`, `img`,
`icon`, `list`, `li`, `form`, `input`, `textarea`, `br`. It refuses to parse anything it
cannot express, so a page can never silently lose content. `inc/sections.php` renders that
tree, and is the only file in the theme that emits section markup.

**A page is a list of blocks.** Each top-level section is one `osous/section` block whose
attribute is that tree. Its editor walks the tree and offers a field for every piece of
text and a picker for every image; its preview is the same PHP the front end runs. So the
words and pictures live in the database and are editable, while the shape stays in the
theme where it can be verified. Sections reorder by dragging, and a new one is made by
duplicating an existing one — an empty section has no shape, so there is nothing to insert.

```bash
python _tools/extract.py        # parse and report the vocabulary in use
python _tools/gen_blocks.py     # write _migration/blocks.php (the page content)
python _tools/seed.py           # write _migration/seed.php (pages and menus)
npm install && npm run build    # the editor's JavaScript, into theme/build/
```

Two ways back, if something breaks:

```bash
./_tools/env.sh save before-blocks     # snapshot the database before experimenting
./_tools/env.sh restore before-blocks
./_tools/env.sh rebuild                # wipe it and build it again from this repo
git tag -l -n1                         # the restore points that travel with the project
```

`theme/build/` is committed on purpose: a theme that needs `npm run build` before anyone
can edit a page is not a theme.

Three things the theme deliberately does better than the static site:

- **The menus are real WordPress menus.** Reordering them in the admin changes the site.
  Live hardcodes them, and so does `*.html`.
- **Scripts follow the markup.** A page gets `list-scroll.js` because it contains
  `.list-scroll`, not because a slug appears in a list. The static site keeps that mapping
  by hand and it had already drifted on two pages, both since corrected.
- **The pages are editable.** Words, pictures and the order of the sections, from the
  admin. Live keeps each page's whole markup in one raw HTML field, which nobody can
  safely touch.

## Deliberate differences from the live site

Three bugs exist in production and are **fixed here on purpose**. Carry the fixes into the
WordPress theme rather than reverting them.

1. **`ict-solutions-iraq` — split section.** The live inline script throws
   `ReferenceError: img is not defined` (a commented-out `forEach` left its body live), so
   no ScrollTrigger is ever created. Because `.split-section` is `height: 75vh; overflow:
   hidden`, four of the five feature cards are unreachable on the live site.
   `assets/js/split-section.js` implements what the code was evidently meant to do. The
   page is therefore ~2500px taller than live — that is GSAP's pin spacer, not a defect.

2. **Pinned "why choose us" list** (services pages). Live measures its ScrollTrigger
   positions before the lazy images land, so the pin fires against empty space.
   `assets/js/list-scroll.js` adds a refresh on `load`. No height difference.

   Making the split section work brought a second-order problem with it on
   `next-generation-firewall-iraq`, the one page that has both. ScrollTrigger measures
   pins in the order they are created, and the split section sits above the pinned list
   but its module loads second — so the list's pin start was computed against a page
   2000px shorter than it was about to become, and the heading pinned early, on top of
   the section before it. Every pinned trigger now carries a `refreshPriority` taken
   from its position in the document, which makes the order independent of which script
   loads first. `_tools/geometry.html` checks for it on every page from now on.

3. **Redirects.** `hikvision-iraq` and `motorola-iraq` 301 to `avigilon-iraq` on live, and
   the nav's `endpoint-secuirty-iraq` (typo is in the source) 301s to
   `endpoint-security-iraq`. A static server cannot 301, so those nav links point at the
   destination directly. Restore the original hrefs once WordPress handles the redirects.

## Known gaps

- **The blog is page 1 only.** Its 15 links — 12 posts and pagination pages 2, 3 and 9 —
  are the only broken internal links in the site. Live has 9 listing pages (~100 posts).
- **One sentence stops mid-air.** On `next-generation-firewall-iraq`, "Osous Al Taqnia
  delivers unmatched enterprise security excellence through" runs straight into the list
  below it with no colon and no ending. It reads as a lead-in that lost its punctuation.
  Cloned as-is, because it is the client's copy; it is the only truncated passage on the
  site, and `_tools/extract.py`'s output makes that checkable.
- **`privacy-policy` and `terms-conditions` are unfinished upstream**, and cloned as-is:
  the body text stops mid-sentence, a Webflow lorem-ipsum style guide follows, and both
  carry the title "privacy policy". They need real content before launch.

## Verification

Nothing here was checked by eye. Two questions get asked separately, because they fail
differently: *is the markup the same?* and *does it lay out the same?*

### The theme against these pages

```bash
python _tools/verify_wp.py                 # all 57 pages
python _tools/verify_wp.py about index     # or a few
```

Fetches each page from the local WordPress, maps its URLs back to static paths, and
compares `<main>` element by element: tag, every attribute the design uses — `class`,
`href`, `src`, `srcset`, `sizes`, `alt`, `id`, `role`, `aria-*` — and every character of
text. It reports the first three divergences with the path to each.

Layout is a separate question, because a stylesheet that failed to load or a scroll module
that never bound leaves the markup untouched and the page wrong. `_tools/geometry.html`
loads both versions of a page in 1440x900 frames, waits for fonts and images, and compares
the offset and height of every section plus the total page height:

```
http://localhost:8080/static/_tools/geometry.html?slugs=about,index,ict-solutions-iraq
```

Add `&w=479&h=900` for another viewport; it defaults to 1440x900, and the default matters
because the page header is `90vh`. It has to run from the WordPress origin — a frame from
another origin cannot be measured — which is why `docker-compose.yml` mounts this repo at
`/static`. Results also land in `window.RESULT`. Omit `slugs` at your peril: it measures
nothing without them.

Both currently report zero differences across all 57 pages.

It also checks each side on its own for a **pinned section that engages too early** — a
fault both copies can share, which no comparison between them would ever find.

The pinned list gets a check of its own, because 45 pages have one and "it looks right on
this page" does not transfer to the other 44:

```
http://localhost:8080/static/_tools/pinned-list.html?slugs=acronis-iraq,vmware-iraq
...&base=/static/          check the static reference instead
```

`siem-soc-solutions-iraq` is the page whose behaviour is right, so its behaviour is the
specification: the pin starts when the heading reaches the middle of the viewport, ends
when the last item leaves it, holds the heading exactly there in between, keeps one item
highlighted, and is never held while its own section is off screen. It scrolls each page
through the range in 25px steps and reports where the pin really engages, which is not
always where it says it will.

Getting a stable reading took more care than the comparison itself. It waits for fonts,
images, Swiper's first transform and GSAP's tweens, then samples until three consecutive
readings agree — two is not enough, because a library that is merely slow to start looks
perfectly stable until it starts. It also re-fetches every shared asset with
`cache: 'reload'` first: the static pages ask for `assets/js/*.js` with no version query,
so a stale copy in the browser cache reads exactly like a difference between the two sides.

Two things it deliberately does not compare, because the browser's answer is not stable:
the transform of anything inside a running animation (the logo marquee), and `margin-left`
/ `margin-right`, which Chrome resolves from `auto` to a used value only when layout
happens to be clean. Each element's measured left edge and width go into the digest
instead, which is what those margins were being read for.

### These pages against the live site

```bash
python _tools/verify.py "<dir>/*.html"   # text, images, links and element structure
python _tools/audit.py                   # completeness and dead code in the CSS
```

`audit.py` reports source rules that would apply to this markup but have no equivalent
here, classes defined but never used, and duplicate selectors. It currently reports zero
missing rules, zero duplicates, and two intentionally-kept classes (`is-current`, the
active-nav state WordPress should emit; `justify-end`, completing the placement set).

Both scripts need the live theme CSS (`main.css`, `webflow.css`, `normalize.css` from
`wp-content/themes/osous-taqnia/assets/css/`) and copies of the live pages alongside them;
paths at the top of each script point at this directory and will need adjusting if the
project moves.

Geometry was compared at **1440x900** — the page header is `90vh`, so any other viewport
silently changes every offset. When measuring a live page, neutralise WP Rocket first:
set `contentVisibility: visible` on `[data-wpr-lazyrender]` and promote every `data-lazy-*`
attribute to a real one, then confirm no image `src` still starts with `data:`. Skipping
that produces convincing but wrong numbers.
