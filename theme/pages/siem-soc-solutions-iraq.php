<?php
/**
 * SIEM and SOC
 *
 * Read from the reference page siem-soc-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'SIEM and SOC',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/cyber-security.SIEM-and-SOC01.webp',
					'alt'    => 'Advanced SIEM dashboard showing real-time threat detection in Iraq',
					'srcset' => 'assets/images/cyber-security.SIEM-and-SOC01-p-500.webp 500w, assets/images/cyber-security.SIEM-and-SOC01-p-800.webp 800w, assets/images/cyber-security.SIEM-and-SOC01-p-1080.webp 1080w, assets/images/cyber-security.SIEM-and-SOC01.webp 1352w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.96875px, 939.984375px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start">Advanced Endpoint Security Solutions in Iraq: <span class="thin">Protecting Your Business in the Digital Age</span></h2>
				<p class="p-r-sm c-on-primary self-center">In today's interconnected business environment, the traditional network perimeter has evolved drastically. With remote work becoming the norm and employees accessing corporate resources from various devices and locations, securing your organization's endpoints has never been more critical. As cyber threats grow increasingly sophisticated, businesses in Iraq need robust endpoint protection strategies to safeguard their digital assets. Osous Al Taqnia delivers comprehensive security solutions tailored to Iraq's unique business landscape.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-neutral">
		<div class="container">
			<div class="grid-2">
				<div class="modal bg-img-1">
					<div class="vflex">
						<h2 class="h2 c-on-secondary">Critical Cybersecurity Challenges Facing Iraqi Businesses</h2>
						<p class="p c-on-secondary">Businesses in Iraq face numerous cybersecurity challenges:</p>
					</div>
				</div>
				<div class="modal c-surface-white">
					<ul class="list" role="list">
						<li class="list-item-bullet">
							<div class="t-r-md">Increasing sophistication of cyber attacks targeting critical infrastructure and sensitive data</div>
						</li>
						<li class="list-item-bullet">
							<div class="t-r-md">Evolving regulatory compliance requirements both locally and internationally</div>
						</li>
						<li class="list-item-bullet">
							<div class="t-r-md">Growing attack surface due to digital transformation initiatives and IoT adoption</div>
						</li>
						<li class="list-item-bullet">
							<div class="t-r-md">Skills shortage in specialized cybersecurity professionals</div>
						</li>
						<li class="list-item-bullet">
							<div class="t-r-md">Cloud security concerns as more organizations migrate to cloud environments</div>
						</li>
						<li class="list-item-bullet">
							<div class="t-r-md">Supply chain vulnerabilities that can compromise even well-protected systems</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Comprehensive SIEM and SOC Solutions from Osous Al-Taqnia</h2>
			</div>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-3">
				<div class="cell span-mobile-2">
					<h3>Advanced <span class="highlight">SIEM</span> Technology</h3>
					<p>Our solution provides real-time threat detection with automated incident correlation and complete network visibility.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3><span class="highlight">24/7</span> SOC Services</h3>
					<p>We offer continuous expert monitoring, proactive threat hunting, and rapid incident response to protect your business.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Tailored <span class="highlight">Security</span> Frameworks</h3>
					<p>Our solutions meet Iraq-specific regulations while seamlessly integrating with your existing systems and industry requirements.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider c-border-neutral span-4"></div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<div class="modal">
					<div class="flex-v">
						<h2 class="h2 c-on-secondary">Key Benefits of <span class="highlight">Osous Al Taqnia's </span>SIEM and SOC Solutions</h2>
					</div>
				</div>
				<div class="flex-v a-l-sb">
					<div class="cell">
						<h3>Real-Time Protection</h3>
						<p>Detect and respond to security incidents instantly before damage occurs</p>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="cell">
						<h3>Regulatory Compliance</h3>
						<p>Meet Iraq's data protection laws and international standards like GDPR</p>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="cell">
						<h3>Cost Efficiency</h3>
						<p>Reduce breach risks and downtime while consolidating security tools</p>
					</div>
					<div class="divider c-border-neutral"></div>
					<div class="cell">
						<h3>Continuous Improvement</h3>
						<p>Receive regular threat intelligence updates and security recommendations from experts</p>
					</div>
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
								'src' => 'assets/images/sentinelone-device-protection.svg',
								'alt' => 'SentinelOne',
							),
							'link'    => home_url( '/sentinelone-iraq/' ),
							'fill'    => true,
							'text'    => 'Leveraging AI-powered endpoint security to offer next-generation protection, detection, and automated response capabilities that reduce incident resolution time.',
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
								'alt' => 'Acronis ',
							),
							'link' => home_url( '/acronis-iraq/' ),
							'text' => 'Providing Iraqi businesses with integrated backup, disaster recovery, and cybersecurity solutions to ensure business continuity against ransomware and data loss.',
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
								'alt' => 'Fortinet ',
							),
							'link' => home_url( '/fortinet-iraq/' ),
							'text' => 'Delivering enterprise-grade network security through the Fortinet Security Fabric, including advanced firewalls and unified threat management systems for Iraqi organizations.',
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
									'alt' => 'Kaspersky ',
								),
								'link'    => home_url( '/kaspersky-iraq/' ),
								'fill'    => true,
								'text'    => 'Combining world-class endpoint protection and threat intelligence with our local expertise to defend Iraqi businesses against malware and advanced persistent threats.',
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'columnClass' => 'cell self-center',
			'heading'     => 'Why Choose Osous Al Taqnia for SIEM and SOC Solutions in Iraq',
			'items'       => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Advanced Threat Management',
					'text'       => 'Proactive intelligence gathering and analytics to identify and neutralize threats before they impact your business',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Local Expertise',
					'text'       => '24/7 access to certified security specialists who understand Iraq\'s unique threat landscape and regulatory environment',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Flexible Solutions',
					'text'       => 'Modular security offerings that integrate with existing systems and scale with your business needs',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Iraq Market Experience',
					'text'       => 'Proven track record helping Iraqi organizations strengthen their security posture against regional threats',
				),
			),
		)
	);
	?>

	<section class="h-100-vh">
		<div class="container-full bg-img-13 h-75">
			<div class="grid-2 h-100">
				<div class="flex-v a-c-c">
					<div class="container fit">
						<?php
						get_template_part(
							'includes/parts/photo-card',
							null,
							array(
								'title' => 'Contact Osous Al Taqnia Today for Unparalleled Security Protection',
								'text'  => 'Don\'t wait until a breach compromises your business. Partner with Osous Al Taqnia to implement state-of-the-art SIEM and SOC solutions tailored specifically for the Iraqi business environment. Our team of security experts is ready to help you navigate the complex cybersecurity landscape and establish the robust protection your organization needs.',
							)
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
