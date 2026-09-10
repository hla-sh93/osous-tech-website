# Converting the static site to a WordPress theme

A plan for turning this repository into `osous-taqnia` — a classic WordPress theme that
renders byte-for-byte what the static site renders today, with the content editable.

Written against the [Theme Handbook](https://developer.wordpress.org/themes/).

**Starting point.** The design originated in Webflow, but osoustaqnia.com is already served
by WordPress — `<meta name="generator" content="WordPress 7.1">`, a `wp-theme-osous-taqnia`
body class, assets under `wp-content/themes/`, and Rank Math, Contact Form 7 and WP Rocket
installed. The Webflow export was turned into a WordPress theme, and the 57 pages and ~100
blog posts already live in that database.

**Target.** A brand-new theme, `osous`, activated on that same installation. The existing
`osous-taqnia` theme is left in place and untouched, so reverting is a single click.
Permalinks, Rank Math settings and every blog post carry over untouched.

---

## 1. Decisions, and why

### Classic theme, not a block theme

A block theme is `templates/*.html` plus `theme.json`, and WordPress drives the markup and
the global styles. This design is a Webflow export with hand-tuned CSS, GSAP-driven pinning
and a precise cascade — `theme.json` would inject its own layout and typography rules and we
would spend the project fighting them.

A classic theme gives complete control of the markup, which is the whole requirement. This
also matches the live theme, so anything learned transfers.

The theme therefore needs, at minimum, `style.css` with a header comment and `index.php`
([Required Theme Files](https://developer.wordpress.org/themes/releasing-your-theme/required-theme-files/)).

### The content architecture is the real decision

The current live theme puts **every page's full Webflow markup into the post content** —
every page is `page-template-default` with a `page-id`, so the markup lives in the database
and is edited as raw HTML. It works, but the client cannot safely edit anything, the DB
carries ~40KB of markup per page, and none of the DRY work in this repo survives.

Counting what is actually on the pages settles the alternative:

```
400 sections across 57 pages  ->  ~15 recurring archetypes
  text pair            79 uses / 45 pages      stacked cards        17 / 16
  card grid            72 / 45                 numbered steps        9 /  9
  page header          55 / 55                 partner grid          3 /  3
  CTA panel            52 / 52                 rich text             3 /  3
  pinned list scroll   45 / 45                 split section         3 /  3
  intro panel          41 / 32                 one-offs (home etc)  ~6 pages
```

The top seven archetypes cover **89% of all sections**. That is a section library, not 57
bespoke pages. So:

> **Each archetype becomes one PHP partial in `template-parts/sections/`. A page is an
> ordered list of sections with their content. The markup lives in the theme; the words and
> images live in the database.**

That list is stored as **native WordPress blocks**. Each archetype is registered as a
*dynamic* block: attributes hold the text and image IDs, and a PHP `render_callback` includes
the very same partial. The markup never leaves the theme.

This was chosen over ACF Pro because the free ACF tier does not include Flexible Content or
Repeater, and because native blocks turn out to be the better answer anyway — no third-party
dependency, drag-to-reorder sections, and a live preview while editing. The cost is a Node
build step for the editor UI: dynamic blocks render in PHP but their editor interface is
JavaScript ([Creating dynamic
blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/)).
Budget 2–3 extra days against the ACF route.

Client-facing result: the editor's inserter lists "Page header", "Card grid", "Pinned list"
and so on; the client fills text and picks images, and reorders by dragging. They never see
HTML — which is the failure of the current site, where each page is one raw HTML blob.

### Chrome becomes real WordPress

The live nav and footer are hardcoded HTML — zero `menu-item` classes anywhere. That is a
downgrade we should not carry over. The header mega-menu and the four footer columns become
registered menus (`register_nav_menus`) rendered through custom `Walker_Nav_Menu` subclasses
that emit this project's exact markup
([Navigation Menus](https://developer.wordpress.org/themes/functionality/navigation-menus/)).

---

## 2. Target structure

```
osous-taqnia/
  style.css                     header comment only — real CSS stays in assets/
  index.php                     required fallback
  functions.php                 thin: requires everything in inc/
  front-page.php                home
  page.php                      every static page (sections loop)
  home.php                      blog listing
  single.php                    blog post
  archive.php  search.php  404.php
  header.php   footer.php
  searchform.php
  screenshot.png                1200x900

  inc/
    setup.php                   add_theme_support, register_nav_menus, favicon, hints
    enqueue.php                 all wp_enqueue_style / wp_enqueue_script
    sections.php                the section renderer
    nav.php                     mega menu and footer menus
    blocks.php                  registers every dynamic block from blocks/*/block.json
    cleanup.php                 dequeue emoji / block-library, srcset control

  fixtures/                     generated section data, one file per page (Phase 2 only)

  blocks/                       one folder per archetype
    page-header/                block.json + edit.js -> render_callback into sections.php
    card-grid/  text-pair/  cta-panel/  pinned-list/  ...
  build/                        compiled editor JS (wp-scripts output, committed)

  template-parts/
    blog/
      card.php  pagination.php
    chrome/
      whatsapp.php

  assets/
    css/                        unchanged, 8 files, same load order
    js/                         unchanged, 6 modules
    images/                     theme artwork only
```

`assets/` moves across **unchanged**. That is the point of the work already done — the CSS
and JS are already correct and verified, and the conversion must not touch them.

---

## 3. Phases

Each phase ends with a check that either passes or does not. No phase starts before the
previous one passes.

### Phase 0 — Environment (half a day)

Pull a copy of production down to a local WordPress (LocalWP or `wp-env`) — database and
uploads. That gives the real 57 pages, the ~100 posts, the Rank Math settings and the CF7
forms to develop against, with nothing at risk.

**Leave WP Rocket off** locally until Phase 5. It was the single largest source of false
readings during the static build and makes early comparisons meaningless.

Nothing is imported and nothing is created: the content already exists. The new theme is
developed alongside the old one and only activated locally.

### Phase 1 — Theme skeleton and chrome (2–3 days)

`style.css` header, `functions.php`, `inc/setup.php`, `inc/enqueue.php`, `header.php`,
`footer.php`, `index.php`, `page.php`.

Enqueue with an explicit dependency chain so the cascade order cannot drift — this order is
load-bearing and a wrong order silently breaks the design:

```php
$v = wp_get_theme()->get( 'Version' );
$base = get_theme_file_uri( 'assets/css/' );

wp_enqueue_style( 'osous-tokens',     $base . 'tokens.css',     [], $v );
wp_enqueue_style( 'osous-base',       $base . 'base.css',       [ 'osous-tokens' ], $v );
wp_enqueue_style( 'osous-layout',     $base . 'layout.css',     [ 'osous-base' ], $v );
wp_enqueue_style( 'osous-components', $base . 'components.css', [ 'osous-layout' ], $v );
wp_enqueue_style( 'osous-navigation', $base . 'navigation.css', [ 'osous-components' ], $v );
wp_enqueue_style( 'osous-sections',   $base . 'sections.css',   [ 'osous-navigation' ], $v );
wp_enqueue_style( 'osous-utilities',  $base . 'utilities.css',  [ 'osous-sections' ], $v );
wp_enqueue_style( 'osous-animations', $base . 'animations.css', [ 'osous-utilities' ], $v );
```

Scripts the same way, `[ 'strategy' => 'defer', 'in_footer' => true ]`, with GSAP and
ScrollTrigger registered as dependencies of `list-scroll` and `split-section`, and each
module enqueued only where its markup exists.

Menus: build the two walkers, create the menus in the admin, and confirm the mega-menu and
footer render identically to the static HTML.

**Passes when:** header, footer, mega menu, mobile drawer and WhatsApp float are
byte-identical to the static site on a page with no body content.

### Phase 2 — Section rendering ✅ done

**What changed from the plan.** The plan said one PHP partial per archetype. Counting the
markup rather than the archetypes said otherwise: the 401 sections reduce to 35 role
sequences, but the *inside* of those sections spreads over 171 distinct shapes, because
every card type has optional parts — an outline, a background, an icon, a heading, a
button. Fifteen rigid partials would each have grown a dozen flags, and the fifteenth would
still not have fitted.

So the unit is one level smaller. `theme/inc/sections.php` renders a tree of fifteen typed
nodes, and an archetype is a *shape* of that tree rather than a file:

```
box   a wrapper element with children       txt   inline-only element
h     h1-h6                                 p     paragraph
rich  prose the editor owns whole           a     link
btn   button                                img   image
icon  the one icon in the design            list  styled ul/ol
li    list item                             form / input / textarea / br
```

This maps onto Phase 3 better than the original plan did, because it is the same shape
Gutenberg already has: a section block containing heading, grid and card blocks. The
`render_callback` for each one calls straight into this renderer.

**How the data got there.** `_tools/extract.py` parses each static page's `<main>` into that
vocabulary and **raises on anything it cannot express** — the one rule that makes the
approach safe, since the failure mode to fear is a page quietly losing content rather than
one loudly failing to build. `_tools/gen_fixtures.py` writes the result to
`theme/fixtures/<slug>.php`, 1.1 MB across 57 files.

That rule earned its keep twice. Treating `<a>` as an inline tag swallowed 40 images and
5 icons into text nodes, where `wp_kses` then stripped them — invisible in a spot check,
caught in seconds by a strict comparison. And a `<span>` used as a block element in the blog
pagination stopped the build rather than disappearing.

**Passed.** Both checks report zero differences across all 57 pages:

- `_tools/verify_wp.py` — every element, every attribute, every character of text.
- `_tools/geometry.html` — every section offset and height, and total page height, at
  1440x900 with fonts and images settled.

**Fixed along the way.** The theme never loaded Inter and Sora; the fonts are now enqueued
as a dependency of the first stylesheet. The favicon had no equivalent and is now printed
unless a Site Icon is set. Asset versions come from `filemtime()` while `WP_DEBUG` is on,
because a theme version that never moves means the browser serves yesterday's JavaScript.
Two static pages loaded the wrong scroll module — corrected in the reference, and
structurally impossible in the theme, which picks modules from the markup.

Repairing that last one exposed a real bug on `next-generation-firewall-iraq`, the only
page with two pinned sections. ScrollTrigger measures pins in creation order, so the
pinned list — below the split section but created first — had its start computed against
a page 2000px shorter than it was about to become, and its heading pinned early, over the
section above. Every pinned trigger now sets `refreshPriority` from its document position,
so script load order stops mattering, and `_tools/geometry.html` fails any page where a
pin engages more than a viewport before its own element arrives.

**Left for later, deliberately.** `/blog/` and `/contact/` render from fixtures like every
other page and verify clean, but their content is a snapshot: Phase 4 replaces the blog grid
with a real loop and the form with Contact Form 7.

### Phase 3 — Blocks and content migration (5–8 days)

Set up `@wordpress/scripts` (`npm i -D @wordpress/scripts`) and register one dynamic block
per archetype. Each `block.json` declares the attributes; `render_callback` hands them to
`inc/sections.php`, the renderer proven correct in Phase 2, so the block supplies data and
nothing else. The block tree and the node tree are the same shape, which is what makes this
a data migration rather than a rewrite.

Start with the seven archetypes covering 89% of sections, then the long tail. The one-offs
(home page carousel, overlapping cards, marquee) can stay hardcoded in `front-page.php`
initially — they appear on one page each and buying editability for them is poor value.

Then a one-shot WP-CLI migration parses each static HTML file — the same `lxml` approach
`_tools/partner_pipeline.py` already uses — and writes the resulting block markup into each
Page's `post_content`.

Theme artwork stays in `assets/images/`. Anything the client will replace goes to the Media
Library.

**Passes when:** `theme/fixtures/` is deleted, every page renders from block content, the
client can reorder sections by dragging, and `verify_wp.py` and `geometry.html` both still
report zero differences.

### Phase 4 — Blog, forms, search, 404 (2–3 days)

`home.php` and `single.php` using the existing `.blog-card` markup; pagination through
`the_posts_pagination()` styled as `.pagination-wrapper.pagination`. Import the ~100 live
posts with WP-CLI. Rebuild the contact form with CF7 + Turnstile so the markup matches
`contact.html`. Add `search.php`, `searchform.php`, `404.php` — none of which exist on the
static site and all of which a real site needs.

**Passes when:** blog listing, a post, pagination, search and 404 all render in-system, and
the contact form delivers mail with Turnstile passing.

### Phase 5 — Production hardening (2 days)

Turn WP Rocket on and re-verify. Confirm Rank Math owns the `<title>` — the theme must
declare `add_theme_support( 'title-tag' )` and never print its own. Check the three
deliberate divergences survived (see the README), and restore the real server-side redirects
for `hikvision-iraq`, `motorola-iraq` and `endpoint-secuirty-iraq` so the nav hrefs can go
back to the live URLs.

**Passes when:** `verify.py` and `audit.py` both pass against the production build with
caching on.

---

## 4. Hazards specific to this site

Things that will bite, from what the static build already taught us.

1. **`wpautop` will corrupt the markup.** WordPress inserts `<p>` and `<br>` into content.
   Because sections render from fields rather than `the_content()`, this mostly disappears —
   but any rich-text field must go through `wp_kses_post()` with autop disabled.

2. **WordPress will rewrite `srcset` on Media Library images.** The static `srcset`/`sizes`
   attributes are hand-matched to Webflow's breakpoints. For theme artwork this is a
   non-issue (not in the library). For editorial images, either register matching image
   sizes or filter `wp_calculate_image_srcset`.

3. **WP Rocket lies to measurement.** `content-visibility: auto` makes unscrolled sections
   report `height: 0`, and its lazyload leaves images as placeholders. Always neutralise
   both before trusting a number — the recipe is in the README, and getting this wrong cost
   real time twice during the static build.

4. **Stylesheet order is load-bearing.** `utilities.css` must stay second-to-last and
   `animations.css` last. The dependency chain above enforces it; do not "simplify" it into
   a single `wp_enqueue_style` loop over a directory listing.

5. **Media-query order matters too.** Within each file the blocks run 991 → 767 → 479. A
   concatenating minifier that reorders them will break mobile in ways desktop testing never
   catches.

6. **Do not re-add `webflow.js`.** Every animation here is reimplemented in the six modules.
   Loading Webflow's IX2 runtime alongside them would double-bind the scroll triggers.

7. **`page-template-default` for everything.** Resist per-page templates. If a page needs
   something unique, it needs a new section archetype, not a new template — otherwise the
   57-page problem comes straight back.

---

## 5. Verification

The strongest asset from the static build is that correctness is **measurable**, and the
same harness works against WordPress with only a base-URL change.

```bash
python _tools/verify.py "<static>/*.html"   # text, images, links, element structure
python _tools/audit.py                      # CSS completeness and dead code
```

`verify.py` compares a rendered page against its reference and reports the first divergence.
Point it at the local WordPress URL instead of the live site and it becomes the acceptance
test for every phase.

For layout, compare section offsets, heights and total `scrollHeight` at **1440x900** —
the page header is `90vh`, so any other viewport silently changes every offset.

Target: zero differences on all 57 pages before launch.

---

## 6. Estimate and open questions

| Phase | Days |
|---|---|
| 0 Environment | 0.5 |
| 1 Skeleton and chrome | 2–3 |
| 2 Section partials | 4–6 |
| 3 Blocks and content migration | 5–8 |
| 4 Blog, forms, search, 404 | 2–3 |
| 5 Production hardening | 2 |
| **Total** | **~16–23 working days** |

Blog post migration is the largest variable — ~100 posts whose content is not in this repo.

**Decided:**

1. **Native blocks, not ACF Pro** — no paid dependency. Adds 2–3 days for the editor build.
2. **Editing scope** — text, images, and reordering sections. Enough for launch; per-section
   style options can come later without reworking the partials.
3. **A new theme on the existing installation.** `osous-taqnia` stays installed but inactive,
   so rollback is one click. No content migration: the pages and posts are already there.
4. **Blog** — nothing to move. `home.php` and `single.php` render the ~100 existing posts
   dynamically from the database.

**Deployment.** Develop locally against a copy of production. Ship by uploading the theme
folder and activating it, ideally during a quiet window, with the old theme one click away.
Re-run `verify.py` against the live URLs immediately after activation.
