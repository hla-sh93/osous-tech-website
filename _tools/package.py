# -*- coding: utf-8 -*-
"""
Build the handoff: the theme zip, the content, the plugins it needs, and the notes.

`theme/assets` is empty in this repository -- Docker mounts the real `assets/` over it,
so that the 57 static reference pages and the theme serve the very same files. A zip of
`theme/` alone would therefore be a theme with no CSS, no JavaScript and no images, which
installs perfectly and renders nothing. The whole reason this script exists is to put
them back.

`theme/build/` is committed and goes in as-is. It is the editor's JavaScript, and a theme
that needs `npm run build` before anyone can edit a page is not a theme.

The two plugins the contact page needs, and the importer the content needs, go in as
zips: a server that cannot reach wordpress.org -- this project's own container cannot --
can still install them from a file. They are fetched once by this machine and kept under
_migration/plugins/.

    python _tools/package.py

Reached through `./_tools/env.sh package`, which exports the content first so that the
zip and the content leave together, and then proven by `./_tools/handoff-test.sh`, which
installs what is in dist/ on a WordPress that has never seen this repository.
"""
import io
import os
import re
import shutil
import sys
import urllib.request
import zipfile

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DIST = os.path.join(ROOT, "dist")
EXPORT = os.path.join(ROOT, "_migration", "export", "osous-content.xml")
PLUGIN_CACHE = os.path.join(ROOT, "_migration", "plugins")
SLUG = "osous"

# What the contact page needs, and what the content import needs. The live site runs the
# first two; the third is WordPress's own importer, which wp-admin offers to install on the
# import screen and cannot when wordpress.org is out of reach.
PLUGINS = ["contact-form-7", "simple-cloudflare-turnstile", "wordpress-importer"]
PLUGIN_URL = "https://downloads.wordpress.org/plugin/%s.latest-stable.zip"

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


def zip_dir(src, zip_path, prefix):
    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as archive:
        for base, dirs, names in os.walk(src):
            dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
            for name in names:
                full = os.path.join(base, name)
                archive.write(full, os.path.join(prefix, os.path.relpath(full, src)))


def fetch_plugins():
    """The plugin zips, from the cache or from wordpress.org.

    With a name in the User-Agent and a timeout: wordpress.org sits on an anonymous
    urllib request for as long as it likes, and a fetch that hangs with no timeout hung
    the whole package for ten minutes. Downloaded to a .part file and renamed, so a
    fetch cut off half way is never mistaken for a cached zip on the next run.
    """
    if not os.path.isdir(PLUGIN_CACHE):
        os.makedirs(PLUGIN_CACHE)
    out = []
    for slug in PLUGINS:
        path = os.path.join(PLUGIN_CACHE, slug + ".zip")
        if not os.path.exists(path):
            print("fetching %s" % slug)
            request = urllib.request.Request(
                PLUGIN_URL % slug, headers={"User-Agent": "osous-package/1 (+python-urllib)"})
            with urllib.request.urlopen(request, timeout=60) as response:
                with open(path + ".part", "wb") as target:
                    shutil.copyfileobj(response, target)
            os.replace(path + ".part", path)
        out.append(path)
    return out


HANDOFF = """Osous theme %(version)s
==============================================================================

Everything the site needs is in this folder:

  osous-theme.zip      the theme
  osous-content.xml    the 57 pages, the three menus, the contact form, and the
                       blog posts (titles, dates and pictures are real; their
                       text is a placeholder until the live posts are imported)
  plugins/             Contact Form 7, Simple Cloudflare Turnstile, and
                       WordPress Importer -- as zips, so no download is needed


0. A fresh WordPress ships with content of its own. Delete it first, or it will
   sit beside the site's: the "Hello world!" post, the "Sample Page", and the
   draft "Privacy Policy" page (Pages -> Drafts). That last one matters: left in
   place, the site's own privacy page arrives as "privacy-policy-2" and every
   footer links to the wrong one.

1. The theme
   Appearance -> Themes -> Add New -> Upload Theme -> osous-theme.zip -> Activate

2. The plugins
   Plugins -> Add New -> Upload Plugin, for each of the three zips. Activate them.
   Contact Form 7 makes a sample "Contact form 1" when it starts; delete it. The
   site's form, called "Contact", arrives with the content in the next step.

3. The content
   Tools -> Import -> WordPress -> Run Importer. Upload osous-content.xml, assign
   the author to yourself, and leave "Download and import file attachments"
   unticked: every picture is inside the theme already.

4. Permalinks
   Settings -> Permalinks -> Post name -> Save

5. Spam protection
   Settings -> Cloudflare Turnstile: enter the site's Turnstile site key and
   secret key. Until real keys are in, Cloudflare's test keys let the form work
   without a challenge:  site key 1x00000000000000000000AA
                          secret   1x0000000000000000000000000000000AA

6. The site's details
   Appearance -> Customize -> Site details: addresses, phone numbers, the map,
   the social links, and the email address the contact form writes to.

The rest happens by itself on your first visit to the dashboard: the three menus
go to their places, the home page and the blog page are set, and the blog shows
twelve posts a page as the design does.

The contact form sends mail with WordPress's wp_mail(). Most hosts need an SMTP
plugin for that to actually leave the server; that is the host's setting, not
the theme's.


What you can change, and where
------------------------------------------------------------------------------

  Pages -> (any page)          every section of the page, in order. Click one and
                               its words, pictures, links, columns and colours are
                               in the sidebar on the right. Cards and list items
                               can be duplicated, moved and removed there. Drag
                               sections in the list view to reorder them.

  + -> Patterns -> Osous sections
                               every kind of section the design has, with a live
                               preview. Drop one in and change its words.

  Appearance -> Menus          the header mega menu and the two footer columns.

  Appearance -> Customize      the site's details (above).

  Contact -> Contact Forms     the contact form's fields, messages and mail.
                               The recipient is [_osous_email] -- the address in
                               Site details -- so it need not be set twice.

There is no colour picker and no free CSS on purpose: every choice offered is
one the design already has, so the site keeps its design whoever edits it.


Working on it properly
------------------------------------------------------------------------------

This folder is for putting the theme on a WordPress install. To develop it, take
the repository: it carries the local WordPress (Docker), the checks that verify
the theme against the original site, and the history. Start with AGENTS.md.
"""


def main():
    version = theme_version()

    if not os.path.exists(EXPORT):
        sys.exit("no content export at %s -- run ./_tools/env.sh package" % EXPORT)

    if os.path.isdir(DIST):
        shutil.rmtree(DIST)
    stage = os.path.join(DIST, SLUG)
    os.makedirs(stage)

    files = copy_tree(os.path.join(ROOT, "theme"), stage)
    assets = copy_tree(os.path.join(ROOT, "assets"), os.path.join(stage, "assets"))
    theme_zip = os.path.join(DIST, "osous-theme.zip")
    zip_dir(stage, theme_zip, SLUG)
    shutil.rmtree(stage)

    shutil.copy2(EXPORT, os.path.join(DIST, "osous-content.xml"))

    os.makedirs(os.path.join(DIST, "plugins"))
    for path in fetch_plugins():
        shutil.copy2(path, os.path.join(DIST, "plugins", os.path.basename(path)))

    io.open(os.path.join(DIST, "HANDOFF.txt"), "w", encoding="utf-8", newline="\r\n").write(
        HANDOFF % {"version": version}
    )

    # One file to send: everything above, inside a folder named for the version. Built
    # beside dist/ and moved in afterwards -- written inside it, the walk finds the
    # half-written bundle and archives it into itself until the disk is full.
    name = "osous-handoff-%s.zip" % version
    zip_dir(DIST, os.path.join(ROOT, name + ".part"), "osous-handoff-%s" % version)
    bundle = os.path.join(DIST, name)
    os.replace(os.path.join(ROOT, name + ".part"), bundle)

    mb = lambda p: os.path.getsize(p) / 1024.0 / 1024.0  # noqa: E731
    print("dist/osous-theme.zip          %5.1f MB  (%d theme files, %d assets)" % (mb(theme_zip), files, assets))
    print("dist/osous-content.xml        %5.1f MB" % mb(os.path.join(DIST, "osous-content.xml")))
    for slug in PLUGINS:
        print("dist/plugins/%-16s %5.1f MB" % (slug + ".zip", mb(os.path.join(DIST, "plugins", slug + ".zip"))))
    print("dist/HANDOFF.txt")
    print("dist/%-26s %5.1f MB  <- the one to send" % (os.path.basename(bundle), mb(bundle)))

    if not os.path.exists(os.path.join(ROOT, "theme", "screenshot.png")):
        print("\nnote: no theme/screenshot.png, so the theme's card in the admin is blank."
              "\n      take one at /static/_tools/screenshot.html")


if __name__ == "__main__":
    main()
