# -*- coding: utf-8 -*-
"""
Write one PHP file per page under theme/pages/, from the reference pages.

    python _tools/gen_pages.py

A page's file is its <main> as the reference has it, in the order it has it: the layout
and the words as HTML, and the design's components as calls into theme/includes/. A
section the design draws only one way -- the page header, the call to action, the pinned
list, the split section, the hero, the card stack, the industries carousel, the contact
section -- is a call to includes/sections/<name>.php with its words as arguments. A card,
a partner mark or a pinned item is a call to includes/parts/<name>.php. A picture or a
button is a helper call, so its paths resolve wherever WordPress is installed. Everything
else is the markup itself, indented one step per element.

The reading of the reference is _tools/gen_blocks.py's -- the same reading that proved
exact against all 57 pages -- used here only to recognise components. The markup written
for everything else is the node's own, so it cannot drift. _tools/verify_wp.py then holds
every generated page against its reference, element for element.

Once written, a page file is the page. The generator is the migration, run once; from
then on somebody changes the words in the file.
"""
import html
import io
import os
import re
import shutil
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from extract import ROOT, extract_all  # noqa: E402
from gen_blocks import QUERY_PAGES, Unexpressible, convert, convert_section, kids  # noqa: E402

OUT = os.path.join(ROOT, "theme", "pages")

# Blocks that are a call to includes/parts/<name>.php, by the block's name.
PARTS = {
    "osous/card": "card",
    "osous/numbered-card": "numbered-card",
    "osous/highlight-card": "highlight-card",
    "osous/photo-card": "photo-card",
    "osous/overlay-card": "overlay-card",
    "osous/feature-card": "feature-card",
    "osous/partner": "partner",
}

# Top-level blocks that are a call to includes/sections/<name>.php.
SECTIONS = {
    "osous/page-header": "page-header",
    "osous/call-to-action": "call-to-action",
    "osous/pinned-list": "pinned-list",
    "osous/split-section": "split-section",
    "osous/hero": "hero",
    "osous/card-stack": "card-stack",
    "osous/industries-carousel": "industries-carousel",
    "osous/contact": "contact",
}

# The attributes each node kind carries into its tag, after `class`.
ATTRS = {
    "box": ("id", "role", "aria-hidden", "data-paged", "data-query", "fs-list-element",
            "fs-list-load", "udy-collection"),
    "txt": ("id",),
    "rich": (),
    "a": ("href", "aria-label", "aria-current", "target", "rel"),
    "btn": ("type", "aria-label"),
    "list": ("role",),
    "form": ("method", "action", "aria-label"),
    "input": ("id", "name", "type", "size", "maxlength", "cols", "rows", "placeholder",
              "aria-label", "value", "required"),
}
ATTRS["textarea"] = ATTRS["input"]

TAG = {"box": "div", "txt": "div", "rich": "div", "h": "h2", "p": "p", "a": "a", "btn": "button",
       "list": "ul", "li": "li", "form": "form", "input": "input", "textarea": "textarea"}

PROSE_BREAK = re.compile(r"(?=<(?:p|h[1-6]|ul|ol|li|blockquote|figure)\b)|(?<=</ul>)|(?<=</ol>)")


# ------------------------------------------------------------------------- PHP --

def php_string(text):
    return "'" + text.replace("\\", "\\\\").replace("'", "\\'") + "'"


def php_url(url):
    """A stored URL as a PHP expression: the site's own pages through home_url()."""
    url = url or "#"
    if url == "index.html":
        return "home_url( '/' )"
    if url.endswith(".html") and "/" not in url.strip("/"):
        return "home_url( '/%s/' )" % url[:-5]
    return php_string(url)


def php_value(value, indent):
    """A PHP literal for an argument value; arrays over several lines, aligned."""
    if isinstance(value, bool):
        return "true" if value else "false"
    if isinstance(value, int):
        return str(value)
    if isinstance(value, str):
        return php_string(value)
    if isinstance(value, list):
        if not value:
            return "array()"
        inner = indent + "\t"
        return "array(\n" + "".join(inner + php_value(v, inner) + ",\n" for v in value) + indent + ")"
    if isinstance(value, dict):
        if not value:
            return "array()"
        if len(value) == 1:
            key, single = next(iter(value.items()))
            return "array( %s => %s )" % (php_string(key), php_value(single, indent))
        return php_array(value, indent)
    raise Unexpressible("no PHP for %r" % (value,))


def php_array(pairs, indent):
    """A keyed array over several lines, `=>` aligned as the coding standard wants."""
    width = max(len(php_string(k)) for k in pairs)
    inner = indent + "\t"
    out = "array(\n"
    for key, value in pairs.items():
        rendered = value if isinstance(value, Expr) else php_value(value, inner)
        out += "%s%s => %s,\n" % (inner, php_string(key).ljust(width), rendered)
    return out + indent + ")"


class Expr(str):
    """A PHP expression written as it is, rather than as a quoted string."""


def part_call(path, pairs, indent):
    lines = [indent + "<?php", indent + "get_template_part(", indent + "\t" + php_string(path) + ",",
             indent + "\tnull,"]
    lines.append(indent + "\t" + php_array(pairs, indent + "\t"))
    lines[-1] = lines[-1]  # the array's closing paren is at indent+\t already
    lines.append(indent + ");")
    lines.append(indent + "?>")
    return lines


# ------------------------------------------------------------------------ HTML --

def rewrite_links(markup):
    """Links to the site's own pages, inside inline HTML, through home_url()."""
    def one(m):
        return 'href="<?php echo esc_url( %s ); ?>"' % php_url(m.group(1))
    return re.sub(r'href="([a-z0-9-]+\.html)"', one, markup)


def attr_string(node, kind):
    out = ""
    if node.get("c"):
        out += ' class="%s"' % html.escape(node["c"], quote=True)
    for key in ATTRS.get(kind, ()):
        if key not in node:
            continue
        value = node[key]
        if value is True:
            out += " " + key
        elif key == "href":
            expr = php_url(value)
            if expr.startswith("'"):
                out += ' href="%s"' % html.escape(value, quote=True)
            else:
                out += ' href="<?php echo esc_url( %s ); ?>"' % expr
        else:
            out += ' %s="%s"' % (key, html.escape(str(value), quote=True))
    return out


def tag_of(node):
    return node.get("g") or TAG[node["t"]]


def image_call(node, indent):
    image = {"src": node.get("src", ""), "alt": node.get("alt", "")}
    for key in ("srcset", "sizes"):
        if node.get(key):
            image[key] = node[key]
    if node.get("fetchpriority") == "high":
        image["priority"] = True
    classes = node.get("c", "")
    lines = [indent + "<?php", indent + "osous_image(", indent + "\t" + php_value(image, indent + "\t") + ("," if classes else "")]
    if classes:
        lines.append(indent + "\t" + php_string(classes))
    lines += [indent + ");", indent + "?>"]
    return lines


def button_call(block, indent):
    attrs = block.attrs
    args = [php_string(attrs["text"]), php_url(attrs.get("url", "#"))]
    style = attrs.get("style", "primary")
    classes = attrs.get("className", "")
    if style != "primary" or classes:
        args.append(php_string(style))
    if classes:
        args.append(php_string(classes))
    return [indent + "<?php osous_button( %s ); ?>" % ", ".join(args)]


def prose_lines(markup, indent):
    pieces = [p for p in PROSE_BREAK.split(markup) if p.strip()]
    return [indent + rewrite_links(p.strip()) for p in pieces]


def emit(node, indent):
    """One node as lines of the page file."""
    t = node["t"]
    if t == "br":
        return [indent + "<br>"]
    if t == "icon":
        return [indent + "<?php osous_icon(); ?>"]
    if t == "img":
        return image_call(node, indent)

    block = None
    if t in ("a", "box"):
        try:
            block = convert(node)
        except Unexpressible:
            block = None
    if block is not None and t == "a" and block.name == "osous/button":
        return button_call(block, indent)
    if block is not None and t == "box" and block.name in PARTS:
        return part_call("includes/parts/" + PARTS[block.name], part_args(block, node), indent)

    tag = tag_of(node)
    open_tag = "<%s%s>" % (tag, attr_string(node, t))
    if t in ("input", "textarea"):
        return [indent + open_tag + ("</textarea>" if t == "textarea" else "")]
    if t == "rich":
        return [indent + open_tag] + prose_lines(node.get("x", ""), indent + "\t") + [indent + "</%s>" % tag]
    if "x" in node:
        text = rewrite_links(node["x"])
        if "<?" in node["x"]:
            raise Unexpressible("text holding a PHP tag: %r" % node["x"][:60])
        return [indent + open_tag + text + "</%s>" % tag]
    children = kids(node)
    if not children:
        return [indent + open_tag + "</%s>" % tag]
    lines = [indent + open_tag]
    for child in children:
        lines.extend(emit(child, indent + "\t"))
    lines.append(indent + "</%s>" % tag)
    return lines


# ------------------------------------------------------------------- arguments --

def with_urls(attrs, *keys):
    """Block attributes as part arguments: the link keys become home_url() expressions."""
    out = {}
    for key, value in attrs.items():
        if key == "className":
            continue
        if key in keys and isinstance(value, str):
            expr = php_url(value)
            out[key] = Expr(expr) if not expr.startswith("'") else value
        else:
            out[key] = value
    return out


def real(nodes):
    """Nodes that are something: the reference's empty, classless boxes are not."""
    return [n for n in nodes if not (n["t"] == "txt" and not n.get("c") and not n.get("x"))]


def part_args(block, node):
    attrs = with_urls(block.attrs, "link", "buttonUrl")
    if block.name == "osous/numbered-card" and block.inner:
        prose = [k for k in kids(node) if k["t"] == "rich"]
        if len(prose) != 1:
            raise Unexpressible("a purple card with prose but no prose box")
        # Kept as the page has it, links included: the part resolves them when it prints.
        attrs["prose"] = prose[0]["x"]
    elif block.inner:
        raise Unexpressible("%s holds blocks the part cannot take" % block.name)
    return attrs


def section_args(block, node):
    name = block.name
    attrs = with_urls(block.attrs, "buttonUrl")
    if name in ("osous/page-header", "osous/call-to-action", "osous/contact"):
        return attrs
    if name == "osous/hero":
        attrs["logos"] = [{"src": b.attrs["src"], "alt": b.attrs.get("alt", "")} for b in block.inner]
        return attrs
    if name in ("osous/card-stack", "osous/industries-carousel"):
        attrs["items"] = [with_urls(b.attrs, "link") for b in block.inner]
        return attrs
    if name == "osous/split-section":
        attrs["items"] = [with_urls(b.attrs) for b in block.inner]
        return attrs
    if name == "osous/pinned-list":
        column, items = block.inner
        scroll = real(kids(kids(node)[0]))[0]
        grid = real(kids(scroll))[0]
        column_node = kids(grid)[0]
        words = kids(column_node)
        if not words or words[0]["t"] != "h" or words[0].get("g") != "h2" or words[0].get("c") != "h2":
            raise Unexpressible("pinned list heading is not h2.h2")
        out = {}
        if column_node.get("c") != "cell":
            out["columnClass"] = column_node["c"]
        out["heading"] = words[0]["x"]
        if len(words) > 1:
            if len(words) != 2 or words[1]["t"] != "p":
                raise Unexpressible("pinned list column holds more than a heading and a line")
            out["text"] = words[1]["x"]
            if words[1].get("c", "") != "p":
                out["textClass"] = words[1].get("c", "")
        if attrs.get("tagName"):
            out["tagName"] = attrs["tagName"]
        out["items"] = [with_urls(b.attrs) for b in items.inner]
        return out
    raise Unexpressible("no arguments for %s" % name)


# ------------------------------------------------------------------------ pages --

HEAD = """<?php
/**
 * %(title)s
 *
 * Read from the reference page %(slug)s.html by _tools/gen_pages.py. From here on this
 * file is the page: change its words here. The design's components -- the header and
 * footer, the sections, the cards -- are under includes/.
 *
 * @package Osous
 */

get_template_part( 'includes/header' );
?>
<main>
"""

FOOT = """</main>
<?php
get_template_part( 'includes/footer' );
"""


def page_title(nodes):
    """The page's own title: the first h1, else the first heading."""
    for node in nodes:
        for n in walk(node):
            if n["t"] == "h" and n.get("g") == "h1":
                return re.sub(r"<[^>]+>", "", n["x"])
    for node in nodes:
        for n in walk(node):
            if n["t"] == "h":
                return re.sub(r"<[^>]+>", "", n["x"])
    return "A page"


def walk(node):
    yield node
    for k in kids(node):
        yield from walk(k)


def page_file(slug, nodes):
    lines = []
    for node in nodes:
        block = convert_section(node)
        if block.name in SECTIONS:
            lines.extend(part_call("includes/sections/" + SECTIONS[block.name], section_args(block, node), "\t"))
        else:
            lines.extend(emit(node, "\t"))
        lines.append("")
    body = "\n".join(lines).rstrip("\n") + "\n"
    return HEAD % {"title": page_title(nodes), "slug": slug} + body + FOOT


def main():
    pages = extract_all()
    if os.path.isdir(OUT):
        shutil.rmtree(OUT)
    os.makedirs(OUT)
    total = 0
    for slug, nodes in sorted(pages.items()):
        if slug in QUERY_PAGES:
            continue
        text = page_file(slug, nodes)
        path = os.path.join(OUT, slug + ".php")
        io.open(path, "w", encoding="utf-8", newline="\n").write(text)
        total += len(text)
        print("  %-40s %5d lines" % (slug + ".php", text.count("\n")))
    print("%d pages, %d KB" % (len(pages) - len(QUERY_PAGES), total // 1024))


if __name__ == "__main__":
    main()
