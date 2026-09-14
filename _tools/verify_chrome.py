# -*- coding: utf-8 -*-
"""
Compare everything outside `<main>`: the navbar, the footer and the WhatsApp badge.

`_tools/verify_wp.py` deliberately checks only `<main>`, because that is where the page
content lives and where a content bug would show. The chrome is the other half, and it is
the half that changes when the footer stops being hardcoded -- addresses, phone numbers,
the map, the social links. Nothing else in the project would notice if one of those came
out wrong.

    python _tools/verify_chrome.py            # a representative handful
    python _tools/verify_chrome.py about      # one page

Known and accepted: nine elements fewer on the WordPress side, all of them dead
`.menu-list-icon` markup that the static pages carry on three of four menu columns and not
the fourth. It can never be seen -- above 991px the icon is `display: none`, and at or
below it the whole anchor that holds it is hidden -- so the theme emits the clean shape for
every column. Anything beyond those nine is a regression.
"""
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

import verify_wp as V  # noqa: E402

# The dead markup described above, as (tag, class) pairs and how many of each to expect.
ACCEPTED = {
    ("div", "menu-list-icon"): 3,
    ("div", "accordion-icon-line-v"): 3,
    ("div", "accordion-icon-line-h"): 3,
}

DEFAULT_PAGES = ["index", "about", "contact", "it-consulting-iraq", "blog"]


def outside_main(doc):
    body = doc.xpath("//body")[0]
    for main in body.xpath(".//main"):
        main.getparent().remove(main)
    # The admin bar only exists when somebody is logged in, and is not part of the design.
    for bar in body.xpath('.//*[@id="wpadminbar"]') + body.xpath('.//div[@class="nojq"]'):
        bar.getparent().remove(bar)

    """
    Each dropdown column holds the same title twice -- once for desktop, once as the
    tablet accordion handle -- and the static pages disagree with themselves about which
    comes first: one column has the desktop copy first, the other three have the tablet
    copy first. Exactly one of the two is ever visible, so the order cannot be seen.
    Sorting both sides by class compares what the column contains rather than which
    inconsistency it happens to carry.
    """
    for toggle in body.xpath('.//div[@class="dd-menu-list-toggle"]'):
        anchors = sorted(toggle, key=lambda a: a.get("class") or "")
        for anchor in anchors:
            toggle.append(anchor)

    return V.flatten(body, "body")


def compare(slug):
    # Both sides through verify_wp's parser policy: html5lib on the pages with a Contact
    # Form 7 form, lxml elsewhere. One page parsed two ways is two different pages.
    wp = outside_main(V.fetch(slug))
    ref = outside_main(V.reference(slug))

    wp_paths = {row[0] for row in wp}
    ref_paths = {row[0] for row in ref}

    only_static = [row for row in ref if row[0] not in wp_paths]
    only_wp = [row for row in wp if row[0] not in ref_paths]

    # Set the accepted dead markup aside, and complain about anything left.
    unexplained = []
    seen = {}
    for row in only_static:
        key = (row[1], row[2].get("class", ""))
        seen[key] = seen.get(key, 0) + 1
        if seen.get(key, 0) > ACCEPTED.get(key, 0):
            unexplained.append("only in static: <%s class=%r>" % (row[1], row[2].get("class", "")))

    for row in only_wp:
        unexplained.append("only in wp: <%s class=%r>" % (row[1], row[2].get("class", "")))

    # Then the shared elements, attribute for attribute and character for character.
    by_path = {row[0]: row for row in ref}
    for row in wp:
        other = by_path.get(row[0])
        if other is None or row == other:
            continue
        if row[2] != other[2]:
            diff = {k: (row[2].get(k), other[2].get(k)) for k in set(row[2]) | set(other[2])
                    if row[2].get(k) != other[2].get(k)}
            # The logo's alt text is the site's name, which is what it should be. Locally
            # that name carries a "(local)" suffix; in production the two match exactly.
            if set(diff) == {"alt"} and (diff["alt"][0] or "").startswith(diff["alt"][1] or ""):
                continue
            unexplained.append("attr at %s <%s>: %s" % (row[0], row[1], diff))
        elif row[3] != other[3]:
            unexplained.append("text at %s <%s>: wp=%r static=%r"
                               % (row[0], row[1], row[3][:80], other[3][:80]))

    return len(wp), len(ref), unexplained


def main(argv):
    slugs = argv or DEFAULT_PAGES
    bad = 0
    for slug in slugs:
        try:
            n_wp, n_ref, problems = compare(slug)
        except Exception as exc:  # noqa: BLE001 - report and keep going
            bad += 1
            print("### %-24s %s" % (slug, exc))
            continue
        if problems:
            bad += 1
            print("### %-24s wp %d el / static %d el" % (slug, n_wp, n_ref))
            for problem in problems[:6]:
                print("     ", problem[:240])
        else:
            print("OK  %-24s %d el, %d accepted differences" % (slug, n_wp, n_ref - n_wp))
    print("\n%d of %d pages differ" % (bad, len(slugs)))
    return bad


if __name__ == "__main__":
    sys.exit(1 if main(sys.argv[1:]) else 0)
