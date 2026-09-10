<?php
/**
 * Osous theme bootstrap.
 *
 * Nothing is defined here directly — each concern lives in its own file under inc/
 * so the theme stays legible as it grows.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'OSOUS_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'OSOUS_DIR', get_template_directory() );

/** Shown on the floating badge and linked from it. */
define( 'OSOUS_WHATSAPP', '+9647870606069' );

require_once OSOUS_DIR . '/inc/setup.php';
require_once OSOUS_DIR . '/inc/sections.php';
require_once OSOUS_DIR . '/inc/enqueue.php';
require_once OSOUS_DIR . '/inc/nav.php';
require_once OSOUS_DIR . '/inc/cleanup.php';
