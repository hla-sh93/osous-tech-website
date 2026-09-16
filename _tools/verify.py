# -*- coding: utf-8 -*-
"""Compare a built clone page against the live page it was made from."""
import io, glob, os, sys
from lxml import html as LH

CLONE = "D:/abbas/osous-tech-website"
SITE = "https://www.osoustaqnia.com/"
THEME = SITE + "wp-content/themes/osous-taqnia/assets/images/"
UPLOADS = SITE + "wp-content/uploads/"
REDIR = {"hikvision-iraq": "avigilon-iraq", "motorola-iraq": "avigilon-iraq",
         "endpoint-secuirty-iraq": "endpoint-security-iraq"}
DROP = {"w-layout-grid", "w-layout-blockcontainer", "w-container", "w-layout-hflex",
        "w-layout-vflex", "w-richtext", "w-embed", "w-inline-block", "card-media",
        "w-container", "container-default", "w-layout-hflex", "w-layout-vflex",
        "w-pagination-wrapper", "hflex", "vflex", "pagination-wrapper"}
UTIL = {"self-center", "self-start", "justify-start", "justify-center", "justify-end",
        "span-2", "span-3", "span-4", "col-right", "media-first", "place-center",
        "self-center-tablet", "self-end-tablet", "row-tablet-1", "row-tablet-2", "row-tablet-3",
        "row-tablet-3-4", "row-tablet-4", "row-tablet-5", "span-mobile-2",
        "row-mobile-4", "row-mobile-5"}
# The live site's Webflow export misspells five class names. The clone corrected them on
# 2026-09-16 -- in its pages, its stylesheets and its scripts alike -- so the live
# spelling is read as the corrected one here, and nothing else about them changes.
RENAMED = {"fotter": "footer", "custom-fotter": "custom-footer", "navebar-menu": "navbar-menu",
           "magnatic": "magnetic", "c-netural-active": "c-neutral-active"}


def live_els(path):
    doc = LH.parse(path).getroot()
    w = doc.xpath('//div[contains(@class,"page-wrapper")]') or doc.xpath('//body')
    kids = [k for k in w[0] if isinstance(k.tag, str)
            and k.tag not in ("script", "style", "noscript")]
    hdr, content = None, []
    for k in kids:
        cl = k.get("class") or ""
        if "section" in cl and "header" in cl:
            hdr = k
        elif "preloader" in cl or "whatsapp" in cl or "navbar" in cl:
            continue
        elif k.tag == "nav" or cl.strip() == "c-surface-secondary":
            continue
        elif hdr is not None and (k.tag == "section" or "section" in cl):
            content.append(k)
    hdr.tag = "section"
    return [hdr] + content


def strip_noise(els):
    for root in els:
        for bad in root.xpath('.//noscript|.//script|.//style'):
            bad.getparent().remove(bad)
    return els


def text(els):
    return " | ".join(" ".join(e.text_content().split()) for e in els)


def seq(els):
    out = []
    for root in els:
        for n in [root] + root.xpath('.//*'):
            if not isinstance(n.tag, str):
                continue
            cls = {RENAMED.get(c, c) for c in (n.get("class") or "").split()
                   if c not in DROP and not c.startswith("w-") and c not in UTIL}
            out.append(n.tag + "." + ".".join(sorted(cls)))
    return out


def media(els, live):
    imgs, links = [], []
    for e in els:
        for im in e.xpath('.//img'):
            s = im.get('data-lazy-src') or im.get('src') or ''
            if live:
                if s.startswith(THEME):
                    imgs.append(s.split('?')[0][len(THEME):])
                elif s.startswith(UPLOADS):
                    imgs.append(s.split('?')[0].rsplit('/', 1)[1])
            elif s.startswith('assets/'):
                imgs.append(s.split('/')[-1])
        for a in e.xpath('.//a[@href]'):
            h = a.get('href')
            if live and h.startswith(SITE):
                sl = h[len(SITE):].strip('/')
                links.append((REDIR.get(sl, sl) or 'index') + '.html')
            else:
                links.append(h)
    return imgs, links


def main(pattern):
    bad = 0
    files = sorted(glob.glob(pattern))
    for lp in files:
        slug = os.path.basename(lp)[:-5]
        a = strip_noise(live_els(lp))
        b = strip_noise(LH.parse(os.path.join(CLONE, slug + ".html")).getroot().xpath('//main/section'))
        probs = []
        ta, tb = text(a), text(b)
        if ta != tb:
            n = min(len(ta), len(tb)); i = 0
            while i < n and ta[i] == tb[i]:
                i += 1
            probs.append(f"TEXT@{i}/{len(ta)} live={ta[max(0,i-30):i+50]!r} mine={tb[max(0,i-30):i+50]!r}")
        ia, la = media(a, True); ib, lb = media(b, False)
        if ia != ib:
            probs.append(f"IMGS live={len(ia)} mine={len(ib)} diff={sorted(set(ia) ^ set(ib))[:4]}")
        if la != lb:
            probs.append(f"LINKS live={len(la)} mine={len(lb)} diff={sorted(set(la) ^ set(lb))[:4]}")
        sa, sb = seq(a), seq(b)
        if sa != sb:
            n = min(len(sa), len(sb)); i = 0
            while i < n and sa[i] == sb[i]:
                i += 1
            probs.append(f"STRUCT live={len(sa)} mine={len(sb)} @{i} live={sa[i:i+3]} mine={sb[i:i+3]}")
        if probs:
            bad += 1
            print(f"### {slug}")
            for p in probs:
                print("    ", p[:280])
        else:
            print(f"OK  {slug:<34} {len(sa):>3} el  {len(ta):>5}b  {len(ia):>2} imgs  {len(la):>2} links  {len(a)} sections")
    print(f"\n{bad} of {len(files)} pages differ")
    return bad


if __name__ == "__main__":
    sys.exit(1 if main(sys.argv[1]) else 0)
