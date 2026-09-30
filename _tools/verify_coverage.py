# -*- coding: utf-8 -*-
"""
Is what the pages hold what the blocks can show?

    python _tools/verify_coverage.py
    python _tools/verify_coverage.py --list     # every leftover class, page by page

Walks every block of every page **as WordPress holds it** and checks three things:

  1. Every block is one the theme registers. A block nobody registers renders nothing,
     and the editor shows a grey box where a section used to be.
  2. Every choice is one the design names. `surface: "purple"` is a class the template
     prints; `surface: "mauve"` prints nothing, and the panel comes out white with no
     word said. The names are read from functions/design.php through WordPress, so a value
     added there is accepted here on the next run.
  3. What is left over. The reference's placement classes -- `span-2`, `row-tablet-3`,
     `hflex` -- ride in `className`, which is WordPress's own "Additional CSS class(es)"
     field: editable, but also the measure of how much of the site the named attributes
     do not yet describe. They are counted and listed, never failed.

The first two fail the run. The third is a report.
"""
import collections
import json
import os
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Which attribute of which block is a choice from which of the design's tables.
OPTIONS = {
    "osous/section": {"spacing": "spacing", "background": "background", "height": "height"},
    "osous/container": {"width": "width", "surface": "surface", "backdrop": "backdrop"},
    "osous/grid": {"gap": "gap", "carousel": "carousel"},
    "osous/column": {"align": "align", "span": "span"},
    "osous/panel": {"kind": "panel", "surface": "surface", "backdrop": "backdrop"},
    "osous/heading": {"size": "heading", "tone": "tone", "weight": "weight"},
    "osous/text": {"style": "text", "tone": "tone", "weight": "weight"},
    "osous/image": {"size": "image-size", "fit": "image-fit"},
    "osous/button": {"style": "button"},
    "osous/divider": {"style": "divider"},
    "osous/spacer": {"size": "spacer"},
    "osous/list": {"style": "list", "tone": "tone"},
    "osous/list-item": {"tone": "tone"},
}

# The choices that are a block's own rather than the design's.
FIXED = {
    ("osous/section-heading", "align"): {"", "center"},
    ("osous/heading", "align"): {"", "center"},
    ("osous/text", "align"): {"", "center"},
    ("osous/text", "tagName"): {"p", "div"},
    ("osous/group", "tagName"): {"div", "section", "span", "p"},
    ("osous/card", "media"): {"box", "none"},
    ("osous/photo-card", "media"): {"box", "none"},
    ("osous/prose", "style"): {"block", "plain"},
    ("osous/pinned-list", "tagName"): {"section", "div"},
    ("osous/pinned-item", "titleStyle"): {"medium", "bold"},
    ("osous/pinned-item", "textStyle"): {"normal", "small", "plain", "bold"},
}


def wp(*args):
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "cli", "wp"] + list(args)
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    done = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env)
    raw = done.stdout
    start = raw.find("{")
    if start < 0:
        raise SystemExit("WP-CLI returned no JSON -- is the stack up?")
    return json.loads(raw[start:])


def from_wordpress():
    """Every page's blocks, flattened, plus the registered names and the design's tables."""
    php = (
        '$flat = array();'
        '$walk = function ( $blocks, $slug ) use ( &$walk, &$flat ) {'
        '  foreach ( $blocks as $b ) {'
        '    if ( empty( $b["blockName"] ) ) { continue; }'
        '    $flat[] = array( "page" => $slug, "name" => $b["blockName"], "attrs" => (object) $b["attrs"] );'
        '    $walk( $b["innerBlocks"], $slug );'
        '  }'
        '};'
        'foreach ( get_posts( array( "post_type" => "page", "numberposts" => -1 ) ) as $p ) {'
        '  $walk( parse_blocks( $p->post_content ), $p->post_name );'
        '}'
        '$registered = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );'
        'echo json_encode( array( "blocks" => $flat, "registered" => $registered, "design" => osous_design() ) );'
    )
    return wp("eval", php)


def main(argv):
    data = from_wordpress()
    blocks = data["blocks"]
    registered = set(data["registered"])
    design = data["design"]

    unknown = collections.Counter()
    bad_values = collections.Counter()
    residue = collections.defaultdict(collections.Counter)
    where = collections.defaultdict(list)
    names = collections.Counter()

    for block in blocks:
        name, attrs, page = block["name"], block["attrs"], block["page"]
        names[name] += 1
        if name not in registered:
            unknown[name] += 1
            continue

        for attribute, group in OPTIONS.get(name, {}).items():
            if attribute in attrs and str(attrs[attribute]) not in design.get(group, {}):
                bad_values["%s.%s = %r" % (name, attribute, attrs[attribute])] += 1
        for (block_name, attribute), allowed in FIXED.items():
            if name == block_name and attribute in attrs and str(attrs[attribute]) not in allowed:
                bad_values["%s.%s = %r" % (name, attribute, attrs[attribute])] += 1

        for cls in str(attrs.get("className", "")).split():
            residue[name][cls] += 1
            where[(name, cls)].append(page)

    ours = {n: c for n, c in names.items() if n.startswith("osous/")}
    core = {n: c for n, c in names.items() if not n.startswith("osous/")}
    print("%d blocks on %d pages: %d of the theme's, %d of WordPress's own (prose)\n"
          % (len(blocks), len({b["page"] for b in blocks}), sum(ours.values()), sum(core.values())))

    print("unregistered blocks")
    print("  none" if not unknown else "\n".join("  %-30s %4d" % kv for kv in unknown.most_common()))

    print("\nchoices the design does not name")
    print("  none" if not bad_values else "\n".join("  %-50s %4d" % kv for kv in bad_values.most_common()))

    total_residue = sum(sum(c.values()) for c in residue.values())
    print("\nclasses carried in \"Additional CSS class(es)\": %d, on %d kinds of block"
          % (total_residue, len(residue)))
    for name, counter in sorted(residue.items(), key=lambda kv: -sum(kv[1].values())):
        print("  %-24s %4d   %s" % (name, sum(counter.values()),
                                    ", ".join("%s x%d" % kv for kv in counter.most_common())))
        if "--list" in argv:
            for cls in counter:
                print("      %-28s %s" % (cls, ", ".join(sorted(set(where[(name, cls)])))))

    problems = sum(unknown.values()) + sum(bad_values.values())
    print("\n%d problems" % problems)
    return problems


if __name__ == "__main__":
    sys.exit(1 if main(sys.argv[1:]) else 0)
