<?php
/**
 * Email Security
 *
 * Read from the reference page email-security-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Email Security',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container">
			<div class="grid-2 gap-1x">
				<div class="cell self-center-tablet">
					<div class="container c-surface-primary h-100">
						<div class="content">
							<h2 class="h2 c-on-primary">Comprehensive Email Security Solutions in Iraq</h2>
							<p class="p c-on-primary-subtle">In today's digital landscape, email remains the backbone of business communication—and unfortunately, the primary gateway for cyberattacks. With sophisticated threats evolving daily, businesses in Iraq require robust email security solutions from Osous Al Taqnia to protect sensitive data, maintain operational continuity, and safeguard their reputation.</p>
						</div>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/email-security-protection-iraq.webp',
							'alt'    => 'Multi-layered email security protection for Iraqi businesses',
							'srcset' => 'assets/images/cyber-security.Email-Security01-p-500.webp 500w, assets/images/email-security-protection-iraq.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						),
						'image h-100 radii-container'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container c-border-neutral">
			<div class="grid-2">
				<h2 class="h2">Why Email Security Is Critical for Iraqi Businesses</h2>
				<p class="p">Iraq's digital economy makes its organizations prime targets for cybercriminals. The statistics are concerning: 94% of malware arrives via email, BEC attacks rose 65% last year, data breaches in Iraq can be financially devastating, and 85% of organizations have faced phishing attacks. For Iraqi businesses, a single email security breach can lead to significant financial losses, regulatory issues, and permanent damage to customer trust.</p>
			</div>
		</div>
	</section>

	<section class="section full c-surface-neutral">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Understanding Modern Email Security Threats</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Advanced Phishing Techniques',
						'text'    => 'Today\'s sophisticated phishing attacks employ social engineering and brand spoofing that can deceive even security-conscious employees by leveraging detailed knowledge of your organization.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Ransomware Distribution',
						'text'  => 'Email remains the primary vector for ransomware attacks that can encrypt critical data and halt operations—particularly devastating for Iraqi businesses establishing stable operations.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Business Email Compromise (BEC)',
						'text'  => 'BEC attacks target executives through meticulous impersonation, bypassing traditional security to authorize fraudulent transfers—a significant risk for Iraq\'s expanding international business connections.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Zero-Day Exploits',
						'text'  => 'These unknown security flaws are exploited before countermeasures exist, requiring advanced detection beyond traditional signature-based security.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Data Exfiltration and Insider Threats',
						'text'  => 'Email security must address both employee mistakes and malicious insiders attempting to extract valuable data through specialized prevention systems.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container c-surface-neutral">
			<div class="heading center">
				<h2 class="h2 a-c">Comprehensive Email Security: The Multi-Layered Approach</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Advanced Threat Protection',
						'text'    => 'Modern email security employs machine learning to detect unusual patterns, sandboxing to analyze attachments safely, and real-time URL analysis to neutralize malicious links. AI-powered systems continuously evolve to counter emerging threats, providing dynamic protection beyond traditional filters.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Anti-Phishing Defenses',
						'text'    => 'Effective phishing protection combines technical measures like DMARC, SPF, and DKIM authentication with human-centered approaches. Visual similarity detection prevents brand spoofing while employee training creates awareness. Real-time warning systems provide immediate alerts when suspicious messages evade technical defenses.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Data Loss Prevention',
						'text'    => 'DLP systems monitor outbound communications for sensitive content, enforce encryption when needed, and implement role-based permissions. These solutions prevent both accidental data leaks and malicious exfiltration while maintaining detailed audit trails for compliance requirements.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Email Continuity',
						'text'    => 'Cloud-based backup systems, automatic failover mechanisms, and emergency inbox access ensure communication continues during outages. Message queuing prevents data loss during disruptions, while rapid recovery protocols minimize downtime and restore normal operations quickly.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Cloud Email Protection',
						'text'    => 'For Iraqi businesses using platforms like Office 365, specialized protection includes API-based integration, account compromise detection, and internal threat monitoring. Enhanced scanning and post-delivery remediation capabilities contain threats before they can spread throughout the organization.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container">
				<div class="heading center">
					<h2 class="h2 a-c">Our Strategic <span class="highlight">Technology Partners</span></h2>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-email-security03.svg',
								'alt' => 'Microsoft ',
							),
							'link'    => home_url( '/microsoft-iraq/' ),
							'text'    => 'As a Microsoft partner, Osous Al Taqnia delivers enhanced security for Office 365 and Exchange environments across Iraq, providing seamless protection for cloud-based email with advanced threat detection.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/partner-acronis-white.svg',
								'alt' => 'Acronis',
							),
							'link' => home_url( '/acronis-iraq/' ),
							'text' => 'Our Acronis partnership brings comprehensive email backup and disaster recovery solutions to Iraqi businesses, ensuring critical communications remain secure and recoverable during attacks or system failures.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/partner-white-fortinet.svg',
								'alt' => 'Fortinet',
							),
							'link' => home_url( '/fortinet-iraq/' ),
							'text' => 'With Fortinet\'s security fabric, we implement enterprise-grade email security gateways featuring advanced threat intelligence and AI-driven detection that stops sophisticated attacks before reaching your network.',
						)
					);
					?>
					<div class="row-tablet-3-4">
						<?php
						get_template_part(
							'includes/parts/card',
							null,
							array(
								'outline' => false,
								'icon'    => array(
									'src' => 'assets/images/partner-email-security05.svg',
									'alt' => 'Kaspersky',
								),
								'link'    => home_url( '/kaspersky-iraq/' ),
								'text'    => 'Our Kaspersky partnership delivers world-class anti-malware and anti-phishing technologies throughout Iraq, providing industry-leading detection rates against email-borne threats through locally managed solutions.',
							)
						);
						?>
					</div>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/sentinelone-device-protection.svg',
								'alt' => 'SentinelOne',
							),
							'link'    => home_url( '/sentinelone-iraq/' ),
							'text'    => 'Through SentinelOne, we offer autonomous email protection powered by behavioral AI, enabling Iraqi businesses to defend against unknown threats and zero-day exploits with immediate containment and remediation capabilities.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Choose Osous Al Taqnia for Email Security in Iraq',
			'items'   => array(
				array(
					'title' => 'Local Expertise and Understanding',
					'text'  => 'Azure cloud management services offer comprehensive support throughout your cloud journey, providing:',
				),
				array(
					'title' => 'Comprehensive Protection',
					'text'  => 'We offer end-to-end email security that addresses all threat vectors with advanced technologies designed for Iraq\'s business environment.',
				),
				array(
					'title' => '24/7 Arabic-Speaking Support',
					'text'  => 'Our local support team is available around the clock to assist with any security concerns in your preferred language.',
				),
				array(
					'title' => 'Scalable Solutions',
					'text'  => 'Our email security systems grow with your business, ensuring continuous protection as your organization expands throughout Iraq.',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Protecting Your Most Vulnerable Communication Channel</h2>
						<p class="p">For Iraqi businesses expanding their digital footprint, robust email security is essential. Implementing a multi-layered defense strategy with advanced technology and employee training significantly reduces vulnerability to attacks while supporting your business objectives in Iraq's unique operating environment.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/strategic-email-security-partners-iraq.webp',
							'alt'    => 'Osous Al Taqnia\'s strategic technology partners for email security',
							'srcset' => 'assets/images/cyber-security.Email-Security02-p-500.webp 500w, assets/images/strategic-email-security-partners-iraq.webp 650w',
							'sizes'  => '(max-width: 767px) 100vw, 649.982666015625px',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/email-compromise-protection-ira.webp',
							'alt'    => 'Business email compromise protection for Iraqi organizations',
							'srcset' => 'assets/images/cyber-security.Email-Security03-p-500.webp 500w, assets/images/email-compromise-protection-ira.webp 650w',
							'sizes'  => '(max-width: 767px) 100vw, 649.982666015625px',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Take Action Today</h2>
						<p class="p">Contact Osous Al Taqnia's specialists today for a comprehensive security assessment tailored to your organization's needs in Iraq. Protect your most critical communication channel before it becomes your greatest vulnerability.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Transform Your Iraqi Business with Professional IT Services',
			'text'       => 'Choosing the right IT partner is key in Iraq’s fast-moving market. Whether you’re launching new services, upgrading infrastructure, or improving cybersecurity, Osous Al Taqnia offers the solutions and local support to help your business grow.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
