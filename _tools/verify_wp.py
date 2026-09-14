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
import io
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
        "xmlns")

# Deliberately not compared: `fs-list-element`, `fs-list-load`, `udy-collection`,
# `data-query` and `data-paged`. They belong to the Webflow list plugin that paginated the
# static blog page in the browser. WordPress runs a real query now, nothing reads them, and
# emitting them would be carrying cargo.


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
        text = r.read().decode("utf-8")
    return browser_parse(text) if slug in CF7 else LH.fromstring(text)


def reference(slug):
    path = os.path.join(ROOT, slug + ".html")
    if slug in CF7:
        return browser_parse(io.open(path, encoding="utf-8").read())
    return LH.parse(path).getroot()


def browser_parse(text):
    """Parse the way a browser does. Needs html5lib: `python -m pip install html5lib`.

    The reference file is a browser's DOM, and on the contact page that matters: CF7 puts
    the Turnstile <div> inside a <p>, which is not allowed, and a browser repairs it by
    closing the <p> early and leaving an empty <p> behind -- which the clone recorded.
    libxml2 repairs it differently, dropping the stray </p> with no empty <p>, so parsed
    with lxml alone the two sides disagree about markup that is the same in every browser.
    Both sides of a CF7 page go through html5lib, which repairs as browsers do.
    """
    from lxml.html import html5parser  # noqa: WPS433 -- optional dependency, loaded on use.
    parser = html5parser.HTMLParser(namespaceHTMLElements=False)
    doc = html5parser.document_fromstring(text, parser=parser)

    # Spell things as lxml does, so a page parsed either way flattens the same. html5lib
    # keeps inline SVG in its namespace and its attributes in their case -- `{svg}svg`,
    # `viewBox` -- where libxml2 says `svg` and `viewbox`; the menu carets and the
    # WhatsApp badge are both, on every page.
    for el in doc.iter():
        if not isinstance(el.tag, str):
            continue
        if "}" in el.tag:
            el.tag = el.tag.split("}", 1)[1]
        for name in list(el.attrib):
            bare = name.split("}", 1)[1] if "}" in name else name
            if bare != name or bare != bare.lower():
                el.attrib[bare.lower()] = el.attrib.pop(name)

    return doc


# Pages whose content is a query result rather than stored content. The static file is a
# snapshot of what production's query returned on the day it was cloned -- different posts,
# in an order that is not even date-descending -- so comparing the words would be comparing
# two different sites. The shape is still worth checking: the same grid, the same card, the
# same pager. The loop's own correctness is checked in _tools/verify_blog.py.
SHAPE_ONLY = {"blog"}


# Pages whose form is Contact Form 7 (theme/inc/contact.php). The reference is CF7's own
# rendering with the plugin's scaffolding stripped -- which is what the live site's markup
# is once you take away what CF7 adds -- so the same is taken away here, item by item and
# by name, and what is left has to line up element for element like any other page.
CF7 = {"contact"}

# The classes CF7's script writes onto the form as its state changes.
CF7_STATES = {"init", "submitting", "sent", "failed", "aborted", "spam", "invalid",
              "unaccepted", "payment-required", "resetting"}


def remove(el):
    """Take an element and its children out, keeping the text after it."""
    parent, prev = el.getparent(), el.getprevious()
    if el.tail:
        if prev is not None:
            prev.tail = (prev.tail or "") + el.tail
        else:
            parent.text = (parent.text or "") + el.tail
    el.tail = None
    parent.remove(el)


def unwrap(el):
    """Take an element out, leaving its children in its place."""
    parent, prev = el.getparent(), el.getprevious()
    index = parent.index(el)
    if el.text:
        if prev is not None:
            prev.tail = (prev.tail or "") + el.text
        else:
            parent.text = (parent.text or "") + el.text
    children = list(el)
    for offset, child in enumerate(children):
        parent.insert(index + offset, child)
    if el.tail:
        last = children[-1] if children else prev
        if last is not None:
            last.tail = (last.tail or "") + el.tail
        else:
            parent.text = (parent.text or "") + el.tail
    el.tail = None
    parent.remove(el)


def strip_cf7(main):
    """Take away what Contact Form 7 adds around the design's form, in place."""
    # Its own furniture: the announcement region, the hidden fields, the response box,
    # the per-field validation tips and the spinner.
    for el in main.xpath('.//*[contains(@class,"screen-reader-response")]'
                         '|.//fieldset[contains(@class,"hidden-fields-container")]'
                         '|.//*[contains(@class,"wpcf7-response-output")]'
                         '|.//*[contains(@class,"wpcf7-not-valid-tip")]'
                         '|.//*[contains(@class,"wpcf7-spinner")]'):
        remove(el)

    # Its wrappers: the outer div, and the span around every control.
    for el in main.xpath('.//div[contains(concat(" ",normalize-space(@class)," ")," wpcf7 ")]'
                         '|.//span[contains(@class,"wpcf7-form-control-wrap")]'):
        unwrap(el)

    # Turnstile mounts its widget inside the box the design reserves for it.
    for el in main.xpath('.//*[contains(@class,"cf7-cf-turnstile")]'):
        for child in list(el):
            el.remove(child)
        el.text = None
        el.attrib.pop("style", None)

    for form in main.xpath(".//form"):
        classes = [c for c in form.get("class", "").split()
                   if not c.startswith("wpcf7") and c not in CF7_STATES]
        form.set("class", " ".join(classes))
        for name in ("novalidate", "data-status"):
            form.attrib.pop(name, None)
        if "#wpcf7-" in form.get("action", ""):
            form.set("action", "#")

    for el in main.xpath(".//input|.//textarea|.//select"):
        classes = [c for c in el.get("class", "").split()
                   if not c.startswith("wpcf7-") and c != "has-spinner"]
        if classes:
            el.set("class", " ".join(classes))
        else:
            el.attrib.pop("class", None)
        for name in ("aria-required", "aria-invalid"):
            el.attrib.pop(name, None)
        if el.get("value") == "":
            el.attrib.pop("value")


def shapes(rows):
    """Tag and classes only, which is what survives a different set of posts."""
    return [(row[1], row[2].get("class", "")) for row in rows]


def compare(slug):
    live_main = main_of(fetch(slug))
    if slug in CF7:
        strip_cf7(live_main)
    live = flatten(live_main)
    ref = flatten(main_of(reference(slug)))

    if slug in SHAPE_ONLY:
        a, b = shapes(live), shapes(ref)
        if a == b:
            return len(live), len(ref), []

        n = min(len(a), len(b))
        i = 0
        while i < n and a[i] == b[i]:
            i += 1

        """
        A pager exists only when there is more than one page of posts, and the snapshot was
        taken from a site with nine. So the shapes matching all the way up to the pagination
        wrapper, with nothing after it on our side, is the right answer rather than a
        difference -- and it stops being the answer the moment the real posts arrive.
        """
        tail = b[i:]
        if not a[i:] and tail and "pagination-wrapper" in tail[0][1]:
            # Depth-first order, and the wrapper is the last thing in <main>, so everything
            # after it is inside it. One test, not a list of the classes a pager can carry.
            return len(live), len(ref), []

        return len(live), len(ref), [
            "shape at %d: wp=%s static=%s" % (i, a[i:i + 2], b[i:i + 2])
        ]

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
