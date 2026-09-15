#!/usr/bin/env bash
#
# Prove the handoff package installs -- on a WordPress that has never seen this repository.
#
#   ./_tools/env.sh package        # first: builds dist/
#   ./_tools/handoff-test.sh
#
# Stands up the clean stack in _tools/handoff/docker-compose.yml, installs WordPress, then
# does exactly what HANDOFF.txt tells the receiver to do -- the theme from its zip, the
# plugins from theirs, the content from its export, pretty permalinks, the Turnstile keys
# (Cloudflare's test pair, where the receiver enters real ones) -- and runs the same
# verifiers the development site has to pass against http://localhost:8090. Then tears the
# stack down, volumes and all.
#
# What this catches: a file missing from the zip, a post type missing from the export, a
# menu that did not travel, a setting the theme was supposed to fill in on arrival, a
# plugin the site needs that the notes forgot.
#
set -euo pipefail

cd "$(dirname "$0")/.."

COMPOSE=(docker compose -f _tools/handoff/docker-compose.yml)
URL="http://localhost:8090"

wp() {
	MSYS_NO_PATHCONV=1 "${COMPOSE[@]}" --profile tools run --rm -T cli wp "$@"
}

[ -f dist/osous-theme.zip ] && [ -f dist/osous-content.xml ] || {
	echo "dist/ is missing or incomplete -- run ./_tools/env.sh package first" >&2
	exit 1
}

echo "== a clean WordPress"
"${COMPOSE[@]}" down -v >/dev/null 2>&1 || true
"${COMPOSE[@]}" up -d >/dev/null
for _ in $(seq 1 60); do
	if "${COMPOSE[@]}" ps --format '{{.Service}} {{.Status}}' | grep -q '^db .*healthy'; then
		break
	fi
	sleep 2
done
# The wordpress container writes core into the volume on first start; wait for wp-settings.php.
for _ in $(seq 1 60); do
	if wp core version >/dev/null 2>&1; then
		break
	fi
	sleep 2
done

wp core install --url="$URL" --title="Osous Al Taqnia" \
	--admin_user=admin --admin_password=admin --admin_email=dev@localhost.test --skip-email >/dev/null

# What HANDOFF.txt tells a receiver with a fresh WordPress to do first: the sample post,
# the sample page and the draft privacy page WordPress ships with would otherwise sit
# beside the imported content -- a thirteenth blog card with no picture, an extra page,
# and the imported privacy page landing as `privacy-policy-2`, with every footer on the
# site linking to the wrong one.
for slug in hello-world sample-page privacy-policy; do
	id="$(wp post list --post_type=post,page --post_status=publish,draft --name="$slug" --field=ID --format=ids)"
	[ -n "$id" ] && wp post delete "$id" --force >/dev/null
done

echo "== 1. the theme"
wp theme install /handoff/osous-theme.zip --activate 2>&1 | tail -1

echo "== 2. the plugins"
for zip in dist/plugins/*.zip; do
	wp plugin install "/handoff/plugins/$(basename "$zip")" --activate 2>&1 | tail -1
done
# Contact Form 7 makes a sample form on activation. The site's form arrives with the
# content; the sample is the one HANDOFF.txt says to delete.
sample="$(wp post list --post_type=wpcf7_contact_form --name=contact-form-1 --field=ID --format=ids)"
[ -n "$sample" ] && wp post delete "$sample" --force >/dev/null

echo "== 3. the content"
wp import /handoff/osous-content.xml --authors=create 2>&1 | tail -1

echo "== 4. permalinks"
wp rewrite structure '/%postname%/' >/dev/null
wp rewrite flush >/dev/null

echo "== 5. the keys (Cloudflare's test pair)"
wp option update cfturnstile_key 1x00000000000000000000AA >/dev/null
wp option update cfturnstile_secret 1x0000000000000000000000000000000AA >/dev/null
wp option update cfturnstile_tested yes >/dev/null

# What the theme does by itself on the receiver's first dashboard visit.
wp eval 'osous_wire_up();'

echo "== the site, as the receiver would see it"
wp eval 'printf("pages %d, posts %d, forms %d, menus %d, front page %s, blog page %s, %s a page\n",
	count(get_posts(array("post_type"=>"page","numberposts"=>-1))),
	count(get_posts(array("post_type"=>"post","numberposts"=>-1))),
	count(get_posts(array("post_type"=>"wpcf7_contact_form","numberposts"=>-1))),
	count(array_filter(get_nav_menu_locations())),
	get_option("page_on_front"), get_option("page_for_posts"), get_option("posts_per_page"));'

echo "== the checks, against $URL"
status=0
OSOUS_WP="$URL" python _tools/verify_wp.py | grep -vE "^OK " || status=1
OSOUS_WP="$URL" python _tools/verify_chrome.py | grep -vE "^OK " || status=1

if [ "$status" -eq 0 ]; then
	"${COMPOSE[@]}" down -v >/dev/null 2>&1
	echo
	echo "The package installs and the site it makes matches the reference."
else
	echo
	echo "The package does NOT install cleanly -- see above. The stack is left running at" >&2
	echo "$URL (admin/admin) to look at; tear it down with:" >&2
	echo "  docker compose -f _tools/handoff/docker-compose.yml down -v" >&2
fi
exit "$status"
