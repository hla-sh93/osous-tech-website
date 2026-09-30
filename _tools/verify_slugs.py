# -*- coding: utf-8 -*-
"""
Change a page's address, and see that nothing breaks.

    python _tools/verify_slugs.py

A page's slug is a field in the editor's sidebar, and the site has to survive somebody
using it. This renames the page most other pages link to, through WordPress -- the same
`wp_update_post()` the editor's save runs -- and looks at everything that used to break:

  - the page answers at its new address, with the same sections, on the same template
  - its old address is a permanent redirect to the new one, in one hop
  - every page that links to it prints the new address, not the old one, and so does
    the menu
  - renamed a second time, both old addresses go straight to the newest
  - given its first slug back, it is where it was: no redirect to itself, the two
    in-between addresses still arrive, the links read as they did
  - moved under another page and back, the same: its links follow, its old address
    arrives

Then it puts the page back exactly -- slug, parent, and the old slugs it collected on
the way -- and checks the page and one that links to it against the reference, so the
run leaves nothing behind. A run that fails half way may: `./_tools/env.sh rebuild`, or
fix the slug of the page it names in Pages.

Why it exists: nothing else changes a slug. Every other check reads the site as the
migration left it, where every page still has the name it was cloned with -- so a theme
that finds a page, a template or a link by slug passes all of them, and breaks the first
time a client tidies an address.

**Passing looks like:** `0 problems`
"""
import collections
import io
import json
import os
import re
import subprocess
import sys
import urllib.error
import urllib.request

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

import verify_wp as V  # noqa: E402
from kinds import page_kinds, template_of  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = V.BASE
LINK_ATTRS = ("link", "buttonUrl", "url")


def wp(*args):
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "-T", "cli", "wp"] + list(args)
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    done = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env, encoding="utf-8")
    return done.stdout.strip()


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):
        return None


def head(path):
    """(status, where it sends the visitor) for an address, without following it."""
    opener = urllib.request.build_opener(NoRedirect)
    try:
        with opener.open(BASE + path, timeout=30) as response:
            return response.status, ""
    except urllib.error.HTTPError as error:
        return error.code, error.headers.get("Location", "")


def hrefs(slug, address=None):
    """Every href on a page, header and footer included."""
    at = address or slug
    url = BASE + "/" if at == "index" else "%s/%s/" % (BASE, at)
    with urllib.request.urlopen(url, timeout=30) as response:
        text = response.read().decode("utf-8")
    return re.findall(r'<a\b[^>]*\bhref="([^"]*)"', text)


def old_slugs(page_id):
    """The addresses a page says it used to have."""
    raw = wp("post", "meta", "list", page_id, "--keys=_wp_old_slug", "--fields=meta_value", "--format=json")
    start = raw.find("[")
    return sorted(row["meta_value"] for row in json.loads(raw[start:])) if start >= 0 else []


def most_linked():
    """The page the content links to most, and the pages that link to it, from the blocks."""
    data = json.load(io.open(os.path.join(ROOT, "_migration", "blocks.json"), encoding="utf-8"))
    linkers = collections.defaultdict(collections.Counter)
    for slug, markup in data.items():
        for attr in LINK_ATTRS:
            for target in re.findall(r'"%s":"([a-z0-9-]+)\.html"' % attr, markup):
                if target != slug:
                    linkers[target][slug] += 1
    target = max(sorted(linkers), key=lambda name: sum(linkers[name].values()))
    return target, dict(linkers[target])


class Run(object):
    def __init__(self):
        self.problems = []
        self.checks = 0

    def check(self, ok, what, detail=""):
        self.checks += 1
        if ok:
            print("  ok  " + what)
        else:
            print("  !!  " + what + (" -- " + detail if detail else ""))
            self.problems.append(what)

    def arrives(self, old, new, what):
        """`old` is a single permanent redirect to `new`."""
        status, location = head("/%s/" % old)
        want = "%s/%s/" % (BASE, new)
        self.check(status == 301 and location == want, what,
                   "got %s %s, wanted 301 %s" % (status, location or "(no Location)", want))

    def links(self, linkers, address, old_addresses, what, own=None):
        """Every page that links to the page prints `address`, and none of `old_addresses`."""
        want = "%s/%s/" % (BASE, address)
        wrong = []
        for slug, count in sorted(linkers.items()):
            found = hrefs(slug)
            stale = [h for h in found if any(h == "%s/%s/" % (BASE, o) for o in old_addresses)]
            # The menu links to the page too, on every page; the content's own links are
            # the ones counted, so at least that many have to be there.
            if found.count(want) < count or stale:
                wrong.append("%s: %d to the new address (its content has %d), %d to an old one"
                             % (slug, found.count(want), count, len(stale)))
        self.check(not wrong, what, "; ".join(wrong[:3]))


def main():
    run = Run()
    target, linkers = most_linked()
    first, second = target + "-renamed", target + "-third"
    page_id = wp("post", "list", "--post_type=page", "--name=" + target, "--field=ID", "--format=ids").split()[-1:]
    if not page_id:
        raise SystemExit("no page called %s -- is the stack up?" % target)
    page_id = page_id[0]
    template = template_of(page_kinds()[target])
    parent = wp("post", "list", "--post_type=page", "--name=about", "--field=ID", "--format=ids").split()[-1]

    print("%s (page %s): %d links to it in the content of %d pages\n"
          % (target, page_id, sum(linkers.values()), len(linkers)))

    try:
        # ------------------------------------------------------------ as it starts
        print("before:")
        status, _ = head("/%s/" % target)
        run.check(status == 200, "/%s/ answers" % target, "got %s" % status)
        run.links(linkers, target, [], "every page that links to it links to /%s/" % target)

        # ------------------------------------------------------------ renamed
        print("\nrenamed to %s:" % first)
        wp("post", "update", page_id, "--post_name=" + first)
        status, _ = head("/%s/" % first)
        run.check(status == 200, "/%s/ answers" % first, "got %s" % status)
        _, _, differences = V.compare(target, address=first)
        run.check(not differences, "it is the same page: its sections match the reference", "; ".join(differences[:2]))
        held = wp("post", "meta", "get", page_id, "_wp_page_template")
        run.check(held == template, "it is still on %s" % template, "now on %r" % held)
        run.arrives(target, first, "/%s/ is a permanent redirect to it" % target)
        run.links(linkers, first, [target], "every page that linked to it links to the new address, none to the old")

        # ------------------------------------------------------------ renamed again
        print("\nrenamed again, to %s:" % second)
        wp("post", "update", page_id, "--post_name=" + second)
        run.arrives(target, second, "/%s/ goes straight to the newest address" % target)
        run.arrives(first, second, "/%s/ goes straight to the newest address" % first)
        run.links(linkers, second, [target, first], "the links follow")

        # ------------------------------------------------------------ and back
        print("\ngiven its first slug back:")
        wp("post", "update", page_id, "--post_name=" + target)
        status, location = head("/%s/" % target)
        run.check(status == 200, "/%s/ answers, and is no redirect" % target, "got %s %s" % (status, location))
        run.arrives(first, target, "/%s/ still arrives" % first)
        run.arrives(second, target, "/%s/ still arrives" % second)
        old = old_slugs(page_id)
        run.check(old == sorted([first, second]), "the page lists its two other addresses as old, and not its own",
                  "it lists %s" % old)
        run.links(linkers, target, [first, second], "the links read as they did")

        # ------------------------------------------------------------ under another page
        print("\nmoved under /about/:")
        wp("post", "update", page_id, "--post_parent=" + parent)
        nested = "about/" + target
        status, _ = head("/%s/" % nested)
        run.check(status == 200, "/%s/ answers" % nested, "got %s" % status)
        run.arrives(target, nested, "/%s/ is a permanent redirect to it" % target)
        run.links(linkers, nested, [target], "the links follow it there")
    finally:
        # ------------------------------------------------------------ as it was
        print("\nput back:")
        wp("post", "update", page_id, "--post_name=" + target, "--post_parent=0")
        wp("post", "meta", "delete", page_id, "_wp_old_slug")

    status, _ = head("/%s/" % target)
    run.check(status == 200, "/%s/ answers" % target, "got %s" % status)
    left = old_slugs(page_id)
    run.check(not left, "no old slug is left on the page", "it lists %s" % left)
    for slug in [target] + sorted(linkers)[:1]:
        _, _, differences = V.compare(slug)
        run.check(not differences, "%s matches the reference again" % slug, "; ".join(differences[:2]))

    print("\n%d checks, %d problems" % (run.checks, len(run.problems)))
    return 1 if run.problems else 0


if __name__ == "__main__":
    sys.exit(main())
