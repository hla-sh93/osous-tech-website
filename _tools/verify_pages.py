# -*- coding: utf-8 -*-
"""
What a page carries besides its blocks, and what follows from the blocks it holds.

    python _tools/verify_pages.py

Four things a page decides for itself, none of which any reference page exercises --
every one of the 57 has the badge, only the front page opens on a hero, none holds a
synced pattern -- so nothing else here would notice any of them breaking:

  - the WhatsApp badge: switched off on a page (Page options), it is not printed there,
    and still is everywhere else
  - the header over a dark opening: a page that starts with the hero gets the navbar's
    dark form whether or not it is the front page, and a page that does not, does not
  - a synced pattern: a section kept under Patterns and placed on a page is printed like
    any other section, and brings its scroll script with it
  - the originals: the editor's "Restore the original sections" is answered with the
    blocks of the page's file for someone who may edit pages, refused for a visitor, and
    finds nothing for a key that is no file

It makes two scratch pages and one scratch pattern, and deletes them; the one page it
changes is put back.

**Passing looks like:** `0 problems`
"""
import json
import os
import subprocess
import sys
import urllib.error
import urllib.request

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.environ.get("OSOUS_WP", "http://localhost:8080").rstrip("/")
PROBE = os.path.join(ROOT, "_migration", "verify_pages.php")

# Run by WP-CLI: makes the scratch content and says what it made.
SETUP = r"""<?php
$patterns = WP_Block_Patterns_Registry::get_instance();
$home     = $patterns->get_registered( 'osous/starter-home' );
$pinned   = $patterns->get_registered( 'osous/pinned-list' );

$hero = wp_insert_post( wp_slash( array(
	'post_type'    => 'page',
	'post_status'  => 'publish',
	'post_title'   => 'verify_pages.py, a page that opens on a hero',
	'post_name'    => 'verify-pages-hero',
	'post_content' => $home['content'],
) ) );
update_post_meta( $hero, '_wp_page_template', 'templates/page-home.php' );

$synced = wp_insert_post( wp_slash( array(
	'post_type'    => 'wp_block',
	'post_status'  => 'publish',
	'post_title'   => 'verify_pages.py, a synced pinned list',
	'post_content' => $pinned['content'],
) ) );

$holder = wp_insert_post( wp_slash( array(
	'post_type'    => 'page',
	'post_status'  => 'publish',
	'post_title'   => 'verify_pages.py, a page holding a synced pattern',
	'post_name'    => 'verify-pages-synced',
	'post_content' => '<!-- wp:block {"ref":' . (int) $synced . '} /-->',
) ) );

$about = get_page_by_path( 'about', OBJECT, 'page' );
update_post_meta( $about->ID, '_osous_hide_whatsapp', true );

/* The originals, asked for the way the editor asks: through the REST route. */
$ask = static function ( $key ) {
	$response = rest_do_request( new WP_REST_Request( 'GET', '/osous/v1/original/' . $key ) );
	$data     = $response->get_data();
	return array(
		'status' => $response->get_status(),
		'blocks' => isset( $data['content'] ) ? substr_count( $data['content'], '<!-- wp:' ) : 0,
	);
};
$visitor = $ask( 'dell-iraq' );
wp_set_current_user( 1 );
$editor  = $ask( 'dell-iraq' );
$missing = $ask( 'no-such-page' );
$dell    = get_page_by_path( 'dell-iraq', OBJECT, 'page' );

echo 'OSOUS-JSON' . wp_json_encode( array(
	'hero'    => $hero,
	'synced'  => $synced,
	'holder'  => $holder,
	'about'   => $about->ID,
	'visitor' => $visitor,
	'editor'  => $editor,
	'missing' => $missing,
	'dell'    => substr_count( $dell->post_content, '<!-- wp:' ),
) );
"""


def wp(*args):
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "-T", "cli", "wp"] + list(args)
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    done = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env, encoding="utf-8")
    return done.stdout.strip() + ("\n" + done.stderr.strip() if done.returncode else "")


def fetch(path):
    with urllib.request.urlopen(BASE + path, timeout=30) as response:
        return response.read().decode("utf-8")


def status(path):
    try:
        with urllib.request.urlopen(BASE + path, timeout=30) as response:
            return response.status
    except urllib.error.HTTPError as error:
        return error.code


def main():
    problems = []

    def check(ok, what, detail=""):
        print(("  ok  " if ok else "  !!  ") + what + ("" if ok or not detail else " -- " + detail))
        if not ok:
            problems.append(what)

    with open(PROBE, "w", encoding="utf-8", newline="\n") as target:
        target.write(SETUP)
    raw = wp("eval-file", "/migration/verify_pages.php")
    start = raw.find("OSOUS-JSON")
    if start < 0:
        raise SystemExit("WP-CLI returned nothing -- is the stack up?\n" + raw[-800:])
    made = json.loads(raw[start + len("OSOUS-JSON"):])

    try:
        # ------------------------------------------------------------ the badge
        print("the WhatsApp badge:")
        about = fetch("/about/")
        other = fetch("/dell-iraq/")
        check('class="whatsapp"' not in about, "switched off on a page, it is not printed there")
        check('class="whatsapp"' in other, "and still is on a page that did not switch it off")

        # ------------------------------------------------------------ the dark header
        print("\nthe header over a dark opening:")
        hero = fetch("/verify-pages-hero/")
        check('class="navbar elevation darkmode"' in hero, "a page that opens on the hero has the dark navbar, front page or not")
        check("c-surface-secondary-900" in hero.split("<body", 1)[1].split(">", 1)[0], "and the dark body behind it")
        check('class="navbar elevation"' in other, "a page that does not, does not")
        check("overlapping-cards.js" in hero and "industries-carousel.js" in hero,
              "and its sections brought their scripts to the new page")

        # ------------------------------------------------------------ a synced pattern
        print("\na synced pattern:")
        holder = fetch("/verify-pages-synced/")
        check("list-scroll-section" in holder, "placed on a page, its section is printed")
        check("list-scroll.js" in holder, "and its scroll script is loaded")

        # ------------------------------------------------------------ the originals
        print("\nthe originals:")
        check(made["editor"]["status"] == 200 and made["editor"]["blocks"] == made["dell"] and made["dell"] > 0,
              "someone who may edit pages is given the page's original blocks",
              "status %s, %s blocks, the page has %s" % (made["editor"]["status"], made["editor"]["blocks"], made["dell"]))
        check(made["visitor"]["status"] in (401, 403), "a visitor is refused", "status %s" % made["visitor"]["status"])
        check(made["missing"]["status"] == 404, "a key that is no file finds nothing", "status %s" % made["missing"]["status"])
        check(status("/wp-json/osous/v1/original/dell-iraq") in (401, 403), "and over HTTP, without a login, the same")
        check(status("/wp-json/osous/v1/original/..%2F..%2Fstyle") == 404, "a key that tries to leave pages/ is no route at all")
    finally:
        wp("post", "delete", str(made["hero"]), str(made["holder"]), str(made["synced"]), "--force")
        wp("post", "meta", "delete", str(made["about"]), "_osous_hide_whatsapp")

    print("\nput back:")
    check('class="whatsapp"' in fetch("/about/"), "the badge is on the page it was switched off on")
    check(status("/verify-pages-hero/") == 404 and status("/verify-pages-synced/") == 404, "the scratch pages are gone")

    print("\n%d problems" % len(problems))
    return 1 if problems else 0


if __name__ == "__main__":
    sys.exit(main())
