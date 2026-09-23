<?php
/**
 * Identity and Access Management (IAM)
 *
 * Read from the reference page identity-access-management-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Cyber Security',
			'title'   => 'Identity and Access Management (IAM)',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit c-surface-primary">
			<div class="grid-3">
				<div class="cell self-center span-2">
					<div class="content">
						<h2 class="h2 c-on-primary">Advanced IAM Solutions for Modern Enterprises</h2>
						<p class="p c-on-primary-subtle">In today's rapidly evolving digital environment, businesses in Iraq face increasing cybersecurity challenges. As organizations across the country digitize their operations in alignment with Iraq's national development vision, proper identity and access management has become not just a security necessity but a competitive advantage.Osous Al Taqnia's comprehensive Identity and Access Management (IAM) solutions provide the robust security framework that modern Iraqi businesses need to protect sensitive information while enabling seamless access for authorized users.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/iam-security-solutions-iraqi-businesses.webp',
							'alt' => 'IAM security solutions for Iraqi businesses',
						),
						'image h-50'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Core Components of Osous Al Taqnia's IAM Solutions</h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Single Sign-On (SSO)',
						'text'    => 'Osous Al Taqnia\'s SSO capabilities allow users to access multiple applications with one set of credentials, reducing password fatigue and security risks while enhancing productivity. This technology is particularly valuable for Iraq\'s financial institutions and government entities managing numerous secure systems.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Multi-Factor Authentication (MFA)',
						'text'  => 'To counter sophisticated cyberthreats targeting Iraqi organizations, Osous Al Taqnia\'s MFA solutions create multiple layers of verification before granting access to sensitive systems. This additional security measure is crucial for organizations handling personal data under Iraq\'s personal data protection frameworks.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Role-Based Access Control (RBAC)',
						'text'  => 'Osous Al Taqnia implements precise access controls based on organizational roles, ensuring employees only access information necessary for their specific functions. This granular approach to permissions management is essential for maintaining data segregation in compliance with Iraq\'s regulatory framework.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'User Provisioning and De-provisioning',
						'text'  => 'Automated user lifecycle management allows Iraqi businesses to efficiently onboard new employees and immediately revoke access when staff depart, closing security gaps that often emerge during personnel transitions.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading center">
					<h2 class="h2 a-c">Leading Cloud Backup Solutions in Iraq</h2>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-dark-acronis.svg',
								'alt' => 'Acronis',
							),
							'link'    => home_url( '/acronis-iraq/' ),
							'fill'    => true,
							'title'   => 'Acronis Cyber Protect',
							'text'    => 'Osous Al Taqnia\'s Acronis implementation combines identity protection with integrated backup capabilities. This unified solution equips Iraqi organizations with advanced authentication while ensuring business continuity through secure data protection, featuring AI-powered threat detection and centralized credential management.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon'  => array(
								'src' => 'assets/images/partner-white-fortinet.svg',
								'alt' => 'Fortinet',
							),
							'link'  => home_url( '/fortinet-iraq/' ),
							'title' => 'Fortinet Identity and Access Management',
							'text'  => 'Osous Al Taqnia deploys Fortinet\'s IAM framework with FortiAuthenticator for centralized authentication and FortiToken for secure multi-factor verification. Iraqi businesses gain comprehensive visibility, automated threat response, and compliance tracking specifically designed for local regulatory requirements.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-4">
				<h2 class="h2 span-2">Business Benefits of Implementing Osous Al Taqnia's IAM Solutions in Iraq</h2>
				<div class="divider c-border-neutral span-4"></div>
				<div class="cell span-mobile-2">
					<h3>Enhanced Security Posture</h3>
					<p>Osous Al Taqnia's IAM solutions reduce vulnerability to unauthorized access and data breaches through a layered security approach that protects sensitive information from both external and internal threats.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Regulatory Compliance</h3>
					<p>Solutions are tailored for Iraq's regulatory landscape, ensuring compliance with local data protection laws, Central Bank directives, and international standards like GDPR and ISO 27001.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Operational Efficiency</h3>
					<p>IAM solutions streamline access processes, reduce IT support requirements, and boost workforce productivity through automated provisioning and access management.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Advanced Threat Detection</h3>
					<p>Built-in monitoring and analytics identify unusual access patterns and potential security incidents before they develop into serious breaches.</p>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Iraqi Businesses Choose Osous Al Taqnia for IAM Implementation',
			'items'   => array(
				array(
					'title' => 'Local Expertise with Global Standards',
					'text'  => 'Deep understanding of Iraq\'s business environment combined with world-class security technologies',
				),
				array(
					'title' => 'Seamless Integration',
					'text'  => 'Solutions that integrate smoothly with existing IT infrastructure, minimizing disruption',
				),
				array(
					'title' => 'Dedicated Support',
					'text'  => 'Comprehensive implementation services and ongoing support from local technical teams',
				),
				array(
					'title' => 'Customized Security Strategies',
					'text'  => 'Tailored IAM approaches addressing specific business objectives and risk profiles',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container fit bg-img-14">
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'title' => 'Conclusion: Securing Iraq\'s Digital Future',
						'text'  => 'As Iraq evolves into a leading digital economy, Osous Al Taqnia\'s IAM solutions provide the essential security foundation that enables innovation while protecting critical assets. Partner with Osous Al Taqnia to enhance your security posture and build the scalable identity framework needed for future growth.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Contact Osous Al Taqnia Today',
			'text'       => 'Protect your business with secure, reliable IAM solutions tailored for Iraq\'s unique environment. Contact Osous Al Taqnia\'s specialists for a comprehensive assessment of your needs.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
