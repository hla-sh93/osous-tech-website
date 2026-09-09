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
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class( $osous_dark ? 'c-surface-secondary-900' : '' ); ?>>
<?php wp_body_open(); ?>

<header class="navbar elevation<?php echo $osous_dark ? ' darkmode' : ''; ?>" role="banner">
	<div class="navbar-wrapper">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand"<?php echo is_front_page() ? ' aria-current="page"' : ''; ?>>
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-full-mixed.svg' ) ); ?>" width="130" height="50" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="logo">
		</a>

		<nav class="navebar-menu" id="primary-menu">
			<?php osous_primary_menu(); ?>

			<div class="navbar-mobile-buttons">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn outline"><div class="t-r-md c-on-secondary"><?php esc_html_e( 'Contact', 'osous' ); ?></div></a>
			</div>
		</nav>

		<div class="navbar-buttons">
			<div class="nav-button-wrapper"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn"><?php esc_html_e( 'Contact', 'osous' ); ?></a></div>
		</div>

		<button type="button" class="btn btn-menu" data-menu-toggle aria-label="<?php esc_attr_e( 'Menu', 'osous' ); ?>" aria-expanded="false" aria-controls="primary-menu">
			<span class="menu-icon-top"></span>
			<span class="menu-icon-middle"></span>
			<span class="menu-icon-bottom"></span>
		</button>

	</div>
</header>

<?php get_template_part( 'template-parts/chrome/whatsapp' ); ?>
