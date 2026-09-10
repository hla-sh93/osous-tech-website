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
