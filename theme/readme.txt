=== Osous ===
Contributors: osous
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: business, custom-menu, translation-ready

The theme for osoustaqnia.com: the site's design, rebuilt by hand, with every page edited in the block editor.

== Description ==

A classic theme. Every page is a list of sections, and every section is one `osous/section` block whose words, pictures, links, columns and colours are edited in the block editor's sidebar. The section library (Osous sections, in the pattern inserter) holds one of each kind of section the design has. The site details that belong to no page -- addresses, phone numbers, social links, the map -- live in the Customizer under Site details.

The design's stylesheets are in `assets/css/` and load in a fixed order from `inc/enqueue.php`; `style.css` carries the theme header only.

== Installation ==

1. Upload the theme folder to `wp-content/themes/` and activate it.
2. Import the site content (pages, posts, menus).
3. Set the front page (Settings → Reading → "index") and the blog page ("blog"), and assign the three menus (Appearance → Menus). The theme does both by itself on the first admin visit if they have not been set.
4. Fill in Appearance → Customize → Site details.

Nothing needs building. The block editor's script is shipped compiled in `build/`.

= Plugins the contact page needs =

* Contact Form 7 -- the form on /contact/ is a CF7 form called "Contact". The page's section points at it by that title, so the form has to exist (the content import carries it) and keep that name.
* Simple Cloudflare Turnstile -- the spam check on that form. Enter the site's Turnstile keys under Settings → Cloudflare Turnstile; until real keys are in, Cloudflare's test keys let the form work without a challenge.

Where the form's mail goes is the email address under Appearance → Customize → Site details: the mail template's recipient is `[_osous_email]`, which the theme fills in from there. The site's host has to be able to send mail; a hosting SMTP plugin is the usual answer.

== Frequently Asked Questions ==

= How do I add a section to a page? =

Open the page, click +, choose Patterns → Osous sections, and pick one. Its words and pictures are then edited in the sidebar on the right.

= Why can't I add a paragraph or an image block? =

Every section on this site is an `osous/section`, and the design has no styles for anything else. A page that needs something new needs a new section.

= Where do the phone numbers and addresses come from? =

Appearance → Customize → Site details.

== Third-party resources ==

* GSAP 3.13 with ScrollTrigger, by Webflow, loaded from cdnjs under the GSAP standard license (free for all use since 3.13): https://gsap.com/licensing/
* Swiper 11, by Vladimir Kharlampidi, loaded from jsDelivr under the MIT license: https://swiperjs.com
* Inter (by Rasmus Andersson) and Sora (by Jonathan Barnbrook), served by Google Fonts under the SIL Open Font License 1.1.

== Changelog ==

= 0.1.0 =
* First release.
