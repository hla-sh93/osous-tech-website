# -*- coding: utf-8 -*-
"""
Prove that every Site detail reaches the page.

    python _tools/verify_settings.py

Every field in functions/settings.php -- and the logo from Customize -> Site Identity -- is set to
a marker nobody would ever write, the home page is fetched, and each marker has to appear
where that field is printed: the phone in its tel: link and its text, the address with its
line breaks, the badge in its img, the button's page in both of the header's buttons. Then
everything is put back exactly as it was and the home page fetched again: it has to be the
same bytes as before the run, which is what "put back" means.

Why a check and not a look: a field that is declared, sanitised and saved but never
printed passes every other check here, because the defaults reproduce the reference. This
is the one place a forgotten `osous_setting()` call would show.

Needs the local stack (docker compose up -d). Base URL from OSOUS_WP, default
http://localhost:8080.
"""
import datetime
import html
import io
import json
import os
import shutil
import subprocess
import sys
import urllib.request

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.environ.get("OSOUS_WP", "http://localhost:8080").rstrip("/")
YEAR = str(datetime.datetime.now(datetime.timezone.utc).year)

# A picture that is not an SVG (WordPress refuses SVG uploads) to stand in as the logo.
LOGO_SOURCE = os.path.join(ROOT, "assets", "images", "9001.webp")
LOGO_STAGED = os.path.join(ROOT, "_migration", "settings-probe-logo.webp")

# key -> (marker, [what must then appear on the home page])
PROBES = {
    "button_label": ("Probe button", [">Probe button<"]),
    "email": ("probe-email@example.test", ['href="mailto:probe-email@example.test"', ">probe-email@example.test<"]),
    "whatsapp": ("+10000000001", ['href="https://wa.me/+10000000001"']),
    "uae_name": ("Probe Office One", [">Probe Office One<"]),
    "uae_address": ("Probe Street 1\nProbe City One", ["Probe Street 1<br>Probe City One<br>"]),
    "uae_phone": ("+1 000 000 0002", ['href="tel:+10000000002"', ">+1 000 000 0002<"]),
    "iraq_name": ("Probe Office Two", [">Probe Office Two<"]),
    "iraq_address": ("Probe Street 2\nProbe City Two", ["Probe Street 2<br>Probe City Two<br>"]),
    "iraq_phone_1": ("+1 000 000 0003", ['href="tel:+10000000003"', ">+1 000 000 0003<"]),
    "iraq_phone_2": ("+1 000 000 0004", ['href="tel:+10000000004"', ">+1 000 000 0004<"]),
    "map_embed": ("https://maps.example.test/probe-map", ['src="https://maps.example.test/probe-map"']),
    "facebook": ("https://social.example.test/probe-facebook", ['href="https://social.example.test/probe-facebook"']),
    "linkedin": ("https://social.example.test/probe-linkedin", ['href="https://social.example.test/probe-linkedin"']),
    "instagram": ("https://social.example.test/probe-instagram", ['href="https://social.example.test/probe-instagram"']),
    "discover_heading": ("Probe Discover", [">Probe Discover<"]),
    "follow_heading": ("Probe Follow", [">Probe Follow<"]),
    "send_heading": ("Probe Send", [">Probe Send<"]),
    "badge_1": ("https://img.example.test/probe-badge-1.png", ['src="https://img.example.test/probe-badge-1.png"']),
    "badge_1_alt": ("Probe badge one", ['alt="Probe badge one"']),
    "badge_2": ("https://img.example.test/probe-badge-2.png", ['src="https://img.example.test/probe-badge-2.png"']),
    "badge_2_alt": ("Probe badge two", ['alt="Probe badge two"']),
    "copyright": ("\u00a9 {year} Probe Company. Probe rights.", ["\u00a9 %s Probe Company. Probe rights." % YEAR]),
    "credit_name": ("Probe Developer", [">Probe Developer<"]),
    "credit_url": ("https://dev.example.test/probe-credit", ['href="https://dev.example.test/probe-credit"']),
}


def wp(*args):
    """WP-CLI in the stack's cli container; stdout, stripped."""
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "-T", "cli", "wp"] + list(args)
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    out = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, encoding="utf-8", env=env)
    if out.returncode != 0:
        sys.exit("wp %s failed:\n%s" % (" ".join(args[:3]), (out.stderr or out.stdout).strip()))
    return out.stdout.strip()


def fetch(path):
    with urllib.request.urlopen(BASE + path, timeout=30) as response:
        return response.read().decode("utf-8")


def php_string(value):
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'").replace("\n", "' . \"\\n\" . '") + "'"


def main():
    before = fetch("/")
    original = wp("option", "get", "theme_mods_osous", "--format=json")
    about_id = wp("post", "list", "--post_type=page", "--name=about", "--field=ID", "--format=ids").split()[0]

    shutil.copyfile(LOGO_SOURCE, LOGO_STAGED)
    logo_id = wp("media", "import", "/migration/settings-probe-logo.webp", "--title=Settings probe logo", "--porcelain").split()[-1]

    problems = []
    try:
        code = "".join("set_theme_mod( 'osous_%s', %s );" % (key, php_string(marker)) for key, (marker, _) in PROBES.items())
        code += "set_theme_mod( 'osous_button_page', %s );" % about_id
        code += "set_theme_mod( 'custom_logo', %s );" % logo_id
        wp("eval", code)

        page = fetch("/")
        checks = dict(PROBES)
        checks["button_page"] = (about_id, ['href="%s/about/" class="btn outline"' % BASE, 'href="%s/about/" class="btn"' % BASE])
        checks["custom_logo"] = (logo_id, ['/settings-probe-logo.webp" width="', 'height="50" alt="', 'class="logo"'])
        for key, (marker, expected) in checks.items():
            missing = [e for e in expected if e not in page]
            if missing:
                problems.append("%s: set to %r, but the page has no %s" % (key, marker, ", ".join(repr(m) for m in missing)))
            else:
                print("ok  %-18s %s" % (key, expected[0]))
        if ">Probe button<" in page and page.count(">Probe button<") < 2:
            problems.append("button_label: printed once, the header has two buttons")
    finally:
        if original:
            wp("option", "update", "theme_mods_osous", original, "--format=json")
        else:
            wp("option", "delete", "theme_mods_osous")
        wp("post", "delete", logo_id, "--force")
        if os.path.exists(LOGO_STAGED):
            os.remove(LOGO_STAGED)

    after = fetch("/")
    if after != before:
        first = next((i for i, (a, b) in enumerate(zip(before, after)) if a != b), min(len(before), len(after)))
        problems.append("the home page is not the same after the run; they part at byte %d: %r" % (first, after[first:first + 80]))
    else:
        print("ok  %-18s the home page is byte for byte what it was" % "restored")

    print()
    for problem in problems:
        print("!! " + problem)
    print("%d settings checked, %d problems" % (len(checks), len(problems)))
    sys.exit(1 if problems else 0)


if __name__ == "__main__":
    main()
