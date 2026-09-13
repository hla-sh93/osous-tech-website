<?php
/**
 * The search form.
 *
 * Nothing on the static site searches, so there is no reference for this. It is built
 * from the vocabulary the contact form already uses -- `.input.t-m-md` in an
 * `.input-wrapper`, and a `.btn` -- so it looks like it belongs rather than like a
 * WordPress default wearing the site's colours.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$osous_field = 'osous-search-' . wp_unique_id();
?>
<form role="search" method="get" class="flex-h search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $osous_field ); ?>">
		<?php esc_html_e( 'Search this site', 'osous' ); ?>
	</label>
	<input
		class="input t-m-md"
		id="<?php echo esc_attr( $osous_field ); ?>"
		type="search"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search…', 'osous' ); ?>"
	>
	<button type="submit" class="btn"><?php esc_html_e( 'Search', 'osous' ); ?></button>
</form>
