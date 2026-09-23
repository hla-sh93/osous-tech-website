<?php
/**
 * Data Loss Prevention
 *
 * Read from the reference page dlp-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Data protection',
			'title'   => 'Data Loss Prevention',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/data-loss-prevention-dashboard-iraq.webp',
					'alt'    => 'advanced data loss prevention dashboard monitoring Iraqi business data',
					'srcset' => 'assets/images/data-loss-prevention-dashboard-iraq-p-500.webp 500w, assets/images/data-loss-prevention-dashboard-iraq-p-800.webp 800w, assets/images/data-loss-prevention-dashboard-iraq.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.96533203125px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start">Advanced Data Loss Prevention Solutions in Iraq</h2>
				<p class="p-r-sm c-on-primary self-center">In today's digital landscape, where data breaches and cyber threats are increasingly sophisticated, protecting your organization's sensitive information has never been more critical. Osous Al Taqnia leads the cybersecurity industry in Iraq with cutting-edge Data Loss Prevention (DLP) solutions designed to safeguard your most valuable digital assets.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Why Iraqi Businesses Need Robust DLP Solutions</h2>
				<p class="p-r-sm a-c">With cyberattacks in Iraq increasing by 300% since 2020, businesses face unprecedented data security challenges. As Iraq's digital economy grows, organizations handling sensitive information are increasingly targeted by sophisticated threat actors. Iraqi companies must protect against:</p>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Escalating Cyber Threats',
						'text'    => 'Iraq\'s businesses are experiencing a dramatic rise in targeted attacks as the country\'s digital infrastructure expands',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'title'   => 'Data Breach Vulnerabilities',
						'text'    => 'Organizations face significant risks from both external attackers and internal security gaps',
						'outline' => false,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'title'   => 'Sensitive Information Exposure',
						'text'    => 'Critical business data, intellectual property, and customer information require enhanced protection',
						'outline' => false,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Regulatory Compliance Challenges',
						'text'    => 'Meeting the growing requirements established by Iraq\'s emerging cybersecurity framework',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'title'   => 'Operational Continuity Risks',
						'text'    => 'Potential downtime and disruption caused by security incidents threaten business stability',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'text'    => 'Implementing effective DLP solutions is critical for Iraqi businesses to secure their digital assets and maintain operational continuity in this challenging threat landscape.',
						'outline' => true,
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading span-2">
				<div class="grid-2">
					<h2 class="h2">Osous Al Taqnia's Comprehensive DLP Approach</h2>
					<p class="p">Osous Al Taqnia combines AI-powered technologies with deep industry expertise to deliver comprehensive data protection:</p>
				</div>
			</div>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-3">
				<div class="cell span-mobile-2">
					<h3>Advanced Data Protection Strategies</h3>
					<p>Our DLP services implement multiple layers of protection through data flow control, intelligent classification, proactive insider threat detection, and regulatory compliance management systems tailored to Iraq's cybersecurity framework.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Seamless Implementation Process</h3>
					<p>We follow a proven six-step methodology: initial consultation, risk assessment, custom solution design, efficient deployment, strategic configuration, and comprehensive training to ensure maximum effectiveness of your DLP solution.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Advanced Technical Features</h3>
					<p>Eliminate vulnerabilities from single points of failure—whether devices, buildings, or geographic areas in Iraq—through redundancy and geographical distribution of your systems.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider c-border-neutral"></div>
		</div>
	</section>

	<section>
		<div class="container-full bg-img-11">
			<div class="grid-2 h-100">
				<div class="cell col-right">
					<div class="container fit">
						<div class="modal c-surface-primary">
							<div class="vflex">
								<h2 class="h2 c-on-primary">Key Benefits of <span>Osous Al-Taqnia's </span>DLP Solutions</h2>
								<p class="p-r-sm c-on-primary">Organizations implementing our DLP solutions gain comprehensive protection through:</p>
								<div class="spacer-3x"></div>
								<ul class="list" role="list">
									<li class="list-item-tick">
										<div class="t-r-md c-on-primary">Hybrid Environment Protection across physical and virtual infrastructure</div>
									</li>
									<li class="list-item-tick">
										<div class="t-r-md c-on-primary">Real-Time Security Alerts for immediate threat response</div>
									</li>
									<li class="list-item-tick">
										<div class="t-r-md c-on-primary">Centralized Management from a single dashboard</div>
									</li>
									<li class="list-item-tick">
										<div class="t-r-md c-on-primary">CentralizedFinancial Loss Prevention against costly data breaches Management from a single dashboard</div>
									</li>
									<li class="list-item-tick">
										<div class="t-r-md c-on-primary">Enhanced Brand Reputation through demonstrated data protection</div>
									</li>
									<li class="list-item-tick">
										<div class="t-r-md c-on-primary">Regulatory Compliance with Iraqi and international standards</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading a-c">
					<h2 class="h2 a-c place-center">Leading Cloud Backup Solutions in Iraq</h2>
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
							'text'    => 'Our Microsoft partnership integrates advanced cloud-based security solutions that provide robust protection for Microsoft 365 environments, ensuring sensitive data remains secure across all collaboration platforms.',
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
							'text' => 'With Acronis, we deliver cutting-edge backup and disaster recovery capabilities, ensuring business continuity for Iraqi organizations even in the face of ransomware attacks or data breaches.',
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
							'text' => 'Our Fortinet collaboration enables end-to-end security fabric solutions that protect data across networks, endpoints, and clouds with advanced threat intelligence and unified security management.',
						)
					);
					?>
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
							'text'    => 'Through Kaspersky, we incorporate world-class threat detection and response capabilities with advanced anti-malware technology to help Iraqi businesses identify and neutralize sophisticated threats.',
						)
					);
					?>
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
							'text'    => 'Our SentinelOne partnership brings autonomous AI-powered security with real-time threat hunting and automated remediation to protect Iraqi organizations against even the most advanced cyber threats.',
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
			'heading' => 'Why Choose Osous Al Taqnia?',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Unmatched Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Proven track record throughout Iraq with qualified experts who understand complex IT environments',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Dedicated Support',
					'textStyle'  => 'small',
					'text'       => 'Trustworthy, experienced engineers committed to solving your IT issues promptly',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Tailored Solutions',
					'textStyle'  => 'small',
					'text'       => 'Customized disaster recovery plans that meet your specific Iraqi business needs and budget',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Rigorous Testing',
					'textStyle'  => 'small',
					'text'       => 'Solutions automatically configured and tested daily to ensure they perform when needed',
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
			'title'      => 'Take Action to Protect Your Business Today',
			'text'       => 'Don\'t wait for disaster to strike—contact Osous Al Taqnia now to secure your Iraqi business with our comprehensive disaster recovery solutions. Protect your critical data, minimize downtime, and ensure business continuity with one call.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
