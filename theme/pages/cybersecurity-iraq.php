<?php
/**
 * Cybersecurity Services in Iraq
 *
 * Read from the reference page cybersecurity-iraq.html by _tools/gen_pages.py. From here on this
 * file is the page: change its words here. The design's components -- the header and
 * footer, the sections, the cards -- are under includes/.
 *
 * @package Osous
 */

get_template_part( 'includes/header' );
?>
<main>
	<?php
	get_template_part(
		'includes/sections/page-header',
		null,
		array(
			'title' => 'Cybersecurity Services in Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/cyber-security.webp',
					'alt'    => 'Osous Al Taqnia cybersecurity experts monitoring security operations center in Baghdad',
					'srcset' => 'assets/images/cyber-security-p-500.webp 500w, assets/images/cyber-security-p-800.webp 800w, assets/images/cyber-security-p-1080.webp 1080w, assets/images/cyber-security.webp 1352w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.953125px, 939.953125px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start"><strong>Expert Cybersecurity Solutions for Iraqi Businesses</strong></h2>
				<p class="p-r-sm c-on-primary self-center">Osous Al Taqnia delivers enterprise-grade cybersecurity services designed specifically for Baghdad and Iraqi businesses, helping organizations protect their valuable digital assets against evolving cyber threats.</p>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c"><strong>Our Robust Suite of Cybersecurity Services</strong></h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'      => '<strong>Next Gen Firewall</strong>',
						'text'       => 'Protect your network perimeter with intelligent, adaptable firewalls that go beyond traditional defenses to detect and block sophisticated attacks.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/next-generation-firewall-iraq/' ),
						'outline'    => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Endpoint Security</strong>',
						'text'       => 'Secure your laptops, desktops, and mobile devices with advanced endpoint protection software that actively prevents malware, ransomware, and other endpoint-focused cyber threats.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/endpoint-security-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Email Security</strong>',
						'text'       => 'Shield your business from phishing attacks, malicious links, and spam with powerful email security gateways and employee awareness training.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/email-security-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Device Management Solutions</strong>',
						'text'       => 'Centrally control and secure corporate devices with remote configuration, app distribution, and security policy enforcement while protecting sensitive data in BYOD environments.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/device-management-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Multi-Factor Authentication (MFA)</strong>',
						'text'       => 'Enhance security by requiring multiple verification methods beyond passwords, creating layered protection that blocks unauthorized access even when credentials are compromised.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/multi-factor-authentication-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Identity and Access Management (IAM)</strong>',
						'text'       => 'Control digital access within your organization, ensuring the right people access only appropriate resources. IAM enhances security and efficiency through authentication tools while maintaining regulatory compliance.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/identity-access-management-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Privileged Access Management (PAM)</strong>',
						'text'       => 'Safeguard critical credentials with elevated access rights through secure vaulting, monitoring, and controlled access. PAM prevents unauthorized system entry while simplifying administration and compliance—essential protection for these prime targets of cyberattacks.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/privileged-access-management-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>SIEM and SOC Services</strong>',
						'text'       => 'SIEM systems monitor your network, providing real-time threat detection through advanced analytics. A SOC is your security command center with experts who continuously watch for and respond to incidents. Together, they deliver comprehensive protection with quick response capabilities while ensuring compliance and minimizing disruptions.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/siem-soc-solutions-iraq/' ),
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-2">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c"><strong>Key Benefits of Our Cybersecurity Services</strong></h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'title' => '<strong>Protect Critical Assets</strong>',
						'text'  => 'Safeguard customer data, intellectual property, and sensitive business information',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'title' => '<strong>Ensure Business Continuity</strong>',
						'text'  => 'Prevent costly downtime and maintain operational resilience',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'title' => '<strong>Build Trust</strong>',
						'text'  => 'Enhance customer and partner confidence through demonstrated security excellence',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'title' => '<strong>Achieve Compliance</strong>',
						'text'  => 'Meet Iraqi Information Assurance Standards, NITC requirements, and industry regulations',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'title' => '<strong>Maximize ROI</strong>',
						'text'  => 'Invest in prevention to avoid costly breaches and recovery expenses',
					)
				);
				?>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => '<strong>Why Choose Osous Al Taqnia</strong>',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Local Expertise</strong>',
					'textStyle'  => 'plain',
					'text'       => 'Our team understands the unique cybersecurity landscape of Iraq and provides solutions tailored to regional challenges.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Comprehensive Protection</strong>',
					'textStyle'  => 'small',
					'text'       => 'We offer end-to-end security services that cover all aspects of your IT infrastructure.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong> Proactive Approach</strong>',
					'textStyle'  => 'small',
					'text'       => 'Our experts continuously monitor and update security measures to stay ahead of emerging threats.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Dedicated Support</strong>',
					'textStyle'  => 'small',
					'text'       => 'Receive 24/7 assistance from our knowledgeable security professionals committed to your protection.',
				),
			),
		)
	);
	?>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => '<strong>Transform Your Cyber Security Posture with Osous Al Taqnia</strong>',
			'text'       => 'Don\'t let cyber threats jeopardize your business. Contact Osous Al Taqnia today to schedule a consultation and discover how we can bolster your cyber defenses and give you the peace of mind to focus on growth.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
