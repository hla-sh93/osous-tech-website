#!/usr/bin/env bash
#
# Every check in AGENTS.md §5, in one run, one line each.
#
#   ./_tools/check.sh              all of them -- about half an hour
#   ./_tools/check.sh --quick      without the editor and the three browser harnesses -- about five
#   ./_tools/check.sh seo slugs    only the ones named (the names are the ones it prints)
#
# Each check is run the way AGENTS.md says to run it and is passed by the sentence that
# section gives as "Passing looks like" -- not by its exit status alone, because a check
# that printed nothing has not passed. What a check printed is kept under
# _migration/check/, and the end of it is shown when the check fails.
#
# Run it with nothing else going on. The layout and scroll harnesses measure where a pin
# engages and where a reveal has got to, frame by frame, and a busy machine makes them
# report faults that are not there (AGENTS.md §7).
#
# It reads the site that is running; it does not rebuild it. For the proof that counts --
# everything, from nothing -- run `./_tools/env.sh rebuild` first, and
# `./_tools/env.sh package && ./_tools/handoff-test.sh` after.
#
set -uo pipefail

cd "$(dirname "$0")/.."

LOGS="_migration/check"
mkdir -p "$LOGS"

quick=0
only=()
for arg in "$@"; do
	case "$arg" in
		--quick) quick=1 ;;
		-h|--help) sed -n '3,21p' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
		*) only+=("$arg") ;;
	esac
done

if ! docker compose ps --status running --services 2>/dev/null | grep -q '^wordpress$'; then
	echo "The stack is not running. Start it with: docker compose up -d" >&2
	exit 1
fi

failed=()
ran=0

wanted() {
	[ "${#only[@]}" -eq 0 ] && return 0
	local name
	for name in "${only[@]}"; do
		[ "$name" = "$1" ] && return 0
	done
	return 1
}

# check <name> <the sentence a pass prints, as a grep -E pattern> <command...>
#
# More than one sentence may be asked for, separated by a newline: every one has to be
# there. A pattern of "-" asks only for a clean exit.
check() {
	local name="$1" pattern="$2"
	shift 2
	wanted "$name" || return 0

	local log="$LOGS/$name.log" status=0 missing="" line
	printf '%-12s ' "$name"
	"$@" >"$log" 2>&1 || status=$?

	if [ "$pattern" = "-" ]; then
		[ "$status" -eq 0 ] || missing="a clean exit (it exited $status)"
	else
		while IFS= read -r line; do
			[ -n "$line" ] || continue
			grep -Eq -- "$line" "$log" || missing="${missing:+$missing; }\"$line\""
		done <<<"$pattern"
	fi

	ran=$((ran + 1))
	if [ -z "$missing" ]; then
		echo "ok    $(grep -E -- "$(head -n 1 <<<"$pattern")" "$log" 2>/dev/null | tail -n 1 | cut -c1-110)"
	else
		echo "FAILED  wanted $missing"
		tail -n 12 "$log" | cut -c1-200 | sed 's/^/             | /'
		failed+=("$name")
	fi
}

slugs() {
	ls ./*.html | sed 's|^\./||; s/\.html$//' | grep -v '^blog$' | tr '\n' ',' | sed 's/,$//'
}

pinned() {
	grep -l 'list-scroll-section' ./*.html | sed 's|^\./||; s/\.html$//' | tr '\n' ',' | sed 's/,$//'
}

# The phone layout passes with the contact page's submit button and nothing else (§5.2).
# That one reads: every difference on the contact page is the same <input>, waiting for
# its reveal on the reference -- `static=<reveal>` -- and already shown on this side.
geometry_phone() {
	node _tools/run_harness.js "geometry.html?slugs=$(slugs)&w=479&h=900" | tee "$LOGS/geometry-479.raw" >/dev/null
	local others
	others="$(grep -E '^\s+[a-z0-9-]+ \| ' "$LOGS/geometry-479.raw" | grep -Ev '\| match( |$)' | grep -Ev '^\s+contact \| [^|]+\| [^|]+(\| style el [0-9]+ <input> [a-z+-]+: static=<reveal> wp=[^|]+)+$' || true)"
	grep -E '^done at 479x900' "$LOGS/geometry-479.raw"
	if [ -z "$others" ]; then
		echo "phone layout: nothing differs but the documented submit button"
	else
		echo "$others"
	fi
}

php_lint() {
	MSYS_NO_PATHCONV=1 docker compose exec -T wordpress sh -c '
		cd /var/www/html/wp-content/themes/osous
		bad=0
		for f in $(find . -name "*.php" -not -path "./assets/*"); do
			php -l "$f" >/dev/null 2>&1 || { echo "syntax: $f"; bad=1; }
		done
		log=/var/www/html/wp-content/debug.log
		if [ -f "$log" ] && grep -v "wp_version_check\|wp_update_plugins\|wp_update_themes\|secure connection" "$log" | grep -q .; then
			echo "debug.log:"
			grep -v "wp_version_check\|wp_update_plugins\|wp_update_themes\|secure connection" "$log" | tail -n 8
			bad=1
		fi
		[ "$bad" -eq 0 ] && echo "every PHP file parses, and the debug log holds nothing of the theme"
		exit "$bad"
	'
}

echo "checking http://localhost:8080 -- what each check printed is under $LOGS/"
echo

check wp         '^0 of 57 pages differ$'                     python _tools/verify_wp.py
check chrome     '^0 of [0-9]+ pages differ$'                 python _tools/verify_chrome.py
check blog       '^0 problems$'                               python _tools/verify_blog.py --pager
check presets    '^0 problems$'                               python _tools/verify_presets.py
check coverage   '^0 problems$'                               python _tools/verify_coverage.py
check settings   'settings checked, 0 problems$'              python _tools/verify_settings.py
check contact    '^0 problems$'                               node _tools/verify_contact.js
check templates  '^0 problems$'                               python _tools/verify_templates.py
check slugs      'checks, 0 problems$'                        python _tools/verify_slugs.py
check pages      '^0 problems$'                               python _tools/verify_pages.py
check seo        '^57 pages, 0 problems$'                     python _tools/verify_seo.py
check php        'every PHP file parses'                      php_lint
check phpcs      '-'                                          ./_tools/env.sh phpcs
check lint       '-'                                          npm run lint

if [ "$quick" -eq 0 ]; then
	check editor "$(printf '%s\n%s\n%s\n%s' \
		'^all 6 pages: the canvas paints what the page paints$' \
		'^all 30 patterns: registered, 21 in the inserter, every block valid, each painted as its source$' \
		'^all 7 kinds: offered to a new page, each sets its template and lays down its starter$' \
		'^all 4 tools: a page is duplicated')"                node _tools/verify_editor.js
	check geometry   '^done at 1440x900 .+ 0 of 56 pages differ$'  node _tools/run_harness.js "geometry.html?slugs=$(slugs)"
	check phone      '^phone layout: nothing differs but the documented submit button$' geometry_phone
	check pinned     '45 pinned lists checked, 0 wrong'           node _tools/run_harness.js "pinned-list.html?slugs=$(pinned)"
fi

echo
if [ "${#failed[@]}" -eq 0 ]; then
	echo "$ran checks, all passed."
else
	echo "$ran checks, ${#failed[@]} FAILED: ${failed[*]}"
	exit 1
fi
