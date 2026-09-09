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
_tools/           build and verification scripts (not part of the site)
```

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

Everything was checked against the live pages rather than by eye.

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
