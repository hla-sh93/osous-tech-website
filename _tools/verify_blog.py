# -*- coding: utf-8 -*-
"""
Check the blog loop against the posts it is supposed to be showing.

`_tools/verify_wp.py` compares the blog page by shape only: it is a query result now, and
the static file is a snapshot of what production's query returned on the day it was
cloned. The shape check proves the grid and the card are right. This proves the *loop* is
right -- that the cards carry the posts the database holds, in the order WordPress puts
them, with their real dates and permalinks.

    python _tools/verify_blog.py
    python _tools/verify_blog.py --pager      # also exercises the pager

The pager only exists when there is more than one page. With `--pager` the script drops
`posts_per_page` to four, checks pages one and two, and puts it back.
"""
import html
import json
import os
import re
import subprocess
import sys
import urllib.request

from lxml import html as LH

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.environ.get("OSOUS_WP", "http://localhost:8080")


def wp(*args):
    """Run a WP-CLI command in the container and return its output."""
    command = ["docker", "compose", "--profile", "tools", "run", "--rm", "cli", "wp"] + list(args)
    env = dict(os.environ, MSYS_NO_PATHCONV="1")
    done = subprocess.run(command, cwd=ROOT, capture_output=True, text=True, env=env)

    return done.stdout.strip()


def fetch(path):
    with urllib.request.urlopen(BASE + path, timeout=30) as response:
        return LH.fromstring(response.read().decode("utf-8"))


def cards_on(path):
    doc = fetch(path)
    found = []

    for card in doc.xpath('//main//a[contains(@class,"blog-card")]'):
        images = card.xpath('.//img[contains(@class,"blog-image-thumb")]')
        dates = card.xpath('.//div[contains(@class,"t-r-sm")]')
        titles = card.xpath(".//h2")
        found.append({
            "href": card.get("href") or "",
            "title": " ".join(titles[0].text_content().split()) if titles else "",
            "date": " ".join(dates[0].text_content().split()) if dates else "",
            "image": images[0].get("src") if images else "",
            "shape": [
                bool(card.xpath('.//div[contains(@class,"blog-image-thumb-container")]')),
                bool(images),
                bool(card.xpath('.//div[contains(@class,"spacer-1x")]')),
                bool(dates),
                bool(titles),
            ],
        })

    return doc, found


def expected_posts():
    """What the database says the blog should be showing, newest first.

    `get_the_title()` hands back `&#038;` where the rendered page has `&` -- WordPress
    encodes the ampersand on its way out of the database and the browser decodes it on the
    way in. Comparing the two without unescaping first compares two spellings of the same
    title.
    """
    raw = wp(
        "eval",
        'echo json_encode( array_map( function ( $p ) { return array('
        '"title" => get_the_title( $p ), "date" => get_the_date( "", $p ),'
        '"href" => get_permalink( $p ) ); },'
        'get_posts( array( "numberposts" => 12, "post_status" => "publish" ) ) ) );',
    )
    posts = json.loads(raw)
    for post in posts:
        post["title"] = html.unescape(post["title"])

    return posts


def check_index(problems):
    doc, cards = cards_on("/blog/")
    posts = expected_posts()

    if len(cards) != len(posts):
        problems.append("%d cards on the page, %d posts in the database" % (len(cards), len(posts)))

    for i, (card, post) in enumerate(zip(cards, posts)):
        if not all(card["shape"]):
            problems.append("card %d is missing part of its markup: %s" % (i + 1, card["shape"]))
        if card["title"] != post["title"]:
            problems.append("card %d title: page=%r database=%r" % (i + 1, card["title"], post["title"]))
        if card["date"] != post["date"]:
            problems.append("card %d date: page=%r database=%r" % (i + 1, card["date"], post["date"]))
        if card["href"] != post["href"]:
            problems.append("card %d link: page=%r database=%r" % (i + 1, card["href"], post["href"]))
        if not card["image"]:
            problems.append("card %d has no picture" % (i + 1))

    return len(cards)


def check_post(problems):
    doc, cards = cards_on("/blog/")
    if not cards:
        return None

    first = cards[0]["href"].replace(BASE, "")
    doc = fetch(first)

    if not doc.xpath('//main//section[contains(@class,"header")]//h1'):
        problems.append("the post at %s has no page header" % first)
    if not doc.xpath('//main//div[contains(@class,"content")]'):
        problems.append("the post at %s has no content wrapper" % first)

    return first


def check_pager(problems):
    """Drop to four posts a page, look at the pager, and put it back."""
    before = wp("option", "get", "posts_per_page")
    wp("option", "update", "posts_per_page", "4")
    try:
        doc = fetch("/blog/")
        wrapper = doc.xpath('//main//div[contains(@class,"pagination-wrapper")]')
        if not wrapper:
            problems.append("no pager with four posts a page")
            return
        current = wrapper[0].xpath('.//span[contains(@class,"current")]/div[@class="t-r-md"]')
        pages = wrapper[0].xpath('.//a[contains(@class,"btn")][contains(@class,"neutral")]')
        if not current:
            problems.append("the pager does not mark the current page")
        elif current[0].text_content().strip() != "1":
            problems.append("the pager says page %r on page one" % current[0].text_content().strip())
        if len(pages) < 2:
            problems.append("the pager offers %d links, expected at least 2" % len(pages))

        two = fetch("/blog/page/2/")
        if not two.xpath('//main//a[contains(@class,"blog-card")]'):
            problems.append("page two has no cards")
        marker = two.xpath('//main//div[contains(@class,"pagination-wrapper")]'
                           '//span[contains(@class,"current")]')
        if not marker or marker[0].text_content().strip() != "2":
            problems.append("page two does not mark itself as current")
    finally:
        wp("option", "update", "posts_per_page", before or "12")


def main(argv):
    problems = []

    cards = check_index(problems)
    post = check_post(problems)

    per_page = int(wp("option", "get", "posts_per_page") or 12)
    if "--pager" in argv:
        check_pager(problems)
        pager = "checked"
    elif cards and cards < per_page + 1:
        pager = "not shown — one page of posts. Run with --pager to exercise it."
    else:
        pager = "shown"

    print("index : %d cards, each matching its post" % cards)
    print("post  : %s" % (post or "none to open"))
    print("pager : %s" % pager)

    if problems:
        print()
        for problem in problems:
            print("  !!", problem)
    print("\n%d problems" % len(problems))

    return len(problems)


if __name__ == "__main__":
    sys.exit(1 if main(sys.argv[1:]) else 0)
