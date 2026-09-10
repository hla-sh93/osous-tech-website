# -*- coding: utf-8 -*-
"""
Turn every static page's <main> into a normalised section tree.

The output is data: no markup strings beyond inline formatting. `_tools/gen_fixtures.py`
writes it out as PHP arrays that `theme/inc/sections.php` renders back into exactly the
markup we started with -- which is what makes the conversion verifiable rather than
hopeful.

Anything the vocabulary does not recognise raises. Silence would mean a page quietly
losing content, and that is the one failure mode this whole approach exists to prevent.
"""
import glob
import os
import re
from lxml import html as LH
from lxml import etree

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Tags allowed to survive inside a text node's markup, kept verbatim.
INLINE = {"strong", "span", "br", "em", "sup", "sub", "a"}
# Prose tags. A wrapper whose children are all of these is one rich-text blob.
RICH = {"h1", "h2", "h3", "h4", "h5", "h6", "p", "ul", "ol", "blockquote", "figure"}
# Prose tags that only ever appear inside a blob, never as a design element.
RICH_ONLY = {"h4", "h5", "h6", "blockquote", "figure"}

HEADINGS = {"h1", "h2", "h3", "h4", "h5", "h6"}
# The single icon the design uses, drawn 57 times.
ARROW_D = "M15.6822 18.7468L21.4651 12M21.4651 12L15.6822 5.25323M21.4651 12L3.7085 12"


class Unknown(Exception):
    pass


def cls(node):
    return " ".join((node.get("class") or "").split())


def kids(node):
    return [k for k in node if isinstance(k.tag, str) and k.tag not in ("script", "noscript")]


def inner_html(node):
    """Serialise a node's children, keeping inline markup and normalising whitespace."""
    out = node.text or ""
    for k in node:
        if not isinstance(k.tag, str):
            continue
        out += etree.tostring(k, encoding="unicode", with_tail=True)
    # Only ASCII whitespace collapses: U+00A0 and U+200D are content here, not layout.
    text = re.sub(r"[ \t\r\n\f]+", " ", out)
    # str.strip() with no argument also eats U+00A0, and several paragraphs open with one.
    return text.strip(" \t\r\n\f\v")


def is_inline_only(node):
    """True when nothing below this node is a block element.

    Descendants, not children: `<div><a><img></a></div>` is a block wrapper even though
    its only child is an anchor. Getting this wrong once cost three pages their partner
    logos, because the inline path stores markup as a string and the output filter then
    quietly dropped the image inside it.
    """
    return all(k.tag in INLINE for k in node.iterdescendants() if isinstance(k.tag, str))


def is_rich(node):
    """A prose blob: everything in it is prose, and at least one part is prose-only.

    The `at least one` half matters. Plenty of wrappers hold just an h2 and a p; those
    are two editable fields, not one lump of HTML, and flattening them would be a real
    loss of structure even though it renders the same.
    """
    ks = kids(node)
    if not ks or not all(k.tag in RICH for k in ks):
        return False
    if "rich-text-block" in (node.get("class") or ""):
        return True
    return any(k.tag in RICH_ONLY or (k.tag in ("ul", "ol") and not k.get("class")) for k in ks)


def attrs_of(node, *names):
    return {n: node.get(n) for n in names if node.get(n) is not None}


def node_to_data(n, page):
    """One HTML element -> one data node."""
    tag = n.tag
    c = cls(n)

    if tag == "img":
        d = {"t": "img"}
        if c:
            d["c"] = c
        d.update(attrs_of(n, "src", "srcset", "sizes", "alt", "fetchpriority", "width", "height"))
        return d

    if tag == "svg":
        path = n.find("path")
        if path is None or ARROW_D not in (path.get("d") or ""):
            raise Unknown(f"{page}: unexpected svg {etree.tostring(n)[:120]}")
        return {"t": "icon", "n": "arrow"}

    if tag in HEADINGS:
        if not is_inline_only(n):
            raise Unknown(f"{page}: {tag} with block children: {sorted(k.tag for k in kids(n))}")
        return {"t": "h", "g": tag, "c": c, "x": inner_html(n)}

    if tag == "p":
        if is_inline_only(n):
            return {"t": "p", "c": c, "x": inner_html(n)}
        # Contact Form 7 wraps its controls in paragraphs; keep the shape, type the fields.
        if all(k.tag in INLINE | {"input", "textarea"} for k in kids(n)):
            return {"t": "box", "g": "p", "c": c, "k": [node_to_data(k, page) for k in kids(n)]}
        raise Unknown(f"{page}: p with block children: {sorted(k.tag for k in kids(n))}")

    if tag == "a":
        d = {"t": "a", "c": c, "href": n.get("href") or "#"}
        d.update(attrs_of(n, "aria-label", "aria-current", "target", "rel"))
        if is_inline_only(n):
            d["x"] = inner_html(n)
        else:
            d["k"] = [node_to_data(k, page) for k in kids(n)]
        return d

    if tag == "button":
        d = {"t": "btn", "c": c}
        d.update(attrs_of(n, "type", "aria-label"))
        if is_inline_only(n):
            d["x"] = inner_html(n)
        else:
            d["k"] = [node_to_data(k, page) for k in kids(n)]
        return d

    # A styled list -- a design component, unlike the plain lists inside prose blobs.
    if tag in ("ul", "ol") and c:
        d = {"t": "list", "g": tag, "c": c}
        d.update(attrs_of(n, "role"))
        d["k"] = [node_to_data(k, page) for k in kids(n)]
        return d

    if tag == "li":
        d = {"t": "li", "c": c}
        if is_inline_only(n):
            d["x"] = inner_html(n)
        else:
            d["k"] = [node_to_data(k, page) for k in kids(n)]
        return d

    if tag == "form":
        d = {"t": "form", "c": c}
        d.update(attrs_of(n, "method", "action", "aria-label"))
        d["k"] = [node_to_data(k, page) for k in kids(n)]
        return d

    if tag == "br":
        return {"t": "br"}

    if tag in ("input", "textarea"):
        d = {"t": tag, "c": c}
        d.update(attrs_of(n, "id", "name", "type", "size", "maxlength", "cols", "rows",
                          "placeholder", "aria-label", "value"))
        if n.get("required") is not None:
            d["required"] = True
        return d

    if tag in ("ul", "ol", "blockquote", "table"):
        raise Unknown(f"{page}: bare <{tag}> outside a rich-text wrapper")

    # `span` reaches this branch only when it stands on its own as a block-level box --
    # the pagination's current-page marker. Inside prose it never gets its own node.
    if tag in ("section", "div", "main", "nav", "span"):
        ks = kids(n)
        if is_rich(n):
            d = {"t": "rich", "x": inner_html(n)}
            if c:
                d["c"] = c
            if tag != "div":
                d["g"] = tag
            return d
        if is_inline_only(n):
            d = {"t": "txt", "c": c, "x": inner_html(n)}
            if tag != "div":
                d["g"] = tag
            return d
        d = {"t": "box", "c": c, "k": [node_to_data(k, page) for k in ks]}
        if tag != "div":
            d["g"] = tag
        d.update(attrs_of(n, "id", "role", "aria-hidden", "data-paged", "data-query",
                          "fs-list-element", "fs-list-load", "udy-collection"))
        return d

    raise Unknown(f"{page}: unhandled <{tag} class={c!r}>")


def extract(path):
    slug = os.path.basename(path)[:-5]
    doc = LH.parse(path).getroot()
    main = doc.xpath("//main")
    if not main:
        raise Unknown(f"{slug}: no <main>")
    return slug, [node_to_data(sec, slug) for sec in kids(main[0])]


def walk(nodes):
    for d in nodes:
        yield d
        yield from walk(d.get("k", ()))


def extract_all():
    pages = {}
    for p in sorted(glob.glob(os.path.join(ROOT, "*.html"))):
        slug, data = extract(p)
        pages[slug] = data
    return pages


if __name__ == "__main__":
    import collections
    import json
    pages, bad, roles = {}, 0, collections.Counter()
    for p in sorted(glob.glob(os.path.join(ROOT, "*.html"))):
        try:
            slug, data = extract(p)
            pages[slug] = data
            for d in walk(data):
                roles[d["t"]] += 1
        except Unknown as e:
            bad += 1
            print("!!", e)
    print(f"\n{len(pages)} pages parsed, {bad} failed")
    print("node types:", dict(roles.most_common()))
    print("sections  :", sum(len(v) for v in pages.values()))
    print("bytes     :", len(json.dumps(pages, ensure_ascii=False)))
