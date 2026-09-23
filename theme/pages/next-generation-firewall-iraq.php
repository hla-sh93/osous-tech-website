<?php
/**
 * Next Generation Firewall
 *
 * Read from the reference page next-generation-firewall-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Next Generation Firewall',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2 c-on-primary">Enterprise-Grade Next Generation Firewall Solutions in Iraq: <span class="thin">Advanced Cybersecurity Protection</span></h2>
						<p class="p c-on-primary-subtle">In today's increasingly hostile digital landscape, Iraqi businesses face sophisticated cyber threats that traditional security measures cannot address. Modern cyberattacks have evolved beyond the capabilities of conventional firewalls, leaving organizations across Baghdad and throughout Iraq vulnerable to ransomware, data breaches, and advanced persistent threats. Next-Generation Firewalls (NGFWs) represent the evolution of network security, providing comprehensive protection that traditional systems simply cannot match.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/cyber-security.Next-Generation-Firewall04.webp',
							'alt' => 'Next Generation Firewall Security Dashboard for Iraqi Businesses',
						),
						'image h-50'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<h2 class="h2">Osous Al Taqnia: <span class="thin">Iraq's Premier </span>Next Generation<span class="thin"> Firewall Security Provider</span></h2>
				<p>As Iraq's leading cybersecurity partner, Osous Al Taqnia delivers enterprise-grade next generation firewall solutions specifically designed for the unique security challenges facing Iraqi businesses. Our advanced NGFW technology implements multiple security layers to detect, prevent, and neutralize threats before they can compromise your network infrastructure.</p>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/split-section',
		null,
		array(
			'title' => '<strong>Comprehensive NGFW Security Features for Iraqi Enterprises</strong>',
			'text'  => 'Our next generation firewall solutions provide Iraqi businesses with:',
			'items' => array(
				array(
					'title'      => '<strong>Deep Packet Inspection Technology</strong>',
					'text'       => 'Real-time traffic analysis that identifies and blocks malicious content before it reaches your network',
					'image'      => array(
						'src'    => 'assets/images/osous-ngfw-protection-iraq.webp',
						'alt'    => 'Osous Al Taqnia\'s Enterprise NGFW Protection for Iraq',
						'srcset' => 'assets/images/cyber-security.Next-Generation-Firewall05-p-500.webp 500w, assets/images/cyber-security.Next-Generation-Firewall05-p-800.webp 800w, assets/images/osous-ngfw-protection-iraq.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 920.9896240234375px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => '<strong>Advanced Threat Prevention</strong>',
					'text'       => 'Cutting-edge deep learning algorithms that detect even zero-day threats and emerging attack vectors',
					'image'      => array(
						'src'    => 'assets/images/threat-prevention-iraq.webp',
						'alt'    => 'Advanced Threat Prevention System for Iraqi Organizations',
						'srcset' => 'assets/images/cyber-security.Next-Generation-Firewall03-p-500.webp 500w, assets/images/cyber-security.Next-Generation-Firewall03-p-800.webp 800w, assets/images/threat-prevention-iraq.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 920.9896240234375px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => 'Multi-Layered Protection',
					'text'       => 'Integrated sandboxing, malware filtering, intrusion prevention, web protection, and application control systems',
					'image'      => array(
						'src'    => 'assets/images/fortinet-ngfw-baghdad.webp',
						'alt'    => 'Fortinet NGFW Technology Implementation in Baghdad',
						'srcset' => 'assets/images/cyber-security.Next-Generation-Firewall02-p-500.webp 500w, assets/images/cyber-security.Next-Generation-Firewall02-p-800.webp 800w, assets/images/fortinet-ngfw-baghdad.webp 922w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 921.9791870117188px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => 'Complete Network Visibility',
					'text'       => 'Comprehensive monitoring of all network traffic for suspicious activity and potential security breaches',
					'image'      => array(
						'src'    => 'assets/images/hyper-converged-infrastructure-iraqi-businesses.webp',
						'alt'    => 'Cybersecurity Monitoring for Iraqi Enterprise Networks',
						'srcset' => 'assets/images/cyber-security.Next-Generation-Firewall01-p-500.webp 500w, assets/images/cyber-security.Next-Generation-Firewall01-p-800.webp 800w, assets/images/hyper-converged-infrastructure-iraqi-businesses.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 920.9896240234375px',
					),
					'mediaFirst' => true,
				),
			),
		)
	);
	?>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Intelligent Network Security Management for Iraqi Organizations</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Advanced Bandwidth Control & Resource Optimization',
						'text'    => 'Take full control of your network resources by prioritizing bandwidth allocation for mission-critical applications, websites, and users. Schedule system maintenance and backups during optimal timeframes to minimize disruption to your core business operations.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Comprehensive Web Filtering & Content Management',
						'text'  => 'Monitor and filter all network traffic to block unauthorized or potentially compromising content. Enhance both security posture and employee productivity by implementing granular access controls to specific websites and online resources.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Real-Time Security Monitoring & Threat Visualization',
						'text'  => 'Stay informed about potential security incidents with intuitive real-time monitoring dashboards that display network vulnerabilities and attack attempts. Our system presents complex firewall data in an easily digestible format, enabling rapid threat identification and response.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Secure SD-WAN & Network Integration',
						'text'  => 'For Iraqi enterprises with multiple locations or internet connections, our solutions provide secure link aggregation and SD-WAN capabilities. Connect different branch offices or cloud services as a single logical interface, creating a unified and protected work environment across your entire organization.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Seamless Identity Management & Access Control',
						'text'  => 'Strengthen your security posture with integrated user access control and single sign-on platform compatibility. This seamless integration can be deployed directly or via remote authentication servers, creating a work environment where user identity verification is paramount.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'IoT Device Management',
						'text'  => 'As connected devices expand throughout Iraqi business environments, Osous Al Taqnia offers specialized IoT management with secure onboarding, firmware updates, and comprehensive monitoring capabilities tailored to local infrastructure needs.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-2">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c"><span class="thin">Understanding</span> Modern <span class="highlight">Email Security</span> Threats</h2>
				<p class="p c-on-secondary a-c">Our advanced NGFW technology delivers measurable benefits that directly impact your organization's security posture and operational efficiency:</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '01',
						'title'  => 'Enhanced Threat Protection',
						'text'   => 'Shield your critical assets from advanced persistent threats and zero-day attacks using AI-powered security protocols',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '02',
						'title'  => 'Complete Security Visibility',
						'text'   => 'Gain unprecedented insight into all network traffic with comprehensive monitoring and threat intelligence',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '03',
						'title'  => 'Proactive Malware Defense',
						'text'   => 'Stop malicious software before it infiltrates your systems with real-time inspection and sandboxing technology',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '04',
						'title'  => 'Optimized Network Performance',
						'text'   => 'Maintain peak operational efficiency with intelligent traffic prioritization and bandwidth management',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '05',
						'title'  => 'Precise Access Control',
						'text'   => 'Implement granular application and port-level restrictions for maximum security without hindering legitimate business activities',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '06',
						'title'  => 'Productivity Enhancement',
						'text'   => 'Boost workplace efficiency by controlling access to non-business websites while maintaining employee satisfaction',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '07',
						'title'  => 'Secure Remote Connectivity',
						'text'   => 'Enable protected VPN access for branch offices and remote workers with enterprise-grade encryption',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/photo-card',
					null,
					array(
						'number' => '08',
						'title'  => 'Uninterrupted Business Operations',
						'text'   => 'Ensure continuous network availability with redundant multi-WAN functionality and automatic failover capabilities',
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
			'heading' => 'Why Choose Osous Al Taqnia as Your Cybersecurity Partner in Iraq',
			'text'    => 'Osous Al Taqnia delivers unmatched enterprise security excellence through',
			'items'   => array(
				array(
					'title' => 'Specialized Local Expertise',
					'text'  => 'With over a decade of experience in Iraq\'s cybersecurity landscape, we understand the unique challenges facing local businesses',
				),
				array(
					'title' => 'Certified Security Professionals',
					'text'  => 'Our team of certified security experts provides personalized consultations tailored to your specific industry requirements',
				),
				array(
					'title' => 'Cost-Effective Enterprise Protection',
					'text'  => 'We deliver the perfect balance of enterprise-grade security and cost-efficiency for businesses of all sizes',
				),
				array(
					'title' => 'Comprehensive 24/7 Local Support',
					'text'  => 'Unlike international providers, we offer round-the-clock local support and training in Iraq\'s time zone and languages',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<div class="cell">
					<h2 class="h2">Strategic Partnership with Fortinet</h2>
					<p class="p">Osous Al Taqnia has partnered with Fortinet, a global leader in high-performance cybersecurity solutions. As an authorized Fortinet partner in Iraq, we implement their advanced FortiGate NGFW technology to deliver enterprise-grade protection against today's most sophisticated threats. This collaboration ensures Iraqi businesses receive cutting-edge security solutions backed by Fortinet's industry-leading threat intelligence network.</p>
				</div>
				<div class="cell">
					<div class="modal h-100">
						<?php
						osous_image(
							array(
								'src' => 'assets/images/partner-white-fortinet.svg',
								'alt' => 'Fortinet',
							),
							'icon-256'
						);
						?>
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
			'title'      => 'Secure Your Iraqi Business Today',
			'text'       => 'Contact Osous Al Taqnia today to discover how our next generation firewall solutions can protect your organization against modern cyber threats while enhancing network performance and reliability. Our security experts will conduct a comprehensive assessment of your current infrastructure and develop a customized security strategy to address your specific needs.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
