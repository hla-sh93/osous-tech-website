# -*- coding: utf-8 -*-
"""
Build the installable theme zip and the notes that go with it.

`theme/assets` is empty in this repository -- Docker mounts the real `assets/` over it,
so that the 57 static reference pages and the theme serve the very same files. A zip of
`theme/` alone would therefore be a theme with no CSS, no JavaScript and no images, which
installs perfectly and renders nothing. The whole reason this script exists is to put
them back.

    python _tools/package.py

Usually reached through `./_tools/env.sh package`, which exports the pages and menus
first so the zip and the content leave together.
"""
import io
import os
import re
import shutil
import zipfile

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DIST = os.path.join(ROOT, "dist")
SLUG = "osous"

SKIP_DIRS = {"__pycache__", ".git", "node_modules"}
SKIP_FILES = {".DS_Store", "Thumbs.db", "desktop.ini"}


def theme_version():
    style = io.open(os.path.join(ROOT, "theme", "style.css"), encoding="utf-8").read()
    found = re.search(r"^\s*Version:\s*(.+)$", style, re.MULTILINE)
    return found.group(1).strip() if found else "0.0.0"


def copy_tree(src, dst):
    """Copy a directory, skipping editor and OS droppings."""
    count = 0
    for base, dirs, files in os.walk(src):
        dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
        target = os.path.join(dst, os.path.relpath(base, src))
        if not os.path.isdir(target):
            os.makedirs(target)
        for name in files:
            if name in SKIP_FILES:
                continue
            shutil.copy2(os.path.join(base, name), os.path.join(target, name))
            count += 1
    return count


HANDOFF = """Osous theme %(version)s
==============================================================================

Two files, and the site is standing:

  osous-theme.zip     the theme
  osous-content.xml   the 57 pages and the three menus


1. Install the theme
   Appearance -> Themes -> Add New -> Upload Theme -> osous-theme.zip -> Activate

2. Import the content
   Tools -> Import -> WordPress ("Run Importer"; WordPress offers to install the
   importer the first time). Upload osous-content.xml.
   Assign the authors to yourself and leave "Download and import file
   attachments" unticked -- every image is inside the theme already.

3. Pretty permalinks
   Settings -> Permalinks -> Post name -> Save

That is all. Activating the theme assigns the three menus to their places and
sets the home page, so there is nothing to wire up by hand.


What you can change, and where
------------------------------------------------------------------------------

  Appearance -> Menus        the header mega menu and the two footer columns.
                             Reorder by dragging; the site follows.

  Pages                      all 57, with the right slugs.

The words and images on the pages are not editable yet -- they live in
theme/fixtures/ as generated PHP. Moving them into the editor as blocks is the
next piece of work. Until then a page's content is changed in the theme.


Working on it properly
------------------------------------------------------------------------------

This zip is for putting the theme on a WordPress install. To develop it, take
the repository instead: it carries the local WordPress (Docker), the tools that
verify the theme against the original site, and the history.

  git clone <repo> && cd osous-tech-website
  docker compose up -d
  ./_tools/env.sh rebuild

See README.md and docs/local-setup.md.
"""


def main():
    version = theme_version()

    if os.path.isdir(DIST):
        shutil.rmtree(DIST)
    stage = os.path.join(DIST, SLUG)
    os.makedirs(stage)

    files = copy_tree(os.path.join(ROOT, "theme"), stage)
    assets = copy_tree(os.path.join(ROOT, "assets"), os.path.join(stage, "assets"))

    zip_path = os.path.join(DIST, "osous-theme.zip")
    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as archive:
        for base, dirs, names in os.walk(stage):
            dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
            for name in names:
                full = os.path.join(base, name)
                archive.write(full, os.path.join(SLUG, os.path.relpath(full, stage)))

    shutil.rmtree(stage)
    io.open(os.path.join(DIST, "HANDOFF.txt"), "w", encoding="utf-8", newline="\r\n").write(
        HANDOFF % {"version": version}
    )

    size = os.path.getsize(zip_path) / 1024.0 / 1024.0
    print("dist/osous-theme.zip   %.1f MB  (%d theme files, %d assets)" % (size, files, assets))
    print("dist/HANDOFF.txt")

    if not os.path.exists(os.path.join(ROOT, "theme", "screenshot.png")):
        print("\nnote: no theme/screenshot.png, so the theme's card in the admin is blank.")


if __name__ == "__main__":
    main()
