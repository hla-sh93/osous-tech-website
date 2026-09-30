# -*- coding: utf-8 -*-
"""
What every page tells a search engine.

    python _tools/verify_seo.py
    python _tools/verify_seo.py about dell-iraq     # just these pages

The reference pages each carry a document title and a description of their own, and the
site that is being replaced prints, for every page, a canonical address, a robots line,
Open Graph and Twitter tags, schema, and hreflang. On this site the first seven are the
SEO plugin's (Rank Math) and hreflang is the theme's (functions/seo.php); the words come
from fields on each page, which the seed fills from the reference. This fetches every
page and reads its <head>:

  - one <title>, and it is the reference page's
  - one description, and it is the reference page's
  - one canonical address, and it is the page's own
  - one robots line, and it lets the page be indexed and followed
  - Open Graph says the same title, description and address, and there is a Twitter card
  - the schema is there, and is JSON
  - hreflang names the page once in the site's language and once as the default

"One" is half the point: a theme and a plugin that both print a title, or a description,
is the fault this is most likely to catch, and no other check reads the <head> at all.

Needs the local stack with Rank Math active, which `env.sh rebuild` installs. Base URL
from OSOUS_WP, default http://localhost:8080.

**Passing looks like:** `57 pages, 0 problems`
"""
import html
import json
import os
import re
import sys
import urllib.request

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from refpage import page_seo, reference_slugs  # noqa: E402

BASE = os.environ.get("OSOUS_WP", "http://localhost:8080").rstrip("/")


def words(text):
    return " ".join(html.unescape(text or "").split())


def head_of(slug):
    url = BASE + "/" if slug == "index" else "%s/%s/" % (BASE, slug)
    with urllib.request.urlopen(url, timeout=30) as response:
        text = response.read().decode("utf-8")
    return text.split("</head>", 1)[0], url


def attr(tag, name):
    found = re.search(r'\b%s\s*=\s*("([^"]*)"|\'([^\']*)\')' % re.escape(name), tag)
    return html.unescape(found.group(2) if found.group(2) is not None else found.group(3)) if found else None


def tags(head, name):
    return re.findall(r"<%s\b[^>]*>" % name, head, re.I)


def metas(head, key, value):
    """The content of every <meta> whose `key` attribute is `value`."""
    return [attr(tag, "content") for tag in tags(head, "meta") if attr(tag, key) == value]


def links(head, rel):
    return [tag for tag in tags(head, "link") if attr(tag, "rel") == rel]


def check(slug):
    problems = []
    head, url = head_of(slug)
    want = page_seo(slug)

    def one(found, what):
        if len(found) != 1:
            problems.append("%d %s" % (len(found), what))
            return None
        return found[0]

    title = one(re.findall(r"<title[^>]*>(.*?)</title>", head, re.S | re.I), "<title> tags")
    if title is not None and words(title) != want["title"]:
        problems.append("title is %r, the reference has %r" % (words(title), want["title"]))

    description = one(metas(head, "name", "description"), "descriptions")
    if description is not None and words(description) != want["description"]:
        problems.append("description is %r, the reference has %r" % (words(description)[:70], want["description"][:70]))

    canonical = one(links(head, "canonical"), "canonical links")
    if canonical is not None and attr(canonical, "href") != url:
        problems.append("canonical is %r, the page is %r" % (attr(canonical, "href"), url))

    robots = one(metas(head, "name", "robots"), "robots lines")
    if robots is not None:
        said = [part.strip() for part in robots.split(",")]
        if "index" not in said or "follow" not in said or "noindex" in said or "nofollow" in said:
            problems.append("robots says %r" % robots)

    for prop, mine in (("og:title", want["title"]), ("og:description", want["description"]), ("og:url", url)):
        found = one(metas(head, "property", prop), "%s tags" % prop)
        if found is not None and words(found) != mine:
            problems.append("%s is %r, wanted %r" % (prop, words(found)[:70], mine[:70]))
    if not metas(head, "name", "twitter:card"):
        problems.append("no Twitter card")

    schema = re.findall(r'<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>', head, re.S | re.I)
    if not schema:
        problems.append("no schema")
    for block in schema:
        try:
            json.loads(block)
        except ValueError as error:
            problems.append("schema is not JSON: %s" % error)

    alternates = dict((attr(tag, "hreflang"), attr(tag, "href")) for tag in links(head, "alternate") if attr(tag, "hreflang"))
    own = [code for code, href in alternates.items() if code != "x-default" and href == url]
    if len(own) != 1:
        problems.append("hreflang names the page %d times in a language: %s" % (len(own), sorted(alternates.items())))
    if alternates.get("x-default") != url:
        problems.append("hreflang x-default is %r" % alternates.get("x-default"))

    return problems


def main(argv):
    slugs = argv or reference_slugs()
    bad = 0
    for slug in slugs:
        try:
            problems = check(slug)
        except Exception as error:  # noqa: BLE001 - report and keep going
            problems = [str(error)]
        if problems:
            bad += 1
            print("### %s" % slug)
            for problem in problems[:6]:
                print("      " + problem)
    print("%d pages, %d problems" % (len(slugs), bad))
    return 1 if bad else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
