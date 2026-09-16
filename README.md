# Osous Al Taqnia — the WordPress theme, and the pages it is measured against

> Working on this? Read **[AGENTS.md](AGENTS.md)** first: the rules, the hazards that have
> already cost time, the checks that have to pass before anything counts as done, and what
> is left.

This repository holds two things.

**`theme/`** is a classic WordPress theme for [osoustaqnia.com](https://www.osoustaqnia.com/)
with its own blocks. A page is a Section holding a Container holding a Section heading, a
Grid, cards, text and pictures; each block is a folder under `theme/blocks/` with a
`block.json`, a `render.php` and an `edit.js`, and each offers the design's own choices by
name. The site's details live in the Customizer; the contact form is Contact Form 7. It
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

The bundle holds the theme zip, the content export, the three plugin zips the site needs,
and HANDOFF.txt with the receiver's steps.

## Running it locally

Docker Desktop, then:

```bash
docker compose up -d         # http://localhost:8080  (admin / admin)  ·  Mailpit on :8025
./_tools/env.sh rebuild      # the database, built from this repository
```

## Checking it

The checks in AGENTS.md §5 — markup, layout, scroll behaviour, chrome, blog, editor,
options, content, contact form, coding standards — all pass before a commit:

```bash
python _tools/verify_wp.py && python _tools/verify_chrome.py && python _tools/verify_blog.py
node _tools/verify_editor.js && node _tools/verify_contact.js
python _tools/verify_presets.py && python _tools/verify_coverage.py
npm run lint && ./_tools/env.sh phpcs
```

## Layout

```
*.html               the 57 reference pages
assets/              their stylesheets, scripts and images -- served by the theme unchanged
theme/               the WordPress theme
  blocks/<name>/       one folder per block: block.json, render.php, edit.js
  inc/                 setup, the design's option tables, the shared helpers
  patterns/            the section library, generated from the reference pages
  build/               the editor bundle, compiled from blocks/ and committed
_tools/              every script and harness; never shipped
_migration/          generated, gitignored: what the database is built from
docker-compose.yml   WordPress + MariaDB + phpMyAdmin + WP-CLI + Mailpit
```
