# -*- coding: utf-8 -*-
"""
Build the handoff: the theme zip, the sample posts, the plugins it needs, and the notes.

Since version 5 the pages travel inside the theme -- pages/ and install/site.json -- and
the receiver lays them down with Appearance -> Site pages (functions/install.php), which
is also the only way onto the live WordPress, where every page already exists. What is
left to export is the twelve sample posts, for an empty WordPress only.

`theme/assets` is empty in this repository -- Docker mounts the real `assets/` over it,
so that the 57 static reference pages and the theme serve the very same files. A zip of
`theme/` alone would therefore be a theme with no CSS, no JavaScript and no images, which
installs perfectly and renders nothing. The whole reason this script exists is to put
them back.

`theme/build/` is committed and goes in as-is. It is the editor's JavaScript, and a theme
that needs `npm run build` before anyone can edit a page is not a theme.

The two plugins the contact page needs, the SEO plugin, and the importer the sample
posts need, go in as zips: a server that cannot reach wordpress.org -- this project's own container cannot --
can still install them from a file. They are fetched once by this machine and kept under
_migration/plugins/.

    python _tools/package.py

Reached through `./_tools/env.sh package`, which exports the posts first so that the
zip and the posts leave together, and then proven by `./_tools/handoff-test.sh`, which
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
EXPORT = os.path.join(ROOT, "_migration", "export", "osous-posts.xml")
PLUGIN_CACHE = os.path.join(ROOT, "_migration", "plugins")
SLUG = "osous"

# What the contact page needs, what tells a search engine about each page, and what the
# sample posts' import needs. The live site runs the first three; the fourth is WordPress's own
# importer, which wp-admin offers to install on the import screen and cannot when
# wordpress.org is out of reach.
PLUGINS = ["contact-form-7", "simple-cloudflare-turnstile", "seo-by-rank-math", "wordpress-importer"]
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

READ THIS FIRST: the pages come from the theme, at the press of one button.
After the theme and the plugins are on (steps 1 and 2), go to
Appearance -> Site pages and press "Install the pages" (step 3). Until then the
dashboard says how many of the site's 57 pages are missing.

ON THE LIVE SITE. The WordPress behind osoustaqnia.com already holds every page
at its address -- empty, because the old theme draws them -- and 104 real posts.
The button is made for that: it fills each page where it is, so each keeps its
address, its id, its history and whatever is already in its Rank Math fields; it
never touches a post and never deletes anything; a page that already has words
of its own is left alone unless you tick the box that replaces it, and then what
it had is kept as a revision. Even so: take a full backup of the live site first
(files and database), and try it on a copy of the live site (staging) before the
live site itself. Do NOT import osous-posts.xml there (step 5): the live site has
its real posts.

The same notes in Arabic: HANDOFF-ar.txt

Everything the site needs is in this folder:

  osous-theme.zip      the theme: the design, and every page of the site --
                       its sections, its template, the title and description
                       it shows a search engine -- with the three menus and
                       the contact form, all laid down by step 3
  osous-posts.xml      twelve sample blog posts, for an EMPTY WordPress only
                       (titles, dates and pictures are real; their text is a
                       placeholder until the live posts are imported)
  plugins/             Contact Form 7, Simple Cloudflare Turnstile, Rank Math
                       SEO and WordPress Importer -- as zips, so no download
                       is needed


0. On a fresh WordPress, delete what it ships with: the "Hello world!" post and
   the "Sample Page". (Its draft "Privacy Policy" can stay: step 3 makes it the
   site's own privacy page.)

1. The theme
   Appearance -> Themes -> Add Theme -> Upload Theme -> osous-theme.zip ->
   Install Now -> Activate

   osous-theme.zip is %(theme_mb).1f MB. Where the host allows less in one upload,
   WordPress installs nothing and says "The link you followed has expired." --
   or that the file "exceeds the upload_max_filesize directive". Media -> Add
   Media File shows the host's limit, as "Maximum upload file size". Ask the
   host to raise it to 64 MB; or unzip osous-theme.zip, copy the osous folder
   into wp-content/themes/ with the host's file manager or FTP, and press
   Activate on it under Appearance -> Themes. The plugin zips in step 2 meet
   the same limit.

2. The plugins
   Plugins -> Add Plugin -> Upload Plugin, for each of the four zips. Activate them.
   Contact Form 7 makes a sample "Contact form 1" when it starts; delete it. The
   site's form, called "Contact", is made in the next step.
   Rank Math opens its setup wizard when it starts. Its first screen asks for a
   Rank Math account: connect the site's, or press "Skip". One of the two has to
   be done -- until then Rank Math prints nothing on any page. The rest of the
   wizard can be closed; step 8 says what to set.

3. The pages
   Appearance -> Site pages. The table says, page by page, what installing will
   do: "Will be made", "Empty: will be filled", "Holds its sections already".
   Press "Install the pages". It works in rounds of a few seconds, so a host's
   time limit cannot cut it off half way: leave the page open until it shows
   "Installed." and what it did. It also lays down the three menus, sets the
   home page and the blog page, and makes the contact form. Pressing it again
   changes nothing that is already installed.

4. Permalinks
   Settings -> Permalinks -> Post name -> Save

5. The sample posts -- an EMPTY WordPress only, never the live site
   Tools -> Import -> WordPress -> Run Importer. Upload osous-posts.xml, assign
   the author to yourself, and leave "Download and import file attachments"
   unticked: every picture is inside the theme already.

6. Spam protection
   Settings -> Cloudflare Turnstile: enter the site's Turnstile site key and
   secret key. Until real keys are in, Cloudflare's test keys let the form work
   without a challenge:  site key 1x00000000000000000000AA
                          secret   1x0000000000000000000000000000000AA

7. The site's details
   Appearance -> Customize -> Site details: the header's button, addresses,
   phone numbers, the map, the social links, the footer's headings, badges and
   copyright line, the email address the contact form writes to, and the
   language the site declares to search engines. The logo is under Site
   Identity.

8. Search engines
   Every page's title and description was filled in by step 3, where the page's
   own were empty; they are in the Rank Math box on each page and are edited
   there. The company's own details --
   name, logo, address, phone, opening hours, social profiles, which the live
   site publishes as schema -- are Rank Math's settings, not the theme's:
   Rank Math -> Titles & Meta -> Local SEO. The quickest way to have them exactly
   as on the live site is to export them there (Rank Math -> Status & Tools ->
   Import & Export) and import that file here.
   One of Rank Math's own defaults changes the pages: General Settings -> Links ->
   "Open External Links in New Tab/Window". Switch it off to keep the design's
   links opening the way the design made them. On the live site that setting has
   nothing to act on, because its pages are not WordPress content; here it would
   send three links to a new tab. Check it again after importing the live
   settings.
   The live site runs Rank Math PRO. The free plugin in this folder is what PRO
   runs on; the schema PRO adds to a page (Service, Offer) needs PRO and its
   licence installed here as well.
   The live home page also names a Syrian version of itself (hreflang en-SY,
   /sy). If that page is kept: Pages -> the home page -> Page options -> Other
   language versions:   en-SY https://www.osoustaqnia.com/sy

The blog shows twelve posts a page, as the design does: the theme sets that by
itself on the first visit to the dashboard.

The contact form sends mail with WordPress's wp_mail(). Most hosts need an SMTP
plugin for that to actually leave the server; that is the host's setting, not
the theme's.


What you can change, and where
------------------------------------------------------------------------------

  Pages -> Add New             a new page is asked what kind of page it is:
                               Home, About, Solution, Partner, Service, Contact,
                               Legal. Choosing one gives the page that kind's
                               template and its sections, in order, with words
                               to replace. "Start with an empty page" gives
                               neither.

  Pages -> (any page)          the page as blocks: a section holds a container,
                               the container holds a heading, a grid, cards.
                               Click any words and type; click a card and its
                               icon, link and options are in the sidebar on the
                               right; pick a section for its background, spacing
                               and height. Cards and list items are ordinary
                               blocks: add one with +, drag to reorder, delete.

  ... in the page's sidebar    Template: the page's kind. It can be changed.
                               Slug (the page's address): it can be changed at
                               any time. The old address is sent on to the new
                               one, the menus and every link to the page from
                               other pages follow by themselves.
                               Page options: whether the WhatsApp badge shows on
                               this page; the page's other language versions;
                               and "Restore the original sections", which puts
                               back what the page was installed with (nothing is
                               saved until you save, and Undo takes it back).
                               Rank Math: the title and description a search
                               engine shows, the picture used when the page is
                               shared, whether it is indexed.

  Pages -> Duplicate           under each page in the list: a draft with the
                               same sections on the same template. The quickest
                               way to the next partner or solution page.

  A link, anywhere             a card's link, a button's: pick the page from the
                               list in the sidebar. The link follows that page
                               if its address is changed later. Anything else --
                               an anchor, a phone number, another site -- is
                               typed in the field under it.

  + -> Patterns -> Osous sections
                               every kind of section the design has, with a live
                               preview. Drop one in and change its words.

  A section -> ... -> Create pattern
                               keeps a section under Patterns. "Synced" means
                               there is one copy: place it on as many pages as
                               need it, edit it once, and it changes on all of
                               them.

  + -> Blocks -> Osous         the building blocks themselves -- section, grid,
                               column, heading, text, image, button, the five
                               kinds of card -- for putting a section together
                               from parts.

  Appearance -> Site pages     the site's pages as the theme carries them: what
                               installing would do to each, and the button. A
                               page with words of its own is never replaced
                               unless the box for that is ticked.

  Appearance -> Menus          the header mega menu and the two footer columns.

  Appearance -> Customize      the site's details (above), and the logo under
                               Site Identity.

  Contact -> Contact Forms     the contact form's fields, messages and mail.
                               The recipient is [_osous_email] -- the address in
                               Site details -- so it need not be set twice.

  Rank Math                    what the whole site tells search engines: the
                               company's details, the sitemap, the defaults for
                               titles.

There is no colour picker and no free CSS on purpose: every colour offered is
one the design already has, so the site keeps its design whoever edits it. A
picture of the site's own can go behind a section, a container, a panel, the
hero or the industries carousel: select it, and in the sidebar, Background
picture -> Choose picture. "Darken the picture" keeps light words readable on it.


How the theme is laid out
------------------------------------------------------------------------------

  header.php, footer.php       the one header and the one footer
  templates/                   one template per kind of page
  includes/                    the markup: layout, elements, parts (the cards),
                               sections, loops (the blog), one file each
  functions/                   the theme's PHP, one concern per file
  blocks/                      what each block is and what it stores; each names
                               its file under includes/
  patterns/sections/           the section library
  patterns/starters/           what a new page of each kind starts as
  pages/                       every page's original sections, one file per page.
                               The page that is edited lives in WordPress; these
                               are what it was installed from, and what "Restore
                               the original sections" puts back.
  install/site.json            what Appearance -> Site pages lays down besides the
                               sections: titles, templates, menus, the form
  assets/                      the design's stylesheets, scripts and pictures


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

اقرأ هاد أولاً: الصفحات بتجي من الثيم نفسه، بكبسة زر وحدة. بعد ما تركّب الثيم
والإضافات (الخطوتين 1 و 2)، روح على Appearance -> Site pages واضغط
"Install the pages" (الخطوة 3). لحين ما تعملها، لوحة التحكم بتضل تقلّك قديش
صفحة من صفحات الموقع الـ 57 ناقصة.

على الموقع الشغّال. ووردبريس تبع osoustaqnia.com فيه أصلاً كل الصفحات على
عناوينها -- فاضية، لأنه القالب القديم هو يلي بيرسمها -- ومعها 104 مقالات حقيقية.
الزر معمول لهالحالة بالذات: بيعبّي كل صفحة بمكانها، فكل صفحة بتحافظ على عنوانها
ورقمها (id) وتاريخها وعلى أي شي مكتوب أصلاً بحقول Rank Math تبعها؛ وما بيقرّب
على ولا مقالة وما بيحذف شي أبداً؛ والصفحة يلي فيها كلام خاص فيها بيتركها متل
ما هي إلا إذا علّمت الخيار يلي بيستبدلها، وساعتها محتواها القديم بينحفظ كمراجعة
(Revision). ومع هيك: خود نسخة احتياطية كاملة من الموقع الشغّال قبل (الملفات وقاعدة
البيانات)، وجرّب على نسخة من الموقع (staging) قبل الموقع نفسه. ولا تستورد
osous-posts.xml هناك (الخطوة 5): الموقع الشغّال فيه مقالاته الحقيقية.

نفس الملاحظات بالإنجليزية: HANDOFF.txt

كل شي بيحتاجه الموقع موجود بهالمجلد:

  osous-theme.zip      الثيم: التصميم، وكل صفحات الموقع -- أقسامها وقالبها
                       (Template) والعنوان والوصف يلي بيظهروا بمحركات البحث --
                       مع القوائم الثلاث ونموذج الاتصال، وكلهم بينركّبوا
                       بالخطوة 3
  osous-posts.xml      12 مقالة تجريبية للمدونة، لووردبريس فاضي بس
                       (العناوين والتواريخ والصور حقيقية؛ نصّها مؤقت لحين
                       استيراد المقالات الحقيقية من الموقع الحالي)
  plugins/             Contact Form 7 و Simple Cloudflare Turnstile و
                       Rank Math SEO و WordPress Importer، كملفات zip فما في
                       داعي لأي تنزيل


0. على ووردبريس جديد، احذف المحتوى التجريبي يلي بيجي معه: مقالة "Hello world!"
   وصفحة "Sample Page". (مسودة "Privacy Policy" فيك تخلّيها: الخطوة 3 بتعملها
   صفحة الخصوصية تبع الموقع.)

1. الثيم
   Appearance -> Themes -> Add Theme -> Upload Theme -> osous-theme.zip ->
   Install Now -> Activate

   حجم osous-theme.zip هو %(theme_mb).1f ميغا. إذا الاستضافة بتسمح برفع ملف أصغر
   من هيك، ووردبريس ما بيركّب شي وبيكتب "The link you followed has expired."
   -- أو إنه الملف "exceeds the upload_max_filesize directive". الحد تبع
   الاستضافة بيبيّن بـ Media -> Add Media File، جنب "Maximum upload file size".
   اطلب من الاستضافة ترفعه لـ 64 ميغا؛ أو فكّ ضغط osous-theme.zip، وانسخ مجلد
   osous لجوّا wp-content/themes/ بمدير الملفات تبع الاستضافة أو بالـ FTP،
   وبعدين اضغط Activate عليه من Appearance -> Themes. ملفات الإضافات بالخطوة 2
   عليها نفس الحد.

2. الإضافات
   Plugins -> Add Plugin -> Upload Plugin، لكل واحد من الملفات الأربعة. فعّلهم.
   Contact Form 7 بيعمل نموذج تجريبي اسمه "Contact form 1" أول ما يشتغل؛
   احذفه. نموذج الموقع، واسمه "Contact"، بينعمل بالخطوة الجاية.
   Rank Math بيفتح معالج الإعداد أول ما يشتغل. أول شاشة فيه بتطلب حساب
   Rank Math: اربط حساب الموقع، أو اضغط "Skip". لازم تعمل وحدة من التنتين:
   قبلها Rank Math ما بيطبع شي بأي صفحة. باقي المعالج فيك تسكّره؛ الخطوة 8
   بتقلّك شو تضبط.

3. الصفحات
   Appearance -> Site pages. الجدول بيقلّك، صفحة صفحة، شو رح يصير فيها:
   "Will be made" (رح تنعمل)، "Empty: will be filled" (فاضية: رح تتعبّى)،
   "Holds its sections already" (فيها أقسامها أصلاً).
   اضغط "Install the pages". الشغل بيمشي على دفعات كل وحدة كم ثانية، فحدّ
   الوقت عند الاستضافة ما بيقدر يقطعه بالنص: خلّي الصفحة مفتوحة لحتى تطلع
   "Installed." مع شو انعمل. نفس الزر بيركّب القوائم الثلاث، وبيضبط الصفحة
   الرئيسية وصفحة المدونة، وبيعمل نموذج الاتصال. إذا ضغطته مرة تانية ما
   بيغيّر شي من يلي انركّب.

4. الروابط الدائمة
   Settings -> Permalinks -> Post name -> Save

5. المقالات التجريبية -- لووردبريس فاضي بس، أبداً مو على الموقع الشغّال
   Tools -> Import -> WordPress -> Run Importer. ارفع osous-posts.xml، اختار
   نفسك كصاحب المحتوى، ولا تعلّم "Download and import file attachments": كل
   الصور موجودة جوّا الثيم أصلاً.

6. الحماية من السبام
   Settings -> Cloudflare Turnstile: أدخل مفتاحي Turnstile تبع الموقع (site key
   و secret key). لحين ما توصل المفاتيح الحقيقية، مفاتيح Cloudflare التجريبية
   بتخلّي النموذج يشتغل بدون تحدّي:
                          site key 1x00000000000000000000AA
                          secret   1x0000000000000000000000000000000AA

7. بيانات الموقع
   Appearance -> Customize -> Site details: زر الهيدر، العناوين، أرقام الهاتف،
   الخريطة، روابط السوشيال، عناوين الفوتر وشعاراته وسطر الحقوق، الإيميل يلي
   بيوصل عليه نموذج الاتصال، ولغة الموقع يلي بيعلنها لمحركات البحث. اللوغو من
   Site Identity.

8. محركات البحث (SEO)
   عنوان ووصف كل صفحة انكتبوا بالخطوة 3، بس وين كانت حقول الصفحة فاضية؛
   موجودين بصندوق Rank Math جوّا كل صفحة ومن هونيك بيتعدّلوا. بيانات الشركة
   نفسها (الاسم، اللوغو، العنوان، الهاتف،
   ساعات الدوام، حسابات التواصل، يلي الموقع الحالي بينشرها كـ schema) هي
   إعدادات Rank Math مو الثيم: Rank Math -> Titles & Meta -> Local SEO. أسرع
   طريقة لتصير متل الموقع الحالي بالضبط: صدّرها من هونيك
   (Rank Math -> Status & Tools -> Import & Export) واستورد الملف هون.
   في إعداد افتراضي واحد بـ Rank Math بيغيّر الصفحات: General Settings -> Links ->
   "Open External Links in New Tab/Window". طفّيه لتضل روابط التصميم تفتح متل ما
   التصميم عاملها. بالموقع الحالي هالإعداد ما إلو شي يشتغل عليه، لأنو صفحاته مو
   محتوى ووردبريس؛ هون بيخلّي تلات روابط تفتح بتبويب جديد. تأكّد منه كمان مرة
   بعد ما تستورد إعدادات الموقع الحالي.
   الموقع الحالي شغّال على Rank Math PRO. الإضافة المجانية يلي بهالمجلد هي
   الأساس يلي PRO بيشتغل فوقه؛ والـ schema يلي PRO بيضيفها للصفحة (Service،
   Offer) بدها PRO وترخيصه يتركّبوا هون كمان.
   الصفحة الرئيسية بالموقع الحالي بتعلن كمان عن نسخة سورية منها
   (hreflang en-SY، /sy). إذا هالصفحة باقية: Pages -> الصفحة الرئيسية ->
   Page options -> Other language versions:
       en-SY https://www.osoustaqnia.com/sy

المدونة بتعرض 12 مقالة بالصفحة متل التصميم: الثيم بيضبطها لحاله بأول زيارة
للوحة التحكم.

نموذج الاتصال بيرسل الإيميل عن طريق wp_mail() تبع ووردبريس. أغلب الاستضافات
بتحتاج إضافة SMTP لحتى يطلع الإيميل فعلاً من السيرفر؛ هاد إعداد عند الاستضافة،
مو بالثيم.


شو بتقدر تغيّر، ووين
------------------------------------------------------------------------------

  Pages -> Add New             أول ما تفتح صفحة جديدة بيسألك: شو نوع الصفحة؟
                               Home، About، Solution، Partner، Service، Contact،
                               Legal. بتختار نوع، فبتاخد الصفحة قالب هالنوع
                               وأقسامه بالترتيب، مع كلام جاهز لتبدّله.
                               "Start with an empty page" بتعطيك صفحة فاضية.

  Pages -> (أي صفحة)           الصفحة كبلوكات: القسم (Section) بيحتوي حاوية
                               (Container)، والحاوية بتحتوي عنوان وشبكة وكروت.
                               اضغط على أي كلام واكتب؛ اضغط على كرت وبتلاقي
                               أيقونته ورابطه وخياراته بالشريط الجانبي؛ اختار
                               قسم لتغيّر خلفيته ومسافاته وارتفاعه. الكروت
                               وعناصر القوائم بلوكات عادية: أضف واحد بزر +،
                               اسحب لإعادة الترتيب، أو احذف.

  ... بالشريط الجانبي للصفحة   Template: نوع الصفحة، وفيك تغيّره.
                               Slug (عنوان الصفحة بالرابط): فيك تغيّره بأي وقت.
                               الرابط القديم بيتحوّل لحاله عالجديد، والقوائم
                               وكل الروابط من الصفحات التانية بتلحق الصفحة
                               لحالها.
                               Page options: إظهار أو إخفاء زر واتساب بهالصفحة؛
                               نسخ الصفحة بلغات تانية؛ وزر "Restore the original
                               sections" يلي بيرجّع الأقسام يلي انركّبت فيها
                               الصفحة (ما بينحفظ شي لحتى تضغط حفظ، و Undo
                               بيرجّعك).
                               Rank Math: العنوان والوصف يلي بيظهروا بمحرك
                               البحث، صورة المشاركة، وهل الصفحة بتتأرشف.

  Pages -> Duplicate           تحت كل صفحة بالقائمة: مسودة بنفس الأقسام وعلى
                               نفس القالب. أسرع طريق لصفحة شريك أو حل جديدة.

  أي رابط                      رابط كرت أو زر: اختار الصفحة من القائمة بالشريط
                               الجانبي. الرابط بيلحق الصفحة إذا تغيّر عنوانها
                               بعدين. أي شي تاني (anchor، رقم هاتف، موقع تاني)
                               بينكتب بالحقل يلي تحتها.

  + -> Patterns -> Osous sections
                               كل أنواع الأقسام الموجودة بالتصميم، مع معاينة
                               حية. أنزل واحد وغيّر كلامه.

  قسم -> ... -> Create pattern بيحفظ القسم تحت Patterns. "Synced" يعني في نسخة
                               وحدة: حطّها بقد ما بدك صفحات، عدّلها مرة وحدة،
                               وبتتغيّر بكل الصفحات.

  + -> Blocks -> Osous         البلوكات الأساسية نفسها: قسم، شبكة، عمود، عنوان،
                               نص، صورة، زر، وأنواع الكروت الخمسة، لتركيب قسم
                               من أجزائه.

  Appearance -> Site pages     صفحات الموقع متل ما الثيم حاملها: شو رح يصير
                               بكل صفحة إذا ركّبتها، والزر. الصفحة يلي فيها
                               كلام خاص فيها ما بتنستبدل إلا إذا علّمت الخيار.

  Appearance -> Menus          القائمة الكبيرة بالهيدر وعمودي الفوتر.

  Appearance -> Customize      بيانات الموقع (فوق)، واللوغو من Site Identity.

  Contact -> Contact Forms     حقول نموذج الاتصال ورسائله وإيميله. المستلم هو
                               [_osous_email]، يعني الإيميل المكتوب بـ Site
                               details، فما في داعي تكتبه مرتين.

  Rank Math                    شو بيقول الموقع كله لمحركات البحث: بيانات
                               الشركة، خريطة الموقع (sitemap)، والقيم
                               الافتراضية للعناوين.

ما في منتقي ألوان ولا CSS حر، وهاد مقصود: كل لون معروض هو لون موجود
بالتصميم أصلاً، فالموقع بيحافظ على تصميمه مهما كان مين يعدّل عليه. بس فيك
تحط صورة من عندك ورا أي قسم (Section) أو حاوية (Container) أو لوحة (Panel)
أو الـ hero أو شريط القطاعات: اختاره، ومن الشريط الجانبي Background picture
-> Choose picture. و "Darken the picture" بيغمّق الصورة لحتى يضل الكلام الفاتح
فوقها مقروء.


كيف مرتّب الثيم
------------------------------------------------------------------------------

  header.php, footer.php       هيدر واحد وفوتر واحد
  templates/                   قالب لكل نوع صفحة
  includes/                    الـ markup: layout، elements، parts (الكروت)،
                               sections، loops (المدونة)، كل شي بملفه
  functions/                   كود PHP تبع الثيم، كل موضوع بملف
  blocks/                      تعريف كل بلوك وشو بيخزّن؛ وكل بلوك بيسمّي ملفه
                               تحت includes/
  patterns/sections/           مكتبة الأقسام
  patterns/starters/           بشو بتبدا الصفحة الجديدة من كل نوع
  pages/                       الأقسام الأصلية لكل صفحة، ملف لكل صفحة. الصفحة
                               يلي بتتعدّل ساكنة بووردبريس؛ هدول هنن يلي
                               انركّبت منهم، ويلي بيرجّعهم زر "Restore the
                               original sections".
  install/site.json            يلي بيركّبه Appearance -> Site pages غير الأقسام:
                               العناوين والقوالب والقوائم والنموذج
  assets/                      ملفات التصميم: CSS و JS والصور


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

    shutil.copy2(EXPORT, os.path.join(staging, "osous-posts.xml"))

    os.makedirs(os.path.join(staging, "plugins"))
    for path in fetch_plugins():
        shutil.copy2(path, os.path.join(staging, "plugins", os.path.basename(path)))

    # The zip's size goes into step 1: it is what meets the host's upload limit.
    notes = {"version": version, "theme_mb": os.path.getsize(theme_zip) / 1024.0 / 1024.0}
    io.open(os.path.join(staging, "HANDOFF.txt"), "w", encoding="utf-8", newline="\r\n").write(
        HANDOFF % notes
    )
    io.open(os.path.join(staging, "HANDOFF-ar.txt"), "w", encoding="utf-8-sig", newline="\r\n").write(
        HANDOFF_AR % notes
    )

    os.makedirs(DIST)
    bundle = os.path.join(DIST, folder + ".zip")
    zip_dir(staging, bundle, folder)

    mb = lambda p: os.path.getsize(p) / 1024.0 / 1024.0  # noqa: E731
    print("  osous-theme.zip                %5.1f MB  (%d theme files, %d assets)" % (mb(theme_zip), files, assets))
    print("  osous-posts.xml                %5.1f MB" % mb(os.path.join(staging, "osous-posts.xml")))
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
