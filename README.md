# Osous Al Taqnia — the WordPress theme, and the pages it is measured against

> Working on this? Read **[AGENTS.md](AGENTS.md)** first: the rules, the hazards that have
> already cost time, the checks that have to pass before anything counts as done, and what
> is left.

This repository holds two things.

**`theme/`** is a classic WordPress theme for [osoustaqnia.com](https://www.osoustaqnia.com/)
with its own blocks. A page is a Section holding a Container holding a Section heading, a
Grid, cards, text and pictures; each block is a folder under `theme/blocks/` with a
`block.json` and an `edit.js`, its markup is one file under `theme/includes/`, and each
offers the design's own choices by name. It is laid out as bst lays a theme out, with a
template per kind of page; a new page is asked its kind and starts as that kind's
sections; a page's slug can be changed without breaking the page or a link to it. The
site's details live in the Customizer; the contact form is Contact Form 7; what a page
tells a search engine is Rank Math's, edited on the page. A section, a container, a panel
or the hero can carry a picture of the site's own from the Media Library. The theme
carries the site's pages too: Appearance → Site pages installs them -- on an empty
WordPress, or on the live one, where every page already exists and is filled in place. It
ships as one zip, installs without a build step, and `_tools/handoff-test.sh` proves that
on a WordPress that has never seen this repository.

**The 57 `*.html` pages and `assets/`** are the reference: a hand-built clone of the live
site, measured against it pixel for pixel when it was made. Every check in this project
compares the theme's output to these. They are the source of truth, not a stage that gets
thrown away.

## Handing it over

```bash
./_tools/env.sh package      # dist/osous-handoff-<version>.zip -- the one file to send
./_tools/handoff-test.sh     # installs exactly that on a clean WordPress and verifies it
```

The bundle holds the theme zip, the sample posts for an empty WordPress, the four plugin
zips the site needs, and the receiver's steps in HANDOFF.txt, in English and in Arabic.
Both open with the one thing a receiver has missed: after the theme and the plugins,
Appearance → Site pages → Install the pages. The dashboard says so too, until it is done.

The theme zip removed and uploaded again from the dashboard, the way a person does it:

```bash
./_tools/env.sh installed                  # the local site's theme a folder of its own, as on a host
node _tools/install_by_hand.js --headed    # Delete in Appearance -> Themes, then Upload Theme, Activate
./_tools/env.sh develop                    # back: the theme is this repository again
```

On the development site the theme folder *is* `theme/`: never delete the theme from the
dashboard there, it deletes the files in this repository (AGENTS.md §7).

The repository's remote is [github.com/hla-sh93/osous-tech-website](https://github.com/hla-sh93/osous-tech-website),
and it is public: nothing secret goes into a commit.

## Running it locally

Docker Desktop, then:

```bash
docker compose up -d         # http://localhost:8080  (admin / admin)  ·  Mailpit on :8025
./_tools/env.sh rebuild      # the database, built from this repository
```

## Checking it

The checks in AGENTS.md §5 — markup, layout, scroll behaviour, chrome, blog, editor,
options, content, contact form, site details, the kinds of page, a changed slug, what a
page decides for itself, the `<head>`, everything on every page changed from the
dashboard, a page of every kind built by hand, the site's pages installed onto a copy
shaped like the live WordPress and onto an empty one, coding standards — all pass before
a commit. One command runs them, a line each (`--quick` leaves out the six that drive a
browser through the editor, the installer or every page):

```bash
./_tools/check.sh
```

Or one at a time:

```bash
python _tools/verify_wp.py && python _tools/verify_chrome.py && python _tools/verify_blog.py
node _tools/verify_editor.js --all && node _tools/verify_editor.js --build && node _tools/verify_contact.js
python _tools/verify_install.py
python _tools/verify_presets.py && python _tools/verify_coverage.py && python _tools/verify_settings.py
python _tools/verify_templates.py && python _tools/verify_slugs.py
python _tools/verify_pages.py && python _tools/verify_seo.py && python _tools/verify_editable.py
npm run lint && ./_tools/env.sh phpcs
```

## Layout

```
*.html               the 57 reference pages
assets/              their stylesheets, scripts and images -- served by the theme unchanged
theme/               the WordPress theme
  header.php, footer.php, page.php, …   the templates, in the root
  templates/           one template per kind of page (home, about, solution, partner, …)
  functions/           setup, the design's option tables, the shared helpers
  includes/            the markup: layout, elements, parts, sections, loops
  blocks/<name>/       one folder per block: block.json and edit.js
  patterns/sections/   the section library, generated from the reference pages
  patterns/starters/   a whole page of each kind: what a new page begins from
  pages/               every page's original, one file per page
  install/site.json    the rest of what Appearance -> Site pages installs: titles, menus, the form
  build/               the editor bundle, compiled from blocks/ and committed
_tools/              every script and harness; never shipped
_migration/          generated, gitignored: what the database is built from
docker-compose.yml   WordPress + MariaDB + phpMyAdmin + WP-CLI + Mailpit
```
