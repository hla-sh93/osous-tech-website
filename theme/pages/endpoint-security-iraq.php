<?php
/**
 * Endpoint Security
 *
 * Read from the reference page endpoint-security-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Endpoint Security',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/endpoint-security-solutions-iraq-businesses.webp',
					'alt'    => 'endpoint security solutions for Iraq businesses',
					'srcset' => 'assets/images/endpoint-security-solutions-iraq-businesses-p-500.webp 500w, assets/images/endpoint-security-solutions-iraq-businesses-p-800.webp 800w, assets/images/endpoint-security-solutions-iraq-businesses.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.982666015625px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start">Advanced Endpoint Security Solutions in Iraq: <span class="thin">Protecting Your Business in the Digital Age</span></h2>
				<p class="p-r-sm c-on-primary self-center">In today's interconnected business environment, the traditional network perimeter has evolved drastically. With remote work becoming the norm and employees accessing corporate resources from various devices and locations, securing your organization's endpoints has never been more critical. As cyber threats grow increasingly sophisticated, businesses in Iraq need robust endpoint protection strategies to safeguard their digital assets. Osous Al-Taqnia delivers comprehensive security solutions tailored to Iraq's unique business landscape.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-4">
				<h2 class="h2 span-2">Understanding Modern Endpoint Security Challenges</h2>
				<p class="span-2">The expansion of workplace flexibility has created a broader attack surface for cybercriminals. Each endpoint—whether a laptop, smartphone, or IoT device—represents a potential entry point for malicious actors. In Iraq's dynamic business landscape, organizations face unique cybersecurity challenges:</p>
				<div class="divider c-border-neutral span-4"></div>
				<div class="cell span-mobile-2">
					<h3>Evolving Threat Landscape</h3>
					<p>Ransomware, zero-day exploits, and advanced persistent threats target businesses of all sizes</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Remote Workforce Security</h3>
					<p>Ensuring protection for employees working outside traditional office environments</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Compliance Requirements</h3>
					<p>Meeting stringent data protection regulations while maintaining operational efficiency</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Resource Limitations</h3>
					<p>Balancing comprehensive security with available IT resources and expertise</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section c-surface-neutral">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Comprehensive Endpoint Protection: <span class="thin">Beyond Traditional Antivirus</span></h2>
				<p>Modern endpoint security transcends conventional antivirus with multiple integrated defense layers for complete protection:</p>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Next-Generation Threat Prevention',
						'text'    => 'Next-generation platforms utilize AI and deep learning to neutralize threats before compromise, analyzing behavior patterns to catch what signature-based systems miss.',
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
						'title' => 'Centralized Visibility and Control',
						'text'  => 'Centralized monitoring provides essential visibility across all endpoints, enabling swift risk identification and response.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Advanced Detection and Response',
						'text'  => 'EDR solutions continuously monitor for suspicious activities, offering rapid investigation tools and containment capabilities.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Data Loss Prevention',
						'text'  => 'DLP systems protect sensitive information through content inspection and access controls to prevent data leakage.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<div class="modal">
					<div class="flex-v">
						<h2 class="h2 c-on-secondary">Strategic Benefits of Advanced Endpoint Security Solutions</h2>
						<p class="p c-on-secondary">Implementing robust endpoint protection delivers multiple advantages for Iraq businesses:</p>
					</div>
				</div>
				<div class="flex-v a-l-sb">
					<div class="cell">
						<h3>Enhanced Threat Resilience</h3>
						<p>Modern endpoint security provides early threat detection, securing systems against both known and emerging threats while ensuring business continuity during attacks.</p>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="cell">
						<h3>Optimized Security Operations</h3>
						<p>Cloud-based protection and MDR services reduce IT burden while providing specialized expertise and scalable solutions that adapt to changing needs.</p>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="cell">
						<h3>Regulatory Compliance Support</h3>
						<p>Comprehensive endpoint security helps meet GDPR, HIPAA, and regional Iraq regulations, reducing legal risks and demonstrating commitment to data protection.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-4">
				<h2 class="h2 span-2">Understanding Modern Endpoint Security Challenges</h2>
				<p class="span-2">Osous Al Taqnia offers Iraq businesses comprehensive protection through:</p>
				<div class="divider c-border-neutral span-4"></div>
				<div class="cell span-mobile-2">
					<h3>Technical Risk Assessment</h3>
					<p>Proactive vulnerability identification through network visibility, access management evaluation, and security gap analysis.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Compromise Assessment</h3>
					<p>Expert detection of attacker activity using advanced threat hunting, forensic analysis, and continuous monitoring.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Incident Response</h3>
					<p>Rapid containment of threats, forensic investigations, root cause analysis, and recovery planning to minimize business impact.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading center">
					<h2 class="h2 a-c">Our Trusted Technology Partners</h2>
					<p class="p-r-sm a-c">Osous Al Taqnia collaborates with industry-leading cybersecurity providers to deliver best-in-class endpoint protection solutions to our clients across Iraq:</p>
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
								'alt' => 'Microsoft',
							),
							'link'    => home_url( '/microsoft-iraq/' ),
							'fill'    => true,
							'text'    => 'We leverage Microsoft Defender for Endpoint to provide comprehensive threat protection with automated response capabilities that seamlessly integrate with existing Windows environments while offering enhanced visibility across your digital estate.',
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
							'text' => 'Our Acronis partnership delivers unified cyber protection that combines backup, disaster recovery, and endpoint security with AI-powered defense against ransomware and other evolving threats while ensuring critical data remains recoverable.',
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
							'text' => 'Through Fortinet\'s Security Fabric architecture, we implement integrated endpoint security from network to cloud environments, providing comprehensive visibility and consistent protection across complex IT infrastructures.',
						)
					);
					?>
					<div class="cell row-tablet-3-4">
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
								'fill'    => true,
								'text'    => 'As a Kaspersky partner, we offer advanced threat detection powered by machine learning and global threat intelligence, defending against sophisticated attacks while minimizing false positives and operational disruption.',
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
							'fill'    => true,
							'text'    => 'Our SentinelOne partnership brings autonomous AI-driven endpoint protection that combines prevention, detection, response, and hunting in a single solution, enabling faster response times against both known and zero-day threats.',
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
			'heading' => 'Secure Your Digital Future with Osous Al Taqnia',
			'text'    => 'As Iraq businesses embrace digital transformation, endpoint security forms the foundation of effective cybersecurity. Comprehensive endpoint protection enables organizations to navigate evolving threats while maintaining operational flexibility. Choose Osous Al-Taqnia for:',
			'items'   => array(
				array(
					'title' => 'Local Expertise',
					'text'  => 'Deep understanding of Iraq\'s unique cybersecurity landscape',
				),
				array(
					'title' => 'Comprehensive Solutions',
					'text'  => 'End-to-end security from assessment to implementation and monitoring',
				),
				array(
					'title' => '24/7 Support',
					'text'  => 'Round-the-clock security monitoring and assistance',
				),
				array(
					'title' => 'Proven Track Record',
					'text'  => 'Trusted by leading organizations across Iraq',
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
