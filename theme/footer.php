<?php
/**
 * Site footer.
 *
 * The link columns come from the two footer menus. Everything else here that is not a
 * menu — the headings, the offices, the map, the social links, the email, the badges,
 * the credit and the copyright line — is a Site detail from inc/settings.php, edited
 * under Appearance → Customize.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="c-surface-secondary">
	<div class="container-full">
		<div class="container-full custom-footer">
			<div class="spacer-3x"></div>
			<div class="grid-4 footer">

				<div class="cell footer-col-discover">
					<div class="t-r-lg c-on-secondary all-cap"><?php echo esc_html( osous_setting( 'discover_heading' ) ); ?></div>
					<div><div class="spacer-1x"></div></div>
					<div class="flex-v">
						<?php osous_footer_links( 'footer-discover' ); ?>
					</div>
				</div>

				<div class="cell footer-col-uae">
					<div class="t-r-lg c-on-secondary all-cap"><?php echo esc_html( osous_setting( 'uae_name' ) ); ?></div>
					<div class="spacer-1x"></div>
					<div class="vflex">
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-pin.svg' ) ); ?>" alt="" class="icon-24">
							<p class="p-r-sm c-on-secondary"><?php echo osous_address( 'uae_address' ); ?></p>
						</div>
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-phone.svg' ) ); ?>" alt="" class="icon-24">
							<a href="<?php echo esc_url( osous_tel( osous_setting( 'uae_phone' ) ) ); ?>" class="link c-on-secondary"><?php echo esc_html( osous_setting( 'uae_phone' ) ); ?></a>
						</div>
					</div>
				</div>

				<div class="cell footer-col-iraq">
					<div class="t-r-lg c-on-secondary all-cap"><?php echo esc_html( osous_setting( 'iraq_name' ) ); ?></div>
					<div class="spacer-1x"></div>
					<div class="vflex">
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-pin.svg' ) ); ?>" alt="" class="icon-24">
							<p class="p-r-sm c-on-secondary"><?php echo osous_address( 'iraq_address' ); ?></p>
						</div>
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-phone.svg' ) ); ?>" alt="" class="icon-24">
							<div class="flex-v">
								<a href="<?php echo esc_url( osous_tel( osous_setting( 'iraq_phone_1' ) ) ); ?>" class="link c-on-secondary"><?php echo esc_html( osous_setting( 'iraq_phone_1' ) ); ?></a>
								<?php if ( osous_setting( 'iraq_phone_2' ) ) : ?>
									<a href="<?php echo esc_url( osous_tel( osous_setting( 'iraq_phone_2' ) ) ); ?>" class="link c-on-secondary"><?php echo esc_html( osous_setting( 'iraq_phone_2' ) ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="map-embed">
						<iframe title="<?php esc_attr_e( 'Osous Al Taqnia on Google Maps', 'osous' ); ?>" width="250" height="200" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="<?php echo esc_url( osous_setting( 'map_embed' ) ); ?>"></iframe>
					</div>
				</div>

				<div class="cell">
					<div>
						<div class="t-r-lg c-on-secondary all-cap"><?php echo esc_html( osous_setting( 'follow_heading' ) ); ?></div>
						<div class="spacer-1x"></div>
						<div class="flex-h">
							<a href="<?php echo esc_url( osous_setting( 'facebook' ) ); ?>" class="btn sm outline" aria-label="Facebook"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-facebook.svg' ) ); ?>" alt="" class="icon-24"></a>
							<a href="<?php echo esc_url( osous_setting( 'linkedin' ) ); ?>" class="btn sm outline" aria-label="LinkedIn"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-linkden.svg' ) ); ?>" alt="" class="icon-24"></a>
							<a href="<?php echo esc_url( osous_setting( 'instagram' ) ); ?>" class="btn sm outline" aria-label="Instagram"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-instagram.svg' ) ); ?>" alt="" class="icon-24"></a>
						</div>
					</div>
					<div class="spacer-1x"></div>
					<div>
						<div class="t-r-lg c-on-secondary all-cap"><?php echo esc_html( osous_setting( 'send_heading' ) ); ?></div>
						<div class="spacer-1x"></div>
						<a href="<?php echo esc_url( 'mailto:' . osous_setting( 'email' ) ); ?>" class="link c-on-secondary"><?php echo esc_html( osous_setting( 'email' ) ); ?></a>
					</div>
					<div class="spacer-1x"></div>
					<div class="footer-images">
						<?php osous_badge( 'badge_1', 'iso-mark iso-mark-27001' ); ?>
						<?php osous_badge( 'badge_2', 'iso-mark iso-mark-9001' ); ?>
					</div>
				</div>

			</div>

			<div class="spacer-3x"></div>
			<div class="divider c-border-secondary"></div>
			<div class="spacer-3x"></div>

			<div class="grid-3 gap-none">
				<div class="flex-h a-l-c footer-credit">
					<div class="t-r-sm c-on-secondary"><?php esc_html_e( 'Developed by', 'osous' ); ?></div>
					<a href="<?php echo esc_url( osous_setting( 'credit_url' ) ); ?>" class="link c-on-secondary"><?php echo esc_html( osous_setting( 'credit_name' ) ); ?></a>
				</div>
				<div class="flex-h footer-legal">
					<?php osous_footer_links( 'footer-legal' ); ?>
				</div>
				<div class="t-r-sm c-on-secondary footer-copyright">
					<?php echo osous_copyright(); ?>
				</div>
			</div>

		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
