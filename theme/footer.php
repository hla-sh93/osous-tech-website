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
							<p class="p-r-sm c-on-secondary">6th Floor, The Meydan Hotel, Nad Al Sheba, Dubai<br></p>
						</div>
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-phone.svg' ) ); ?>" alt="" class="icon-24">
							<a href="tel:+971585325606" class="link c-on-secondary">+971 58 532 5606</a>
						</div>
					</div>
				</div>

				<div class="cell footer-col-iraq">
					<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'IRAQ', 'osous' ); ?></div>
					<div class="spacer-1x"></div>
					<div class="vflex">
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-pin.svg' ) ); ?>" alt="" class="icon-24">
							<p class="p-r-sm c-on-secondary">Villa S 11/5, Atconz, Erbil <br>62nd St, Baghdad<br></p>
						</div>
						<div class="flex-h">
							<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-phone.svg' ) ); ?>" alt="" class="icon-24">
							<div class="flex-v">
								<a href="tel:+9647508417028" class="link c-on-secondary">+964 750 841 7028</a>
								<a href="tel:+9647804455104" class="link c-on-secondary">+964 780 445 5104</a>
							</div>
						</div>
					</div>
					<div class="map-embed">
						<iframe title="<?php esc_attr_e( 'Osous Al Taqnia on Google Maps', 'osous' ); ?>" width="250" height="200" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3217.6770720498216!2d44.022947900000005!3d36.24733779999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40071f6fc027b687%3A0x2a5846c52d553dfc!2sOSOUS%20AL%20TAQNIA!5e0!3m2!1sen!2siq!4v1769344992153!5m2!1sen!2siq"></iframe>
					</div>
				</div>

				<div class="cell">
					<div>
						<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'Follow us', 'osous' ); ?></div>
						<div class="spacer-1x"></div>
						<div class="flex-h">
							<a href="https://www.facebook.com/profile.php?id=61582436337354" class="btn sm outline" aria-label="Facebook"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-facebook.svg' ) ); ?>" alt="" class="icon-24"></a>
							<a href="https://www.linkedin.com/company/osous-al-taqnia/" class="btn sm outline" aria-label="LinkedIn"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-linkden.svg' ) ); ?>" alt="" class="icon-24"></a>
							<a href="#" class="btn sm outline" aria-label="Instagram"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/icon-instagram.svg' ) ); ?>" alt="" class="icon-24"></a>
						</div>
					</div>
					<div class="spacer-1x"></div>
					<div>
						<div class="t-r-lg c-on-secondary all-cap"><?php esc_html_e( 'Send us', 'osous' ); ?></div>
						<div class="spacer-1x"></div>
						<a href="mailto:info@osoustaqnia.com" class="link c-on-secondary">info@osoustaqnia.com</a>
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
					<a href="https://www.osoustech.com/" class="link c-on-secondary">Osous Technology</a>
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
