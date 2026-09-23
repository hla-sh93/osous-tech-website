<?php
/**
 * Nothing at this address.
 *
 * One of the two genuinely short pages on the site, and therefore the first real test
 * of the sticky footer in base.css: without it the footer would finish mid-screen with
 * the page background below it.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'includes/header' );
?>
<main>
	<?php osous_page_header( __( '404', 'osous' ), __( 'This page is not here', 'osous' ) ); ?>
	<section class="section">
		<div class="container">
			<div class="heading center">
				<p class="p a-c">
					<?php esc_html_e( 'The address may have changed, or the page may never have existed. Try searching, or start again from the front page.', 'osous' ); ?>
				</p>
				<div class="spacer-3x"></div>
				<?php get_search_form(); ?>
				<div class="spacer-3x"></div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn outline">
					<div class="t-r-md"><?php esc_html_e( 'Go to the home page', 'osous' ); ?></div>
					<div class="icon-24 c-on-neutral"><?php osous_icon( 'arrow' ); ?></div>
				</a>
			</div>
		</div>
	</section>
</main>
<?php
get_template_part( 'includes/footer' );
