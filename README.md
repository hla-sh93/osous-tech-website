# Osous Al Taqnia — static site

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
  inc/cleanup.php     what WordPress adds that this design does not want
  fixtures/           generated section data, one file per page
  template-parts/chrome/whatsapp.php
```

**A page is a tree of typed nodes.** `_tools/extract.py` reads each static page's `<main>`
and expresses it in fifteen node types — `box`, `txt`, `h`, `p`, `rich`, `a`, `btn`, `img`,
`icon`, `list`, `li`, `form`, `input`, `textarea`, `br`. It refuses to parse anything it
cannot express, so a page can never silently lose content. `inc/sections.php` renders that
tree, and is the only file in the theme that emits section markup.

```bash
python _tools/extract.py        # parse and report the vocabulary in use
python _tools/gen_fixtures.py   # write theme/fixtures/*.php
python _tools/seed.py           # write _migration/seed.php (pages and menus)
```

Fixtures are generated — never edit them by hand. Phase 3 replaces them with block content
in the database; the node shape and the renderer stay exactly as they are, which is the
reason for generating them rather than writing them.

Two things the theme deliberately does better than the static site:

- **The menus are real WordPress menus.** Reordering them in the admin changes the site.
  Live hardcodes them, and so does `*.html`.
- **Scripts follow the markup.** A page gets `list-scroll.js` because it contains
  `.list-scroll`, not because a slug appears in a list. The static site keeps that mapping
  by hand and it had already drifted on two pages, both since corrected.

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

3. **Redirects.** `hikvision-iraq` and `motorola-iraq` 301 to `avigilon-iraq` on live, and
   the nav's `endpoint-secuirty-iraq` (typo is in the source) 301s to
   `endpoint-security-iraq`. A static server cannot 301, so those nav links point at the
   destination directly. Restore the original hrefs once WordPress handles the redirects.

## Known gaps

- **The blog is page 1 only.** Its 15 links — 12 posts and pagination pages 2, 3 and 9 —
  are the only broken internal links in the site. Live has 9 listing pages (~100 posts).
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
