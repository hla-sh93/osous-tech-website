# -*- coding: utf-8 -*-
"""
Check that every choice the editor offers is a choice the design can draw.

    python _tools/verify_presets.py

Every option in functions/design.php -- a background, a spacing, a text style, a backdrop --
stands for a class, and each is a promise that the stylesheets have a rule for it. Add
one whose class no sheet mentions and nothing complains: the editor shows the option,
somebody picks it, and the section comes out unstyled on the live site.

Nothing else catches that. `verify_wp.py` and `verify_editor.js` both read the 57 pages
as they are, and an option nobody has picked yet is on none of them.

Two things are checked. First, every class in the tables against every selector in
assets/css: an option of two classes has to be carried by one selector holding both
(`.section.overflow-hidden.bg-3` paints nothing if either half is missing). Second, that
the copy of those tables in _tools/gen_blocks.py, which the migration reads the reference
pages with, still says what functions/design.php says -- the two are kept by hand, and this is
what keeps them the same.
"""
import io
import json
import os
import re
import subprocess
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

import gen_blocks  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CSS = os.path.join(ROOT, "assets", "css")

# gen_blocks.py's tables, and the group of functions/design.php each one mirrors.
MIRRORS = [
    ("SPACING", "spacing"), ("BACKGROUND", "background"), ("HEIGHT", "height"),
    ("WIDTH", "width"), ("SURFACE", "surface"), ("GAP", "gap"), ("CAROUSEL", "carousel"),
    ("ALIGN", "align"), ("SPAN", "span"), ("TABLET_ROW", "tablet-row"),
    ("MOBILE_ROW", "mobile-row"), ("DIRECTION", "direction"), ("ARRANGE", "arrange"), ("HEADING_SIZE", "heading"), ("TONE", "tone"),
    ("IMAGE_SIZE", "image-size"), ("IMAGE_FIT", "image-fit"), ("BUTTON", "button"),
    ("SPACER", "spacer"),
]


def design():
    """functions/design.php's tables, through WordPress: group -> value -> [classes, label]."""
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "cli", "wp",
               "eval", "echo json_encode( osous_design() );"]
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    raw = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env).stdout
    start = raw.find("{")
    if start < 0:
        raise SystemExit("WP-CLI returned no JSON -- is the stack up?")
    return json.loads(raw[start:])


def stylesheets():
    """Every selector in the theme's CSS, comments stripped."""
    text = []
    for name in sorted(os.listdir(CSS)):
        if name.endswith(".css"):
            body = io.open(os.path.join(CSS, name), encoding="utf-8").read()
            text.append(re.sub(r"/\*.*?\*/", "", body, flags=re.S))
    return "\n".join(text)


def styled(css, classes):
    """Does the design draw this option?

    One selector carrying every class at once is the sure sign: `.section.overflow-hidden
    .bg-3` is one look. Failing that, each class has to be in a rule of its own somewhere,
    which is how `image h-100` is drawn -- two utilities that happen to be worn together.
    """
    if not classes:
        return True
    named = [set(re.findall(r"\.([A-Za-z0-9_-]+)", part))
             for selector in re.findall(r"([^{}]+)\{", css) for part in selector.split(",")]
    if any(set(classes) <= names for names in named):
        return True
    return all(any(cls in names for names in named) for cls in classes)


def main():
    tables = design()
    css = stylesheets()
    problems = []
    checked = 0

    for group, options in tables.items():
        for value, (classes, label) in options.items():
            checked += 1
            if not styled(css, classes.split()):
                problems.append("%s: %r (%s) writes %s, and no rule in assets/css carries %s"
                                % (group, value, label, " ".join("." + c for c in classes.split()),
                                   "them together" if len(classes.split()) > 1 else "it"))
        print("%-12s %2d options" % (group, len(options)))

    for table, group in MIRRORS:
        mirror = getattr(gen_blocks, table)
        for value, classes in mirror.items():
            if value not in tables[group]:
                problems.append("gen_blocks.%s knows %r, functions/design.php does not" % (table, value))
            elif tables[group][value][0] != classes:
                problems.append("gen_blocks.%s: %r is %r, functions/design.php says %r"
                                % (table, value, classes, tables[group][value][0]))
    for backdrop in gen_blocks.BACKDROP:
        if backdrop not in tables["backdrop"]:
            problems.append("gen_blocks.BACKDROP knows %r, functions/design.php does not" % backdrop)

    print("\n%d options checked against the stylesheets, %d tables against the migration" % (checked, len(MIRRORS) + 1))
    for problem in problems:
        print("  !!", problem)
    print("%d problems" % len(problems))
    return len(problems)


if __name__ == "__main__":
    sys.exit(1 if main() else 0)
