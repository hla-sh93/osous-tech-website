=== Osous ===
Contributors: osous
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 0.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: business, custom-menu, translation-ready

The theme for osoustaqnia.com: the site's design, rebuilt by hand, one file per page.

== Description ==

A classic theme laid out the way a small hand-written theme is. Every page of the site is a file under `pages/`, named by its slug: `pages/about.php` is the About page. The file holds the page's sections in order, with its words, as HTML; the design's components -- the header and footer, the page header, the call to action, the pinned list, the cards -- are template parts under `includes/`, each printed from one file. The functions are split by concern under `functions/`: setup, the site details, the helpers, the blog, the contact form.

The blog is written in the editor with WordPress's own blocks and listed by `index.php`. The site details that belong to no page -- the header's button, the addresses, phone numbers, social links and map, the footer's headings, badges and copyright line -- live in the Customizer under Site details, and the logo under Site Identity.

The design's stylesheets are in `assets/css/` and load in a fixed order from `functions/enqueues.php`; `style.css` carries the theme header only.

== Installation ==

1. Upload the theme folder to `wp-content/themes/` and activate it.
2. Import the site content: the pages WordPress needs to know about, the menus, the contact form, the posts. The pages' words are in the theme; the import tells WordPress the pages exist.
3. Set the front page (Settings → Reading → "index") and the blog page ("blog"), and assign the three menus (Appearance → Menus). The theme does both by itself on the first admin visit if they have not been set.
4. Fill in Appearance → Customize → Site details.

Nothing needs building.

= Plugins the contact page needs =

* Contact Form 7 -- the form on /contact/ is a CF7 form called "Contact". The contact section points at it by that title, so the form has to exist (the content import carries it) and keep that name.
* Simple Cloudflare Turnstile -- the spam check on that form. Enter the site's Turnstile keys under Settings → Cloudflare Turnstile; until real keys are in, Cloudflare's test keys let the form work without a challenge.

Where the form's mail goes is the email address under Appearance → Customize → Site details: the mail template's recipient is `[_osous_email]`, which the theme fills in from there. The site's host has to be able to send mail; a hosting SMTP plugin is the usual answer.

== Frequently Asked Questions ==

= How do I change the words on a page? =

Open the page's file under `pages/` and change them there. A heading is an `<h2>`, a paragraph a `<p>`; a card's words are the arguments of its `get_template_part()` call.

= How do I add a section to a page? =

Copy one from another page file -- a call to `includes/sections/…` or a `<section>` block -- into the page's file where it should go, and change its words.

= How do I add a page? =

Add the page under Pages so WordPress knows its address, then make `pages/<its-slug>.php` by copying a similar page's file. Until the file exists the page shows what is written in the editor.

= Where do the phone numbers and addresses come from? =

Appearance → Customize → Site details, with the header's button, the footer's headings, its two badges and its copyright line.

= How do I change the logo? =

Appearance → Customize → Site Identity → Logo. Any shape works: it is shown 50px tall at its own proportions. Remove it to get the design's own back.

== Third-party resources ==

* GSAP 3.13 with ScrollTrigger, by Webflow, loaded from cdnjs under the GSAP standard license (free for all use since 3.13): https://gsap.com/licensing/
* Swiper 11, by Vladimir Kharlampidi, loaded from jsDelivr under the MIT license: https://swiperjs.com
* Inter (by Rasmus Andersson) and Sora (by Jonathan Barnbrook), served by Google Fonts under the SIL Open Font License 1.1.

== Changelog ==

= 0.3.0 =
* One file per page under `pages/`, a single header and footer under `includes/`, the sections and cards as template parts, the functions under `functions/`. The block editor is used for posts only.

= 0.2.1 =
* The logo comes from Customize → Site Identity; the header's button, the footer's headings, badges and copyright line are Site details.
* A purple card no longer saves an empty paragraph when it is merely clicked.

= 0.2.0 =
* Every kind of section, card and piece of content is its own block, with named options; the one tree-carrying block is gone.
* Running text is written in WordPress's own paragraph, heading and list blocks.
* The design's misspelt class names from the Webflow export are corrected.

= 0.1.0 =
* First release.
