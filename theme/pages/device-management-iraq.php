<?php
/**
 * Device Management Solutions
 *
 * Read from the reference page device-management-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Device Management Solutions',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/osous-device-management-iraq.webp',
						'alt'    => 'Osous Al Taqnia comprehensive device management solutions for Iraqi businesses',
						'srcset' => 'assets/images/cyber-security.DMS01-p-500.webp 500w, assets/images/osous-device-management-iraq.webp 652w',
						'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
					),
					'header-img-1'
				);
				?>
				<div class="cell self-center">
					<h2 class="h2 c-on-primary">Device Management Solutions in Iraq by Osous Al Taqnia</h2>
					<p class="p c-on-secondary">In today's hyper-connected business landscape, effective device management is no longer optional—it's essential. With the proliferation of diverse devices across multiple platforms, organizations in Iraq face unprecedented challenges in maintaining security, compliance, and operational efficiency. This comprehensive guide explores how Osous Al Taqnia's device management solutions provide the centralized control and visibility Iraqi businesses need to thrive in a digital-first world.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Device Management Solutions by Osous Al Taqnia Iraq</h2>
				<p class="p-r-sm">Osous Al Taqnia delivers tailored device management services crafted for Iraq's unique business environment. Our comprehensive solutions cover multiple deployment models and device types, ensuring complete coverage regardless of your organization's size or industry sector.</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'On-Premises Device Management',
						'text'    => 'Osous Al Taqnia\'s on-premises solutions provide complete control within your physical infrastructure for organizations with strict data sovereignty requirements, offering customized deployment and seamless integration with existing security systems across Iraq.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Cloud-Based Device Management',
						'text'  => 'Designed for remote and hybrid work environments, Osous Al Taqnia\'s cloud-based management enables remote administration, reduces infrastructure costs, and offers scalable resources that adapt to changing business needs in the Iraqi market.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Enterprise Device Management',
						'text'  => 'For large organizations throughout Iraq, Osous Al Taqnia delivers robust enterprise solutions featuring centralized policy management, role-based access control, and comprehensive reporting capabilities for complex device ecosystems.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Mobile Device Management',
						'text'  => 'Osous Al Taqnia\'s Mobile Device Management solutions secure mobile productivity with BYOD support, remote device control, app management, and specialized security policies to protect mobile endpoints for Iraqi businesses.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Unified Endpoint Management (UEM)',
						'text'  => 'Osous Al Taqnia\'s UEM provides a consolidated approach to managing all endpoints through a single interface, ensuring consistent policies and streamlined IT workflows across your entire device fleet in Iraq.',
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

	<section class="section">
		<div class="container-full">
			<div class="container padding-x-on-mobile">
				<div class="heading center">
					<h2 class="h2 a-c">Key Features That Drive Business Value</h2>
					<p class="p-r-sm a-c">Osous Al Taqnia's device management solutions incorporate advanced features designed to enhance security, improve efficiency, and reduce costs for Iraqi organizations.</p>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'title'   => 'Real-Time Monitoring and Management',
							'text'    => 'Osous Al Taqnia provides immediate visibility into device status, health, and compliance through live dashboards, automated alerts, and real-time policy enforcement capabilities that enable quick response to potential issues across Iraqi business environments.',
						)
					);
					?>
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/osous-device-management-iraq.webp',
							'alt'    => 'Real-time device monitoring system for Iraqi organizations',
							'srcset' => 'assets/images/cyber-security.DMS01-p-500.webp 500w, assets/images/osous-device-management-iraq.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						),
						'image h-100 row-tablet-1'
					);
					?>
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/osous-endpoint-dashboard-iraq.webp',
							'alt'    => 'Secure device management solutions in Iraq',
							'srcset' => 'assets/images/cyber-security.DMS04-p-500.webp 500w, assets/images/osous-endpoint-dashboard-iraq.webp 688w',
							'sizes'  => '(max-width: 767px) 100vw, 687.9688110351562px',
						),
						'image h-100'
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'title'   => 'Comprehensive Security Controls',
							'text'    => 'Protect sensitive business data with Osous Al Taqnia\'s multi-layered security approach, including device encryption, application control, network integration, and advanced threat detection mechanisms designed for Iraq\'s unique security landscape.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'title'   => 'Automated Deployment and Updates',
							'text'    => 'Reduce IT workload with zero-touch deployment, automated patching, scheduled maintenance, and bandwidth-optimized update distribution across your device ecosystem throughout Iraq.',
						)
					);
					?>
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/iot-device-management-iraq.webp',
							'alt'    => 'IoT device management for Iraqi enterprises',
							'srcset' => 'assets/images/cyber-security.DMS03-p-500.webp 500w, assets/images/iot-device-management-iraq.webp 688w',
							'sizes'  => '(max-width: 767px) 100vw, 687.9688110351562px',
						),
						'image h-100 row-tablet-5'
					);
					?>
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/osous-device-partners-iraq.webp',
							'alt'    => 'Osous Al Taqnia\'s technology partners for device management solutions',
							'srcset' => 'assets/images/cyber-security.DMS02-p-500.webp 500w, assets/images/osous-device-partners-iraq.webp 688w',
							'sizes'  => '(max-width: 767px) 100vw, 687.9688110351562px',
						),
						'image h-100'
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'title'   => 'Detailed Analytics and Reporting',
							'text'    => 'Make informed decisions with Osous Al Taqnia\'s customizable dashboards, compliance reporting, resource metrics, and historical trend analysis that deliver actionable insights for your Iraqi business operations.',
						)
					);
					?>
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
					<div class="cell self-center row-tablet-2">
						<div class="card">
							<h2>Our Strategic Technology Partners</h2>
							<p class="p">Osous Al Taqnia partners with industry leaders to enhance our device management solutions:</p>
						</div>
					</div>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon'  => array(
								'src' => 'assets/images/kaspersky-endpoint-security-iraq.svg',
								'alt' => 'Kaspersky-powered endpoint security for Iraqi businesses',
							),
							'link'  => home_url( '/kaspersky-iraq/' ),
							'title' => 'Kaspersky Partnership',
							'text'  => 'Our Kaspersky alliance enhances protection through integrated Endpoint Security and Security Center, providing multi-layered defense against cyber threats targeting Iraqi organizations.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon'  => array(
								'src' => 'assets/images/microsoft-endpoint-manager-iraq.svg',
								'alt' => 'Microsoft Endpoint Manager implementation by Osous Al Taqnia Iraq',
							),
							'link'  => home_url( '/microsoft-iraq/' ),
							'title' => 'Microsoft Partnership',
							'text'  => 'As a Microsoft partner, we implement Intune and Endpoint Manager for secure management of Windows, iOS, Android, and macOS devices with seamless Microsoft ecosystem integration tailored to Iraqi requirements.',
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
								'alt' => 'SentinelOne AI protection integrated with Osous Al Taqnia device management',
							),
							'link'    => home_url( '/sentinelone-iraq/' ),
							'fill'    => true,
							'title'   => 'SentinelOne Partnership',
							'text'    => 'We leverage SentinelOne\'s AI-powered technology for autonomous protection with real-time prevention, detection, and automated response against sophisticated attacks on Iraqi businesses.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Device Management in Iraq',
			'text'    => 'As a leading provider of IT solutions in Iraq, Osous Al Taqnia brings unique advantages:',
			'items'   => array(
				array(
					'title' => 'Local Expertise',
					'text'  => 'Deep understanding of Iraqi compliance requirements and business landscape',
				),
				array(
					'title' => 'Technical Excellence',
					'text'  => 'Certified professionals with advanced device management skills',
				),
				array(
					'title' => 'Vendor Relationships',
					'text'  => 'Strong partnerships with leading technology providers',
				),
				array(
					'title' => 'Flexible Engagement Models',
					'text'  => 'From fully managed services to co-managed solutions tailored to Iraqi business needs',
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
			'title'      => 'Take Control of Your Device Ecosystem Today',
			'text'       => 'In today\'s complex IT landscape, effective device management is crucial for security and productivity. Osous Al Taqnia\'s solutions provide the expertise and tools needed to transform device management from a challenge into a competitive advantage for Iraqi businesses.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
