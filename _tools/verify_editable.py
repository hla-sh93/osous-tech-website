"""
Is everything a visitor sees on a page something the dashboard changes?

    python _tools/verify_editable.py                     # every page, all four parts
    python _tools/verify_editable.py --part=blocks       # just one part: blocks, seo, templates, slugs
    python _tools/verify_editable.py about contact       # just these pages

The owner's test, put to every page: every word, picture, link, colour and background on
the page, and what the page tells a search engine and a share, is changed from WordPress;
a page can be moved to another kind of page; a page can be given another address. Each
part changes the site through WordPress, reads every page as a visitor gets it, and puts
back what it changed. Four parts:

  blocks     Every block of every page is saved again the way the editor's save writes
             it (wp_update_post, as the administrator), with each word turned into a
             marker nobody would write, each picture into a marker picture in the uploads,
             each link into a marker address, and every colour and background choice back
             to its plain state. Then each page's <main> is read:
               - every marker is printed: a field that is saved and not printed is a field
                 that changes nothing
               - nothing is left that is not a marker: a word, picture or link that stays
                 put when every field has changed is one no field changes
               - the backgrounds and colours still drawn are the ones a component fixes;
                 the ones that went away were choices, and are counted as such
             The contact form's own labels belong to Contact -> Contact Forms, and are
             looked for there.
  seo        Rank Math's fields on every page -- title, description, the Facebook and the
             Twitter title, description and picture, the card, robots, canonical -- are
             set to markers, and each has to be in the <head> where it is read. Then the
             title and description alone, and the social tags have to follow them.
  templates  Every page is moved to another kind of page. WordPress has to accept it, the
             page's body has to say which template it is on, and the page must not change.
             A template the theme does not have has to be refused.
  slugs      Every page is renamed at once. Each answers at its new address with the same
             sections, its old address arrives there in one permanent redirect, and no link
             on any page points to an old address.

Every change is written down under _migration/editable/ before it is made, and put back
at the end of its part even when the part fails; a run that dies half way leaves the files
there for the next run to put back first. `./_tools/env.sh rebuild` is the last resort.

A summary per page goes to _migration/check/editable.json.

**Passing looks like:** `N pages, 0 problems`
"""
import collections
import glob
import io
import json
import os
import re
import subprocess
import sys
import urllib.error
import urllib.request

from lxml import html as lhtml

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.environ.get("OSOUS_WP", "http://localhost:8080").rstrip("/")
STORE = os.path.join(ROOT, "_migration", "editable")
PROBE = os.path.join(ROOT, "_migration", "verify_editable.php")
REPORT = os.path.join(ROOT, "_migration", "check", "editable.json")
PARTS = ("blocks", "seo", "templates", "slugs")

MARKER = re.compile(r"Zx\d{5}x")
COLOUR = re.compile(r"^(c-(surface|on|border)-[a-z0-9-]+|c-white|gradient-\d+)$")

# Run by WP-CLI as the administrator: `wp eval-file /migration/verify_editable.php <what>`.
# Everything it changes it writes down under /migration/editable/ first.
HANDS = r"""<?php
$what    = isset( $args[0] ) ? $args[0] : '';
$store   = '/migration/editable';
$blog    = (int) get_option( 'page_for_posts' );
$pages   = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'ID', 'order' => 'ASC' ) );
$out     = array();

$GLOBALS['vx'] = array(
	'n'       => 0,
	'words'   => array( 'title', 'text', 'content', 'caption', 'eyebrow', 'buttonText', 'number', 'stat', 'subtitle', 'emailLabel', 'formTitle', 'formText' ),
	'links'   => array( 'url', 'link', 'buttonUrl' ),
	'images'  => array( 'icon', 'image', 'logo' ),
	'choices' => array( 'background', 'backdrop', 'surface', 'tone' ),
	'picture' => home_url( '/wp-content/uploads/probe/' ),
);

function vx_mark( $kind, $block, $attr, $was ) {
	$marker = sprintf( 'Zx%05dx', ++$GLOBALS['vx']['n'] );
	$GLOBALS['vx']['markers'][ $marker ] = array(
		'kind'  => $kind,
		'block' => $block,
		'attr'  => $attr,
		'was'   => mb_substr( trim( wp_strip_all_tags( (string) $was ) ), 0, 80 ),
	);
	return $marker;
}

/*
 * Every field is changed, the empty ones too: a field left empty or at its default is
 * not in the saved block at all, and whatever the page prints in its place -- a default
 * label, a `#` for a card with no link -- is still that field's to change.
 */
function vx_attr( $block, $key, $value ) {
	$vx = $GLOBALS['vx'];
	if ( in_array( $key, $vx['words'], true ) && is_string( $value ) ) {
		return vx_mark( 'word', $block, $key, $value );
	}
	if ( 'alt' === $key && is_string( $value ) ) {
		return vx_mark( 'alt', $block, $key, $value );
	}
	if ( 'email' === $key && is_string( $value ) ) {
		return vx_mark( 'word', $block, $key, $value ) . '@example.com';
	}
	if ( in_array( $key, $vx['links'], true ) && is_string( $value ) ) {
		return 'https://probe.example/' . vx_mark( 'link', $block, $key, $value );
	}
	if ( 'src' === $key && is_string( $value ) && '' !== $value ) {
		return $vx['picture'] . vx_mark( 'picture', $block, $key, $value ) . '.webp';
	}
	if ( 'srcset' === $key && is_string( $value ) && '' !== $value ) {
		return $vx['picture'] . vx_mark( 'srcset', $block, $key, $value ) . '.webp 500w';
	}
	if ( in_array( $key, $vx['images'], true ) && ( is_array( $value ) || null === $value ) ) {
		$value = (array) $value;
		foreach ( array( 'src', 'srcset', 'alt' ) as $part ) {
			$value[ $part ] = vx_attr( $block, $part, isset( $value[ $part ] ) ? $value[ $part ] : ( 'srcset' === $part ? '' : 'none' ) );
		}
		return $value;
	}
	if ( 'phones' === $key && is_array( $value ) ) {
		foreach ( $value as $i => $phone ) {
			foreach ( array( 'label', 'number' ) as $part ) {
				if ( isset( $phone[ $part ] ) && '' !== $phone[ $part ] ) {
					$value[ $i ][ $part ] = vx_mark( 'word', $block, 'phones.' . $part, $phone[ $part ] );
				}
			}
		}
		return $value;
	}
	// A card's picture and its link are offered once its picture is switched on.
	if ( 'media' === $key && 'none' === $value ) {
		return 'box';
	}
	if ( in_array( $key, $vx['choices'], true ) && is_string( $value ) && '' !== $value ) {
		$GLOBALS['vx']['chosen'][] = array( 'block' => $block, 'attr' => $key, 'value' => $value );
		return '';
	}
	return $value;
}

/* A core block keeps its words in its markup: every run of text, every href and src. */
function vx_markup( $block, $markup ) {
	$markup = preg_replace_callback(
		'/(^|>)([^<>]+)(?=<|$)/',
		function ( $m ) use ( $block ) {
			$text = html_entity_decode( $m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			if ( ! preg_match( '/[\p{L}\p{N}]/u', $text ) ) {
				return $m[0];
			}
			return $m[1] . vx_mark( 'word', $block, 'markup', $text );
		},
		$markup
	);
	$markup = preg_replace_callback(
		'/(\shref=")([^"]*)"/',
		function ( $m ) use ( $block ) {
			return $m[1] . 'https://probe.example/' . vx_mark( 'link', $block, 'href', $m[2] ) . '"';
		},
		$markup
	);
	return preg_replace_callback(
		'/(\ssrc=")([^"]*)"/',
		function ( $m ) use ( $block ) {
			return $m[1] . $GLOBALS['vx']['picture'] . vx_mark( 'picture', $block, 'src', $m[2] ) . '.webp"';
		},
		$markup
	);
}

function vx_walk( array $blocks ) {
	foreach ( $blocks as $i => $block ) {
		if ( empty( $block['blockName'] ) ) {
			continue;
		}
		if ( 0 === strpos( $block['blockName'], 'osous/' ) ) {
			$type  = WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] );
			$attrs = $block['attrs'];
			foreach ( $type ? $type->attributes : array() as $key => $schema ) {
				if ( ! array_key_exists( $key, $attrs ) ) {
					$attrs[ $key ] = array_key_exists( 'default', $schema ) ? $schema['default'] : null;
				}
			}
			foreach ( $attrs as $key => $value ) {
				$changed = vx_attr( $block['blockName'], $key, $value );
				if ( $changed !== $value || array_key_exists( $key, $block['attrs'] ) ) {
					$block['attrs'][ $key ] = $changed;
				}
			}
		} else {
			foreach ( $block['innerContent'] as $j => $chunk ) {
				if ( is_string( $chunk ) ) {
					$block['innerContent'][ $j ] = vx_markup( $block['blockName'], $chunk );
				}
			}
		}
		$block['innerBlocks'] = vx_walk( $block['innerBlocks'] );
		$blocks[ $i ]         = $block;
	}
	return $blocks;
}

/*
 * What a part changed is written down with the install it was changed on: the moment the
 * administrator was made, which `env.sh rebuild` makes anew. A record left by a run that
 * died before a rebuild names page ids that are other pages now, or none, and is dropped
 * rather than put back.
 */
function vx_site() {
	$admin = get_userdata( 1 );
	return $admin ? $admin->user_registered : '';
}

function vx_keep( $file, $data ) {
	wp_mkdir_p( dirname( $file ) );
	file_put_contents( $file, wp_json_encode( array( 'site' => vx_site(), 'data' => $data ) ) );
}

function vx_kept( $file ) {
	if ( ! file_exists( $file ) ) {
		return null;
	}
	$kept = json_decode( file_get_contents( $file ), true );
	if ( ! is_array( $kept ) || ! isset( $kept['site'] ) || vx_site() !== $kept['site'] ) {
		@unlink( $file );
		$GLOBALS['vx']['dropped'] = basename( $file );
		return null;
	}
	return $kept['data'];
}

$seo_keys = array(
	'rank_math_title', 'rank_math_description', 'rank_math_robots', 'rank_math_canonical_url',
	'rank_math_facebook_title', 'rank_math_facebook_description', 'rank_math_facebook_image', 'rank_math_facebook_image_id',
	'rank_math_twitter_use_facebook', 'rank_math_twitter_title', 'rank_math_twitter_description',
	'rank_math_twitter_image', 'rank_math_twitter_image_id', 'rank_math_twitter_card_type',
);

switch ( $what ) {
	// ---------------------------------------------------------------- blocks
	case 'blocks':
		$kept = array();
		foreach ( $pages as $page ) {
			if ( $page->ID === $blog ) {
				continue;
			}
			$kept[ $page->ID ] = $page->post_content;
		}
		vx_keep( "$store/blocks.json", $kept );
		foreach ( $pages as $page ) {
			if ( ! isset( $kept[ $page->ID ] ) ) {
				continue;
			}
			$GLOBALS['vx']['markers'] = array();
			$GLOBALS['vx']['chosen']  = array();
			$saved                    = wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_content' => serialize_blocks( vx_walk( parse_blocks( $page->post_content ) ) ) ) ), true );
			$out[ $page->post_name ]  = array(
				'markers' => $GLOBALS['vx']['markers'],
				'chosen'  => $GLOBALS['vx']['chosen'],
				'error'   => is_wp_error( $saved ) ? $saved->get_error_message() : '',
			);
		}
		$form = get_posts( array( 'post_type' => 'wpcf7_contact_form', 'numberposts' => 1 ) );
		$out['__form'] = $form ? (string) get_post_meta( $form[0]->ID, '_form', true ) : '';
		break;

	case 'blocks-back':
		$kept = vx_kept( "$store/blocks.json" );
		foreach ( (array) $kept as $id => $content ) {
			wp_update_post( wp_slash( array( 'ID' => (int) $id, 'post_content' => $content ) ) );
			$out[ get_post_field( 'post_name', (int) $id ) ] = get_post_field( 'post_content', (int) $id, 'raw' ) === $content;
		}
		@unlink( "$store/blocks.json" );
		break;

	// ---------------------------------------------------------------- seo
	case 'seo':
	case 'seo-inherit':
		$kept = vx_kept( "$store/seo.json" );
		if ( null === $kept ) {
			$kept = array( 'meta' => array() );
			foreach ( $pages as $page ) {
				foreach ( $seo_keys as $key ) {
					if ( metadata_exists( 'post', $page->ID, $key ) ) {
						$kept['meta'][ $page->ID ][ $key ] = get_post_meta( $page->ID, $key, true );
					}
				}
			}
			/*
			 * Rank Math takes a share picture from the Media Library, by its id, the way its
			 * own picker stores it: two pictures are uploaded, and deleted again at the end.
			 */
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			$kept['pictures'] = array();
			foreach ( array( 1, 2 ) as $n ) {
				$tmp = wp_tempnam();
				copy( "$store/osous-test-picture-$n.png", $tmp );
				$kept['pictures'][ $n ] = media_handle_sideload( array( 'name' => "osous-share-picture-$n.png", 'tmp_name' => $tmp ), 0 );
			}
			vx_keep( "$store/seo.json", $kept );
		}
		$share = array();
		foreach ( $kept['pictures'] as $n => $picture_id ) {
			$share[ $n ] = array( (int) $picture_id, wp_get_attachment_url( (int) $picture_id ) );
		}
		foreach ( $pages as $page ) {
			$id   = $page->ID;
			$mark = function ( $part ) use ( $id ) {
				return sprintf( 'Zs%05d%sx', $id, $part );
			};
			foreach ( $seo_keys as $key ) {
				delete_post_meta( $id, $key );
			}
			update_post_meta( $id, 'rank_math_title', $mark( 't' ) );
			update_post_meta( $id, 'rank_math_description', $mark( 'd' ) );
			if ( 'seo' === $what ) {
				update_post_meta( $id, 'rank_math_facebook_title', $mark( 'ft' ) );
				update_post_meta( $id, 'rank_math_facebook_description', $mark( 'fd' ) );
				update_post_meta( $id, 'rank_math_facebook_image_id', $share[1][0] );
				update_post_meta( $id, 'rank_math_facebook_image', $share[1][1] );
				update_post_meta( $id, 'rank_math_twitter_use_facebook', 'off' );
				update_post_meta( $id, 'rank_math_twitter_title', $mark( 'tt' ) );
				update_post_meta( $id, 'rank_math_twitter_description', $mark( 'td' ) );
				update_post_meta( $id, 'rank_math_twitter_image_id', $share[2][0] );
				update_post_meta( $id, 'rank_math_twitter_image', $share[2][1] );
				update_post_meta( $id, 'rank_math_twitter_card_type', 'summary' );
				update_post_meta( $id, 'rank_math_canonical_url', 'https://probe.example/' . $mark( 'c' ) );
			} else {
				// Robots apart: Rank Math leaves the canonical out of a page that is not indexed.
				update_post_meta( $id, 'rank_math_robots', array( 'noindex', 'nofollow' ) );
			}
			$out[ $page->post_name ] = $id;
		}
		break;

	case 'seo-back':
		$kept = vx_kept( "$store/seo.json" );
		foreach ( $pages as $page ) {
			foreach ( $seo_keys as $key ) {
				delete_post_meta( $page->ID, $key );
			}
			foreach ( isset( $kept['meta'][ $page->ID ] ) ? $kept['meta'][ $page->ID ] : array() as $key => $value ) {
				update_post_meta( $page->ID, $key, $value );
			}
			$out[ $page->post_name ] = true;
		}
		foreach ( isset( $kept['pictures'] ) ? $kept['pictures'] : array() as $picture_id ) {
			wp_delete_attachment( (int) $picture_id, true );
		}
		@unlink( "$store/seo.json" );
		break;

	// ---------------------------------------------------------------- templates
	case 'templates':
		$kinds = array_values( wp_list_pluck( osous_page_kinds(), 'template' ) );
		$kept  = array();
		foreach ( $pages as $page ) {
			$kept[ $page->ID ] = get_page_template_slug( $page->ID );
		}
		vx_keep( "$store/templates.json", $kept );
		foreach ( $pages as $page ) {
			$was   = $kept[ $page->ID ];
			$other = $kinds[ ( (int) array_search( $was, $kinds, true ) + 1 ) % count( $kinds ) ];
			$saved = wp_update_post( array( 'ID' => $page->ID, 'page_template' => $other ), true );
			$out[ $page->post_name ] = array(
				'was'   => $was,
				'asked' => $other,
				'now'   => get_page_template_slug( $page->ID ),
				'error' => is_wp_error( $saved ) ? $saved->get_error_message() : '',
			);
		}
		$refused         = wp_update_post( array( 'ID' => $pages[0]->ID, 'page_template' => 'templates/page-nothing.php' ), true );
		$out['__refused'] = array(
			'error' => is_wp_error( $refused ) ? $refused->get_error_code() : '',
			'now'   => get_page_template_slug( $pages[0]->ID ),
		);
		break;

	case 'templates-back':
		$kept = vx_kept( "$store/templates.json" );
		foreach ( (array) $kept as $id => $template ) {
			wp_update_post( array( 'ID' => (int) $id, 'page_template' => '' === $template ? 'default' : $template ) );
			$out[ get_post_field( 'post_name', (int) $id ) ] = get_page_template_slug( (int) $id ) === $template;
		}
		@unlink( "$store/templates.json" );
		break;

	// ---------------------------------------------------------------- slugs
	case 'slugs':
		$kept = array();
		foreach ( $pages as $page ) {
			$kept[ $page->ID ] = array(
				'name' => $page->post_name,
				'old'  => get_post_meta( $page->ID, '_wp_old_slug', false ),
			);
		}
		vx_keep( "$store/slugs.json", $kept );
		foreach ( $pages as $page ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_name' => $page->post_name . '-moved' ) );
			$out[ $page->post_name ] = array(
				'now'  => get_post_field( 'post_name', $page->ID ),
				'url'  => get_permalink( $page->ID ),
				'old'  => get_post_meta( $page->ID, '_wp_old_slug', false ),
			);
		}
		break;

	case 'slugs-back':
		$kept = vx_kept( "$store/slugs.json" );
		foreach ( (array) $kept as $id => $was ) {
			wp_update_post( array( 'ID' => (int) $id, 'post_name' => $was['name'] ) );
			delete_post_meta( (int) $id, '_wp_old_slug' );
			foreach ( $was['old'] as $old ) {
				add_post_meta( (int) $id, '_wp_old_slug', $old );
			}
			$out[ $was['name'] ] = get_post_field( 'post_name', (int) $id ) === $was['name']
				&& get_post_meta( (int) $id, '_wp_old_slug', false ) == $was['old'];
		}
		@unlink( "$store/slugs.json" );
		break;
}

if ( ! empty( $GLOBALS['vx']['dropped'] ) ) {
	$out['__dropped'] = $GLOBALS['vx']['dropped'];
}
echo 'OSOUS-JSON' . wp_json_encode( $out );
"""


def wp_hands(what):
    """Run one step of HANDS inside WordPress and return what it reports."""
    with io.open(PROBE, "w", encoding="utf-8", newline="\n") as target:
        target.write(HANDS)
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "-T", "cli", "wp",
               "eval-file", "/migration/verify_editable.php", what, "--user=admin"]
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    done = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env, encoding="utf-8")
    start = done.stdout.find("OSOUS-JSON")
    if start < 0:
        raise SystemExit("WP-CLI said nothing for %s -- is the stack up?\n%s\n%s"
                         % (what, done.stdout[-800:], done.stderr[-800:]))
    said = json.loads(done.stdout[start + len("OSOUS-JSON"):]) or {}  # PHP's empty array is []
    if isinstance(said, dict) and "__dropped" in said:
        print("  %s was written on another install of the site (rebuilt since): dropped, not put back"
              % said.pop("__dropped"))
    return said


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs):
        return None


def get(path, follow=True):
    """(status, body, Location) for an address on the site."""
    opener = urllib.request.build_opener() if follow else urllib.request.build_opener(NoRedirect)
    try:
        with opener.open(BASE + path, timeout=60) as response:
            return response.status, response.read().decode("utf-8"), ""
    except urllib.error.HTTPError as error:
        return error.code, error.read().decode("utf-8", "replace"), error.headers.get("Location", "")


def address(slug):
    return "/" if slug == "index" else "/%s/" % slug


def page_slugs():
    return sorted(os.path.basename(p)[:-5] for p in glob.glob(os.path.join(ROOT, "*.html")))


def main_of(document):
    tree = lhtml.fromstring(document)
    found = tree.xpath("//main")
    return found[0] if found else None


def main_markup(document):
    """A page's <main> as markup, the same from one fetch to the next.

    The Turnstile widget in the contact form is given a new random id on every request,
    so two fetches of an unchanged contact page differ there and nowhere else.
    """
    main = main_of(document)
    markup = lhtml.tostring(main, encoding="unicode") if main is not None else ""
    return re.sub(r"(cf-turnstile(?:-br)?-cf7-)\d+", r"\1N", markup)


def body_class(document):
    match = re.search(r"<body[^>]*\bclass=\"([^\"]*)\"", document)
    return match.group(1).split() if match else []


# ---------------------------------------------------------------- what a page shows

def words(root):
    """Every run of visible text in an element: (text, inside the contact form?)."""
    out = []

    def walk(element, in_form, hidden):
        tag = element.tag if isinstance(element.tag, str) else None
        classes = (element.get("class") or "").split() if tag else []
        hidden = hidden or tag in ("script", "style", "noscript", "template") or (tag and element.get("aria-hidden") == "true")
        in_form = in_form or "wpcf7" in classes
        if tag and not hidden and element.text and re.search(r"\w", element.text):
            out.append((element.text.strip(), in_form))
        for child in element:
            walk(child, in_form, hidden)
            if child.tail and not hidden and re.search(r"\w", child.tail):
                out.append((child.tail.strip(), in_form))

    walk(root, False, False)
    return out


def pictures(root):
    """Every picture an element prints: (where, address, part of a control?).

    A picture inside a <button> is the control's own icon -- the carousel's arrows -- and
    is reported apart from the content's pictures.
    """
    found = []
    for element in root.iter():
        if not isinstance(element.tag, str):
            continue
        control = any(a.tag == "button" for a in element.iterancestors())
        if element.tag == "img" and element.get("src"):
            found.append(("img", element.get("src"), control))
        if element.tag in ("img", "source") and element.get("srcset"):
            for candidate in element.get("srcset").split(","):
                if candidate.strip():
                    found.append(("srcset", candidate.split()[0], control))
        if element.tag == "video" and element.get("poster"):
            found.append(("poster", element.get("poster"), control))
        style = element.get("style") or ""
        for url in re.findall(r"url\(([^)]*)\)", style):
            found.append(("style", url.strip("'\" "), control))
    return found


def links(root):
    return [(a.get("href") or "") for a in root.iter("a")]


def stylesheet_backgrounds():
    """The compound class selectors whose rules draw a picture: [(classes, picture)]."""
    rules = []
    for path in glob.glob(os.path.join(ROOT, "assets", "css", "*.css")):
        if os.path.basename(path) == "editor.css":
            continue
        css = re.sub(r"/\*.*?\*/", "", io.open(path, encoding="utf-8").read(), flags=re.S)
        for selectors, body in re.findall(r"([^{}@]+)\{([^{}]*)\}", css):
            picture = re.search(r"background(?:-image)?\s*:[^;]*url\(([^)]*)\)", body)
            if not picture:
                continue
            for selector in selectors.split(","):
                last = re.split(r"[\s>+~]+", selector.strip())[-1]
                last = re.split(r"::?[a-z-]+(?:\([^)]*\))?", last)[0]
                classes = frozenset(re.findall(r"\.([A-Za-z0-9_-]+)", last))
                if classes:
                    rules.append((classes, os.path.basename(picture.group(1).strip("'\" "))))
    return rules


def drawn_backgrounds(root, rules):
    """The elements a stylesheet draws a picture behind: [(their classes, picture)]."""
    found = []
    for element in root.iter():
        if not isinstance(element.tag, str):
            continue
        classes = set((element.get("class") or "").split())
        for needed, picture in rules:
            if needed <= classes:
                found.append((" ".join(sorted(needed)), picture))
    return found


def colours(root):
    counted = collections.Counter()
    for element in root.iter():
        if isinstance(element.tag, str):
            for name in (element.get("class") or "").split():
                if COLOUR.match(name):
                    counted[name] += 1
    return counted


# ---------------------------------------------------------------- the parts

class Run(object):
    def __init__(self, slugs):
        self.slugs = slugs
        self.problems = collections.defaultdict(list)
        self.summary = collections.defaultdict(dict)

    def fault(self, slug, what):
        self.problems[slug].append(what)


def part_blocks(run, rules):
    print("blocks: every word, picture and link of every page, changed through WordPress")
    before = {}
    for slug in run.slugs:
        if slug == "blog":
            continue
        status, document, _ = get(address(slug))
        before[slug] = main_of(document)

    made = wp_hands("blocks")
    form = made.pop("__form", "")
    try:
        for slug in run.slugs:
            if slug == "blog":
                continue
            page = made.get(slug)
            if not page:
                run.fault(slug, "blocks: not a page WordPress has")
                continue
            if page["error"]:
                run.fault(slug, "blocks: WordPress refused the save: " + page["error"])
                continue
            status, document, _ = get(address(slug))
            main = main_of(document)
            if status != 200 or main is None:
                run.fault(slug, "blocks: the changed page did not load (%s)" % status)
                continue
            printed = lhtml.tostring(main, encoding="unicode")
            markers = page["markers"]
            unprinted = [m for m in markers if m not in printed]
            kinds = collections.Counter(markers[m]["kind"] for m in markers)

            fixed_words = [t for t, in_form in words(main) if not MARKER.search(t) and not in_form]
            form_words = [t for t, in_form in words(main) if in_form]
            not_in_form = [t for t in form_words if t not in form]
            fixed_pictures = [u for where, u, control in pictures(main) if "/probe/" not in u and not control]
            control_icons = sorted(set(os.path.basename(u) for where, u, control in pictures(main) if control))
            fixed_links = [h for h in links(main) if h and not MARKER.search(h)]

            was_drawn = drawn_backgrounds(before[slug], rules)
            still_drawn = drawn_backgrounds(main, rules)
            chosen_backgrounds = [c for c in page["chosen"] if c["attr"] in ("background", "backdrop")]
            chosen_colours = [c for c in page["chosen"] if c["attr"] in ("surface", "tone")]

            for marker in unprinted:
                about = markers[marker]
                run.fault(slug, "blocks: %s.%s is saved and not printed (was %r)" % (about["block"], about["attr"], about["was"]))
            for text in fixed_words:
                run.fault(slug, "blocks: words no field changes: %r" % text[:60])
            for text in not_in_form:
                run.fault(slug, "blocks: words in the contact form that its settings do not hold: %r" % text[:60])
            for url in fixed_pictures:
                run.fault(slug, "blocks: a picture no field changes: %s" % url)
            for href in fixed_links:
                run.fault(slug, "blocks: a link no field changes: %s" % href)

            run.summary[slug]["blocks"] = {
                "words": kinds["word"],
                "pictures": kinds["picture"],
                "links": kinds["link"],
                "alt": kinds["alt"],
                "unprinted": len(unprinted),
                "fixed_words": fixed_words,
                "fixed_pictures": fixed_pictures,
                "fixed_links": fixed_links,
                "control_icons": control_icons,
                "form_words": len(form_words),
                "backgrounds_chosen": [c["value"] for c in chosen_backgrounds],
                "backgrounds_fixed": sorted(set(p for _, p in still_drawn)),
                "backgrounds_before": sorted(set(p for _, p in was_drawn)),
                "colours_chosen": [c["attr"] + "=" + c["value"] for c in chosen_colours],
                "colours_fixed": sorted(colours(main)),
            }
            print("  %-34s %3d words, %2d pictures, %2d links%s"
                  % (slug, kinds["word"], kinds["picture"], kinds["link"],
                     "" if not run.problems[slug] else "   !! %d" % len(run.problems[slug])))
    finally:
        back = wp_hands("blocks-back")
        wrong = [slug for slug, same in back.items() if not same]
        print("  put back: %d pages%s" % (len(back), "" if not wrong else ", NOT the same: " + ", ".join(wrong)))
        for slug in wrong:
            run.fault(slug, "blocks: not put back exactly")


def head_of(document):
    return document.split("</head>", 1)[0]


def meta(head, attribute, name):
    found = re.findall(r'<meta\s+%s="%s"\s+content="([^"]*)"' % (attribute, re.escape(name)), head)
    return found


def part_seo(run):
    print("\nseo: every search and social field of every page, changed through WordPress")
    made = wp_hands("seo")
    try:
        for slug in run.slugs:
            page_id = made.get(slug)
            if page_id is None:
                run.fault(slug, "seo: not a page WordPress has")
                continue
            mark = lambda part: "Zs%05d%sx" % (page_id, part)  # noqa: E731
            status, document, _ = get(address(slug))
            head = head_of(document)
            title = re.findall(r"<title>([^<]*)</title>", head)
            if slug == "blog":
                # The posts page is not singular, and there Rank Math reads "use the Facebook
                # values for Twitter" from no page at all, so its default holds: Twitter
                # says what the Facebook fields say, and the Twitter tab's own are not read.
                want = [
                    ("the <title>", title == [mark("t")]),
                    ("the description", meta(head, "name", "description") == [mark("d")]),
                    ("og:title", meta(head, "property", "og:title") == [mark("ft")]),
                    ("og:description", meta(head, "property", "og:description") == [mark("fd")]),
                    ("og:image", any("osous-share-picture-1" in v for v in meta(head, "property", "og:image"))),
                    ("twitter:title, from the Facebook field", meta(head, "name", "twitter:title") == [mark("ft")]),
                    ("twitter:description, from the Facebook field", meta(head, "name", "twitter:description") == [mark("fd")]),
                    ("twitter:image, from the Facebook field", any("osous-share-picture-1" in v for v in meta(head, "name", "twitter:image"))),
                    ("canonical", ('rel="canonical" href="https://probe.example/%s"' % mark("c")) in head),
                ]
                run.summary[slug]["seo_note"] = "Twitter follows the Facebook fields on the posts page (Rank Math)"
            else:
                want = None
            want = want or [
                ("the <title>", title == [mark("t")]),
                ("the description", meta(head, "name", "description") == [mark("d")]),
                ("og:title", meta(head, "property", "og:title") == [mark("ft")]),
                ("og:description", meta(head, "property", "og:description") == [mark("fd")]),
                ("og:image", any("osous-share-picture-1" in v for v in meta(head, "property", "og:image"))),
                ("twitter:title", meta(head, "name", "twitter:title") == [mark("tt")]),
                ("twitter:description", meta(head, "name", "twitter:description") == [mark("td")]),
                ("twitter:image", any("osous-share-picture-2" in v for v in meta(head, "name", "twitter:image"))),
                ("twitter:card", meta(head, "name", "twitter:card") == ["summary"]),
                ("canonical", ('rel="canonical" href="https://probe.example/%s"' % mark("c")) in head),
            ]
            missed = [name for name, ok in want if not ok]
            for name in missed:
                run.fault(slug, "seo: %s does not follow its field" % name)
            run.summary[slug]["seo"] = {"fields": len(want), "follow": len(want) - len(missed)}

        made = wp_hands("seo-inherit")
        for slug in run.slugs:
            page_id = made.get(slug)
            if page_id is None:
                continue
            mark = lambda part: "Zs%05d%sx" % (page_id, part)  # noqa: E731
            status, document, _ = get(address(slug))
            head = head_of(document)
            follow = [
                ("og:title", meta(head, "property", "og:title") == [mark("t")]),
                ("og:description", meta(head, "property", "og:description") == [mark("d")]),
                ("twitter:title", meta(head, "name", "twitter:title") == [mark("t")]),
                ("twitter:description", meta(head, "name", "twitter:description") == [mark("d")]),
            ]
            for name, ok in follow:
                if not ok:
                    run.fault(slug, "seo: %s does not follow the page's title and description" % name)
            robots = any("noindex" in v and "nofollow" in v for v in meta(head, "name", "robots"))
            if not robots:
                run.fault(slug, "seo: robots does not follow its field")
            run.summary[slug]["seo"]["fields"] += 1
            run.summary[slug]["seo"]["follow"] += int(robots)
            run.summary[slug]["seo"]["inherit"] = sum(ok for _, ok in follow)
            print("  %-34s %2d of %d fields reach the head; the social tags follow the title: %s"
                  % (slug, run.summary[slug]["seo"]["follow"], run.summary[slug]["seo"]["fields"],
                     "yes" if all(ok for _, ok in follow) else "NO"))
    finally:
        back = wp_hands("seo-back")
        print("  put back: %d pages" % len(back))


def part_templates(run):
    print("\ntemplates: every page moved to another kind of page")
    # The blog is WordPress's posts page: what it prints is the posts, on no template of
    # a page's, so it has no kind to move.
    slugs = [slug for slug in run.slugs if slug != "blog"]
    before = {}
    for slug in slugs:
        status, document, _ = get(address(slug))
        before[slug] = main_markup(document)
    made = wp_hands("templates")
    refused = made.pop("__refused")
    try:
        for slug in slugs:
            moved = made.get(slug)
            if not moved:
                run.fault(slug, "templates: not a page WordPress has")
                continue
            status, document, _ = get(address(slug))
            now = main_markup(document)
            asked = moved["asked"]
            stem = os.path.basename(asked)[:-4]
            classes = body_class(document)
            ok_saved = not moved["error"] and moved["now"] == asked
            ok_body = ("page-template-%s" % stem) in classes
            ok_same = now == before[slug]
            if not ok_saved:
                run.fault(slug, "templates: WordPress did not take %s (%s)" % (asked, moved["error"] or moved["now"]))
            if not ok_body:
                run.fault(slug, "templates: the page's body does not say %s" % stem)
            if not ok_same:
                run.fault(slug, "templates: the page changed when its template did")
            run.summary[slug]["template"] = {"was": moved["was"], "moved_to": asked, "ok": ok_saved and ok_body and ok_same}
            print("  %-34s %-28s -> %-28s %s" % (slug, moved["was"] or "(default)", asked,
                                                 "ok" if ok_saved and ok_body and ok_same else "!!"))
        if not refused["error"] or refused["now"] == "templates/page-nothing.php":
            run.fault("(site)", "templates: a template the theme does not have was accepted")
        print("  a template the theme does not have: %s" % (("refused (%s)" % refused["error"]) if refused["error"] else "ACCEPTED"))
    finally:
        back = wp_hands("templates-back")
        wrong = [slug for slug, same in back.items() if not same]
        print("  put back: %d pages%s" % (len(back), "" if not wrong else ", NOT the same: " + ", ".join(wrong)))


def part_slugs(run):
    print("\nslugs: every page renamed at once")
    before = {}
    for slug in run.slugs:
        status, document, _ = get(address(slug))
        before[slug] = main_markup(document)
    made = wp_hands("slugs")
    old_addresses = set("%s/%s/" % (BASE, slug) for slug in made if slug != "index")
    try:
        stale_anywhere = collections.Counter()
        for slug in run.slugs:
            moved = made.get(slug)
            if not moved:
                run.fault(slug, "slugs: not a page WordPress has")
                continue
            new = moved["now"]
            here = "/" if slug == "index" else "/%s/" % new
            status, document, _ = get(here)
            now = main_markup(document)
            ok_new = status == 200
            ok_same = now.replace("-moved/", "/") == before[slug]
            hrefs = re.findall(r'<a\b[^>]*\bhref="([^"]*)"', document)
            stale = [h for h in hrefs if h in old_addresses]
            for h in stale:
                stale_anywhere[h] += 1
            if slug == "index":
                ok_old = True
            else:
                status_old, _, location = get("/%s/" % slug, follow=False)
                ok_old = status_old == 301 and location == "%s/%s/" % (BASE, new)
            if not ok_new:
                run.fault(slug, "slugs: the new address answers %s" % status)
            if not ok_same:
                run.fault(slug, "slugs: the page is not the same at its new address")
            if not ok_old:
                run.fault(slug, "slugs: the old address is not one permanent redirect to the new one")
            if stale:
                run.fault(slug, "slugs: %d links on this page still point to old addresses" % len(stale))
            run.summary[slug]["slug"] = {"new": new, "ok": ok_new and ok_same and ok_old and not stale}
            print("  %-34s -> %-40s %s" % (slug, here, "ok" if run.summary[slug]["slug"]["ok"] else "!!"))
        if stale_anywhere:
            print("  links to old addresses:", dict(stale_anywhere.most_common(5)))
    finally:
        back = wp_hands("slugs-back")
        wrong = [slug for slug, same in back.items() if not same]
        print("  put back: %d pages%s" % (len(back), "" if not wrong else ", NOT the same: " + ", ".join(wrong)))


def main(argv):
    parts = [a.split("=", 1)[1] for a in argv if a.startswith("--part=")] or list(PARTS)
    slugs = [a for a in argv if not a.startswith("--")] or page_slugs()
    os.makedirs(STORE, exist_ok=True)
    os.makedirs(os.path.dirname(REPORT), exist_ok=True)

    # A run that died half way left its record of what it changed: put that back first.
    for left in sorted(glob.glob(os.path.join(STORE, "*.json"))):
        what = os.path.basename(left)[:-5]
        print("a run before this one left %s changed -- putting it back" % what)
        wp_hands(what + "-back")

    run = Run(slugs)
    if "blocks" in parts:
        part_blocks(run, stylesheet_backgrounds())
    if "seo" in parts:
        part_seo(run)
    if "templates" in parts:
        part_templates(run)
    if "slugs" in parts:
        part_slugs(run)

    with io.open(REPORT, "w", encoding="utf-8") as target:
        json.dump({"summary": run.summary, "problems": run.problems}, target, indent=1, ensure_ascii=False)

    problems = sum(len(v) for v in run.problems.values())
    if problems:
        print("\nproblems:")
        for slug in sorted(run.problems):
            for what in run.problems[slug][:12]:
                print("  %-34s %s" % (slug, what))
            if len(run.problems[slug]) > 12:
                print("  %-34s ... and %d more" % (slug, len(run.problems[slug]) - 12))
    print("\n%d pages, %d problems" % (len(slugs), problems))
    return 1 if problems else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
