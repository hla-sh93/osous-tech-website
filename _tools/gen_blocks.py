# -*- coding: utf-8 -*-
"""
Turn the reference pages into block content, and write it into the database.

    python _tools/gen_blocks.py                # writes _migration/blocks.json and its loader, prints a report
    python _tools/gen_blocks.py --show about   # prints one page's block markup instead
    docker compose --profile tools run --rm cli wp eval-file /migration/blocks.php

Every section of every reference page becomes the theme's blocks (theme/blocks/): a page
header, a call to action, or a section holding a container, a heading, a grid of cards.
Each block's attributes are named -- `title`, `text`, `icon`, `columns`, `surface` -- and
each takes only what its template can print back, so the page WordPress renders from
these blocks is the reference page, element for element. `_tools/verify_wp.py` is the
proof, and it has to report zero differences after this runs.

Anything the blocks cannot express raises here rather than being dropped or approximated.
That is the whole discipline: a page cannot lose a heading, a class or a picture quietly.
"""
import io
import json
import os
import re
import sys

from lxml import html as LH

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from extract import ROOT, extract_all, walk  # noqa: E402

OUT = os.path.join(ROOT, "_migration", "blocks.php")     # the loader
DATA = os.path.join(ROOT, "_migration", "blocks.json")   # the content, page by page

CONTACT_FORM = '[contact-form-7 title="Contact" html_class="contact-form"]'

# The design's option tables, as theme/inc/design.php has them: value -> classes. Kept in
# step by _tools/verify_presets.py, which reads both and compares.
SPACING = {"default": "section", "tall": "section full", "slim": "section p-y-1",
           "tight-top": "section top-tight", "none": "section padding-none",
           "flat": "section p-y-0", "no-bottom": "section p-b-0", "flush": ""}
BACKGROUND = {"neutral": "c-surface-neutral", "white": "c-surface-white",
              "dark": "c-surface-secondary", "dark-pattern": "c-surface-secondary bg-img-1",
              "dark-pattern-2": "c-surface-secondary bg-img-2", "photo": "bg-img-5",
              "photo-2": "overflow-hidden bg-3"}
HEIGHT = {"three-quarters": "h-75", "screen": "h-100-vh"}
WIDTH = {"default": "container", "full": "container-full", "small": "container-sm",
         "wide": "container-default"}
SURFACE = {"white": "c-surface-white", "neutral": "c-surface-neutral",
           "primary": "c-surface-primary", "secondary": "c-surface-secondary", "plain": "c-white"}
BACKDROP = ["bg-img-%d" % n for n in (1, 2, 7, 8, 9, 10, 11, 13, 14, 15, 16)] + \
           ["img-4", "bg-12", "bg-17", "bg-azura", "bg-img-partner"]
GAP = {"sm": "gap-sm", "md": "gap-md", "1x": "gap-1x"}
CAROUSEL = {"desktop": "grid-to-carousel", "mobile": "carousel-in-mobile"}
ALIGN = {"center": "self-center", "start": "self-start",
         "center-tablet": "self-center-tablet", "justify-start": "justify-start"}
SPAN = {"2": "span-2", "3": "span-3", "4": "span-4"}
HEADING_SIZE = {"h1": "h1", "h2": "h2", "h3": "h3", "label": "t-r-md"}
TEXT_STYLE = ["p", "p-r-sm", "paragraph", "paragraph-2", "p-b", "t-r-sm", "t-r-md",
              "t-r-lg", "t-m-lg", "t-b-md", "t-b-lg", "h2", "h3"]
TONE = {"on-primary": "c-on-primary", "on-primary-subtle": "c-on-primary-subtle",
        "on-secondary": "c-on-secondary", "on-neutral": "c-on-neutral",
        "on-neutral-subtle": "c-on-neutral-subtle"}
IMAGE_SIZE = {"icon-24": "icon-24", "icon-60": "icon-60", "icon-128": "icon-128",
              "icon-160": "icon-160", "icon-256": "icon-256",
              "marquee-logo": "infinite-carousel-logo"}
IMAGE_FIT = {"half": "image h-50", "full": "image h-100", "header": "header-img-1",
             "tall": "image-custom-1"}
BUTTON = {"primary": "btn", "outline": "btn outline", "text": "btn text", "neutral": "btn neutral"}
SPACER = {"1x": "spacer-1x", "2x": "spacer-2x", "3x": "spacer-3x", "5x": "spacer-5x", "8": "spacer-8"}


class Unexpressible(Exception):
    """A node the blocks cannot hold exactly. Never caught quietly."""


class Block(object):
    """One block: its name, the attributes worth writing, and the blocks inside it.

    An attribute is written only when it says something: not for None, an empty string
    or an empty list. `False` is kept, because a card's `outline` defaults to true and
    "no outline" has to be stored to be seen.
    """

    def __init__(self, name, attrs=None, inner=None, html=None):
        self.name = name
        self.attrs = {k: v for k, v in (attrs or {}).items() if v is not None and v not in ("", [], {})}
        self.inner = inner or []
        self.html = html   # static core blocks carry their markup; ours carry none


def flag(value):
    """A boolean attribute whose default is false: written when true, silent otherwise."""
    return True if value else None


# ------------------------------------------------------------------- reading nodes --

def classes(node):
    return (node.get("c") or "").split()


def text_of(node):
    """A node's inline HTML, with `<br>` spelt as the editor spells it."""
    return (node.get("x") or "").replace("<br/>", "<br>").strip(" \t\r\n\f\v")


def is_empty(node):
    return not node.get("k") and text_of(node) == ""


def kids(node):
    return [k for k in node.get("k") or [] if isinstance(k, dict)]


def has(node, *names):
    cs = classes(node)
    return all(name in cs for name in names)


def only(node, *names):
    return sorted(classes(node)) == sorted(names)


def image_attrs(node, expect_class=None):
    """An image node as an `image` attribute: src, alt, and srcset/sizes when it has them."""
    if node.get("t") != "img":
        raise Unexpressible("expected an image, got %r" % node.get("t"))
    if expect_class is not None and sorted(classes(node)) != sorted(expect_class.split()):
        raise Unexpressible("image carries %r, expected %r" % (node.get("c"), expect_class))
    out = {"src": node.get("src", ""), "alt": node.get("alt", "")}
    for key in ("srcset", "sizes"):
        if node.get(key):
            out[key] = node[key]
    if node.get("fetchpriority") == "high":
        out["priority"] = True
    return out


def take(names, *wanted):
    """Remove `wanted` from a class list, in place; return which were there."""
    found = [w for w in wanted if w in names]
    for w in found:
        names.remove(w)
    return found


def pick(names, table):
    """The one table entry whose classes are all present, consumed; or None.

    Longest match first, so `c-surface-secondary bg-img-1` wins over `c-surface-secondary`.
    """
    for value, cls in sorted(table.items(), key=lambda kv: -len(kv[1].split())):
        want = cls.split()
        if want and all(w in names for w in want):
            for w in want:
                names.remove(w)
            return value
    return None


def residue(names, block):
    """What is left of a class list, as a `className`; or nothing."""
    return " ".join(names)


# ------------------------------------------------------------------ leaf blocks --

def heading_block(node):
    names = classes(node)
    tag = node.get("g") or "h2"
    level = int(tag[1])
    size = pick(names, HEADING_SIZE)
    attrs = {"content": text_of(node), "level": level if level != 2 else None}
    if size is None:
        attrs["size"] = "none"
    elif size != tag:
        attrs["size"] = size
    attrs["tone"] = pick(names, TONE)
    if take(names, "thin"):
        attrs["weight"] = "thin"
    if take(names, "a-c"):
        attrs["align"] = "center"
    attrs["className"] = residue(names, "heading")
    return Block("osous/heading", attrs)


def text_block(node):
    names = classes(node)
    tag = "div" if node["t"] == "txt" else "p"
    style = None
    for candidate in TEXT_STYLE:
        if candidate in names:
            names.remove(candidate)
            style = candidate
            break
    attrs = {"content": text_of(node), "tagName": tag if tag != "p" else None}
    if style is None:
        attrs["style"] = "none"
    elif style != "p":
        attrs["style"] = style
    attrs["tone"] = pick(names, TONE)
    if take(names, "thin"):
        attrs["weight"] = "thin"
    if take(names, "a-c"):
        attrs["align"] = "center"
    attrs["className"] = residue(names, "text")
    return Block("osous/text", attrs)


def image_block(node):
    names = classes(node)
    attrs = image_attrs(node)
    attrs["size"] = pick(names, IMAGE_SIZE)
    attrs["fit"] = pick(names, IMAGE_FIT)
    if take(names, "radii-container"):
        attrs["rounded"] = True
    attrs["className"] = residue(names, "image")
    return Block("osous/image", attrs)


def button_block(node, narrow=False):
    """`a.btn…` with its label inside, as the design draws it."""
    names = classes(node)
    style = pick(names, BUTTON)
    if style is None:
        raise Unexpressible("a link that is not a button: %r" % node.get("c"))
    label_class = {"primary": "t-r-md c-on-primary", "outline": "t-r-md",
                   "text": "t-r-md c-on-secondary link", "neutral": "t-r-md"}[style]
    arrow = {"outline": "icon-24 c-on-neutral", "text": "icon-24 c-neutral-active"}.get(style)

    ks = kids(node)
    if not ks or ks[0]["t"] != "txt" or sorted(classes(ks[0])) != sorted(label_class.split()):
        raise Unexpressible("button label is not %r: %r" % (label_class, ks[0] if ks else None))
    text = text_of(ks[0])
    rest = ks[1:]
    if arrow:
        if len(rest) != 1 or not only(rest[0], *arrow.split()) or [k["t"] for k in kids(rest[0])] != ["icon"]:
            raise Unexpressible("button arrow missing or wrong: %r" % rest)
    elif rest:
        raise Unexpressible("extra children in a button: %r" % rest)

    return Block("osous/button", {"text": text, "url": node.get("href", "#") if node.get("href", "#") != "#" else None,
                                  "style": style if style != "primary" else None,
                                  "narrow": flag(narrow), "className": residue(names, "button")})


def divider_block(node, narrow=False):
    names = classes(node)
    take(names, "divider")
    style = "gradient" if take(names, "gradient-1") else ("neutral" if take(names, "c-border-neutral") else None)
    if style is None:
        raise Unexpressible("a divider with no style: %r" % node.get("c"))
    return Block("osous/divider", {"style": style if style != "neutral" else None, "narrow": flag(narrow),
                                   "className": residue(names, "divider")})


def spacer_block(node):
    for value, cls in SPACER.items():
        if only(node, cls):
            return Block("osous/spacer", {"size": value if value != "3x" else None})
    raise Unexpressible("a spacer of unknown size: %r" % node.get("c"))


# ------------------------------------------------------------------------ prose --

def core_attrs(attrs):
    return " " + serialize_attrs(attrs) if attrs else ""


def inline_html(el):
    """The inside of an element, tags kept, `<br/>` spelt as the editor spells it."""
    out = el.text or ""
    for child in el:
        out += LH.tostring(child, encoding="unicode", with_tail=True)
    out = out.replace("<br/>", "<br>")
    return re.sub(r"[ \t\r\n\f]+", " ", out).strip(" \t\r\n\f\v")


def prose_blocks(html):
    """Prose as WordPress's own blocks: paragraphs, headings, lists."""
    fragment = LH.fragment_fromstring(html, create_parent="div")
    out = []
    for el in fragment:
        if not isinstance(el.tag, str):
            continue
        tag = el.tag.lower()
        if tag == "p":
            out.append(Block("core/paragraph", html="<p>%s</p>" % inline_html(el)))
        elif tag in ("h1", "h2", "h3", "h4", "h5", "h6"):
            level = int(tag[1])
            # No `wp-block-heading` here, and no `wp-block-list` below: since WordPress 7
            # the editor saves these blocks without their class and the server adds it on
            # output, where the prose block takes it away again (inc/markup.php).
            out.append(Block("core/heading", {"level": level} if level != 2 else {},
                             html="<%s>%s</%s>" % (tag, inline_html(el), tag)))
        elif tag in ("ul", "ol") and not any(isinstance(li.tag, str) for li in el):
            # A list with no items and a stray character in it, twice on the site. The
            # list block cannot hold text, so it stays as the markup it is.
            out.append(Block("core/html", html=LH.tostring(el, encoding="unicode", with_tail=False).strip()))
        elif tag in ("ul", "ol"):
            items = []
            for li in el:
                if not isinstance(li.tag, str):
                    continue
                if li.tag.lower() != "li" or any(isinstance(k.tag, str) and k.tag in ("ul", "ol") for k in li):
                    raise Unexpressible("a list the list block cannot hold: %s" % LH.tostring(el)[:120])
                items.append(Block("core/list-item", html="<li>%s</li>" % inline_html(li)))
            out.append(Block("core/list", {"ordered": True} if tag == "ol" else {}, inner=items,
                             html="<%s>%%s</%s>" % (tag, tag)))
        elif tag == "blockquote":
            # Only on the two placeholder legal pages, and as bare text, which the quote
            # block would wrap in a paragraph. Kept as it is, in the block for exactly that.
            out.append(Block("core/html", html="<blockquote>%s</blockquote>" % inline_html(el)))
        else:
            raise Unexpressible("prose holds a <%s>" % tag)
    return out


def prose_block(node):
    names = classes(node)
    style = "block" if take(names, "rich-text-block") else "plain"
    return Block("osous/prose", {"style": style if style != "block" else None, "className": residue(names, "prose")},
                 inner=prose_blocks(node.get("x") or ""))


# ------------------------------------------------------------------------ cards --

def media(children, allow_none=True):
    """The `a.card-media` at the front of a card: (icon, link, media), consuming it."""
    if not children or children[0]["t"] != "a":
        return {}, None, "none" if allow_none else None
    link = children.pop(0)
    href = link.get("href", "#")
    ks = kids(link)
    if only(link) and not ks and text_of(link) == "":
        # Webflow's bare empty link at the top of a card with no icon. The blocks give the
        # card its empty media box instead: the same zero-height flex item, opening the
        # same gap. _tools/verify_wp.py reads the two as one thing, and the layout check
        # is where that was proved.
        return {}, href, "box"
    if not only(link, "card-media"):
        raise Unexpressible("a card's link carries %r" % link.get("c"))
    if not ks:
        return {}, href, "box"
    if len(ks) != 1:
        raise Unexpressible("a card's media holds %d things" % len(ks))
    return image_attrs(ks[0], "icon-128"), href, "box"


def number_of(children):
    if children and children[0]["t"] == "box" and only(children[0], "number"):
        box = children.pop(0)
        ks = kids(box)
        if len(ks) != 2 or not only(ks[0], "h3", "number-style-1") or not only(ks[1], "number-shape"):
            raise Unexpressible("a number badge of an unknown shape")
        return text_of(ks[0])
    return ""


def next_is(children, t, *names):
    return bool(children) and children[0]["t"] == t and only(children[0], *names)


def card_button(children):
    if next_is(children, "a", "btn", "_w-max-200", "m-t-auto"):
        link = children.pop(0)
        ks = kids(link)
        if len(ks) != 1 or not only(ks[0], "t-r-md", "c-on-primary"):
            raise Unexpressible("a card button with an unexpected label")
        return text_of(ks[0]), link.get("href", "#")
    return "", None


def card_block(node):
    ch = kids(node)
    attrs = {}
    if next_is(ch, "txt", "card1-outline"):
        ch.pop(0)
    else:
        attrs["outline"] = False
    icon, link, media_kind = media(ch)
    attrs.update({"icon": icon, "link": link if link not in (None, "#") else None,
                  "media": media_kind if media_kind != "box" else None})
    if next_is(ch, "txt", "card1-background"):
        ch.pop(0)
        attrs["fill"] = True
    if next_is(ch, "txt", "h2", "thin", "position-relative-10"):
        attrs["stat"] = text_of(ch.pop(0))
    if next_is(ch, "h", "position-relative-10") and ch[0].get("g") == "h3":
        attrs["title"] = text_of(ch.pop(0))
    if next_is(ch, "p", "position-relative-10"):
        attrs["text"] = text_of(ch.pop(0))
    attrs["buttonText"], attrs["buttonUrl"] = card_button(ch)
    if ch:
        raise Unexpressible("card-1 holds something else: %r" % ch[0])
    if not icon and media_kind == "box" and attrs.get("outline", True) is False:
        # WordPress cannot tell a false `outline` written as false from one never written;
        # both are stored, so nothing to do. Kept as a note of the one asymmetry.
        pass
    return Block("osous/card", attrs)


def numbered_card_block(node):
    ch = kids(node)
    attrs = {"number": number_of(ch)}
    if ch and ch[0]["t"] == "img":
        attrs["icon"] = image_attrs(ch.pop(0), "icon-60")
    if next_is(ch, "h", "h3", "c-on-primary") and ch[0].get("g") == "h3":
        attrs["title"] = text_of(ch.pop(0))
    if next_is(ch, "p", "p", "c-on-primary-subtle"):
        attrs["text"] = text_of(ch.pop(0))
    if next_is(ch, "txt", "card2-background"):
        ch.pop(0)
    else:
        attrs["fill"] = False
    inner = []
    if ch and ch[0]["t"] == "rich":
        rich = ch.pop(0)
        if not only(rich, "rich-text-block"):
            raise Unexpressible("card-2 prose carries %r" % rich.get("c"))
        inner = prose_blocks(rich.get("x") or "")
    attrs["buttonText"], attrs["buttonUrl"] = card_button(ch)
    if next_is(ch, "txt", "card1-outline"):
        ch.pop(0)
        attrs["outline"] = True
    if ch:
        raise Unexpressible("card-2 holds something else: %r" % ch[0])
    return Block("osous/numbered-card", attrs, inner=inner)


def highlight_card_block(node):
    ch = kids(node)
    icon, link, media_kind = media(ch, allow_none=False)
    if media_kind != "box":
        raise Unexpressible("card-5 without its media link")
    attrs = {"icon": icon, "link": link if link != "#" else None}
    if next_is(ch, "h", "h3", "c-on-primary") and ch[0].get("g") == "h3":
        attrs["title"] = text_of(ch.pop(0))
    if not next_is(ch, "p", "p-r-sm", "c-on-secondary", "position-relative"):
        raise Unexpressible("card-5 without its text")
    attrs["text"] = text_of(ch.pop(0))
    if not next_is(ch, "txt", "card3-bg"):
        raise Unexpressible("card-5 without its wash")
    ch.pop(0)
    if next_is(ch, "txt", "card1-outline"):
        ch.pop(0)
    else:
        attrs["outline"] = False
    if ch:
        raise Unexpressible("card-5 holds something else: %r" % ch[0])
    return Block("osous/highlight-card", attrs)


def photo_card_block(node):
    ch = kids(node)
    attrs = {"number": number_of(ch)}
    icon, link, media_kind = media(ch)
    attrs.update({"icon": icon, "link": link if link not in (None, "#") else None,
                  "media": media_kind if media_kind != "box" else None})
    if not (ch and ch[0]["t"] == "box" and only(ch[0], "content")):
        raise Unexpressible("card-4 without its content box")
    content = kids(ch.pop(0))
    if content and content[0]["t"] == "h" and only(content[0]) and content[0].get("g") == "h3":
        attrs["title"] = text_of(content.pop(0))
    if len(content) != 1 or not next_is(content, "p", "paragraph"):
        raise Unexpressible("card-4 content is not [h3] + p.paragraph: %r" % content)
    attrs["text"] = text_of(content[0])
    if ch:
        raise Unexpressible("card-4 holds something else: %r" % ch[0])
    return Block("osous/photo-card", attrs)


def overlay_card_block(node):
    ch = kids(node)
    attrs = {"number": number_of(ch)}
    if not (ch and ch[0]["t"] == "box" and only(ch[0], "div-block-2")):
        raise Unexpressible("card-3 without its text box")
    words = kids(ch.pop(0))
    if len(words) != 2 or not only(words[0], "h3", "c-on-primary") or not only(words[1], "p", "c-on-primary-subtle"):
        raise Unexpressible("card-3 text box of an unknown shape")
    attrs["title"], attrs["text"] = text_of(words[0]), text_of(words[1])
    if not next_is(ch, "txt", "card-3-gradient"):
        raise Unexpressible("card-3 without its gradient")
    ch.pop(0)
    if len(ch) != 1:
        raise Unexpressible("card-3 without its picture")
    attrs["image"] = image_attrs(ch[0], "card-3-image")
    return Block("osous/overlay-card", attrs)


def feature_card_block(node):
    ch = kids(node)
    if len(ch) != 2 or not only(ch[0], "feature-content-item") or not has(ch[1], "feature-image-item"):
        raise Unexpressible("feature-card of an unknown shape")
    inner = kids(ch[0])
    if len(inner) != 1 or not only(inner[0], "feature-content-item-content"):
        raise Unexpressible("feature-card content of an unknown shape")
    words = kids(inner[0])
    if len(words) != 2 or not only(words[0], "h2") or words[0].get("g") != "h3" or not only(words[1], "p"):
        raise Unexpressible("feature-card words of an unknown shape")
    names = classes(ch[1])
    take(names, "feature-image-item")
    media_first = flag(take(names, "media-first"))
    if names:
        raise Unexpressible("feature-card picture carries %r" % names)
    pictures = kids(ch[1])
    if len(pictures) != 1:
        raise Unexpressible("feature-card without one picture")
    return Block("osous/feature-card", {
        "title": text_of(words[0]), "text": text_of(words[1]),
        "image": image_attrs(pictures[0], "feature-image-item-image"), "mediaFirst": flag(media_first)})


def partner_block(node):
    if only(node, "partner-card-logo", "_w-100"):
        logo_box, caption = node, ""
    elif only(node, "partner-card"):
        ch = kids(node)
        if len(ch) != 2 or not only(ch[0], "partner-card-logo") or not only(ch[1], "p"):
            raise Unexpressible("partner-card of an unknown shape")
        logo_box, caption = ch[0], text_of(ch[1])
    else:
        raise Unexpressible("not a partner: %r" % node.get("c"))
    links = kids(logo_box)
    if len(links) != 1 or not only(links[0], "card-media"):
        raise Unexpressible("partner without its link")
    pictures = kids(links[0])
    if len(pictures) != 1:
        raise Unexpressible("partner without its mark")
    href = links[0].get("href", "#")
    return Block("osous/partner", {"logo": image_attrs(pictures[0], "icon-128"),
                                   "link": href if href != "#" else None, "caption": caption})


def list_block(node):
    names = classes(node)
    take(names, "list")
    tone = pick(names, TONE)
    if names or node.get("g") != "ul":
        raise Unexpressible("a styled list carrying %r" % node.get("c"))
    items = []
    style = None
    for li in kids(node):
        marker = "bullet" if only(li, "list-item-bullet") else ("tick" if only(li, "list-item-tick") else None)
        if marker is None or (style and marker != style):
            raise Unexpressible("a list item carrying %r" % li.get("c"))
        style = marker
        ks = kids(li)
        if len(ks) != 1 or ks[0]["t"] != "txt":
            raise Unexpressible("a list item that is not one label")
        item_names = classes(ks[0])
        take(item_names, "t-r-md")
        item_tone = pick(item_names, TONE)
        if item_names:
            raise Unexpressible("a list item label carrying %r" % ks[0].get("c"))
        items.append(Block("osous/list-item", {"content": text_of(ks[0]), "tone": item_tone}))
    return Block("osous/list", {"style": style if style != "bullet" else None, "tone": tone}, inner=items)


# --------------------------------------------------------------- the generic path --

def convert(node):
    """Any node inside a section, as a block. Raises for what no block can hold."""
    t = node["t"]

    if t == "h":
        return heading_block(node)
    if t == "p":
        return text_block(node)
    if t == "img":
        return image_block(node)
    if t == "rich":
        return prose_block(node)
    if t == "list":
        return list_block(node)
    if t == "a":
        if has(node, "btn"):
            return button_block(node)
        raise Unexpressible("a link outside a card or a button: %r" % node.get("c"))

    if t == "txt":
        if text_of(node) != "":
            return text_block(node)
        if has(node, "divider"):
            return divider_block(node)
        if any(only(node, cls) for cls in SPACER.values()):
            return spacer_block(node)
        if only(node):
            return None     # an empty, class-less div: Webflow debris, nothing on the page
        raise Unexpressible("an empty element carrying %r" % node.get("c"))

    if t == "box":
        names = classes(node)
        first = names[0] if names else ""

        if first == "card-1":
            return card_block(node)
        if first == "card-2":
            return numbered_card_block(node)
        if first == "card-5":
            return highlight_card_block(node)
        if first == "card-4":
            return photo_card_block(node)
        if first == "card-3":
            return overlay_card_block(node)
        if first in ("partner-card", "partner-card-logo"):
            return partner_block(node)
        if only(node, "partner-cards-wrapper"):
            return Block("osous/partners", inner=convert_all(kids(node)))

        if only(node, "_w-max-200") and len(kids(node)) == 1:
            inner = kids(node)[0]
            if inner["t"] == "txt" and has(inner, "divider"):
                return divider_block(inner, narrow=True)
            if inner["t"] == "a" and has(inner, "btn"):
                return button_block(inner, narrow=True)

        if first.startswith("container"):
            return container_block(node)
        if re.match(r"^grid-[1-5]$", first):
            return grid_block(node)
        if first == "cell":
            return column_block(node)
        if first == "heading":
            return section_heading_block(node)
        if only(node, "content"):
            return Block("osous/stack", inner=convert_all(kids(node)))
        if first in ("modal", "card"):
            return panel_block(node)

        return Block("osous/group", {"tagName": node.get("g") if node.get("g") not in (None, "div") else None,
                                     "className": " ".join(names)}, inner=convert_all(kids(node)))

    raise Unexpressible("no block for a %r node" % t)


def convert_all(nodes):
    out = []
    for node in nodes:
        block = convert(node)
        if block is not None:
            out.append(block)
    return out


def container_block(node):
    names = classes(node)
    width = pick(names, WIDTH)
    if width is None:
        raise Unexpressible("a container of unknown width: %r" % node.get("c"))
    attrs = {"width": width if width != "default" else None}
    attrs["fit"] = flag(take(names, "fit"))
    attrs["surface"] = pick(names, SURFACE)
    attrs["border"] = flag(take(names, "c-border-neutral"))
    attrs["elevation"] = flag(take(names, "elevation"))
    attrs["mobilePadding"] = flag(take(names, "padding-x-on-mobile"))
    attrs["carousel"] = flag(take(names, "grid-to-carousel"))
    attrs["fullHeight"] = flag(take(names, "h-100"))
    for backdrop in BACKDROP:
        if backdrop in names:
            names.remove(backdrop)
            attrs["backdrop"] = backdrop
            break
    attrs["className"] = residue(names, "container")
    return Block("osous/container", attrs, inner=convert_all(kids(node)))


def grid_block(node):
    names = classes(node)
    columns = int(names.pop(0)[-1])
    attrs = {"columns": columns if columns != 2 else None}
    attrs["gap"] = pick(names, GAP)
    attrs["carousel"] = pick(names, CAROUSEL)
    attrs["fullHeight"] = flag(take(names, "h-100"))
    attrs["className"] = residue(names, "grid")
    return Block("osous/grid", attrs, inner=convert_all(kids(node)))


def column_block(node):
    names = classes(node)
    take(names, "cell")
    attrs = {"align": pick(names, ALIGN), "span": pick(names, SPAN)}
    attrs["wideOnMobile"] = flag(take(names, "span-mobile-2"))
    attrs["mediaFirst"] = flag(take(names, "media-first"))
    attrs["className"] = residue(names, "column")
    return Block("osous/column", attrs, inner=convert_all(kids(node)))


def section_heading_block(node):
    names = classes(node)
    take(names, "heading")
    attrs = {"align": "center" if take(names, "center") else None, "className": residue(names, "section-heading")}
    return Block("osous/section-heading", attrs, inner=convert_all(kids(node)))


def panel_block(node):
    names = classes(node)
    kind = names.pop(0)
    attrs = {"kind": kind if kind != "modal" else None, "surface": pick(names, SURFACE)}
    for backdrop in BACKDROP:
        if backdrop in names:
            names.remove(backdrop)
            attrs["backdrop"] = backdrop
            break
    attrs["className"] = residue(names, "panel")
    return Block("osous/panel", attrs, inner=convert_all(kids(node)))


def section_block(node):
    """`<section>` as a section block: the band, then its container."""
    names = classes(node)
    attrs = {}
    if take(names, "section"):
        spacing = None
        for value, cls in SPACING.items():
            extra = cls.split()[1:]
            if extra and extra[0] in names:
                names.remove(extra[0])
                spacing = value
                break
        attrs["spacing"] = spacing
    else:
        attrs["spacing"] = "flush"
    attrs["background"] = pick(names, BACKGROUND)
    attrs["height"] = pick(names, HEIGHT)
    attrs["joinBelow"] = flag(take(names, "m-b-0"))
    attrs["anchor"] = node.get("id")
    attrs["className"] = residue(names, "section")
    return Block("osous/section", attrs, inner=convert_all(kids(node)))


# ---------------------------------------------------------- the dedicated sections --

def one_child(node, t, *names):
    ks = kids(node)
    if len(ks) != 1 or ks[0]["t"] != t or not only(ks[0], *names):
        raise Unexpressible("expected one %s.%s under %r, got %r" % (t, ".".join(names), node.get("c"), ks))
    return ks[0]


def page_header(node):
    if not only(node, "section", "header"):
        return None
    contents = one_child(one_child(node, "box", "container", "h-100"), "box", "header-contents")
    ch = kids(contents)
    attrs = {}
    if ch and ch[0]["t"] == "txt" and only(ch[0], "t-r-md", "c-on-neutral-subtle"):
        attrs["eyebrow"] = text_of(ch.pop(0))
        rule = ch.pop(0)
        if not only(rule, "_w-max-200") or not is_empty(one_child(rule, "txt", "divider", "gradient-1")):
            raise Unexpressible("page header rule of an unknown shape")
    if len(ch) != 1 or not only(ch[0]):
        raise Unexpressible("page header title box of an unknown shape")
    words = kids(ch[0])
    if not words or not only(words[0], "h2", "a-c") or words[0].get("g") != "h1":
        raise Unexpressible("page header without its h1")
    attrs["title"] = text_of(words[0])
    if len(words) == 2:
        if not only(words[1], "p-r-sm", "c-on-neutral", "a-c"):
            raise Unexpressible("page header text carries %r" % words[1].get("c"))
        attrs["text"] = text_of(words[1])
    elif len(words) > 2:
        raise Unexpressible("page header holds more than a title and a line")
    return Block("osous/page-header", attrs)


def call_to_action(node):
    if not only(node, "section", "top-tight"):
        return None
    ks = kids(node)
    if len(ks) != 1 or not only(ks[0], "container", "c-surface-white", "c-border-neutral"):
        return None     # `top-tight` on some other section: an ordinary section, tight at the top
    heading = one_child(ks[0], "box", "heading", "center")
    ch = kids(heading)
    if len(ch) != 4 or not only(ch[0], "h2", "a-c") or not only(ch[1], "p", "a-c") or not only(ch[2], "spacer-3x"):
        raise Unexpressible("call to action of an unknown shape")
    button = button_block(ch[3])
    if button.attrs.get("style") != "outline":
        raise Unexpressible("call to action button is not outlined")
    return Block("osous/call-to-action", {"title": text_of(ch[0]), "text": text_of(ch[1]),
                                          "buttonText": button.attrs["text"],
                                          "buttonUrl": button.attrs.get("url", "#") if button.attrs.get("url", "contact.html") != "contact.html" else None,
                                          "anchor": node.get("id")})


def pinned_item_block(node):
    names = classes(node)
    take(names, "list-item", "active")
    if names:
        raise Unexpressible("a pinned item carrying %r" % node.get("c"))
    ch = kids(node)
    attrs = {}
    if ch and ch[0]["t"] == "txt":
        title = ch.pop(0)
        if only(title, "t-m-lg"):
            pass
        elif only(title, "t-b-lg"):
            attrs["titleStyle"] = "bold"
        else:
            raise Unexpressible("a pinned item title carrying %r" % title.get("c"))
        attrs["title"] = text_of(title)
    if ch and ch[0]["t"] == "p":
        text = ch.pop(0)
        style = {"p": "normal", "p-r-sm": "small", "": "plain", "p-b": "bold"}.get(" ".join(classes(text)))
        if style is None:
            raise Unexpressible("a pinned item text carrying %r" % text.get("c"))
        attrs["textStyle"] = style if style != "normal" else None
        attrs["text"] = text_of(text)
    if ch:
        raise Unexpressible("a pinned item holds something else: %r" % ch[0])
    return Block("osous/pinned-item", attrs)


def pinned_list(node):
    if not only(node, "section", "list-scroll-section"):
        return None
    container = one_child(node, "box", "container")
    scroll = [k for k in kids(container) if not (k["t"] == "txt" and only(k) and is_empty(k))]
    if len(scroll) != 1 or not only(scroll[0], "list-scroll"):
        raise Unexpressible("pinned list without its scroll box")
    grid = [k for k in kids(scroll[0]) if not (k["t"] == "txt" and only(k) and is_empty(k))]
    if len(grid) != 1 or not only(grid[0], "grid-2"):
        raise Unexpressible("pinned list without its grid")
    halves = kids(grid[0])
    if len(halves) != 2 or not has(halves[0], "cell") or not only(halves[1], "list-items-wrapper"):
        raise Unexpressible("pinned list halves of an unknown shape")
    items = [pinned_item_block(item) for item in kids(halves[1])]
    # extract.py names a tag only when it is not a div, so no tag means a div.
    return Block("osous/pinned-list", {"tagName": "div" if scroll[0].get("g") != "section" else None},
                 inner=[column_block(halves[0]), Block("osous/pinned-items", inner=items)])


def split_section(node):
    if not only(node, "section", "split-section"):
        return None
    band = one_child(node, "box", "container-full", "c-surface-neutral", "h-100")
    ch = kids(band)
    if len(ch) != 2 or not only(ch[0], "misc") or not only(ch[1], "feature-card-wrapper"):
        raise Unexpressible("split section of an unknown shape")
    words = kids(one_child(ch[0], "box", "div-block"))
    if len(words) != 2 or not only(words[1], "hide-on-tablet"):
        raise Unexpressible("split section intro of an unknown shape")
    attrs = {}
    if words[0]["t"] == "h" and only(words[0], "t-r-md"):
        attrs["title"] = text_of(words[0])
    elif words[0]["t"] == "box" and only(words[0], "heading"):
        pair = kids(words[0])
        if len(pair) != 2 or not only(pair[0], "t-r-md") or not only(pair[1]):
            raise Unexpressible("split section heading of an unknown shape")
        attrs["title"], attrs["text"] = text_of(pair[0]), text_of(pair[1])
    else:
        raise Unexpressible("split section without its title")
    cards = [feature_card_block(card) for card in kids(ch[1])]
    dots = kids(one_child(words[1], "box", "flex-h"))
    if len(dots) != len(cards):
        raise Unexpressible("split section has %d dots for %d features" % (len(dots), len(cards)))
    return Block("osous/split-section", attrs, inner=cards)


def hero(node):
    if not only(node, "bg-img-6", "h-100-vh"):
        return None
    ch = kids(node)
    if len(ch) != 2 or not only(ch[0], "container", "h-85") or not only(ch[1], "infinite-carousel-wrapper"):
        raise Unexpressible("hero of an unknown shape")
    heading = one_child(one_child(ch[0], "box", "flex-v", "a-c-c", "h-100"), "box", "heading", "center")
    words = kids(heading)
    if len(words) != 2 or not only(words[0], "h1", "c-on-secondary", "a-c") or not only(words[1], "p", "c-on-secondary", "a-c"):
        raise Unexpressible("hero words of an unknown shape")
    rows = kids(ch[1])
    if len(rows) != 2 or not only(rows[0], "infinite-carousel") or not only(rows[1], "infinite-carousel") \
            or rows[1].get("aria-hidden") != "true":
        raise Unexpressible("hero marquee of an unknown shape")
    marks = [Block("osous/image", dict(image_attrs(img, "infinite-carousel-logo"), size="marquee-logo")) for img in kids(rows[0])]
    copies = [img.get("src") for img in kids(rows[1])]
    if copies != [m.attrs["src"] for m in marks]:
        raise Unexpressible("hero marquee copies differ")
    return Block("osous/hero", {"title": text_of(words[0]), "text": text_of(words[1])}, inner=marks)


def card_stack(node):
    if not only(node, "section", "overlapping-cards"):
        return None
    grid = one_child(one_child(one_child(node, "box", "flex-v", "h-100", "a-c-c"), "box", "container"), "box", "grid-2")
    halves = kids(grid)
    if len(halves) != 2 or not only(halves[0], "cell", "self-center", "why-us-heading") or not only(halves[1], "cards-overlapping-wrapper"):
        raise Unexpressible("card stack of an unknown shape")
    title = one_child(halves[0], "h", "h2", "c-on-secondary")
    cards = []
    for item in kids(halves[1]):
        if not only(item, "card-overlapping-item"):
            raise Unexpressible("card stack item carrying %r" % item.get("c"))
        cards.append(photo_card_block(one_child(item, "box", "card-4", "bg-blur")))
    return Block("osous/card-stack", {"title": text_of(title)}, inner=cards)


def industries_carousel(node):
    if not only(node, "section", "overflow-hidden", "bg-3") or not has(one_child(node, "box", "container", "swiper-container"), "swiper-container"):
        return None
    container = one_child(node, "box", "container", "swiper-container")
    ch = kids(container)
    if len(ch) != 2 or not only(ch[0], "flex-h", "a-sb-t") or not only(ch[1], "flex-h", "swiper-wrapper"):
        raise Unexpressible("carousel of an unknown shape")
    top = kids(ch[0])
    if len(top) != 2 or not only(top[0], "heading") or not only(top[1], "button-wrapper"):
        raise Unexpressible("carousel top of an unknown shape")
    words = kids(top[0])
    if len(words) != 2 or not only(words[0], "h2", "c-on-secondary") or not only(words[1], "p", "c-on-secondary"):
        raise Unexpressible("carousel words of an unknown shape")
    cards = []
    for slide in kids(ch[1]):
        if not only(slide, "swiper-slide", "industry-specific"):
            raise Unexpressible("carousel slide carrying %r" % slide.get("c"))
        card = kids(slide)
        if len(card) != 1 or not has(card[0], "card-4", "bg-blur"):
            raise Unexpressible("carousel slide without its card")
        cards.append(photo_card_block(card[0]))
    return Block("osous/industries-carousel", {"title": text_of(words[0]), "text": text_of(words[1])}, inner=cards)


def contact(node):
    if not only(node, "section", "contact"):
        return None
    grid = one_child(one_child(node, "box", "container-full", "c-surface-neutral"), "box", "grid-2")
    halves = kids(grid)
    if len(halves) != 2 or not only(halves[0], "container") or not only(halves[1], "modal", "form-panel"):
        raise Unexpressible("contact section halves of an unknown shape")
    left = kids(one_child(halves[0], "box", "f-v-l-c"))
    if len(left) != 3 or not only(left[0], "heading") or not only(left[1], "divider", "c-border-neutral") or not only(left[2], "content"):
        raise Unexpressible("contact details of an unknown shape")
    title = one_child(left[0], "h", "h2")
    content = kids(left[2])
    if len(content) != 3 or not only(content[0], "h3") or not only(content[1], "call-us") or not only(content[2], "spacer-3x"):
        raise Unexpressible("contact content of an unknown shape")
    rows = kids(content[1])
    phones = []
    while len(rows) >= 2 and rows[0]["t"] == "txt" and only(rows[0], "t-m-lg") and rows[1]["t"] == "a":
        label, link = rows.pop(0), rows.pop(0)
        m = re.match(r'^<span class="t-b-lg link">(.*)</span>$', link.get("x") or "")
        if not m or link.get("href") != "tel:" + re.sub(r"\s+", "", m.group(1)):
            raise Unexpressible("contact phone of an unknown shape: %r" % link)
        phones.append({"label": text_of(label), "number": m.group(1)})
    if len(rows) != 3 or not only(rows[0], "span-2") or not only(rows[1], "t-m-lg") or rows[2]["t"] != "a":
        raise Unexpressible("contact email row of an unknown shape")
    m = re.match(r'^<span class="t-b-lg link reveal-left">(.*)</span>$', rows[2].get("x") or "")
    if not m or rows[2].get("href") != "mailto:" + m.group(1):
        raise Unexpressible("contact email of an unknown shape")
    panel = kids(halves[1])
    if len(panel) != 2 or not only(panel[0], "heading") or not only(panel[1], "form-block"):
        raise Unexpressible("contact form panel of an unknown shape")
    words = kids(panel[0])
    if len(words) != 2 or not only(words[0], "h3", "c-on-primary", "a-c") or not only(words[1], "t-r-lg", "c-on-primary-subtle", "a-c"):
        raise Unexpressible("contact form heading of an unknown shape")
    return Block("osous/contact", {
        "title": text_of(title), "subtitle": text_of(content[0]), "phones": phones,
        "emailLabel": text_of(rows[1]) if text_of(rows[1]) != "Email" else None, "email": m.group(1),
        "formTitle": text_of(words[0]), "formText": text_of(words[1]),
        "anchor": node.get("id")})


DEDICATED = (page_header, call_to_action, pinned_list, split_section, hero, card_stack,
             industries_carousel, contact)


def convert_section(node):
    """One top-level section of a page, as a block."""
    if node["t"] == "txt" and has(node, "divider"):
        return divider_block(node)
    if node["t"] != "box" or node.get("g") != "section":
        raise Unexpressible("a top-level node that is not a section: %r" % node.get("c"))
    for matcher in DEDICATED:
        block = matcher(node)
        if block is not None:
            return block
    return section_block(node)


# ---------------------------------------------------------------- serialising --

def serialize_attrs(attrs):
    """Block attributes the way the editor writes them.

    `<`, `>`, `&`, `--` and the escaped quote become \\u sequences so the JSON cannot end
    the HTML comment it sits in -- the same rules as WordPress's serialize_block_attributes().
    """
    text = json.dumps(attrs, ensure_ascii=False, separators=(",", ":"))
    for a, b in (("--", "\\u002d\\u002d"), ("<", "\\u003c"), (">", "\\u003e"), ("&", "\\u0026"), ('\\"', "\\u0022")):
        text = text.replace(a, b)
    return text


def serialize(block, indent="", step=""):
    """Block markup, one block per line; with a `step`, nested blocks are indented by it."""
    name = block.name if not block.name.startswith("core/") else block.name[5:]
    head = "<!-- wp:%s%s" % (name, " " + serialize_attrs(block.attrs) if block.attrs else "")

    if block.html is not None:
        # A core block: its saved markup, with its list items inside the list's tags.
        inner = block.html
        if block.inner:
            inner = block.html % "\n\n".join(serialize(child, "", "") for child in block.inner)
        return "%s%s -->\n%s%s\n%s<!-- /wp:%s -->" % (indent, head, indent, inner, indent, name)

    if not block.inner:
        return "%s%s /-->" % (indent, head)

    children = "\n\n".join(serialize(child, indent + step, step) for child in block.inner)
    return "%s%s -->\n%s\n%s<!-- /wp:%s -->" % (indent, head, children, indent, name)


def page_markup(nodes, indent=False):
    blocks = [convert_section(node) for node in nodes]
    return "\n\n".join(serialize(block, "", "\t" if indent else "") for block in blocks)


# ---------------------------------------------------------------- the labels --

# A name for each kind of section, for the pattern library and the reports. Matched in
# order: the first hit wins, so the most distinctive marker has to come first.
LABELS = [
    ("header-contents", "Page header"),
    ("list-scroll", "Pinned list"),
    ("split-section", "Split section"),
    ("card-overlapping-item", "Card stack"),
    ("swiper-container", "Industries carousel"),
    ("infinite-carousel", "Hero"),
    ("contact-form", "Contact form"),
    ("blog-card", "Blog list"),
    ("partner-card", "Partner logos"),
    ("partner-card-logo", "Partner logos"),
    ("rich-text-block", "Rich text"),
    ("feature-card", "Feature cards"),
    ("card-2", "Numbered cards"),
    ("card-5", "Highlight cards"),
    ("card-4", "Photo cards"),
    ("card-3", "Overlay cards"),
    ("card-1", "Cards"),
    ("list-item-tick", "Tick list"),
    ("list-item-bullet", "Bullet list"),
    ("modal", "Intro panel"),
    ("cell", "Columns"),
    ("grid-2", "Text pair"),
    ("heading", "Heading"),
    ("divider", "Divider"),
]


def classes_in(node):
    found = set()
    for item in walk([node]):
        found.update(classes(item))
        if item.get("t") == "rich":
            found.add("rich-text-block")
    return found


def label_for(node):
    present = classes_in(node)
    if "top-tight" in present and "c-border-neutral" in present:
        return "Call to action"
    for marker, name in LABELS:
        if marker in present:
            return name
    return "Section"


# ------------------------------------------------------------------ the script --

PHP = """<?php
/**
 * Write the reference pages' content into the database as blocks.
 *
 * Generated by _tools/gen_blocks.py -- local development only. Run once:
 *
 *   docker compose --profile tools run --rm cli wp eval-file /migration/blocks.php
 *
 * Rerunning it overwrites every page, which is how an experiment in the editor is undone
 * without reaching for a database snapshot.
 */

/*
 * The content is in blocks.json beside this file, not inline: WP-CLI eval()s a script
 * rather than including it, and a script carrying half a megabyte on one line came back
 * from that eval() with nothing run and nothing said. The path is the container's mount
 * of _migration/ (docker-compose.yml).
 */
$pages = json_decode( file_get_contents( '/migration/blocks.json' ), true );
if ( ! is_array( $pages ) ) {
\tWP_CLI::error( 'blocks.json did not decode: ' . json_last_error_msg() );
}

$done  = 0;
$bytes = 0;

foreach ( $pages as $slug => $content ) {
\t$page = get_page_by_path( $slug, OBJECT, 'page' );
\tif ( ! $page ) {
\t\tWP_CLI::warning( "no page for {$slug}" );
\t\tcontinue;
\t}
\t$bytes += strlen( $content );

\t/*
\t * Slashed, because wp_update_post() unslashes what it is given. The block delimiters
\t * are JSON with %(bs)su escapes in them -- pass that through unslashed and every backslash
\t * is eaten, leaving `u0026` in the middle of the words.
\t */
\twp_update_post( wp_slash( array(
\t\t'ID'           => $page->ID,
\t\t'post_content' => $content,
\t) ) );
\tWP_CLI::log( sprintf( '%%-36s %%6d bytes', $slug, strlen( $content ) ) );
\t$done++;
}

WP_CLI::success( sprintf( '%%d pages, %%s KB of block content', $done, number_format( $bytes / 1024 ) ) );
"""

# The blog page is the posts index: home.php renders it from the query, and whatever its
# content holds is never shown. It is left empty rather than carrying a copy of the grid.
QUERY_PAGES = {"blog"}


def main(argv):
    pages = extract_all()

    if argv[:1] == ["--show"]:
        print(page_markup(pages[argv[1]], indent=True))
        return 0

    content = {}
    failures = []
    counts = {}
    for slug, nodes in sorted(pages.items()):
        if slug in QUERY_PAGES:
            content[slug] = ""
            continue
        try:
            content[slug] = page_markup(nodes)
        except Unexpressible as exc:
            failures.append((slug, str(exc)))
            continue
        for node in nodes:
            counts[label_for(node)] = counts.get(label_for(node), 0) + 1

    if failures:
        for slug, why in failures:
            print("!! %-32s %s" % (slug, why))
        print("\n%d pages could not be expressed; nothing written" % len(failures))
        return 1

    io.open(OUT, "w", encoding="utf-8", newline="\n").write(PHP % {"bs": chr(92)})
    io.open(DATA, "w", encoding="utf-8", newline="\n").write(
        json.dumps(content, ensure_ascii=False, indent=1))

    blocks = {}
    for markup in content.values():
        for name in re.findall(r"<!-- wp:([a-z/-]+)", markup):
            blocks[name] = blocks.get(name, 0) + 1

    print("wrote %s  (%.0f KB)" % (DATA, os.path.getsize(DATA) / 1024.0))
    print("%d pages, %d sections, %d blocks" % (len(content), sum(counts.values()), sum(blocks.values())))
    for name, count in sorted(blocks.items(), key=lambda kv: -kv[1]):
        print("  %-28s %4d" % (name, count))
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
