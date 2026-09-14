<?php
/**
 * Site footer.
 *
 * The link columns come from registered menus. The office details, social links and
 * ISO marks are still template content — they belong in a Customizer section, which is
 * a small job for after the pages are done rather than something to guess at now.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="c-surface-secondary">
	<div class="container-full">
		<div class="container-full custom-fotter">
			<div class="spacer-3x"></div>
			<div class="grid-4 fotter">

				<div class="cell footer-col-discover">
					<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'Discover', 'osous' ); ?></div>
					<div><div class="spacer-1x"></div></div>
					<div class="flex-v">
						<?php osous_footer_links( 'footer-discover' ); ?>
					</div>
				</div>

				<div class="cell footer-col-uae">
					<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'UAE', 'osous' ); ?></div>
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
					<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'IRAQ', 'osous' ); ?></div>
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
						<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'Follow us', 'osous' ); ?></div>
						<div class="spacer-1x"></div>
						<div class="flex-h">
							<a href="<?php echo esc_url( osous_setting( 'facebook' ) ); ?>" class="btn sm outline" aria-label="Facebook"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-facebook.svg' ) ); ?>" alt="" class="icon-24"></a>
							<a href="<?php echo esc_url( osous_setting( 'linkedin' ) ); ?>" class="btn sm outline" aria-label="LinkedIn"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-linkden.svg' ) ); ?>" alt="" class="icon-24"></a>
							<a href="<?php echo esc_url( osous_setting( 'instagram' ) ); ?>" class="btn sm outline" aria-label="Instagram"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-instagram.svg' ) ); ?>" alt="" class="icon-24"></a>
						</div>
					</div>
					<div class="spacer-1x"></div>
					<div>
						<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'Send us', 'osous' ); ?></div>
						<div class="spacer-1x"></div>
						<a href="<?php echo esc_url( 'mailto:' . osous_setting( 'email' ) ); ?>" class="link c-on-secondary"><?php echo esc_html( osous_setting( 'email' ) ); ?></a>
					</div>
					<div class="spacer-1x"></div>
					<div class="footer-images">
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/27001.webp' ) ); ?>" alt="ISO 27001" class="iso-mark iso-mark-27001">
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/9001.webp' ) ); ?>" alt="ISO 9001" class="iso-mark iso-mark-9001">
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
					<?php
					printf(
						/* translators: %s: current year. */
						esc_html__( '© %s Osous Al Taqnia. All rights reserved.', 'osous' ),
						esc_html( gmdate( 'Y' ) )
					);
					?>
				</div>
			</div>

		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
