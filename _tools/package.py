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

# In the repository, not in the zip: the editor's source. WordPress runs build/index.js
# and reads each block's block.json and its file under includes/; the JavaScript those were built from
# -- every edit.js, the shared controls, the registry -- is for whoever develops the
# theme, and HANDOFF.txt sends them to the repository for that.
def editor_source():
    theme = os.path.join(ROOT, "theme")
    found = set()
    for base, dirs, files in os.walk(os.path.join(theme, "blocks")):
        for name in files:
            if name.endswith(".js"):
                found.add(os.path.relpath(os.path.join(base, name), theme))
    return found


def theme_version():
    style = io.open(os.path.join(ROOT, "theme", "style.css"), encoding="utf-8").read()
    found = re.search(r"^\s*Version:\s*(.+)$", style, re.MULTILINE)
    return found.group(1).strip() if found else "0.0.0"


def copy_tree(src, dst, skip=()):
    """Copy a directory, skipping editor and OS droppings, and anything in `skip`."""
    count = 0
    for base, dirs, files in os.walk(src):
        dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
        target = os.path.join(dst, os.path.relpath(base, src))
        if not os.path.isdir(target):
            os.makedirs(target)
        for name in files:
            if name in SKIP_FILES or os.path.relpath(os.path.join(base, name), src) in skip:
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

READ THIS FIRST: the pages are not in the theme zip. osous-theme.zip is the
design only; the 57 pages, the menus and the contact form arrive with the
content file, osous-content.xml, in step 3 below. A site with the theme alone
shows one empty home page, no menu and no pages.

The same notes in Arabic: HANDOFF-ar.txt

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
   Appearance -> Customize -> Site details: the header's button, addresses,
   phone numbers, the map, the social links, the footer's headings, badges and
   copyright line, and the email address the contact form writes to. The logo
   is under Site Identity.

The rest happens by itself on your first visit to the dashboard: the three menus
go to their places, the home page and the blog page are set, and the blog shows
twelve posts a page as the design does.

The contact form sends mail with WordPress's wp_mail(). Most hosts need an SMTP
plugin for that to actually leave the server; that is the host's setting, not
the theme's.


What you can change, and where
------------------------------------------------------------------------------

  Pages -> (any page)          the page as blocks: a section holds a container,
                               the container holds a heading, a grid, cards.
                               Click any words and type; click a card and its
                               icon, link and options are in the sidebar on the
                               right; pick a section for its background, spacing
                               and height. Cards and list items are ordinary
                               blocks: add one with +, drag to reorder, delete.

  + -> Patterns -> Osous sections
                               every kind of section the design has, with a live
                               preview. Drop one in and change its words.

  + -> Blocks -> Osous         the building blocks themselves -- section, grid,
                               column, heading, text, image, button, the five
                               kinds of card -- for putting a section together
                               from parts.

  Appearance -> Menus          the header mega menu and the two footer columns.

  Appearance -> Customize      the site's details (above), and the logo under
                               Site Identity.

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

# The same notes in Arabic, for the receiver who reads Arabic first. The first receiver
# installed the theme zip alone and asked where the pages were, so both versions now
# open with that.
HANDOFF_AR = """قالب أسس التقنية %(version)s
==============================================================================

اقرأ هاد أولاً: الصفحات مو جوّا ملف الثيم. ملف osous-theme.zip هو التصميم بس؛
الـ 57 صفحة والقوائم ونموذج الاتصال بيجوا من ملف المحتوى osous-content.xml
بالخطوة 3 تحت. إذا ركّبت الثيم لحاله بتشوف صفحة رئيسية فاضية، بدون قائمة
وبدون صفحات.

نفس الملاحظات بالإنجليزية: HANDOFF.txt

كل شي بيحتاجه الموقع موجود بهالمجلد:

  osous-theme.zip      الثيم (التصميم)
  osous-content.xml    الـ 57 صفحة، القوائم الثلاث، نموذج الاتصال، ومقالات
                       المدونة (العناوين والتواريخ والصور حقيقية؛ نصّها مؤقت
                       لحين استيراد المقالات الحقيقية من الموقع الحالي)
  plugins/             Contact Form 7 و Simple Cloudflare Turnstile و
                       WordPress Importer، كملفات zip فما في داعي لأي تنزيل


0. ووردبريس الجديد بيجي معه محتوى تجريبي. احذفه أولاً وإلا رح يظل جنب محتوى
   الموقع: مقالة "Hello world!"، وصفحة "Sample Page"، ومسودة صفحة
   "Privacy Policy" (Pages -> Drafts). المسودة الأخيرة مهمة: إذا ظلّت، صفحة
   الخصوصية تبع الموقع بتنزل باسم "privacy-policy-2" وكل روابط الفوتر بتصير
   تشير للصفحة الغلط.

1. الثيم
   Appearance -> Themes -> Add New -> Upload Theme -> osous-theme.zip -> Activate

2. الإضافات
   Plugins -> Add New -> Upload Plugin، لكل واحد من الملفات الثلاثة. فعّلهم.
   Contact Form 7 بيعمل نموذج تجريبي اسمه "Contact form 1" أول ما يشتغل؛
   احذفه. نموذج الموقع، واسمه "Contact"، بيجي مع المحتوى بالخطوة الجاية.

3. المحتوى (هون بتجي الصفحات)
   Tools -> Import -> WordPress -> Run Importer. ارفع osous-content.xml،
   اختار نفسك كصاحب المحتوى، ولا تعلّم "Download and import file attachments":
   كل الصور موجودة جوّا الثيم أصلاً. انتظر لحتى تظهر "All done".
   بعدها لازم يظهر 57 صفحة تحت Pages. إذا الاستيراد وقف بالنص، أعده بنفس
   الملف: الأداة بتتخطى الموجود.

4. الروابط الدائمة
   Settings -> Permalinks -> Post name -> Save

5. الحماية من السبام
   Settings -> Cloudflare Turnstile: أدخل مفتاحي Turnstile تبع الموقع (site key
   و secret key). لحين ما توصل المفاتيح الحقيقية، مفاتيح Cloudflare التجريبية
   بتخلّي النموذج يشتغل بدون تحدّي:
                          site key 1x00000000000000000000AA
                          secret   1x0000000000000000000000000000000AA

6. بيانات الموقع
   Appearance -> Customize -> Site details: زر الهيدر، العناوين، أرقام الهاتف،
   الخريطة، روابط السوشيال، عناوين الفوتر وشعاراته وسطر الحقوق، والإيميل
   اللي بيوصل عليه نموذج الاتصال. اللوغو من Site Identity.

الباقي بيصير لحاله بأول زيارة للوحة التحكم: القوائم الثلاث بتروح لأماكنها،
الصفحة الرئيسية وصفحة المدونة بينضبطوا، والمدونة بتعرض 12 مقالة بالصفحة
متل التصميم.

نموذج الاتصال بيرسل الإيميل عن طريق wp_mail() تبع ووردبريس. أغلب الاستضافات
بتحتاج إضافة SMTP لحتى يطلع الإيميل فعلاً من السيرفر؛ هاد إعداد عند الاستضافة،
مو بالثيم.


شو بتقدر تغيّر، ووين
------------------------------------------------------------------------------

  Pages -> (أي صفحة)           الصفحة كبلوكات: القسم (Section) بيحتوي حاوية
                               (Container)، والحاوية بتحتوي عنوان وشبكة وكروت.
                               اضغط على أي كلام واكتب؛ اضغط على كرت وبتلاقي
                               أيقونته ورابطه وخياراته بالشريط الجانبي؛ اختار
                               قسم لتغيّر خلفيته ومسافاته وارتفاعه. الكروت
                               وعناصر القوائم بلوكات عادية: أضف واحد بزر +،
                               اسحب لإعادة الترتيب، أو احذف.

  + -> Patterns -> Osous sections
                               كل أنواع الأقسام الموجودة بالتصميم، مع معاينة
                               حية. أنزل واحد وغيّر كلامه.

  + -> Blocks -> Osous         البلوكات الأساسية نفسها: قسم، شبكة، عمود، عنوان،
                               نص، صورة، زر، وأنواع الكروت الخمسة، لتركيب قسم
                               من أجزائه.

  Appearance -> Menus          القائمة الكبيرة بالهيدر وعمودي الفوتر.

  Appearance -> Customize      بيانات الموقع (فوق)، واللوغو من Site Identity.

  Contact -> Contact Forms     حقول نموذج الاتصال ورسائله وإيميله. المستلم هو
                               [_osous_email]، يعني الإيميل المكتوب بـ Site
                               details، فما في داعي تكتبه مرتين.

ما في منتقي ألوان ولا CSS حر، وهاد مقصود: كل خيار معروض هو خيار موجود
بالتصميم أصلاً، فالموقع بيحافظ على تصميمه مهما كان مين يعدّل عليه.


للتطوير الجدّي
------------------------------------------------------------------------------

هالمجلد لتركيب الثيم على ووردبريس. لتطوير الثيم نفسه، خذ المستودع (repository):
فيه ووردبريس المحلي (Docker)، والفحوصات اللي بتتأكد إنو الثيم مطابق للموقع
الأصلي، والتاريخ الكامل. ابدأ من AGENTS.md.
"""


def main():
    version = theme_version()

    if not os.path.exists(EXPORT):
        sys.exit("no content export at %s -- run ./_tools/env.sh package" % EXPORT)

    # Everything is assembled in a staging folder and zipped from there, so that dist/
    # ends up holding one file -- the one to send -- and nothing anyone could send by
    # mistake instead.
    folder = "osous-handoff-%s" % version
    staging = os.path.join(ROOT, "_migration", folder)
    for path in (DIST, staging):
        if os.path.isdir(path):
            shutil.rmtree(path)
    stage = os.path.join(staging, SLUG)
    os.makedirs(stage)

    files = copy_tree(os.path.join(ROOT, "theme"), stage, skip=editor_source())
    assets = copy_tree(os.path.join(ROOT, "assets"), os.path.join(stage, "assets"))
    theme_zip = os.path.join(staging, "osous-theme.zip")
    zip_dir(stage, theme_zip, SLUG)
    shutil.rmtree(stage)

    shutil.copy2(EXPORT, os.path.join(staging, "osous-content.xml"))

    os.makedirs(os.path.join(staging, "plugins"))
    for path in fetch_plugins():
        shutil.copy2(path, os.path.join(staging, "plugins", os.path.basename(path)))

    io.open(os.path.join(staging, "HANDOFF.txt"), "w", encoding="utf-8", newline="\r\n").write(
        HANDOFF % {"version": version}
    )
    io.open(os.path.join(staging, "HANDOFF-ar.txt"), "w", encoding="utf-8-sig", newline="\r\n").write(
        HANDOFF_AR % {"version": version}
    )

    os.makedirs(DIST)
    bundle = os.path.join(DIST, folder + ".zip")
    zip_dir(staging, bundle, folder)

    mb = lambda p: os.path.getsize(p) / 1024.0 / 1024.0  # noqa: E731
    print("  osous-theme.zip                %5.1f MB  (%d theme files, %d assets)" % (mb(theme_zip), files, assets))
    print("  osous-content.xml              %5.1f MB" % mb(os.path.join(staging, "osous-content.xml")))
    for slug in PLUGINS:
        print("  plugins/%-24s %5.1f MB" % (slug + ".zip", mb(os.path.join(staging, "plugins", slug + ".zip"))))
    print("  HANDOFF.txt, HANDOFF-ar.txt")
    shutil.rmtree(staging)
    print("dist/%s   %.1f MB  -- the one file to send" % (os.path.basename(bundle), mb(bundle)))

    if not os.path.exists(os.path.join(ROOT, "theme", "screenshot.png")):
        print("\nnote: no theme/screenshot.png, so the theme's card in the admin is blank."
              "\n      take one at /static/_tools/screenshot.html")


if __name__ == "__main__":
    main()
