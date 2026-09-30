<?php
/**
 * Document head, floating navbar and the WhatsApp badge.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * The home page runs the navbar over a dark hero; every other page sits on white.
 * In the static site this was two hand-edited copies of the markup — here it is one
 * flag, which is the whole reason for doing this in PHP.
 */
$osous_dark = is_front_page();

/*
 * The logo comes from Customize → Site Identity when one has been chosen, and from the
 * design otherwise. The button appears twice — once in the phone menu, once beside the
 * menu — and both are plain links rather than menu items, so they need the current-page
 * marker spelled out here; the static pages carry it on both. Its label and its page are
 * Site details.
 */
$osous_logo   = osous_logo();
$osous_button = osous_header_button();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( $osous_dark ? 'c-surface-secondary-900' : '' ); ?>>
<?php wp_body_open(); ?>

<header class="navbar elevation<?php echo $osous_dark ? ' darkmode' : ''; ?>" role="banner">
	<div class="navbar-wrapper">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>
			<img src="<?php echo esc_url( $osous_logo['src'] ); ?>" width="<?php echo (int) $osous_logo['width']; ?>" height="<?php echo (int) $osous_logo['height']; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="logo">
		</a>

		<nav class="navbar-menu" id="primary-menu">
			<?php osous_primary_menu(); ?>

			<div class="navbar-mobile-buttons">
				<a href="<?php echo esc_url( $osous_button['url'] ); ?>" class="btn outline"<?php echo osous_aria_current( $osous_button['id'] ); ?>><div class="t-r-md c-on-secondary"><?php echo esc_html( $osous_button['label'] ); ?></div></a>
			</div>
		</nav>

		<div class="navbar-buttons">
			<div class="nav-button-wrapper"><a href="<?php echo esc_url( $osous_button['url'] ); ?>" class="btn"<?php echo osous_aria_current( $osous_button['id'] ); ?>><?php echo esc_html( $osous_button['label'] ); ?></a></div>
		</div>

		<button type="button" class="btn btn-menu" data-menu-toggle aria-label="<?php esc_attr_e( 'Menu', 'osous' ); ?>" aria-expanded="false" aria-controls="primary-menu">
			<span class="menu-icon-top"></span>
			<span class="menu-icon-middle"></span>
			<span class="menu-icon-bottom"></span>
		</button>

	</div>
</header>

<?php get_template_part( 'includes/whatsapp' ); ?>
