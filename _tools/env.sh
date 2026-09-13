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

usage() {
	sed -n '3,20p' "$0" | sed 's/^# \{0,1\}//'
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
	wp export --dir=/migration/export --post_type=page,nav_menu_item --filename_format=osous-content.xml >/dev/null
	python _tools/package.py
	cp _migration/export/osous-content.xml dist/osous-content.xml
	echo "dist/osous-content.xml  $(du -h dist/osous-content.xml | cut -f1)"
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
	wp rewrite structure '/%postname%/'
	wp rewrite flush

	# Without this WordPress caches its scan of theme/patterns/ and a pattern added later
	# simply does not exist. The cache is keyed per theme and survives a plugin flush, so
	# it is worth turning off rather than remembering to clear.
	wp config set WP_DEVELOPMENT_MODE theme

	python _tools/seed.py
	wp eval-file /migration/seed.php

	echo
	echo "Rebuilt. $SITE_URL  ·  ${SITE_URL}/wp-admin  ($ADMIN_USER / $ADMIN_PASS)"
	echo "Check it with: python _tools/verify_wp.py"
	;;

*)
	usage
	;;
esac
