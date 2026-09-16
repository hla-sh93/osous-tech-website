# -*- coding: utf-8 -*-
"""Completeness + dead-code audit of the clone CSS against the live theme CSS."""
import io, re, glob, sys
from collections import Counter
from lxml import html as LH

CLONE = "D:/abbas/osous-tech-website"
strip = lambda c: re.sub(r'/\*.*?\*/', '', c, flags=re.S)

def media_split(c):
    segs, i = [], 0
    while True:
        m = re.search(r'@media([^{]*)\{', c[i:])
        if not m:
            segs.append(("TOP", c[i:])); break
        segs.append(("TOP", c[i:i + m.start()]))
        st = i + m.end(); d = 1; j = st
        while d > 0 and j < len(c):
            d += (c[j] == '{') - (c[j] == '}'); j += 1
        segs.append((" ".join(m.group(1).split()), c[st:j - 1])); i = j
    return segs

def class_sets(css):
    css = strip(css); out = {}
    for ctx, body in media_split(css):
        body = re.sub(r'@keyframes[^{]*\{(?:[^{}]|\{[^{}]*\})*\}', '', body)
        for h, b in re.findall(r'([^{}]+)\{([^{}]*)\}', body):
            for sel in h.split(','):
                sel = " ".join(sel.split())
                if not sel or re.search(r'[ >+~]', sel) or '#' in sel or '[' in sel:
                    continue
                base = re.sub(r'::?[a-z-]+(\([^)]*\))?$', '', sel)
                cls = re.findall(r'\.([A-Za-z_][\w-]*)', base)
                if not cls or re.sub(r'\.[A-Za-z_][\w-]*', '', base) != '':
                    continue
                out.setdefault(frozenset(cls), set()).add(sel)
    return out

src = "".join(io.open(f, encoding="utf-8", errors="replace").read()
              for f in ("main.css", "webflow-full.css", "normalize.css"))
mine = "".join(io.open(f, encoding="utf-8").read()
               for f in sorted(glob.glob(CLONE + "/assets/css/*.css")))
S, M = class_sets(src), class_sets(mine)

elem = set(); used = Counter()
for p in glob.glob(CLONE + "/*.html"):
    for el in LH.parse(p).getroot().iter():
        if not isinstance(el.tag, str):
            continue
        c = (el.get("class") or "").split()
        if c:
            elem.add(frozenset(c))
            for x in c:
                used[x] += 1

EQUIV = {"w-inline-block": "card-media", "w-container": "container-default",
         "w-layout-blockcontainer": "container-default", "w-layout-hflex": "hflex",
         "w-layout-vflex": "vflex", "w-pagination-wrapper": "pagination-wrapper",
         "w--current": "is-current",
         # The export's misspellings, corrected in the clone on 2026-09-16.
         "fotter": "footer", "custom-fotter": "custom-footer", "navebar-menu": "navbar-menu",
         "magnatic": "magnetic", "c-netural-active": "c-neutral-active"}
# Rules we deliberately do not reproduce.
#  - list-items-wrapper: the source sets grid gaps on a plain block, so they never apply.
#  - the rest: the source restates in a compound selector what one of our single-class
#    utilities already provides, and our load order makes the utility win. Each was
#    checked against live with getComputedStyle and matched exactly.
DELIBERATE = {frozenset(x) for x in [
    {"list-items-wrapper"},
    {"c-surface-white", "container"}, {"c-surface-white", "section"},
    {"c-border-secondary", "divider"}, {"h2", "thin"}, {"h3", "thin"},
    {"h2", "thin", "position-relative-10"}, {"c-on-neutral", "p-r-sm"},
    {"a-c", "c-on-neutral", "p-r-sm"}, {"h-100", "image", "radii-container"},
    {"_w-100", "partner-card-logo"}, {"overflow-hidden", "section"},
    {"c-surface-primary", "container", "custom-section-1", "h-100"},
]}

gaps = []
for cs, sels in S.items():
    if any(c.startswith("w-node") for c in cs):
        continue
    mapped = frozenset(EQUIV.get(c, c) for c in cs)
    if mapped in DELIBERATE or mapped in M:
        continue
    if any(mapped <= e for e in elem):
        gaps.append((sorted(sels)[0], sorted(mapped)))

js = "".join(io.open(f, encoding="utf-8").read() for f in glob.glob(CLONE + "/assets/js/*.js"))
jscls = set(re.findall(r"['\"]([a-z][\w-]*)['\"]", js))
defined = {c for cs in M for c in cs}
dead = sorted(c for c in defined if c not in used and c not in jscls)

dupes = 0
for f in sorted(glob.glob(CLONE + "/assets/css/*.css")):
    css = strip(io.open(f, encoding="utf-8").read())
    for ctx, body in media_split(css):
        body = re.sub(r'@keyframes[^{]*\{(?:[^{}]|\{[^{}]*\})*\}', '', body)
        sels = [" ".join(h.split()) for h, b in re.findall(r'([^{}]+)\{([^{}]*)\}', body)]
        for k, v in Counter(sels).items():
            if v > 1:
                dupes += 1
                print(f"  DUPLICATE {f.split('/')[-1]} @{ctx[:16]} {v}x {k[:50]}")

print(f"missing source rules that would apply : {len(gaps)}")
for sel, m in sorted(gaps):
    print(f"    {sel:<46} -> .{'.'.join(m)}")
print(f"dead classes (defined, never used)   : {len(dead)}  {dead}")
print(f"duplicate selectors                  : {dupes}")
