# Local development environment

Everything runs in Docker, so nothing but Node is installed on the machine itself.

## What the machine already has

| Tool | Version | Used for |
|---|---|---|
| Node | 24.11.1 | building the editor UI for the section blocks |
| npm | 11.6.2 | same |
| Docker + Compose | 29.6.1 / v5.2.0 | WordPress, MariaDB, phpMyAdmin, WP-CLI |
| Git | 2.52.0 | this repository |
| Python | 3.12.7 | `_tools/verify.py` and `_tools/audit.py` |

PHP, MySQL, WP-CLI and Composer are deliberately **not** installed on the host — every one
of them ships inside the containers, which keeps the machine clean and pins the versions.

## Starting it

Docker Desktop has to be running first. Then, from the repository root:

```bash
docker compose up -d
```

| | URL | Credentials |
|---|---|---|
| The site | http://localhost:8080 | — |
| Dashboard | http://localhost:8080/wp-admin | `admin` / `admin` (local only) |
| phpMyAdmin | http://localhost:8081 | server `db` · user `wordpress` · password `wordpress` |
| Static reference | http://localhost:8080/static/ | — |

`/wp-admin` redirects to the login form until you sign in; that 302 is normal, not a fault.

First run downloads about 1.7 GB of images and takes a few minutes. After that it is seconds.

```bash
docker compose down       # stop, keep the database
docker compose down -v    # stop and wipe the database
docker compose logs -f wordpress
```

> **Disk space.** There were 16 GB free when this was set up. The images need ~1.7 GB and the
> WSL virtual disk grows over time, so keep an eye on it.

## WP-CLI

Runs as a one-off container under the `tools` profile:

```bash
docker compose run --rm cli wp theme list
docker compose run --rm cli wp theme activate osous
docker compose run --rm cli wp plugin install contact-form-7 seo-by-rank-math --activate
```

`_migration/` on the host is mounted at `/migration` in that container — put database dumps
there:

```bash
docker compose run --rm cli wp db import /migration/production.sql
```

## How the theme is mounted

```
./theme    ->  /var/www/html/wp-content/themes/osous
./assets   ->  /var/www/html/wp-content/themes/osous/assets   (read-only)
./         ->  /var/www/html/static                           (read-only)
```

Two mounts rather than one for the theme, so `assets/` stays a single source of truth: the
57 static reference pages at the repository root keep loading the very same CSS and JS that
the theme serves. Editing a PHP file changes the site immediately — no build step, no
copying.

The read-only flags are deliberate. Nothing WordPress does should ever write into the
verified stylesheets or the reference pages.

The third mount serves the static reference from the WordPress origin, which is what lets
`_tools/geometry.html` measure both versions of a page in one browser context — a frame
from another origin cannot be measured. It is a development convenience and has no place on
a server.

## Seeding

The 57 pages and the three menus come from the static site:

```bash
python _tools/seed.py                                   # writes _migration/seed.php
docker compose --profile tools run --rm cli wp eval-file /migration/seed.php
```

Pretty permalinks have to be on, or every page is `?page_id=`:

```bash
docker compose --profile tools run --rm cli wp rewrite structure '/%postname%/'
```

Two details the script handles on purpose. WordPress ships its own Privacy Policy page as a
**draft** on the `privacy-policy` slug, so the seed adopts and publishes it rather than
creating a second one. And `page_for_posts` is left unset until Phase 4 — while /blog/ is
still a section page it has to render like every other page so it can be verified.

## Saving and restoring

Two safety nets, for two different accidents.

```bash
./_tools/env.sh save before-blocks     # snapshot the database as it is now
./_tools/env.sh list
./_tools/env.sh restore before-blocks  # put it back
./_tools/env.sh rebuild                # throw it away and build it from this repo
```

`save` and `restore` are for experimenting in the admin: take a snapshot, try something,
put it back if it goes wrong. Snapshots land in `_migration/snapshots/` and are **not
committed** — they are large, they go stale, and `rebuild` makes them unnecessary for
getting back to a known state.

`rebuild` is the one that still works when the snapshot is lost, the Docker volume is
gone, or someone is setting the project up on another machine. It wipes the database and
runs the whole path again: install, activate the theme, set permalinks, seed the 57 pages
and the three menus. Everything it needs is in the repository.

The restore points that travel with the project are **git tags**:

```bash
git tag -l -n1                 # what is marked
git checkout phase-2           # the theme exactly as it was verified
git checkout main
```

A tag and a `rebuild` together recreate the whole thing from nothing.

> **Git Bash mangles container paths.** `wp rewrite structure '/%postname%/'` becomes
> `/C:/Program Files/Git/%postname%/` inside the container, and the damage is invisible
> until blog posts get permalinks. Every WP-CLI call in `_tools/env.sh` is wrapped with
> `MSYS_NO_PATHCONV=1` for that reason; do the same for one-off commands with a leading
> slash in an argument.

## Handing the theme to someone else

```bash
./_tools/env.sh package
```

Writes three files to `dist/`:

| | |
|---|---|
| `osous-theme.zip` | the theme, ~9 MB, installable through the admin |
| `osous-content.xml` | the 57 pages and the three menus |
| `HANDOFF.txt` | the three steps, for whoever receives it |

The recipient installs the zip under **Appearance → Themes → Add New → Upload**, imports
the XML under **Tools → Import → WordPress**, and sets permalinks to **Post name**. Order
does not matter, and there is nothing to wire up by hand: the theme assigns the three
menus to their locations and sets the home page itself, on the next admin page load.

**`assets/` has to be copied into the zip.** `theme/assets` is an empty directory here —
Docker mounts the real `assets/` over it so the theme and the static reference pages serve
the same files. Zipping `theme/` on its own produces a theme that installs perfectly and
renders a blank page. `_tools/package.py` exists for that reason, and checks that every
one of the 353 images the pages ask for is inside the archive.

This zip is for putting the theme *on* a WordPress install. Anyone who will **develop** it
wants the repository instead — `git clone`, `docker compose up -d`, `./_tools/env.sh
rebuild` — which brings the local WordPress, the verification tools and the history.

The one cosmetic gap: there is no `theme/screenshot.png`, so the theme's card in Appearance
is blank. A 1200x900 capture of the home page dropped in at that path fixes it.

## Bringing production down locally

The live site is already WordPress, so develop against a copy of it rather than inventing
content. From the production host (or its control panel), export the database and the
uploads folder, then:

```bash
# database
cp production.sql _migration/
docker compose run --rm cli wp db import /migration/production.sql
docker compose run --rm cli wp search-replace 'https://www.osoustaqnia.com' 'http://localhost:8080' --all-tables

# uploads
docker cp ./uploads/. osous-wordpress-1:/var/www/html/wp-content/uploads/
```

`search-replace` is not optional — without it every link and image URL still points at
production, and it is easy to think the local copy is broken when it is only pointing away.

Leave **WP Rocket deactivated locally**. Its `content-visibility` and lazyload behaviour make
measured comparisons meaningless; it goes back on in Phase 5.

## Checking the theme

```bash
docker compose --profile tools run --rm cli wp theme list   # is `osous` seen and valid?
docker compose exec wordpress php -l wp-content/themes/osous/functions.php
python _tools/verify_wp.py                                  # markup, all 57 pages
```

Then geometry, in the browser, from the WordPress origin:

```
http://localhost:8080/static/_tools/geometry.html?slugs=about,index
```

If something looks wrong and the markup checks out, read `wp-content/debug.log` inside the
container — `WP_DEBUG_LOG` is on and `WP_DEBUG_DISPLAY` is off, so notices go there rather
than into the page and into your measurements.
