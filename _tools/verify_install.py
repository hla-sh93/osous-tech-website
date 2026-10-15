# -*- coding: utf-8 -*-
"""
Install the site's pages onto the two kinds of WordPress the theme meets, and look.

    python _tools/verify_install.py            # all three
    python _tools/verify_install.py live       # the live site's shape, through WP-CLI
    python _tools/verify_install.py button     # the same, through the dashboard in Chrome
    python _tools/verify_install.py empty      # an empty WordPress

Appearance → Site pages (theme/functions/install.php) is the theme putting its own pages
into WordPress. It has to come out right on two sites that start nothing alike, and this
makes each of them out of the local one, runs `wp osous install-pages` -- the button's
own code -- and checks what it did.

**The live site's shape**, as measured through its REST API on 2026-10-01: every page
already at its address, published, empty, on the default template, plus pages this
project has no original for (`hikvision-iraq`) and real posts dated with a time. Here,
also: the home page known only as the front page (its slug is not `index`), one page
with words of its own, one with its search title filled in and its description not, the
Privacy Policy a draft, no menus, and the contact form under another name. Then:

  - no page is made, no page is renamed, no `-2` appears, every page keeps its id
  - every page but the one with its own words holds exactly its original, on its kind's
    template; that one is left as it is, and the page this project has no original for
  - the search fields: kept where they were filled, filled where they were empty
  - the draft is published; the front page is the home page; the menus are laid down
    in their places; a form called Contact is made, and the other one left alone
  - no post, and nothing of a post, is changed
  - run a second time it changes nothing at all
  - asked to replace content, the page with words of its own gets its original, and
    those words are in a revision of it
  - the site then reads as the reference: `verify_wp.py`, `verify_templates.py`,
    `verify_chrome.py`, and `verify_seo.py` on every page whose search fields were not
    the site's own

**An empty WordPress**: the pages, the menus and the form deleted, the posts kept. Every
page is made at its own address, and the same four checks pass.

The database is exported before anything changes and imported again at the end, even
when a step fails; a run that is killed half way leaves it in
_migration/snapshots/verify-install.sql, to put back with
`./_tools/env.sh restore verify-install`.

**Passing looks like:** `0 problems`
"""
import base64
import io
import json
import os
import subprocess
import sys
import time

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PROBE = os.path.join(ROOT, "_migration", "verify_install.php")
SNAPSHOT = "verify-install"
# A must-use plugin the local site loads (docker-compose.yml mounts the folder): rounds of
# one second, so the button's rounds are exercised however quickly pages save here.
SHORT_ROUNDS = os.path.join(ROOT, "_tools", "local", "mu-plugins", "osous-test-rounds.php")

# The pages the live-shaped site changes by hand, and what it puts in them.
OWN_WORDS = "about"
OWN_HTML = "<p>Words from the live site, written in its old editor.</p>"
SEO_PAGE = "dell-iraq"
LIVE_EXTRA = "hikvision-iraq"
FORM_ELSEWHERE = "Contact form on the live site"

HANDS = r"""<?php
/*
 * Steps of _tools/verify_install.py, run inside WordPress. Generated -- do not edit.
 */
global $wpdb;
$what = isset( $args[0] ) ? $args[0] : '';
$cfg  = json_decode( base64_decode( isset( $args[1] ) ? $args[1] : '' ), true );

function vi_pages() {
	return get_posts( array( 'post_type' => 'page', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'numberposts' => 500, 'orderby' => 'ID', 'order' => 'ASC' ) );
}

if ( 'live' === $what ) {
	// Every page as the old theme left it: empty, the default template, no original.
	foreach ( vi_pages() as $page ) {
		$wpdb->update( $wpdb->posts, array( 'post_content' => '' ), array( 'ID' => $page->ID ) );
		delete_post_meta( $page->ID, '_wp_page_template' );
		delete_post_meta( $page->ID, '_osous_original' );
		delete_post_meta( $page->ID, 'rank_math_title' );
		delete_post_meta( $page->ID, 'rank_math_description' );
		clean_post_cache( $page->ID );
	}
	$id = osous_page_id( $cfg['own_words'] );
	$wpdb->update( $wpdb->posts, array( 'post_content' => $cfg['own_html'] ), array( 'ID' => $id ) );
	update_post_meta( $id, 'rank_math_title', 'LIVE title of ' . $cfg['own_words'] );
	update_post_meta( $id, 'rank_math_description', 'LIVE description of ' . $cfg['own_words'] );
	update_post_meta( osous_page_id( $cfg['seo_page'] ), 'rank_math_title', 'LIVE title of ' . $cfg['seo_page'] );

	// The home page is the front page, under a slug that is not its key.
	$home = osous_page_id( 'index' );
	$wpdb->update( $wpdb->posts, array( 'post_name' => 'home' ), array( 'ID' => $home ) );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home );

	$privacy = osous_page_id( 'privacy-policy' );
	$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $privacy ) );
	update_option( 'wp_page_for_privacy_policy', $privacy );

	wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_name' => $cfg['extra'], 'post_title' => 'Hikvision Partner in Iraq', 'post_content' => '' ) );
	wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'private', 'post_name' => 'a-live-post', 'post_title' => 'A post from the live site', 'post_content' => '<p>Real words.</p>', 'post_date' => '2025-12-10 17:04:07' ) );

	foreach ( wp_get_nav_menus() as $menu ) {
		wp_delete_nav_menu( $menu->term_id );
	}
	set_theme_mod( 'nav_menu_locations', array() );

	$form = wpcf7_get_contact_form_by_title( 'Contact' );
	$form->set_title( $cfg['form_elsewhere'] );
	$form->save();
	wp_cache_flush();
	echo 'OSOUS-JSON' . wp_json_encode( array( 'home' => $home ) );
	return;
}

if ( 'empty' === $what ) {
	foreach ( vi_pages() as $page ) {
		wp_delete_post( $page->ID, true );
	}
	foreach ( wp_get_nav_menus() as $menu ) {
		wp_delete_nav_menu( $menu->term_id );
	}
	set_theme_mod( 'nav_menu_locations', array() );
	$form = wpcf7_get_contact_form_by_title( 'Contact' );
	wp_delete_post( $form->id(), true );
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_on_front', 0 );
	update_option( 'page_for_posts', 0 );
	wp_cache_flush();
	echo 'OSOUS-JSON' . wp_json_encode( array( 'pages' => count( vi_pages() ) ) );
	return;
}

if ( 'unrename' === $what ) {
	$wpdb->update( $wpdb->posts, array( 'post_name' => 'index' ), array( 'ID' => (int) $cfg['home'] ) );
	clean_post_cache( (int) $cfg['home'] );
	echo 'OSOUS-JSON' . wp_json_encode( array( 'ok' => true ) );
	return;
}

// state: everything the installer may and may not change.
$out = array( 'pages' => array(), 'posts' => '', 'menus' => array(), 'forms' => array(), 'reading' => array(), 'revisions' => array() );
foreach ( vi_pages() as $page ) {
	$original = (string) get_post_meta( $page->ID, '_osous_original', true );
	$out['pages'][ $page->ID ] = array(
		'name'     => $page->post_name,
		'status'   => $page->post_status,
		'title'    => $page->post_title,
		'content'  => md5( $page->post_content ),
		'empty'    => '' === trim( $page->post_content ),
		'own'      => false !== strpos( $page->post_content, 'Words from the live site' ),
		'template' => (string) get_post_meta( $page->ID, '_wp_page_template', true ),
		'original' => $original,
		'matches'  => '' !== $original && osous_original_markup( $original, true ) === $page->post_content,
		'seo'      => array( (string) get_post_meta( $page->ID, 'rank_math_title', true ), (string) get_post_meta( $page->ID, 'rank_math_description', true ) ),
	);
	foreach ( wp_get_post_revisions( $page->ID ) as $revision ) {
		if ( false !== strpos( $revision->post_content, 'Words from the live site' ) ) {
			$out['revisions'][] = $page->post_name;
		}
	}
}
$posts = $wpdb->get_results( "SELECT p.ID, p.post_name, p.post_date, p.post_modified, p.post_status, MD5(p.post_content) AS c FROM {$wpdb->posts} p WHERE p.post_type = 'post' ORDER BY p.ID", ARRAY_A );
$meta  = $wpdb->get_results( "SELECT m.meta_id, m.post_id, m.meta_key, MD5(m.meta_value) AS v FROM {$wpdb->postmeta} m JOIN {$wpdb->posts} p ON p.ID = m.post_id WHERE p.post_type = 'post' ORDER BY m.meta_id", ARRAY_A );
$out['posts']      = md5( wp_json_encode( array( $posts, $meta ) ) );
$out['post_count'] = count( $posts );
$locations = get_nav_menu_locations();
foreach ( array( 'primary', 'footer-discover', 'footer-legal' ) as $location ) {
	$items = empty( $locations[ $location ] ) ? array() : wp_get_nav_menu_items( $locations[ $location ] );
	$out['menus'][ $location ] = $items ? count( $items ) : 0;
}
foreach ( get_posts( array( 'post_type' => 'wpcf7_contact_form', 'numberposts' => 50, 'post_status' => 'any' ) ) as $form ) {
	$out['forms'][ $form->post_title ] = md5( $form->post_content . wp_json_encode( get_post_meta( $form->ID ) ) );
}
$out['reading'] = array(
	'show_on_front'  => get_option( 'show_on_front' ),
	'page_on_front'  => (int) get_option( 'page_on_front' ),
	'page_for_posts' => (int) get_option( 'page_for_posts' ),
);
echo 'OSOUS-JSON' . wp_json_encode( $out );
"""


def run(command):
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    return subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env, encoding="utf-8")


def wp(*args):
    return run(["docker", "compose", "--profile", "tools", "run", "--rm", "-T", "cli", "wp"] + list(args))


def hands(what, cfg=None):
    started = time.time()
    with io.open(PROBE, "w", encoding="utf-8", newline="\n") as target:
        target.write(HANDS)
    blob = base64.b64encode(json.dumps(cfg or {}).encode("utf-8")).decode("ascii")
    done = wp("eval-file", "/migration/verify_install.php", what, blob, "--user=admin")
    start = done.stdout.find("OSOUS-JSON")
    if start < 0:
        raise SystemExit("WP-CLI said nothing for %s -- is the stack up?\n%s\n%s"
                         % (what, done.stdout[-800:], done.stderr[-800:]))
    print("    (%s: %ds)" % (what, time.time() - started))
    return json.loads(done.stdout[start + len("OSOUS-JSON"):])


def install(*flags):
    started = time.time()
    done = wp("osous", "install-pages", "--user=admin", *flags)
    last = [line for line in done.stdout.splitlines() if line.strip()]
    print("    wp osous install-pages %s-> %s  (%ds)" % (" ".join(flags) + " " if flags else "",
                                                       last[-1] if last else done.stderr.strip()[-300:],
                                                       time.time() - started))
    return done.returncode == 0


def tool(name, *args):
    """Another check, run against the site as it is now; its last line, and whether it passed."""
    done = run([sys.executable, os.path.join("_tools", name)] + list(args))
    lines = [line for line in done.stdout.splitlines() if line.strip()]
    last = lines[-1] if lines else done.stderr.strip()[-300:]
    if done.returncode != 0:
        for line in [l for l in lines if l.startswith(("###", "      ")) or "!!" in l][:8]:
            print("        " + line.strip()[:200])
    return last


class Run:
    def __init__(self):
        self.problems = []

    def check(self, ok, what):
        if not ok:
            self.problems.append(what)
            print("    !! " + what)


def site():
    with io.open(os.path.join(ROOT, "theme", "install", "site.json"), encoding="utf-8") as source:
        return json.load(source)


def by_name(state):
    return {page["name"]: dict(page, id=int(pid)) for pid, page in state["pages"].items()}


def reads_as_the_reference(r, seo_skip=()):
    """The four checks that hold a site against the reference pages."""
    expect = {
        "verify_wp.py": "0 of 57 pages differ",
        "verify_templates.py": "0 problems",
        "verify_chrome.py": "0 of 5 pages differ",
    }
    for name, sentence in expect.items():
        started = time.time()
        said = tool(name)
        print("    %-20s %s  (%ds)" % (name, said, time.time() - started))
        r.check(said == sentence, "%s said %r" % (name, said))
    slugs = [slug for slug in site()["pages"] if slug not in seo_skip]
    said = tool("verify_seo.py", *slugs)
    print("    %-20s %s" % ("verify_seo.py", said))
    r.check(said == "%d pages, 0 problems" % len(slugs), "verify_seo.py said %r" % said)


def make_live(r):
    """The local site in the live site's shape: (the home page's id, the state before)."""
    made = hands("live", {"own_words": OWN_WORDS, "own_html": OWN_HTML, "seo_page": SEO_PAGE,
                          "extra": LIVE_EXTRA, "form_elsewhere": FORM_ELSEWHERE})
    before = hands("state")
    names = by_name(before)
    r.check(all(names[k]["empty"] for k in names if k != OWN_WORDS), "the pages were not all emptied")
    return made["home"], before


def installed_in_place(r, before, after, home):
    """Everything the first install onto the live site's shape has to have done, and not done."""
    data = site()
    names = by_name(before)
    now = by_name(after)

    # No page made, none renamed, every one where it was.
    r.check(sorted(before["pages"]) == sorted(after["pages"]),
            "pages were made or removed: %d before, %d after" % (len(before["pages"]), len(after["pages"])))
    for pid, page in after["pages"].items():
        if pid in before["pages"]:
            r.check(page["name"] == before["pages"][pid]["name"],
                    "page %s was renamed %r -> %r" % (pid, before["pages"][pid]["name"], page["name"]))
        r.check(not page["name"].endswith("-2"), "a page at %r" % page["name"])

    # What each page holds now.
    for key, entry in data["pages"].items():
        page = now.get("home" if key == "index" else key)
        if not page:
            r.check(False, "%s: no page" % key)
            continue
        if key == OWN_WORDS:
            r.check(page["own"] and page["template"] in ("", "default"),
                    "%s: its own words were not left alone" % key)
            r.check(page["seo"] == ["LIVE title of " + key, "LIVE description of " + key],
                    "%s: its search fields changed: %r" % (key, page["seo"]))
            continue
        if entry["original"]:
            r.check(page["matches"], "%s: does not hold its original" % key)
        r.check(page["status"] == "publish", "%s: is %s" % (key, page["status"]))
        if entry["template"] != "default":
            r.check(page["template"] == entry["template"], "%s: on %r" % (key, page["template"]))
        want = [entry["seo"]["title"], entry["seo"]["description"]]
        if key == SEO_PAGE:
            want[0] = "LIVE title of " + key
        r.check(page["seo"] == want, "%s: search fields %r, wanted %r" % (key, page["seo"], want))

    extra_before, extra_after = names[LIVE_EXTRA], now[LIVE_EXTRA]
    r.check(extra_before == extra_after, "%s was touched" % LIVE_EXTRA)
    r.check(after["posts"] == before["posts"], "the posts changed")
    r.check(after["reading"]["page_on_front"] == home and after["reading"]["show_on_front"] == "page",
            "the front page is %r" % after["reading"])
    r.check(after["reading"]["page_for_posts"] == now["blog"]["id"], "the posts page is %r" % after["reading"])
    r.check(after["menus"] == {"primary": 58, "footer-discover": 6, "footer-legal": 2},
            "the menus are %r" % after["menus"])
    r.check("Contact" in after["forms"], "no form called Contact")
    r.check(after["forms"].get(FORM_ELSEWHERE) == before["forms"].get(FORM_ELSEWHERE),
            "the live site's own form changed")
    print("    %d pages held as before by id; %d filled; %s left with its own words; %d posts untouched"
          % (len(after["pages"]), sum(1 for p in now.values() if p["matches"]), OWN_WORDS, after["post_count"]))


def scenario_live(r):
    print("the live site's shape")
    home, before = make_live(r)
    r.check(install(), "the install failed")
    after = hands("state")
    installed_in_place(r, before, after, home)

    # A second run changes nothing.
    r.check(install(), "the second install failed")
    again = hands("state")
    r.check(again == after, "the second run changed something")

    # Asked to, it replaces the page with words of its own, and keeps them as a revision.
    r.check(install("--replace-content"), "the install with --replace-content failed")
    replaced = hands("state")
    page = by_name(replaced)[OWN_WORDS]
    r.check(page["matches"], "%s: not replaced" % OWN_WORDS)
    r.check(OWN_WORDS in replaced["revisions"], "%s: its own words are in no revision" % OWN_WORDS)
    r.check(page["seo"][0] == "LIVE title of " + OWN_WORDS, "%s: its search title was replaced" % OWN_WORDS)
    r.check(replaced["posts"] == before["posts"], "the posts changed")

    # The home page's address back, so the checks that read pages by slug find it.
    hands("unrename", {"home": home})
    reads_as_the_reference(r, seo_skip=(OWN_WORDS, SEO_PAGE))


def scenario_button(r):
    """The same site, installed from the dashboard by a browser, as a person would."""
    print("the live site's shape, through Appearance -> Site pages in Chrome")
    home, before = make_live(r)
    started = time.time()
    with io.open(SHORT_ROUNDS, "w", encoding="utf-8", newline="\n") as target:
        target.write("<?php\n// Written by _tools/verify_install.py for one run, and removed after it.\n"
                     "add_filter( 'osous_install_round', function () { return 1; } );\n")
    try:
        done = run(["node", os.path.join("_tools", "install_by_button.js")])
    finally:
        os.remove(SHORT_ROUNDS)
    for line in done.stdout.splitlines():
        if not line.startswith("OSOUS-JSON"):
            print("    " + line)
    start = done.stdout.find("OSOUS-JSON")
    if start < 0:
        r.check(False, "the browser said nothing: %s" % done.stderr[-400:])
        return
    said = json.loads(done.stdout[start + len("OSOUS-JSON"):])
    print("    (browser: %ds)" % (time.time() - started))
    expected = {"fill": 54, "publish": 1, "current": 1, "kept": 1}
    r.check(not said["errors"], "the browser met errors: %s" % said["errors"])
    r.check("55 of the site" in said.get("noticeBefore", ""),
            "the Dashboard said %r" % said.get("noticeBefore"))
    r.check(said.get("plan") == expected, "the screen planned %r" % said.get("plan"))
    r.check(said.get("report") == expected, "the report said %r" % said.get("report"))
    r.check(said.get("planAfter") == {"current": 56, "kept": 1}, "after, the screen shows %r" % said.get("planAfter"))
    r.check(said.get("noticeAfter") == "", "the Dashboard still says %r" % said.get("noticeAfter"))
    # Rounds of one second: however fast a page saves here, fifty-five do not fit in one.
    r.check(said.get("rounds", 0) >= 2, "it was installed in %r round(s)" % said.get("rounds"))
    after = hands("state")
    installed_in_place(r, before, after, home)
    # The page with its own words is its owner's until replaced; that is the live run's to show.
    install("--replace-content")
    hands("unrename", {"home": home})
    reads_as_the_reference(r, seo_skip=(OWN_WORDS, SEO_PAGE))


def scenario_empty(r):
    data = site()
    print("an empty WordPress")
    emptied = hands("empty")
    r.check(emptied["pages"] == 0, "%d pages left" % emptied["pages"])
    before = hands("state")

    r.check(install(), "the install failed")
    after = hands("state")
    now = by_name(after)
    r.check(sorted(now) == sorted(data["pages"]),
            "the pages made are not the site's: %s" % sorted(set(now) ^ set(data["pages"])))
    for key, entry in data["pages"].items():
        page = now.get(key)
        if not page:
            continue
        if entry["original"]:
            r.check(page["matches"], "%s: does not hold its original" % key)
        r.check(page["title"] == entry["title"], "%s: titled %r" % (key, page["title"]))
    r.check(after["posts"] == before["posts"], "the posts changed")
    r.check(after["reading"]["page_on_front"] == now["index"]["id"], "the front page is %r" % after["reading"])
    r.check(after["menus"] == {"primary": 58, "footer-discover": 6, "footer-legal": 2},
            "the menus are %r" % after["menus"])
    r.check("Contact" in after["forms"], "no form called Contact")
    print("    %d pages made, %d posts untouched" % (len(now), after["post_count"]))
    reads_as_the_reference(r)


def main(argv):
    sys.stdout.reconfigure(line_buffering=True)
    which = argv or ["live", "button", "empty"]
    r = Run()
    saved = wp("db", "export", "/migration/snapshots/%s.sql" % SNAPSHOT, "--add-drop-table")
    if saved.returncode != 0:
        raise SystemExit("could not save the database: %s" % saved.stderr[-400:])
    try:
        for name in which:
            {"live": scenario_live, "button": scenario_button, "empty": scenario_empty}[name](r)
            back = wp("db", "import", "/migration/snapshots/%s.sql" % SNAPSHOT)
            r.check(back.returncode == 0, "the database did not go back: %s" % back.stderr[-300:])
            wp("cache", "flush")
    finally:
        wp("db", "import", "/migration/snapshots/%s.sql" % SNAPSHOT)
        wp("cache", "flush")
        if os.path.exists(PROBE):
            os.remove(PROBE)

    print()
    if r.problems:
        print("%d problems" % len(r.problems))
        return 1
    print("0 problems")
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
