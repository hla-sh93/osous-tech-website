# -*- coding: utf-8 -*-
"""
Compare what WordPress renders against the static page it was built from.

This is the acceptance test for the theme conversion. It is stricter than
`_tools/verify.py`, which has to tolerate Webflow and WP Rocket artefacts on the live
site: here both sides are ours, so every element, every attribute and every character of
text has to line up.

    python _tools/verify_wp.py             # all pages
    python _tools/verify_wp.py about       # one page
"""
import glob
import os
import re
import sys
import urllib.request

from lxml import html as LH

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.environ.get("OSOUS_WP", "http://localhost:8080")
THEME = "/wp-content/themes/osous/"

# Attributes that are part of the design rather than of WordPress.
KEEP = ("class", "href", "src", "srcset", "sizes", "alt", "id", "role", "aria-hidden",
        "aria-label", "aria-current", "target", "rel", "width", "height", "fetchpriority",
        "type", "name", "placeholder", "maxlength", "size", "cols", "rows", "value",
        "required", "method", "action", "stroke", "stroke-width", "d", "viewbox", "fill",
        "xmlns", "data-paged", "data-query", "fs-list-element", "fs-list-load",
        "udy-collection")


def unurl(value):
    """Turn a rendered URL back into the static path it came from."""
    if not value:
        return value
    value = value.strip()
    if THEME in value:
        return value.split(THEME, 1)[1]
    if value.startswith(BASE):
        rest = value[len(BASE):].strip("/")
        return (rest + ".html") if rest else "index.html"
    return value


def norm_attrs(el):
    out = {}
    for name in KEEP:
        if name not in el.attrib:
            continue
        value = el.get(name)
        if name in ("href", "src"):
            value = unurl(value)
        elif name == "srcset":
            value = ", ".join(
                " ".join([unurl(p.split(" ")[0])] + p.split(" ")[1:])
                for p in [c.strip() for c in value.split(",")] if p
            )
        elif name == "class":
            value = " ".join(sorted(value.split()))
        else:
            value = re.sub(r"[ \t\r\n]+", " ", value).strip()
        out[name] = value
    return out


def norm_text(value):
    # ASCII whitespace only: a leading U+00A0 is content, and losing one in
    # translation is exactly the kind of difference this is here to catch.
    return re.sub(r"[ \t\r\n]+", " ", value or "").strip(" \t\r\n\f\v")


def flatten(el, path="main"):
    """Depth-first list of (path, tag, attrs, own-text) for an element tree."""
    rows = []
    kids = [k for k in el if isinstance(k.tag, str) and k.tag not in ("script", "noscript", "style")]
    for i, k in enumerate(kids):
        here = "%s/%s[%d]" % (path, k.tag, i)
        text = norm_text(k.text) + " " + norm_text(k.tail)
        rows.append((here, k.tag.lower(), norm_attrs(k), text.strip(" ")))
        rows.extend(flatten(k, here))
    return rows


def main_of(doc):
    m = doc.xpath("//main")
    if not m:
        raise SystemExit("no <main> in document")
    return m[0]


def fetch(slug):
    url = BASE + "/" if slug == "index" else "%s/%s/" % (BASE, slug)
    with urllib.request.urlopen(url, timeout=30) as r:
        return LH.fromstring(r.read().decode("utf-8"))


def compare(slug):
    live = flatten(main_of(fetch(slug)))
    ref = flatten(main_of(LH.parse(os.path.join(ROOT, slug + ".html")).getroot()))

    problems = []
    for i in range(max(len(live), len(ref))):
        a = live[i] if i < len(live) else None
        b = ref[i] if i < len(ref) else None
        if a == b:
            continue
        if a is None:
            problems.append("missing %s <%s %s>" % (b[0], b[1], b[2]))
        elif b is None:
            problems.append("extra   %s <%s %s>" % (a[0], a[1], a[2]))
        elif a[1] != b[1]:
            problems.append("tag  at %s: wp=<%s> static=<%s>" % (b[0], a[1], b[1]))
        elif a[2] != b[2]:
            diff = {k: (a[2].get(k), b[2].get(k)) for k in set(a[2]) | set(b[2])
                    if a[2].get(k) != b[2].get(k)}
            problems.append("attr at %s <%s>: %s" % (b[0], b[1], diff))
        else:
            problems.append("text at %s <%s>: wp=%r static=%r" % (b[0], b[1], a[3][:90], b[3][:90]))
        if len(problems) >= 3:
            break

    return len(live), len(ref), problems


def main(argv):
    slugs = argv or sorted(os.path.basename(p)[:-5] for p in glob.glob(os.path.join(ROOT, "*.html")))
    bad = 0
    for slug in slugs:
        try:
            n_wp, n_ref, problems = compare(slug)
        except Exception as exc:  # noqa: BLE001 - report and keep going
            bad += 1
            print("### %-34s %s" % (slug, exc))
            continue
        if problems:
            bad += 1
            print("### %-34s wp %d el / static %d el" % (slug, n_wp, n_ref))
            for p in problems:
                print("     ", p[:300])
        else:
            print("OK  %-34s %d elements" % (slug, n_ref))
    print("\n%d of %d pages differ" % (bad, len(slugs)))
    return bad


if __name__ == "__main__":
    sys.exit(1 if main(sys.argv[1:]) else 0)
