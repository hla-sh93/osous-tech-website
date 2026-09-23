<?php
/**
 * Osous theme bootstrap.
 *
 * Nothing is defined here: each concern lives in its own file under functions/. The
 * pages are under pages/, one file each; the header, the footer, the sections and the
 * cards under includes/; the design's stylesheets, scripts and pictures under assets/.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'OSOUS_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'OSOUS_DIR', get_template_directory() );

require_once OSOUS_DIR . '/functions/settings.php';
require_once OSOUS_DIR . '/functions/setup.php';
require_once OSOUS_DIR . '/functions/markup.php';
require_once OSOUS_DIR . '/functions/templates.php';
require_once OSOUS_DIR . '/functions/blog.php';
require_once OSOUS_DIR . '/functions/enqueues.php';
require_once OSOUS_DIR . '/functions/navbar.php';
require_once OSOUS_DIR . '/functions/contact.php';
require_once OSOUS_DIR . '/functions/cleanup.php';
