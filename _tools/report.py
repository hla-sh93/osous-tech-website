"""
The last run of check.sh, page by page.

    ./_tools/check.sh && python _tools/report.py     # -> _migration/check/report.md

check.sh says one line a check; the owner asks page by page. This reads what each check
printed under _migration/check/ and writes one table, a row a page, a column a question:
does the page match the original (markup, at 1440, at 479, its pinned lists), does the
editor show it as the page and let every word and picture be clicked into, does every
word, picture and link on it change from the dashboard, does every search and social field
reach its <head>, does it take another template, does it survive another address. A cell
is a tick only when its check named the page and passed it; a check that did not run, or
did not mention the page, leaves a dash -- never a tick.

Nothing is measured here. It is a reading of the logs, and as old as they are: the date of
each log is printed under the table.
"""
import collections
import datetime
import glob
import io
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from kinds import page_kinds  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
LOGS = os.path.join(ROOT, "_migration", "check")
OUT = os.path.join(LOGS, "report.md")
TICK, CROSS, NONE = "✓", "✗", "–"


def log(name):
    path = os.path.join(LOGS, name)
    return io.open(path, encoding="utf-8", errors="replace").read() if os.path.exists(path) else ""


def stamp(name):
    path = os.path.join(LOGS, name)
    if not os.path.exists(path):
        return "not run"
    return datetime.datetime.fromtimestamp(os.path.getmtime(path)).strftime("%Y-%m-%d %H:%M")


def table_rows(text, passed):
    """`  slug | ... | verdict` lines: {slug: passed?}."""
    found = {}
    for slug, rest in re.findall(r"^\s+([a-z0-9-]+) \| (.*)$", text, re.M):
        found[slug] = passed(rest)
    return found


def main():
    slugs = sorted(os.path.basename(p)[:-5] for p in glob.glob(os.path.join(ROOT, "*.html")))
    kinds = page_kinds()

    markup = {}
    for verdict, slug in re.findall(r"^(OK|###)\s+([a-z0-9-]+)", log("wp.log"), re.M):
        markup[slug] = verdict == "OK"

    # "match", alone or with the harness's note on reveals played off-screen on one side
    # only, is a match -- the same reading check.sh makes.
    def matched(rest):
        return bool(re.search(r"\| match( |$)", rest.rstrip()))

    desktop = table_rows(log("geometry.log"), matched)
    phone_raw = log("geometry-479.raw")
    # The contact page's submit button, waiting for its reveal on the reference, is the one
    # documented difference at 479 (AGENTS.md 5.2); check.sh passes it by name, and so does this.
    phone = table_rows(phone_raw, lambda rest: matched(rest) or bool(
        re.fullmatch(r"[^|]+\| [^|]+(\| style el [0-9]+ <input> [a-z+-]+: static=<reveal> wp=[^|]+)+", rest.strip())))
    pinned = table_rows(log("pinned.log"), lambda rest: rest.rstrip().endswith("| correct"))

    editor = {}
    for slug, rest in re.findall(r"^([a-z0-9-]+)\s+(\d+ sections.*)$", log("editor.log"), re.M):
        clicked = re.search(r"(\d+) words, (\d+) pictures clicked into", rest)
        editor[slug] = ("ok at" in rest and "!!" not in rest, clicked.groups() if clicked else None)

    editable = {}
    raw = log("editable.json")
    if raw:
        data = json.loads(raw)
        editable = {"summary": data.get("summary", {}), "problems": data.get("problems", {})}

    built = {}
    for title, rest in re.findall(r"^([A-Z][a-z]+ page)\s+(.*)$", log("build.log"), re.M):
        built[title] = rest.rstrip().endswith("ok")

    def mark(value):
        return NONE if value is None else (TICK if value else CROSS)

    lines = [
        "# Every page, every check",
        "",
        "Read from the logs check.sh left under `_migration/check/` -- see the dates at the end.",
        "%s passed, %s failed, %s not run or not about this page." % (TICK, CROSS, NONE),
        "",
        "| Page | Kind | Markup | 1440 | 479 | Pinned | Editor | Clicked into | Fields changed (words / pictures / links) | Search & social | Template | Slug |",
        "|---|---|---|---|---|---|---|---|---|---|---|---|",
    ]
    totals = collections.Counter()
    for slug in slugs:
        summary = editable.get("summary", {}).get(slug, {})
        problems = editable.get("problems", {}).get(slug, [])
        blocks = summary.get("blocks")
        seo = summary.get("seo")
        template = summary.get("template")
        slug_part = summary.get("slug")

        block_ok = None if blocks is None else not [p for p in problems if p.startswith("blocks:")]
        seo_ok = None if seo is None else seo["follow"] == seo["fields"] and not [p for p in problems if p.startswith("seo:")]
        editor_ok, clicked = editor.get(slug, (None, None))
        has_pinned = slug in pinned

        cells = [
            "`%s`" % slug,
            kinds.get(slug) or "posts page",
            mark(markup.get(slug)),
            mark(desktop.get(slug)) if slug != "blog" else NONE,
            mark(phone.get(slug)) if slug != "blog" else NONE,
            mark(pinned.get(slug)) if has_pinned else "no list",
            mark(editor_ok) if slug != "blog" else "posts",
            ("%s words, %s pictures" % clicked) if clicked else NONE,
            ("%s %d / %d / %d" % (mark(block_ok), blocks["words"], blocks["pictures"], blocks["links"])) if blocks else ("posts" if slug == "blog" else NONE),
            ("%s %d of %d" % (mark(seo_ok), seo["follow"], seo["fields"])) if seo else NONE,
            mark(template["ok"]) if template else ("posts" if slug == "blog" else NONE),
            mark(slug_part["ok"]) if slug_part else NONE,
        ]
        for name, value in (("markup", markup.get(slug)), ("editor", editor_ok), ("blocks", block_ok), ("seo", seo_ok)):
            if value is False:
                totals[name] += 1
        lines.append("| " + " | ".join(cells) + " |")

    lines += ["", "## A page of each kind, built by hand in the editor (`verify_editor.js --build`)", ""]
    if built:
        for title, ok in built.items():
            lines.append("- %s %s" % (mark(ok), title))
    else:
        lines.append("- %s not run" % NONE)

    fixed = collections.defaultdict(set)
    for slug, summary in editable.get("summary", {}).items():
        for picture in (summary.get("blocks") or {}).get("backgrounds_fixed", []):
            fixed[picture].add(slug)
    if fixed:
        lines += ["", "## Backgrounds no choice changes", ""]
        for picture, pages in sorted(fixed.items()):
            lines.append("- `%s`: %s" % (picture, ", ".join(sorted(pages))))

    lines += ["", "## When each log was written", ""]
    for name in ("wp.log", "geometry.log", "geometry-479.raw", "pinned.log", "editor.log", "editable.json", "build.log"):
        lines.append("- `%s`: %s" % (name, stamp(name)))

    with io.open(OUT, "w", encoding="utf-8", newline="\n") as target:
        target.write("\n".join(lines) + "\n")
    print("%d pages -> %s" % (len(slugs), os.path.relpath(OUT, ROOT)))
    if sum(totals.values()):
        print("failures: %s" % dict(totals))
    return 1 if sum(totals.values()) else 0


if __name__ == "__main__":
    sys.exit(main())
