<?php
/**
 * Receive the theme screenshot from the browser and write it into the theme.
 *
 * WordPress shows `screenshot.png` as the theme's card in Appearance, and it wants
 * 1200x900. Nothing in this project can produce that: the browser pane returns an image
 * the size of the pane, and there is no headless browser in the stack. So the page
 * renders itself with html2canvas at exactly 1200x900 and posts the result here, which
 * makes the card a photograph of the real home page rather than something drawn to look
 * like one.
 *
 * Development only. It lives in `_tools/`, which is never part of the packaged theme,
 * and it is reachable solely because docker-compose mounts this repository at /static
 * for the measuring harnesses. Delete the mount and this file is unreachable.
 *
 *   POST a data URL as the request body to
 *   http://localhost:8080/static/_tools/save-shot.php
 */

const TARGET   = '/var/www/html/wp-content/themes/osous/screenshot.png';
const MAX_SIZE = 4 * 1024 * 1024;

header('Content-Type: text/plain');
header('Access-Control-Allow-Origin: *');

if ('POST' !== $_SERVER['REQUEST_METHOD']) {
	http_response_code(405);
	exit("POST a data URL as the body\n");
}

$body = file_get_contents('php://input');
if (strlen($body) > MAX_SIZE) {
	http_response_code(413);
	exit("too large\n");
}

if (!preg_match('#^data:image/(png|jpeg);base64,#', $body, $match)) {
	http_response_code(400);
	exit("expected a data:image/png or data:image/jpeg URL\n");
}

$binary = base64_decode(substr($body, strlen($match[0])), true);
if (false === $binary) {
	http_response_code(400);
	exit("the base64 did not decode\n");
}

// Always a PNG on disk: it is what WordPress looks for first and what the convention is.
$image = @imagecreatefromstring($binary);
if (!$image) {
	http_response_code(400);
	exit("not a readable image\n");
}

$ok = imagepng($image, TARGET, 9);
imagedestroy($image);

if (!$ok) {
	http_response_code(500);
	exit("could not write " . TARGET . "\n");
}

printf("wrote %s  %d x %d  %s bytes\n", TARGET, imagesx(imagecreatefrompng(TARGET)),
	imagesy(imagecreatefrompng(TARGET)), number_format(filesize(TARGET)));
