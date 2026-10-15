#!/usr/bin/env bash
#
# Save, restore and rebuild the local WordPress.
#
# There are two kinds of safety net here and they protect against different things.
#
#   save / restore   a snapshot of the database as it is right now. Use it before
#                    experimenting in the admin, and to undo it afterwards.
#
#   rebuild          throws the database away and builds it again from this repository.
#                    Slower, and the one that still works when a snapshot is lost, the
#                    volume is gone, or you are setting the project up on another machine.
#
# Snapshots live in _migration/snapshots/ and are deliberately not committed: they are
# large, they go stale, and `rebuild` makes them unnecessary for getting back to a known
# state. Git tags are the restore points that travel with the project.
#
#   ./_tools/env.sh save before-blocks
#   ./_tools/env.sh list
#   ./_tools/env.sh restore before-blocks
#   ./_tools/env.sh rebuild
#   ./_tools/env.sh package     -> dist/, ready to hand to someone else
#   ./_tools/handoff-test.sh    installs dist/ on a clean WordPress and verifies it
#   ./_tools/env.sh phpcs       the WordPress Coding Standards over theme/
#   ./_tools/env.sh phpcbf      ... and fix what they can fix
#   ./_tools/env.sh installed   the theme a folder of the site's own, as on a host (AGENTS.md §4)
#   ./_tools/env.sh develop     ... and back: the theme is this repository again
#
set -euo pipefail

cd "$(dirname "$0")/.."

SNAPSHOTS="_migration/snapshots"
SITE_URL="http://localhost:8080"
SITE_TITLE="Osous Al Taqnia (local)"
ADMIN_USER="admin"
ADMIN_PASS="admin"
ADMIN_EMAIL="dev@localhost.test"

# Git Bash rewrites anything that looks like a Unix path into a Windows one, which turns
# `/migration/x.sql` into `C:/Program Files/Git/migration/x.sql` inside the container. It
# did that to the permalink structure once and the damage was invisible for days.
wp() {
	MSYS_NO_PATHCONV=1 docker compose --profile tools run --rm cli wp "$@"
}

# PHP, without PHP on this machine. The WP-CLI image has 8.3 and reaches Packagist, so
# the coding standards run inside it with the repository mounted at /app. The Windows path
# is passed on purpose: given the Unix one, Git Bash rewrites the mount (see wp() above).
php_here() {
	MSYS_NO_PATHCONV=1 docker run --rm -v "$(pwd -W 2>/dev/null || pwd):/app" -w /app 		--entrypoint sh wordpress:cli -c "$*"
}

# vendor/ is gitignored, so the first run installs it. `docker pull composer:2` was cut off
# mid-layer twice on this machine; a composer.phar fetched by the host, checked against the
# published hash and run inside the image already on disk needs no new image at all.
ensure_standards() {
	[ -f vendor/bin/phpcs ] && return
	mkdir -p _migration
	if [ ! -f _migration/composer.phar ]; then
		echo "fetching composer.phar from getcomposer.org"
		curl -sSL -o _migration/composer.phar https://getcomposer.org/download/latest-stable/composer.phar
		curl -sSL -o _migration/composer.phar.sha256 https://getcomposer.org/download/latest-stable/composer.phar.sha256
		if [ "$(cut -d' ' -f1 _migration/composer.phar.sha256)" != "$(sha256sum _migration/composer.phar | cut -d' ' -f1)" ]; then
			echo "composer.phar does not match its published checksum; not using it" >&2
			rm -f _migration/composer.phar
			exit 1
		fi
	fi
	php_here '(command -v unzip >/dev/null || apk add --no-cache -q unzip) >/dev/null 2>&1;' 		'php /app/_migration/composer.phar install --no-interaction --no-progress'
}

usage() {
	sed -n '3,27p' "$0" | sed 's/^# \{0,1\}//'
	exit 1
}

require_stack() {
	if ! docker compose ps --status running --services 2>/dev/null | grep -q '^wordpress$'; then
		echo "The stack is not running. Start it with: docker compose up -d" >&2
		exit 1
	fi
}

case "${1:-}" in

save)
	require_stack
	name="${2:-$(date +%Y%m%d-%H%M%S)}"
	mkdir -p "$SNAPSHOTS"
	wp db export "/migration/snapshots/${name}.sql" --add-drop-table >/dev/null
	printf 'saved  %s  (%s)\n' "$name" "$(du -h "${SNAPSHOTS}/${name}.sql" | cut -f1)"
	;;

restore)
	require_stack
	name="${2:-}"
	[ -n "$name" ] || { echo "which snapshot? try: $0 list" >&2; exit 1; }
	file="${SNAPSHOTS}/${name}.sql"
	[ -f "$file" ] || { echo "no snapshot called '$name' in $SNAPSHOTS" >&2; exit 1; }
	wp db import "/migration/snapshots/${name}.sql" >/dev/null
	wp cache flush >/dev/null 2>&1 || true
	echo "restored  $name"
	;;

list)
	if [ -d "$SNAPSHOTS" ] && [ -n "$(ls -A "$SNAPSHOTS" 2>/dev/null)" ]; then
		ls -lh "$SNAPSHOTS" | awk 'NR>1 { printf "  %-34s %6s  %s %s %s\n", $9, $5, $6, $7, $8 }'
	else
		echo "  (no snapshots yet)"
	fi
	;;

package)
	require_stack
	rm -rf _migration/export dist
	mkdir -p _migration/export
	# The placeholder posts, for an empty WordPress. The pages, the menus and the contact
	# form are the theme's own now (Appearance -> Site pages). package.py adds the theme,
	# the plugins and the notes, and bundles the lot.
	wp export --dir=/migration/export --post_type=post --filename_format=osous-posts.xml >/dev/null
	python _tools/package.py
	echo
	echo "Prove it installs on a clean WordPress:  ./_tools/handoff-test.sh"
	;;

rebuild)
	echo "This deletes the local database and builds it again from the repository."
	printf 'Type "yes" to continue: '
	read -r answer
	[ "$answer" = "yes" ] || { echo "nothing done"; exit 1; }

	docker compose down -v
	docker compose up -d

	printf 'waiting for the database'
	for _ in $(seq 1 60); do
		if docker compose ps --format '{{.Service}} {{.Status}}' | grep -q '^db .*healthy'; then
			break
		fi
		printf '.'
		sleep 2
	done
	echo

	wp core install \
		--url="$SITE_URL" \
		--title="$SITE_TITLE" \
		--admin_user="$ADMIN_USER" \
		--admin_password="$ADMIN_PASS" \
		--admin_email="$ADMIN_EMAIL" \
		--skip-email
	wp theme activate osous

	# The contact form is Contact Form 7 with Simple Cloudflare Turnstile, and what each
	# page tells a search engine is Rank Math's to print, as on the live site. The zips are
	# fetched by the host: `wp plugin install <slug>` inside the container could not reach
	# downloads.wordpress.org on this machine, and the host can.
	for slug in contact-form-7 simple-cloudflare-turnstile seo-by-rank-math; do
		curl -sSL -o "_migration/${slug}.zip" "https://downloads.wordpress.org/plugin/${slug}.latest-stable.zip"
		wp plugin install "/migration/${slug}.zip" --activate --force >/dev/null
	done
	# Rank Math prints nothing on any page until its first screen has been answered: an
	# account connected, or "Skip" pressed. This is the Skip -- the option that button sets.
	wp option update rank_math_registration_skip 1 >/dev/null
	# One of Rank Math's defaults changes the pages: it opens every link to another site in
	# a new tab, which puts a target on three links the design prints plain. On the live
	# site the setting has nothing to act on -- its pages are raw HTML, not content -- so the
	# reference has those links as the design made them, and so does this.
	wp eval '$general = (array) get_option( "rank-math-options-general" ); $general["new_window_external_links"] = "off"; update_option( "rank-math-options-general", $general );'
	# Rank Math's Instant Indexing sends every saved page's address to api.indexnow.org --
	# from a local site too, where the address is localhost. Off here; the live site's own
	# setting is its owner's.
	wp eval 'update_option( "rank_math_modules", array_values( array_diff( (array) get_option( "rank_math_modules" ), array( "instant-indexing" ) ) ) );'

	wp rewrite structure '/%postname%/'
	wp rewrite flush

	# Without this WordPress caches its scan of theme/patterns/ and a pattern added later
	# simply does not exist. The cache is keyed per theme and survives a plugin flush, so
	# it is worth turning off rather than remembering to clear.
	wp config set WP_DEVELOPMENT_MODE theme

	# What the theme installs: every page's original under theme/pages/, and the rest --
	# titles, templates, search fields, menus, the form -- in theme/install/site.json.
	# Both are committed; regenerating them here is what proves they still are the
	# reference pages.
	python _tools/gen_blocks.py
	python _tools/seed.py
	# The form first, from its generator, with Cloudflare's test keys: the installer
	# then finds a form called Contact and leaves it be.
	python _tools/seed_contact.py
	wp eval-file /migration/contact.php
	# The pages, the menus and the front page, through the theme's own installer -- the
	# code behind Appearance -> Site pages -- so every rebuild is a run of it from nothing.
	wp osous install-pages
	# WordPress's own Sample Page is not one of ours. The installer never deletes; this does.
	wp eval '$sample = get_page_by_path( "sample-page" ); if ( $sample ) { wp_delete_post( $sample->ID, true ); }'
	# The blog's placeholder posts, from the reference index page.
	python _tools/seed_posts.py
	wp eval-file /migration/posts.php

	echo
	echo "Rebuilt. $SITE_URL  ·  ${SITE_URL}/wp-admin  ($ADMIN_USER / $ADMIN_PASS)"
	echo "Check it with: python _tools/verify_wp.py"
	;;

installed)
	# The site as a host has it: wp-content/themes/osous an ordinary folder in the site's
	# own volume, which Appearance -> Themes deletes and an uploaded zip fills (AGENTS.md §4,
	# §5.20). In development that folder is this repository, mounted, and deleting the theme
	# from the dashboard deletes theme/. The first time, the volume holds nothing there but
	# the mount point, so the theme that was running is copied in from the repository, owned
	# by the web server as an upload leaves it. After that the site keeps what it has.
	require_stack
	MSYS_NO_PATHCONV=1 docker run --rm -u 0 -v osous_wp:/wp \
		-v "$(pwd -W 2>/dev/null || pwd)/theme:/src/theme:ro" \
		-v "$(pwd -W 2>/dev/null || pwd)/assets:/src/assets:ro" \
		--entrypoint sh wordpress:cli -c '
			set -e
			dst=/wp/wp-content/themes/osous
			mkdir -p "$dst"
			if [ -n "$(ls -A "$dst")" ]; then
				echo "the site keeps the theme it has: $(grep -m1 "^Version:" "$dst/style.css" 2>/dev/null | tr -d "\r")"
				exit 0
			fi
			cp -R /src/theme/. "$dst/"
			mkdir -p "$dst/assets"
			cp -R /src/assets/. "$dst/assets/"
			chown -R 33:33 "$dst"
			find "$dst" -type d -exec chmod 755 {} +
			find "$dst" -type f -exec chmod 644 {} +
			echo "copied in from the repository: $(find "$dst" -type f | wc -l) files"
		'
	# Both files named, so that a COMPOSE_FILE set in the shell cannot change which.
	docker compose -f docker-compose.yml -f _tools/installed/docker-compose.yml up -d wordpress
	echo "The theme is the site's own: remove it and upload a zip with node _tools/install_by_hand.js"
	echo "Back to development: ./_tools/env.sh develop"
	;;

develop)
	# The theme is this repository again. What the site installed stays in its volume, under
	# the mount, for the next `installed`; `rebuild` throws it away with the rest.
	docker compose -f docker-compose.yml up -d wordpress
	;;

phpcs|phpcbf)
	ensure_standards
	tool="$1"
	shift
	php_here "vendor/bin/$tool --no-colors ${*:-}"
	;;

*)
	usage
	;;
esac
