=== Osous ===
Contributors: osous
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 0.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: business, custom-menu, translation-ready

The theme for osoustaqnia.com: the site's design, rebuilt by hand, with every page edited in the block editor.

== Description ==

A classic theme with its own blocks. Every page is built from them: a Section holds a Container, the Container holds a Section heading, a Grid, cards, text and pictures. Each block lives in its own folder under `blocks/` -- `block.json` says what it is and what it stores, `edit.js` is how it is edited -- and is printed by one file under `includes/`, named in its `block.json` -- and each offers only the choices the design has: a background, a spacing, a text style, a column count, chosen by name from `functions/design.php`. Words and pictures are typed and picked in place, in the editor's canvas.

A page is one of seven kinds -- home, about, solution, partner, service, contact, legal -- and each kind is a template under `templates/`, shown in the editor's Template panel. A new page is asked which kind it is and starts as that kind's sections, from `patterns/starters/`; every page the site was installed with has its original sections as a file under `pages/`, which the editor can put back. A page's address can be changed at any time: the old address is sent on to the new one and links to the page follow it.

The section library (Osous sections, in the pattern inserter) holds one of each kind of section the design has. Running text -- a policy page, the paragraphs in a purple card -- is written in WordPress's own paragraph, heading and list blocks inside a Prose block. The site details that belong to no page -- the header's button, the addresses, phone numbers, social links and map, the footer's headings, badges and copyright line -- live in the Customizer under Site details, and the logo under Site Identity.

The design's stylesheets are in `assets/css/` and load in a fixed order from `functions/enqueue.php`; `style.css` carries the theme header only.

== Installation ==

1. Upload the theme folder to `wp-content/themes/` and activate it.
2. Import the site content (pages, posts, menus).
3. Set the front page (Settings → Reading → "index") and the blog page ("blog"), and assign the three menus (Appearance → Menus). The theme does both by itself on the first admin visit if they have not been set.
4. Fill in Appearance → Customize → Site details.

Nothing needs building. The block editor's script is shipped compiled in `build/`.

= What a search engine is told =

The theme prints no title, description or canonical address of its own: an SEO plugin does, from fields on each page. The site this theme was built for uses Rank Math, and the content import carries each page's title and description in Rank Math's fields. The theme adds the one line no SEO plugin writes -- hreflang, from Appearance → Customize → Site details → Search engines, and from each page's Page options.

= Plugins the contact page needs =

* Contact Form 7 -- the form on /contact/ is a CF7 form called "Contact". The page's Contact block points at it by that title, so the form has to exist (the content import carries it) and keep that name.
* Simple Cloudflare Turnstile -- the spam check on that form. Enter the site's Turnstile keys under Settings → Cloudflare Turnstile; until real keys are in, Cloudflare's test keys let the form work without a challenge.

Where the form's mail goes is the email address under Appearance → Customize → Site details: the mail template's recipient is `[_osous_email]`, which the theme fills in from there. The site's host has to be able to send mail; a hosting SMTP plugin is the usual answer.

== Frequently Asked Questions ==

= How do I add a section to a page? =

Open the page, click +, choose Patterns → Osous sections, and pick one. Then click its words and type, or click a card for its icon and link in the sidebar.

= How do I build a section from scratch? =

Insert a Section, then a Container inside it, then whatever it needs: a Section heading with a Heading in it, a Grid with cards or Columns in it, Text, an Image, a Button. Every block's choices are in the sidebar.

= Why can't I add a core paragraph at the top of a page? =

You can, but nothing on this site styles one outside a Prose block or a card, and the design has no place for it there. Put running text in a Prose block inside a Container.

= Where do the phone numbers and addresses come from? =

Appearance → Customize → Site details, with the header's button, the footer's headings, its two badges and its copyright line.

= How do I make a new page? =

Pages → Add New. The editor asks what kind of page it is; choose one and the page starts with that kind's sections and its template, with words to replace. Or, in the list of pages, use Duplicate under a page that is already close to what you want.

= Can I change a page's address? =

Yes, in the editor's sidebar, at any time. The old address is redirected to the new one, the menus follow, and so does every link to the page from another page.

= I changed a page and want it back as it was =

Open the page, and under Page options in the sidebar choose "Restore the original sections". Nothing is saved until you save the page, and Undo takes it back.

= How do I change the logo? =

Appearance → Customize → Site Identity → Logo. Any shape works: it is shown 50px tall at its own proportions. Remove it to get the design's own back.

== Third-party resources ==

* GSAP 3.13 with ScrollTrigger, by Webflow, loaded from cdnjs under the GSAP standard license (free for all use since 3.13): https://gsap.com/licensing/
* Swiper 11, by Vladimir Kharlampidi, loaded from jsDelivr under the MIT license: https://swiperjs.com
* Inter (by Rasmus Andersson) and Sora (by Jonathan Barnbrook), served by Google Fonts under the SIL Open Font License 1.1.

== Changelog ==

= 0.4.0 =
* The theme is laid out as bst lays one out: `functions/`, `includes/` for the markup, templates in the root. Every block's markup is a file under `includes/`.
* A page is one of seven kinds, each a template; a new page is asked its kind and starts as that kind's sections. Every installed page has its original under `pages/`, and the editor can restore it.
* A page's slug can be changed: its old address redirects, and links to it follow.
* Page options (the WhatsApp badge, other language versions), Duplicate in the list of pages, links picked from the list of pages, and synced patterns on pages.
* Search engines: each page's title and description travel with the content into Rank Math's fields; the theme prints hreflang.

= 0.2.1 =
* The logo comes from Customize → Site Identity; the header's button, the footer's headings, badges and copyright line are Site details.
* A purple card no longer saves an empty paragraph when it is merely clicked.

= 0.2.0 =
* Every kind of section, card and piece of content is its own block, with named options; the one tree-carrying block is gone.
* Running text is written in WordPress's own paragraph, heading and list blocks.
* The design's misspelt class names from the Webflow export are corrected.

= 0.1.0 =
* First release.
