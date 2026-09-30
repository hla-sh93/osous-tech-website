# -*- coding: utf-8 -*-
"""
The kinds of page this site has, and which kind each reference page is.

A kind is three things with one name: a page template in the theme
(`theme/templates/page-<kind>.php`, offered in the editor's Template panel), a starter a new
page of that kind begins from (`theme/patterns/starters/<kind>.php`), and a title. The
theme keeps the same table in `functions/templates.php`; `verify_templates.py` holds the
two together.

Which kind a reference page is comes from the site's own navigation: the pages under
Solutions and Software are solution pages, those under Services service pages, those
under Partners partner pages. The handful the menu does not reach are named here.

    python _tools/kinds.py          # the table, and every page's kind
"""
import collections
import os

from lxml import html as LH

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# In the order the editor offers them. `title` is the file's `Template Name`.
KINDS = collections.OrderedDict([
    ("home", {
        "title": "Home page",
        "description": "The front page: the hero with the partner marks, the company in a few lines, "
                       "the services, the card stack, the industries and a call to action.",
    }),
    ("about", {
        "title": "About page",
        "description": "The company: who it is, its mission and values, its numbers, its partners "
                       "and its certifications.",
    }),
    ("solution", {
        "title": "Solution page",
        "description": "One solution: what it is, what it covers as cards, why this company, "
                       "a pinned list of points and a call to action.",
    }),
    ("partner", {
        "title": "Partner page",
        "description": "One technology partner: the partnership, what is offered as numbered cards, "
                       "the solutions as cards, a pinned list of reasons and a call to action.",
    }),
    ("service", {
        "title": "Service page",
        "description": "One service: what it is, what it includes, how it is delivered "
                       "and a call to action.",
    }),
    ("contact", {
        "title": "Contact page",
        "description": "The ways to reach the company, and the enquiry form.",
    }),
    ("legal", {
        "title": "Legal page",
        "description": "A page of plain text under a page header: a policy, the terms.",
    }),
])

# The menu's own words for its three dropdowns (Software sits beside Solutions and its
# pages are solutions in everything but the menu).
MENU_KIND = {"Solutions": "solution", "Software": "solution", "Services": "service", "Partners": "partner"}

# What the menu does not say. `None` is a page with no kind: the blog is the posts index,
# drawn by home.php, and its template is never consulted.
EXPLICIT = {
    "index": "home",
    "about": "about",
    "contact": "contact",
    "privacy-policy": "legal",
    "terms-conditions": "legal",
    "it-services-iraq": "service",
    "device-management-iraq": "solution",
    "email-security-iraq": "solution",
    "blog": None,
}


def template_of(kind):
    """The template file a kind names, as WordPress stores it on a page."""
    return "templates/page-%s.php" % kind if kind else "default"


def starter_of(kind):
    """The name its starter pattern is registered under."""
    return "osous/starter-%s" % kind


def menu_kinds():
    """slug -> kind, for every page the primary menu lists under a dropdown."""
    doc = LH.parse(os.path.join(ROOT, "index.html")).getroot()
    found = {}
    for item in doc.xpath('//header//nav[contains(@class,"navbar-menu")]/div[contains(@class,"dd-menu-item")]'):
        label = item.xpath('.//span[@class="nav-link"]/text()')
        kind = MENU_KIND.get(label[0].strip() if label else "")
        if not kind:
            continue
        for href in item.xpath(".//a/@href"):
            href = (href or "").strip()
            if href.endswith(".html") and not href.startswith(("http", "#")):
                found.setdefault(href[:-5], kind)
    return found


def page_kinds():
    """slug -> kind (or None) for every reference page. Raises on a page nobody has named."""
    from_menu = menu_kinds()
    kinds = {}
    unknown = []
    for name in sorted(os.listdir(ROOT)):
        if not name.endswith(".html"):
            continue
        slug = name[:-5]
        if slug in EXPLICIT:
            kinds[slug] = EXPLICIT[slug]
        elif slug in from_menu:
            kinds[slug] = from_menu[slug]
        else:
            unknown.append(slug)
    if unknown:
        raise SystemExit("no kind for: %s -- name them in _tools/kinds.py EXPLICIT" % ", ".join(unknown))
    return kinds


if __name__ == "__main__":
    pages = page_kinds()
    counts = collections.Counter(pages.values())
    for key, kind in KINDS.items():
        print("%-9s %-14s %2d pages   %s" % (key, kind["title"], counts.get(key, 0), template_of(key)))
    print("%-9s %-14s %2d page    (the posts index)" % ("-", "no kind", counts.get(None, 0)))
    print()
    for slug, kind in sorted(pages.items()):
        print("  %-36s %s" % (slug, kind or "-"))
