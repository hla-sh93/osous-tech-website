# -*- coding: utf-8 -*-
"""
Turn a live Webflow/WordPress partner page into a clean <main> for the clone.

Structure-driven, not template-driven: it walks the real DOM and rewrites it,
so a page that deviates from the partner template still comes out correct.
"""
import io, os, re, sys, json, glob
from lxml import html as LH
from lxml import etree

CLONE = "D:/abbas/osous-tech-website"
THEME_IMG = "https://www.osoustaqnia.com/wp-content/themes/osous-taqnia/assets/images/"
UPLOADS = "https://www.osoustaqnia.com/wp-content/uploads/"
SITE = "https://www.osoustaqnia.com/"

# Webflow scaffolding classes that carry no styling we keep.
DROP_CLASSES = {
    "w-layout-grid", "w-layout-blockcontainer",
    "w-richtext", "w-embed", "w-dropdown", "w-dropdown-toggle",
    "w-dropdown-list", "w-icon-dropdown-toggle", "w-nav", "w-nav-menu", "w-nav-link",
    "w-nav-brand", "w-nav-button", "w-clearfix", "w-condition-invisible",
}
# `.w-inline-block` supplies inline-block + max-width:100%; `.card-media` is our
# equivalent. Elements that set their own display don't need it.
SELF_DISPLAY = {"btn", "navbar-brand", "whatsapp"}
OUR_CONTAINERS = {"container", "container-full", "container-lg", "container-sm"}
OUR_FLEX = {"flex-h", "flex-v", "hflex", "vflex"}
# Webflow classes that carry real layout, and the clone class that reproduces each
LAYOUT_EQUIVALENT = {
    "w-layout-hflex": "hflex",
    "w-layout-vflex": "vflex",
    "w-pagination-wrapper": "pagination-wrapper",
}

DROP_ATTRS_EXACT = {
    "style", "loading", "words-rotate-in", "text-split", "data-w-id", "role",
    "data-wf-sku-bindings", "data-wf-collection", "data-wf-template-id",
    "data-hover", "data-delay", "data-animation", "data-easing", "data-easing2",
    "data-duration", "data-doc-height", "data-collapse", "data-nav-menu-open",
}
DROP_ATTR_PREFIXES = ("data-rocket", "data-wpr", "data-lazy", "data-ix", "aria-")

# Webflow generates a `#w-node-…` rule per placed grid item, and repeats it per
# breakpoint. Each (breakpoint, property, value) maps to one utility class.
TABLET = "screen and (max-width: 991px)"
MOBILE = "screen and (max-width: 767px)"
TINY = "screen and (max-width: 479px)"

PLACEMENT_CLASS = {
    ("TOP", "align-self", "center"): "self-center",
    ("TOP", "align-self", "start"): "self-start",
    ("TOP", "align-self", "flex-start"): "self-start",
    ("TOP", "place-self", "center"): "place-center",
    ("TOP", "justify-self", "start"): "justify-start",
    ("TOP", "justify-self", "center"): "justify-center",
    ("TOP", "justify-self", "end"): "justify-end",
    ("TOP", "grid-area", "span 1 / span 2 / span 1 / span 2"): "span-2",
    ("TOP", "grid-area", "span 1 / span 3 / span 1 / span 3"): "span-3",
    ("TOP", "grid-area", "span 1 / span 4 / span 1 / span 4"): "span-4",
    ("TOP", "grid-area", "1 / 2 / 2 / 3"): "col-right",
    (TABLET, "align-self", "center"): "self-center-tablet",
    (TABLET, "align-self", "end"): "self-end-tablet",
    (TABLET, "grid-area", "1 / 1 / 3 / 2"): "media-first",
    (TABLET, "grid-area", "1 / 1 / 2 / 2"): "row-tablet-1",
    (TABLET, "grid-area", "2 / 1 / 3 / 2"): "row-tablet-2",
    (TABLET, "grid-area", "3 / 1 / 4 / 2"): "row-tablet-3",
    (TABLET, "grid-area", "3 / 1 / 5 / 2"): "row-tablet-3-4",
    (TABLET, "grid-area", "4 / 1 / 5 / 2"): "row-tablet-4",
    (TABLET, "grid-area", "5 / 1 / 6 / 2"): "row-tablet-5",
    (MOBILE, "grid-area", "span 1 / span 2 / span 1 / span 2"): "span-mobile-2",
    (MOBILE, "grid-row", "4 / 5"): "row-mobile-4",
    (MOBILE, "grid-area", "5 / 1 / 6 / 2"): "row-mobile-5",
}
# `span 1 / span 1 …` is the grid default; `.span-mobile-2` already carries its
# own 479px reset, so the repeat at that breakpoint needs no class of its own.
PLACEMENT_IGNORE = {"span 1 / span 1 / span 1 / span 1"}


def node_map(css_path="main.css"):
    """{node id: {breakpoint: {property: value}}} — breakpoint matters, the same
    id is usually redeclared per media query with a different placement."""
    css = io.open(css_path, encoding="utf-8", errors="replace").read()
    segments, i = [], 0
    while True:
        m = re.search(r"@media([^{]*)\{", css[i:])
        if not m:
            segments.append(("TOP", css[i:]))
            break
        segments.append(("TOP", css[i:i + m.start()]))
        start, depth, j = i + m.end(), 1, i + m.end()
        while depth > 0 and j < len(css):
            depth += (css[j] == "{") - (css[j] == "}")
            j += 1
        segments.append((m.group(1).strip(), css[start:j - 1]))
        i = j

    out = {}
    for ctx, text in segments:
        for head, body in re.findall(r"(#w-node[^{]*)\{([^}]*)\}", text):
            decls = {}
            for d in body.split(";"):
                if ":" in d:
                    k, v = d.split(":", 1)
                    decls[k.strip()] = v.strip()
            for sel in head.split(","):
                sel = sel.strip()
                if sel.startswith("#w-node"):
                    out.setdefault(sel[1:], {})[ctx] = decls
    return out


def local_img(url):
    """Remote image URL -> a local assets path, keeping the filename."""
    url = url.split("?")[0]
    if url.startswith(THEME_IMG):
        return "assets/images/" + url[len(THEME_IMG):]
    if url.startswith(UPLOADS):
        # blog media lives under /wp-content/uploads/<year>/<month>/<file>
        return "assets/uploads/" + url[len(UPLOADS):].rsplit("/", 1)[1]
    return url


def local_href(url, redirects):
    if not url or url.startswith(("#", "mailto:", "tel:", "https://wa.me")):
        return url
    if url.startswith(SITE):
        slug = url[len(SITE):].strip("/")
        if not slug:
            return "index.html"
        slug = redirects.get(slug, slug)
        return slug + ".html"
    return url


def transform(el, nodes, redirects, assets):
    """Rewrite one element subtree in place."""
    for bad in el.xpath(".//noscript"):
        bad.getparent().remove(bad)

    for node in [el] + el.xpath(".//*"):
        tag = node.tag if isinstance(node.tag, str) else ""

        # --- placement from the generated #w-node rule, before the id is dropped
        extra = []
        nid = node.get("id", "")
        if nid.startswith("w-node-"):
            per_breakpoint = nodes.get(nid, {})
            desktop = per_breakpoint.get("TOP", {})
            for ctx, decls in per_breakpoint.items():
                for prop, val in sorted(decls.items()):
                    if val in PLACEMENT_IGNORE:
                        continue
                    # a breakpoint that just restates the desktop value changes nothing
                    if ctx != "TOP" and desktop.get(prop) == val:
                        continue
                    cls = PLACEMENT_CLASS.get((ctx, prop, val))
                    if cls:
                        if cls not in extra:
                            extra.append(cls)
                    else:
                        print(f"    ! unmapped placement @{ctx}  {prop}: {val}", file=sys.stderr)
            del node.attrib["id"]
        elif nid == "virtual-booths":          # duplicated id in the source
            del node.attrib["id"]

        # --- images: promote the lazyload attributes to real ones
        if tag == "img":
            for lazy, real in (("data-lazy-src", "src"),
                               ("data-lazy-srcset", "srcset"),
                               ("data-lazy-sizes", "sizes")):
                if node.get(lazy):
                    node.set(real, node.get(lazy))
            if node.get("src", "").startswith("data:"):
                node.attrib.pop("src", None)
            for attr in ("src", "srcset"):
                if node.get(attr):
                    parts = []
                    for chunk in node.get(attr).split(","):
                        chunk = chunk.strip()
                        if not chunk:
                            continue
                        bits = chunk.split()
                        bits[0] = local_img(bits[0])
                        if bits[0].startswith("assets/"):
                            assets.add(bits[0])
                        parts.append(" ".join(bits))
                    node.set(attr, ", ".join(parts))

        if tag == "a" and node.get("href"):
            node.set("href", local_href(node.get("href"), redirects))

        # --- classes
        classes = (node.get("class") or "").split()
        keep = []
        for c in classes:
            if c in DROP_CLASSES or c.startswith("w--"):
                continue
            if c in LAYOUT_EQUIVALENT:
                # only needed when the element has no flex class of its own;
                # `.hflex`/`.vflex` share their rule with `.flex-h`/`.flex-v`
                if c.startswith("w-layout-") and (set(classes) & OUR_FLEX):
                    continue
                keep.append(LAYOUT_EQUIVALENT[c])
                continue
            if c == "w-container":
                # normally paired with one of our container classes, which wins;
                # on its own it is the source's plain centred column
                if not (set(classes) & OUR_CONTAINERS):
                    keep.append("container-default")
                continue
            if c == "w-inline-block":
                if not (set(classes) & SELF_DISPLAY):
                    keep.append("card-media")
                continue
            if c.startswith("w-"):
                continue
            keep.append(c)
        keep += [c for c in extra if c not in keep]
        if keep:
            node.set("class", " ".join(keep))
        elif "class" in node.attrib:
            del node.attrib["class"]

        for attr in list(node.attrib):
            if attr in DROP_ATTRS_EXACT or attr.startswith(DROP_ATTR_PREFIXES):
                if attr == "role" and node.get("role") == "list":
                    continue                      # the source keeps role="list"
                del node.attrib[attr]
    return el


def extract(path, nodes, redirects, assets):
    doc = LH.parse(path).getroot()
    title = doc.findtext(".//title") or ""
    desc_el = doc.xpath('//meta[@name="description"]/@content')
    desc = desc_el[0] if desc_el else ""

    wrapper = doc.xpath('//div[contains(@class,"page-wrapper")]') or doc.xpath("//body")
    kids = [k for k in wrapper[0] if isinstance(k.tag, str)
            and k.tag not in ("script", "style", "noscript")]

    header, content = None, []
    for k in kids:
        cls = k.get("class") or ""
        if "section" in cls and "header" in cls:
            header = k
        elif "preloader" in cls or "whatsapp" in cls or "navbar" in cls:
            continue
        elif k.tag == "nav" or "c-surface-secondary" == cls.strip():
            continue                               # footer
        elif header is not None and (k.tag == "section" or "section" in cls):
            # some content blocks are a bare <section> with no class at all
            content.append(k)

    def ser(node):
        return etree.tostring(node, encoding="unicode", method="html")

    header = transform(header, nodes, redirects, assets)
    header.tag = "section"                         # the source ships a bare div
    body = [ser(transform(c, nodes, redirects, assets)) for c in content]
    return title, desc, ser(header), body


def label(html):
    """A structural name for a section, so the markup reads like the hand-built pages."""
    checks = [
        ("section header", "PAGE HEADER"),
        ("grid-to-carousel", "INDUSTRIES"),
        ("list-scroll", "WHY CHOOSE US (pinned list)"),
        ("top-tight", "CTA"),
        ("bg-img-1", "SOLUTIONS"),
        ("divider gradient-1", "ABOUT"),
        ("elevation", "INTRO"),
    ]
    for needle, name in checks:
        if needle in html:
            return name
    return "SECTION"


def banner(name):
    pad = max(4, (74 - len(name)) // 2)
    return "  <!-- " + "=" * pad + " " + name + " " + "=" * pad + " -->"


def tidy(html):
    html = re.sub(r"\s+\n", "\n", html)
    html = re.sub(r"\n\s*\n+", "\n", html)
    return html.strip()


def main():
    src_dir = sys.argv[1] if len(sys.argv) > 1 else "partners"
    out_dir = sys.argv[2] if len(sys.argv) > 2 else "partners_main"
    tag = os.path.basename(src_dir)
    redirects = {"hikvision-iraq": "avigilon-iraq", "motorola-iraq": "avigilon-iraq",
                 "endpoint-secuirty-iraq": "endpoint-security-iraq"}
    nodes = node_map()
    assets = set()
    manifest = []
    for path in sorted(glob.glob(os.path.join(src_dir, "*.html"))):
        slug = os.path.basename(path)[:-5]
        title, desc, header, body = extract(path, nodes, redirects, assets)
        blocks = [tidy(header)] + [tidy(b) for b in body]
        parts = []
        for blk in blocks:
            parts.append(banner(label(blk)))
            parts.append("  " + blk)
        main_html = "<main>\n\n" + "\n\n".join(parts) + "\n\n</main>\n"
        out = os.path.join(out_dir, slug + ".html")
        os.makedirs(out_dir, exist_ok=True)
        io.open(out, "w", encoding="utf-8").write(main_html)
        manifest.append({"slug": slug, "title": title.strip(), "desc": desc.strip(),
                         "sections": len(body), "bytes": len(main_html)})
        print(f"{slug:<22} sections={len(body):<3} {len(main_html):>6} b  {title[:52]}")
    io.open(f"{tag}_manifest.json", "w", encoding="utf-8").write(json.dumps(manifest, indent=1))
    io.open(f"{tag}_assets.txt", "w", encoding="utf-8").write("\n".join(sorted(assets)))
    print(f"\n{len(manifest)} pages, {len(assets)} distinct images referenced")


if __name__ == "__main__":
    main()
