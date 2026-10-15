#!/usr/bin/env bash
#
# Prove the handoff package installs -- on a WordPress that has never seen this repository.
#
#   ./_tools/env.sh package        # first: builds dist/
#   ./_tools/handoff-test.sh
#
# Stands up the clean stack in _tools/handoff/docker-compose.yml, installs WordPress, then
# does exactly what HANDOFF.txt tells the receiver to do -- the theme from its zip, the
# plugins from theirs, the pages from the theme (Appearance -> Site pages, run here as
# `wp osous install-pages`, the same code; verify_install.py presses the button itself),
# pretty permalinks, the sample posts from their export, the Turnstile keys (Cloudflare's
# test pair, where the receiver enters real ones) -- and runs the same
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

bundle="$(ls dist/osous-handoff-*.zip 2>/dev/null | head -1 || true)"
[ -n "$bundle" ] || {
	echo "no dist/osous-handoff-<version>.zip -- run ./_tools/env.sh package first" >&2
	exit 1
}

# What the receiver does first: unzip it. The folder inside is what gets mounted.
echo "== unpacking $bundle"
rm -rf _migration/handoff-test
python - "$bundle" <<'UNZIP'
import os, sys, zipfile
bundle, out = sys.argv[1], os.path.join("_migration", "handoff-test")
with zipfile.ZipFile(bundle) as z:
    for name in z.namelist():
        inner = name.split("/", 1)[1] if "/" in name else name   # drop the top folder
        if not inner or name.endswith("/"):
            continue
        target = os.path.join(out, inner)
        os.makedirs(os.path.dirname(target), exist_ok=True)
        with z.open(name) as src, open(target, "wb") as dst:
            dst.write(src.read())
print("   " + ", ".join(sorted(os.listdir(out))))
UNZIP

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

# What HANDOFF.txt tells a receiver with a fresh WordPress to do first: the sample post
# and the sample page WordPress ships with would otherwise sit beside the site's -- a
# thirteenth blog card with no picture, and an extra page. Its draft Privacy Policy stays:
# step 3 is meant to make it the site's own privacy page, and this is where that is proven.
for slug in hello-world sample-page; do
	id="$(wp post list --post_type=post,page --post_status=publish,draft --name="$slug" --field=ID --format=ids)"
	[ -n "$id" ] && wp post delete "$id" --force >/dev/null
done

echo "== 1. the theme"
wp theme install /handoff/osous-theme.zip --activate 2>&1 | tail -1

echo "== 2. the plugins"
for zip in _migration/handoff-test/plugins/*.zip; do
	wp plugin install "/handoff/plugins/$(basename "$zip")" --activate 2>&1 | tail -1
done
# Rank Math's first screen asks for an account, and until one is connected or "Skip" is
# pressed the plugin prints nothing on any page. HANDOFF.txt says so; this is the Skip.
wp option update rank_math_registration_skip 1 >/dev/null 2>&1
# And one of its defaults changes the pages: "Open External Links in New Tab/Window" puts
# a target on three links the design prints plain. HANDOFF.txt says to switch it off.
wp eval '$general = (array) get_option( "rank-math-options-general" ); $general["new_window_external_links"] = "off"; update_option( "rank-math-options-general", $general );' >/dev/null 2>&1
# Its Instant Indexing would send each page step 3 saves to api.indexnow.org, as
# localhost:8090. A test site has nothing to announce.
wp eval 'update_option( "rank_math_modules", array_values( array_diff( (array) get_option( "rank_math_modules" ), array( "instant-indexing" ) ) ) );' >/dev/null 2>&1
# Contact Form 7 makes a sample form on activation. The site's form is made by step 3;
# the sample is the one HANDOFF.txt says to delete.
sample="$(wp post list --post_type=wpcf7_contact_form --name=contact-form-1 --field=ID --format=ids)"
[ -n "$sample" ] && wp post delete "$sample" --force >/dev/null

echo "== 3. the pages"
wp osous install-pages 2>&1 | tail -1

echo "== 4. permalinks"
wp rewrite structure '/%postname%/' >/dev/null
wp rewrite flush >/dev/null

echo "== 5. the sample posts"
wp import /handoff/osous-posts.xml --authors=create 2>&1 | tail -1

echo "== 6. the keys (Cloudflare's test pair)"
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
# The editor script comes from build/, which the zip carries; the source does not go in.
editor="$(wp eval 'echo ( WP_Block_Type_Registry::get_instance()->is_registered( "osous/section" ) && file_exists( get_template_directory() . "/build/index.js" ) ) ? "registered" : "missing";')"
echo "editor script from the zip: $editor"
[ "$editor" = "registered" ] || status=1
OSOUS_WP="$URL" python _tools/verify_wp.py | grep -vE "^OK " || status=1
OSOUS_WP="$URL" python _tools/verify_chrome.py | grep -vE "^OK " || status=1
# What travelled with the pages: each one's template, and its search title and description
# in Rank Math's fields -- read back from the <head> the receiver's visitors will get.
kinds="$(wp eval 'echo count( get_posts( array( "post_type" => "page", "numberposts" => -1, "fields" => "ids", "meta_query" => array( array( "key" => "_wp_page_template", "value" => "templates/page-", "compare" => "LIKE" ) ) ) ) );' 2>/dev/null)"
echo "pages on a kind's template: $kinds"
[ "$kinds" = "56" ] || status=1
OSOUS_WP="$URL" python _tools/verify_seo.py || status=1

if [ "$status" -eq 0 ]; then
	"${COMPOSE[@]}" down -v >/dev/null 2>&1
	rm -rf _migration/handoff-test
	echo
	echo "The package installs and the site it makes matches the reference."
else
	echo
	echo "The package does NOT install cleanly -- see above. The stack is left running at" >&2
	echo "$URL (admin/admin) to look at; tear it down with:" >&2
	echo "  docker compose -f _tools/handoff/docker-compose.yml down -v" >&2
fi
exit "$status"
