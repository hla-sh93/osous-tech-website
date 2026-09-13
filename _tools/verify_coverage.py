# -*- coding: utf-8 -*-
"""
Is everything on the site actually editable?

    python _tools/verify_coverage.py
    python _tools/verify_coverage.py --list gap    # every gap, page by page

Walks every node of every section of every page **as WordPress holds it**, and sorts each
one into three piles: something the editor offers a control for, something there is
nothing to edit about, and something a person would want to change that no control
reaches.

The third pile is the point. It is easy to look at the editor, see fields, and believe the
page is editable; the only way to know is to count what is on the pages and check each
thing against the controls that exist. A heading nobody can reach looks exactly like a
heading somebody can.

The rules are read out of `theme/blocks/section/section.js` rather than restated here, so
a control added there is counted here on the next run and the two cannot drift.

WHAT COUNTS AS REACHABLE

  text      a node whose type is in TEXT_TYPES and whose `x` has words in it
  image     any `img` node -- replace, and its alt text
  link      any `a` node with an `href`
  colour    a class in BACKGROUNDS on the section root, or in SURFACES on a container
  layout    a grid class in GRIDS, a spacing class in SPACING
  repeat    a node marked by one of GROUPS: duplicate, move, remove

WHAT HAS NOTHING TO EDIT

  Structural boxes, line breaks, list wrappers, and the empty `txt` nodes the design uses
  as painted layers -- `.card1-outline` is a border, `.card2-background` is a fill. They
  carry no words and no picture. They are counted, never ignored: if one ever gains text,
  it moves to the third pile by itself.
"""
import collections
import io
import json
import os
import re
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
EDITOR = os.path.join(ROOT, "theme", "blocks", "section", "section.js")

# Types that hold nothing a person edits, whatever else is true of them.
STRUCTURE = {"box", "br", "list", "form"}


def wp(*args):
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "cli", "wp"] + list(args)
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    done = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env)

    return done.stdout


def sections_from_wordpress():
    """Every page's sections, straight out of the database."""
    php = (
        '$out = array();'
        'foreach ( get_posts( array( "post_type" => "page", "numberposts" => -1 ) ) as $p ) {'
        '  $found = array();'
        '  foreach ( parse_blocks( $p->post_content ) as $b ) {'
        '    if ( "osous/section" !== ( $b["blockName"] ?? "" ) ) { continue; }'
        '    $found[] = array('
        '      "label" => $b["attrs"]["label"] ?? "",'
        '      "nodes" => $b["attrs"]["nodes"] ?? array(),'
        '    );'
        '  }'
        '  $out[ $p->post_name ] = $found;'
        '}'
        'echo json_encode( $out );'
    )
    raw = wp("eval", php)
    start = raw.find("{")
    if start < 0:
        raise SystemExit("WP-CLI returned no JSON -- is the stack up?")

    return json.loads(raw[start:])


# ------------------------------------------------------------------ the editor's rules --

def editor_rules():
    """TEXT_TYPES, GROUPS and the preset class lists, read from the editor's source."""
    text = io.open(EDITOR, encoding="utf-8").read()

    block = text[text.index("const TEXT_TYPES = {"):]
    block = block[:block.index("};")]
    text_types = set(re.findall(r"^\s*(\w+):", block, flags=re.M))

    block = text[text.index("const GROUPS = ["):]
    block = block[:block.index("\n];")]
    groups = set(re.findall(r"\[\s*'([^']+)'", block))

    def classes(name):
        start = text.index("const %s = [" % name) + len("const %s = " % name)
        depth, end = 0, start
        for end in range(start, len(text)):
            depth += (text[end] == "[") - (text[end] == "]")
            if depth == 0:
                break
        found = set()
        for entry in re.findall(r"\{[^{}]*\}", text[start:end + 1], flags=re.S):
            listed = re.search(r"classes:\s*\[([^\]]*)\]", entry, flags=re.S)
            if listed:
                found |= set(re.findall(r"'([^']+)'", listed.group(1)))
            else:
                value = re.search(r"value:\s*'([^']+)'", entry)
                if value:
                    found.add(value.group(1))
        return found

    # The test collectPanels() uses to decide what a surface may sit on, taken from the
    # source so the two cannot disagree about what counts as a panel.
    panels = re.search(r"classes\.some\(\s*\(\s*name\s*\)\s*=>\s*/([^/]+)/\.test", text)
    if not panels:
        raise SystemExit("could not find the panel test in section.js")

    # Which node types get a placeholder field.
    placeholders = set(re.findall(r"node\.t === '(\w+)'\s*\|\|\s*node\.t === '(\w+)'", text))
    placeholders = set(sum((list(pair) for pair in placeholders), []))

    return {
        "text": text_types,
        "groups": groups,
        "panel": re.compile(panels.group(1)),
        "placeholder": placeholders,
        "backgrounds": classes("BACKGROUNDS"),
        "surfaces": classes("SURFACES"),
        "grids": classes("GRIDS"),
        "spacing": classes("SPACING"),
    }


# ------------------------------------------------------------------------- the walk ----

def classes_of(node):
    return (node.get("c") or "").split()


def has_words(node):
    """Text with something in it -- markup alone is not something to edit."""
    return re.sub(r"<[^>]*>", "", node.get("x") or "").strip() != ""


def audit(pages, rules):
    """Every node, sorted into reachable / nothing-to-edit / gap."""
    reach = collections.Counter()
    inert = collections.Counter()
    gaps = collections.Counter()
    where = collections.defaultdict(list)

    # Colours the Appearance panel can reach, and the nodes it reaches them on.
    appearance = rules["backgrounds"] | rules["surfaces"] | rules["grids"] | rules["spacing"]

    def note(pile, kind, slug, label, node):
        pile[kind] += 1
        # Keyed by the pile as well as the kind: "colour" lands in two of them, and one
        # shared key made a reachable section root the printed example of a gap.
        key = (id(pile), kind)
        if len(where[key]) < 60:
            where[key].append("%s / %s / %s%s" % (
                slug, label or "?", node.get("t"),
                "." + ".".join(classes_of(node)) if node.get("c") else ""))

    def walk(nodes, slug, label, root):
        for index, node in enumerate(nodes):
            if not isinstance(node, dict):
                continue
            kind = node.get("t")
            cs = classes_of(node)
            is_root = root and index == 0
            controls = 0

            # --- the fields, in the same shape collectGroups() offers them --------------
            # Not exclusive: a link with its own words gets two.
            if kind in rules["text"] and has_words(node):
                note(reach, "text", slug, label, node)
                controls += 1
            if kind == "img":
                note(reach, "image", slug, label, node)
                controls += 1
            if kind == "a" and node.get("href"):
                note(reach, "link", slug, label, node)
                controls += 1
            if kind in rules["placeholder"]:
                note(reach, "placeholder", slug, label, node)
                controls += 1

            # --- colour ----------------------------------------------------------------
            coloured = [c for c in cs if c.startswith("c-surface-") or c.startswith("bg-")]
            if coloured == ["bg-blur"]:
                # Not a colour anybody picks: the frosted pane is what a `card-4` *is*, and
                # the design offers it nowhere else and in no other shade.
                note(inert, "card effect", slug, label, node)
            elif coloured:
                if is_root or any(rules["panel"].match(c) for c in cs):
                    note(reach, "colour", slug, label, node)
                    controls += 1
                else:
                    note(gaps, "colour", slug, label, node)

            # --- the rest: nothing to edit, or a gap ------------------------------------
            if not controls:
                if kind in STRUCTURE:
                    note(inert, kind, slug, label, node)
                elif kind in rules["text"]:
                    # Empty by design: `.card1-outline` is a border, `.card2-background`
                    # a fill. They carry no words and no picture.
                    note(inert, "painted layer", slug, label, node)
                elif kind == "icon":
                    # `osous_icon()` draws one icon, `arrow`. There is no second to choose.
                    note(inert, "the one icon", slug, label, node)
                elif kind == "btn":
                    # The carousel's own arrows: an aria-label and nothing else.
                    note(inert, "carousel arrow", slug, label, node)
                elif has_words(node) or node.get("href") or node.get("src"):
                    note(gaps, kind or "?", slug, label, node)
                else:
                    note(inert, "%s, empty" % kind, slug, label, node)

            walk(node.get("k") or [], slug, label, False)

    for slug, sections in sorted(pages.items()):
        for section in sections:
            walk(section.get("nodes") or [], slug, section.get("label"), True)

    return reach, inert, gaps, where


def main(argv):
    rules = editor_rules()
    pages = sections_from_wordpress()
    reach, inert, gaps, where = audit(pages, rules)

    total_pages = len(pages)
    total_sections = sum(len(s) for s in pages.values())
    total = sum(reach.values()) + sum(inert.values()) + sum(gaps.values())

    print("%d pages, %d sections\n" % (total_pages, total_sections))

    print("reachable — a control exists")
    for kind, n in reach.most_common():
        print("  %-16s %5d" % (kind, n))

    print("\nnothing to edit")
    for kind, n in inert.most_common():
        print("  %-16s %5d" % (kind, n))

    print("\ngaps — content with no control")
    if not gaps:
        print("  none")
    for kind, n in gaps.most_common():
        examples = where[(id(gaps), kind)]
        print("  %-16s %5d   e.g. %s" % (kind, n, examples[0] if examples else ""))

    if "--list" in argv:
        for kind in gaps:
            print("\n%s:" % kind)
            for line in where[(id(gaps), kind)]:
                print("   ", line)

    print("\n%d nodes counted, %d reachable, %d with nothing to edit, %d gaps"
          % (total, sum(reach.values()), sum(inert.values()), sum(gaps.values())))

    return sum(gaps.values())


if __name__ == "__main__":
    sys.exit(1 if main(sys.argv[1:]) else 0)
