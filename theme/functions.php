<?php
/**
 * Osous theme bootstrap.
 *
 * Nothing is defined here directly. The theme's PHP is one concern per file under
 * functions/; the markup of every block, the loops and the WhatsApp badge are files
 * under includes/; every block is a folder under blocks/ (what it is, and how it is
 * edited); the kinds of page are the templates under templates/; the sections the
 * library offers and the starters of each kind are under patterns/; every page's original
 * is a file under pages/; the design's stylesheets, scripts and pictures under assets/.
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
require_once OSOUS_DIR . '/functions/design.php';
require_once OSOUS_DIR . '/functions/markup.php';
require_once OSOUS_DIR . '/functions/blocks.php';
require_once OSOUS_DIR . '/functions/patterns.php';
require_once OSOUS_DIR . '/functions/templates.php';
require_once OSOUS_DIR . '/functions/blog.php';
require_once OSOUS_DIR . '/functions/enqueue.php';
require_once OSOUS_DIR . '/functions/nav.php';
require_once OSOUS_DIR . '/functions/contact.php';
require_once OSOUS_DIR . '/functions/cleanup.php';
