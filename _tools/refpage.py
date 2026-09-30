# -*- coding: utf-8 -*-
"""
What a reference page says about itself outside `<main>`.

Its title as a page in the admin -- the words of its `<h1>` -- and what its `<head>` tells
a search engine: the document title and the description. The seed gives the first to
WordPress as the page's title and the other two to the SEO plugin's fields
(`rank_math_title`, `rank_math_description`), which is where they are edited from then on.
"""
import os

from lxml import html as LH

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))


def _doc(slug):
    return LH.parse(os.path.join(ROOT, slug + ".html")).getroot()


def _words(text):
    return " ".join((text or "").split())


def page_title(slug):
    """The page's title in the admin: its <h1>, or the first half of its <title>."""
    doc = _doc(slug)
    h1 = doc.xpath("//main//h1")
    if h1:
        return _words(h1[0].text_content())
    return _words((doc.findtext(".//title") or "").split("|")[0]) or slug


def page_seo(slug):
    """The document title and the meta description, exactly as the reference page has them."""
    doc = _doc(slug)
    description = doc.xpath('//head/meta[@name="description"]/@content')
    return {
        "title": _words(doc.findtext(".//title")),
        "description": _words(description[0]) if description else "",
    }


def reference_slugs():
    return sorted(name[:-5] for name in os.listdir(ROOT) if name.endswith(".html"))
