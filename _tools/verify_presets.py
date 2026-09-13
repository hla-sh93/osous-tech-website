# -*- coding: utf-8 -*-
"""
Check that every choice the editor offers is a choice the design can draw.

    python _tools/verify_presets.py

The Appearance panel writes classes onto a section: a grid count, a background, a
spacing. Each of those lists lives in `theme/blocks/section/section.js`, and each entry
is a promise that the design has a rule for it. Add one whose class no stylesheet
mentions and nothing complains -- the editor shows the option, somebody picks it, and the
section comes out unstyled on the live site.

Nothing else catches that. `verify_wp.py` and `verify_editor.js` both read the 57 pages
as they are, and a preset nobody has picked yet is on none of them.

This reads the lists out of the editor source rather than restating them, so the two
cannot drift: a preset added there is checked here on the next run without anybody
remembering to.
"""
import io
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CSS = os.path.join(ROOT, "assets", "css")
EDITOR = os.path.join(ROOT, "theme", "blocks", "section", "section.js")

# Every class in these lists sits on the section root, which always carries `.section`
# too -- except the grids, which sit on a container inside it.
LISTS = ("GRIDS", "BACKGROUNDS", "SPACING")


def source():
    return io.open(EDITOR, encoding="utf-8").read()


def stylesheets():
    """Every selector in the theme's CSS, comments stripped."""
    text = []
    for name in sorted(os.listdir(CSS)):
        if not name.endswith(".css"):
            continue
        body = io.open(os.path.join(CSS, name), encoding="utf-8").read()
        text.append(re.sub(r"/\*.*?\*/", "", body, flags=re.S))

    return "\n".join(text)


def presets(text, name):
    """The `classes` arrays in one list, in order, as lists of class names.

    `GRIDS` has no `classes` key -- its `value` is the class -- so both shapes are read.
    """
    start = text.index("const %s = [" % name) + len("const %s = " % name)
    depth = 0
    end = start
    for end in range(start, len(text)):
        depth += (text[end] == "[") - (text[end] == "]")
        if depth == 0:
            break
    block = text[start:end + 1]

    found = []
    for entry in re.findall(r"\{[^{}]*\}", block, flags=re.S):
        classes = re.search(r"classes:\s*\[([^\]]*)\]", entry, flags=re.S)
        if classes:
            found.append((re.findall(r"'([^']+)'", classes.group(1)),
                          re.search(r"value:\s*'([^']+)'", entry).group(1)))
            continue
        value = re.search(r"value:\s*'([^']+)'", entry)
        if value:
            found.append(([value.group(1)], value.group(1)))

    return found


def styled(css, classes):
    """Is there a selector carrying all of these classes at once?

    A preset of two classes is one look, not two -- `.section.overflow-hidden.bg-3` paints
    nothing if either half is missing -- so the test is a single selector holding both,
    in any order, not each class somewhere.
    """
    if not classes:
        return True  # The "as built" entries: no classes, nothing to check.

    for selector in re.findall(r"([^{}]+)\{", css):
        for part in selector.split(","):
            names = set(re.findall(r"\.([A-Za-z0-9_-]+)", part))
            if set(classes) <= names:
                return True

    return False


def main():
    text = source()
    css = stylesheets()
    problems = []
    checked = 0

    for name in LISTS:
        for classes, value in presets(text, name):
            checked += 1
            if not styled(css, classes):
                problems.append("%s: %r writes %s, and no rule in assets/css carries %s"
                                % (name, value, " ".join("." + c for c in classes),
                                   "them together" if len(classes) > 1 else "it"))
        print("%-12s %d options" % (name, len(presets(text, name))))

    print("\n%d options checked" % checked)
    if problems:
        print()
        for problem in problems:
            print("  !!", problem)
    print("%d unstyled" % len(problems))

    return len(problems)


if __name__ == "__main__":
    sys.exit(1 if main() else 0)
