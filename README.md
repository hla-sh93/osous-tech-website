# Osous Al Taqnia — the WordPress theme, and the pages it is measured against

> Working on this? Read **[AGENTS.md](AGENTS.md)** first: the rules, the hazards that have
> already cost time, the checks that have to pass before anything counts as done, and what
> is left.

This repository holds two things.

**`theme/`** is a classic WordPress theme for [osoustaqnia.com](https://www.osoustaqnia.com/),
laid out the way a small hand-written theme is: one file per page under `pages/`, with the
page's sections and words in it; one header and one footer under `includes/`, with the
sections and cards the pages are built from beside them; the functions split by concern
under `functions/`. The site's details live in the Customizer; the blog is written in the
editor; the contact form is Contact Form 7. It ships as one zip, installs without a build
step, and `_tools/handoff-test.sh` proves that on a WordPress that has never seen this
repository.

**The 57 `*.html` pages and `assets/`** are the reference: a hand-built clone of the live
site, measured against it pixel for pixel when it was made. The page files were written
from them, and every check in this project compares the theme's output to them. They are
the source of truth, not a stage that gets thrown away.

## Handing it over

```bash
./_tools/env.sh package      # dist/osous-handoff-<version>.zip -- the one file to send
./_tools/handoff-test.sh     # installs exactly that on a clean WordPress and verifies it
```

The bundle holds the theme zip, the content export, the three plugin zips the site needs,
and the receiver's steps in HANDOFF.txt, in English and in Arabic. Both open with the one
thing a receiver has missed: WordPress has to be told the pages exist, which the content
file does; the theme holds each page's file.

## Running it locally

Docker Desktop, then:

```bash
docker compose up -d         # http://localhost:8080  (admin / admin)  ·  Mailpit on :8025
./_tools/env.sh rebuild      # the database, built from this repository
```

## Checking it

The checks in AGENTS.md §5 — markup, layout, scroll behaviour, chrome, blog, contact form,
site details, coding standards — all pass before a commit:

```bash
python _tools/verify_wp.py && python _tools/verify_chrome.py && python _tools/verify_blog.py
node _tools/verify_contact.js && python _tools/verify_settings.py
npm run lint && ./_tools/env.sh phpcs
```

## Layout

```
*.html               the 57 reference pages
assets/              their stylesheets, scripts and images -- served by the theme unchanged
theme/               the WordPress theme
  pages/               one file per page: its sections in order, with its words
  includes/            header.php and footer.php; sections/, parts/ and loops/
  functions/           setup, the site details, the helpers, the blog, the contact form
_tools/              every script and harness; never shipped
_migration/          generated, gitignored: what the database is built from
docker-compose.yml   WordPress + MariaDB + phpMyAdmin + WP-CLI + Mailpit
```
