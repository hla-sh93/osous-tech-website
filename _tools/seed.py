# -*- coding: utf-8 -*-
"""
Write what the theme's installer lays down besides the pages' sections:
theme/install/site.json.

Reads the static pages for their slugs and titles, and index.html's navbar for the
menu tree. The installer (theme/functions/install.php -- Appearance → Site pages, or
`wp osous install-pages`) reads the file on any WordPress: an empty one, where it makes
every page, and the live one, where the pages already exist at their addresses and it
fills each in place. `env.sh rebuild` builds the local site through it.

Every page is also given its kind -- the page template its sections belong to
(_tools/kinds.py) -- and the name of its original under theme/pages/, which is what the
editor's "Restore the original sections" looks up whatever the page's slug has become.

And what its <head> says: the reference page's document title and description go into
Rank Math's own fields on the page (`rank_math_title`, `rank_math_description`), which is
where they are edited from then on. The theme prints neither; without the plugin the
fields are simply not read.
"""
import io, json, os, glob, re, sys
from lxml import html as LH

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from kinds import page_kinds, template_of  # noqa: E402
from refpage import page_seo, page_title  # noqa: E402
from seed_contact import FORM, MAIL, MAIL_BODY  # noqa: E402

ROOT = "D:/abbas/osous-tech-website"


def slug_of(href):
    href = (href or "").strip()
    if not href or href.startswith(("#", "http", "mailto:", "tel:")):
        return None
    return href[:-5] if href.endswith(".html") else None


# ---------------------------------------------------------------- pages
kinds = page_kinds()
pages = {}
for p in sorted(glob.glob(os.path.join(ROOT, "*.html"))):
    slug = os.path.basename(p)[:-5]
    pages[slug] = {
        "title": page_title(slug),
        "template": template_of(kinds[slug]),
        # The blog is the posts index and has no sections of its own, so no original.
        "original": slug if kinds[slug] else "",
        # What the reference page's <head> tells a search engine, for the SEO plugin's fields.
        "seo": page_seo(slug),
    }

# ---------------------------------------------------------------- primary menu
doc = LH.parse(os.path.join(ROOT, "index.html")).getroot()
header = doc.xpath("//header")[0]
primary = []

for node in header.xpath('.//nav[contains(@class,"navbar-menu")]/div'):
    cls = node.get("class") or ""
    if "navbar-mobile-buttons" in cls:
        continue

    if "dd-menu-item" in cls:
        label = node.xpath('.//span[@class="nav-link"]/text()')
        entry = {
            "title": (label[0].strip() if label else "?"),
            "url": None,
            "classes": "hide-on-desktop" if "hide-on-desktop" in cls else "",
            "children": [],
        }
        for col in node.xpath('.//div[@class="menu"]/div[@class="dd-menu-list"]'):
            title = col.xpath('.//a[contains(@class,"menu-title") and contains(@class,"hide-on-tablet")]')
            leaves = col.xpath('.//div[@class="menu-list"]/a')
            if title:
                group = {
                    "title": " ".join(title[0].text_content().split()),
                    "url": slug_of(title[0].get("href")),
                    "classes": "",
                    "children": [
                        {"title": " ".join(a.text_content().split()),
                         "url": slug_of(a.get("href")), "classes": "", "children": []}
                        for a in leaves
                    ],
                }
                entry["children"].append(group)
            else:
                # a bare column: its links belong directly under the dropdown
                for a in leaves:
                    entry["children"].append(
                        {"title": " ".join(a.text_content().split()),
                         "url": slug_of(a.get("href")), "classes": "", "children": []}
                    )
        primary.append(entry)
    elif "menu-item" in cls:
        a = node.xpath(".//a")[0]
        primary.append({
            "title": " ".join(a.text_content().split()),
            "url": slug_of(a.get("href")),
            "classes": "hide-on-desktop" if "hide-on-desktop" in cls else "",
            "children": [],
        })

# ---------------------------------------------------------------- footer menus
footer = doc.xpath("//footer")[0]
discover = [
    {"title": " ".join(a.text_content().split()), "url": slug_of(a.get("href")), "classes": "", "children": []}
    for a in footer.xpath('.//div[contains(@class,"footer-col-discover")]//a')
]
legal = [
    {"title": " ".join(a.text_content().split()), "url": slug_of(a.get("href")), "classes": "", "children": []}
    for a in footer.xpath('.//div[contains(@class,"footer-legal")]//a')
]

data = {
    "pages": pages,
    "menus": {
        "primary": {"name": "Primary", "items": primary},
        "footer-discover": {"name": "Footer Discover", "items": discover},
        "footer-legal": {"name": "Footer Legal", "items": legal},
    },
    # The form the contact page's section names by its title (gen_blocks.CONTACT_FORM).
    "contact_form": {
        "title": "Contact",
        "form": FORM,
        "mail": {
            "subject": MAIL["subject"],
            "sender": MAIL["sender"],
            "recipient": MAIL["recipient"],
            "body": MAIL_BODY,
            "additional_headers": MAIL["headers"],
            "attachments": "",
            "use_html": False,
            "exclude_blank": False,
        },
    },
}

out = os.path.join(ROOT, "theme", "install", "site.json")
os.makedirs(os.path.dirname(out), exist_ok=True)
io.open(out, "w", encoding="utf-8", newline="\n").write(
    json.dumps(data, ensure_ascii=False, indent="\t") + "\n")
print(f"wrote {out}")
print(f"  pages          {len(pages)}")
print(f"  primary items  {len(primary)} top-level")
for e in primary:
    kids = sum(1 + len(c['children']) for c in e['children'])
    print(f"     {e['title']:<12} {len(e['children'])} children ({kids} total)")
print(f"  discover       {len(discover)}")
print(f"  legal          {len(legal)}")
