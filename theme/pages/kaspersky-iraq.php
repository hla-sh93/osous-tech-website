<?php
/**
 * Kaspersky Partner in Iraq
 *
 * Read from the reference page kaspersky-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Kaspersky Partner in Iraq',
			'text'  => 'Certified endpoint, cloud, and hybrid cybersecurity solutions trusted by enterprises, government entities, and small businesses across Iraq.',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Kaspersky Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified Kaspersky partner and licensed reseller in Iraq, delivering advanced cybersecurity solutions to organizations of all sizes. From endpoint protection to hybrid cloud security, we provide:<br/>- Genuine Kaspersky licenses<br/>- Professional deployment and integration<br/>- Tailored security strategies for Iraq’s infrastructure<br/>- Local support teams in Baghdad, Basra, and Erbil<br/>Our approach ensures that every Kaspersky solution aligns with Iraq’s evolving digital ecosystem and regulatory standards.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-kaspersky.webp',
						'alt'    => 'Kaspersky',
						'srcset' => 'assets/images/partner-bg-kaspersky-p-500.webp 500w, assets/images/partner-bg-kaspersky.webp 652w',
						'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<div class="cell">
					<h2 class="h2">About Kaspersky</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Kaspersky is a global leader in cybersecurity with more than 25 years of experience protecting over 400 million users and 270,000 businesses worldwide. Its AI-powered platforms defend against malware, ransomware, and advanced cyber threats across endpoints, cloud workloads, and hybrid environments.<br/><br/>Key features include:<br/>- Real-time threat intelligence<br/>- Vulnerability scanning<br/>- Ransomware mitigation<br/>- Proactive analytics for high-risk environments</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Kaspersky Solutions & Licenses Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Kaspersky Total Security',
						'text'  => 'Comprehensive protection for workstations, servers, and mobile devices. Includes malware defense, phishing protection, secure file storage, password management, and encrypted backups. Ideal for small to medium-sized businesses in Iraq seeking all-in-one licensed coverage.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Kaspersky Internet Security',
						'text'  => 'Multi-layered protection for desktops, laptops, and mobile devices against spyware, trojans, and network threats. Features browser protection and lightweight performance. Available with licensed annual or multi-year plans.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Kaspersky Cloud Security',
						'text'  => 'A cloud-delivered endpoint security solution for Windows, macOS, Android, and iOS. Offers real-time protection, secure remote access, and centralized cloud management—ideal for remote teams across Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Kaspersky Hybrid Cloud Security',
						'text'  => 'Built for mixed environments, securing virtualized, cloud, and on-premises workloads. Compatible with AWS, Azure, VMware, and OpenStack. Licensed deployments guarantee compliance for hybrid enterprise infrastructures.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Kaspersky Threat Intelligence Services',
						'text'  => 'Provides deep insights into attacker tactics, vulnerabilities, and emerging threats. Licensed access integrates with SIEM and SOC systems, improving detection speed and incident response.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c"> Industries We Support with Licensed Kaspersky in Iraq</h2>
			</div>
			<div class="grid-3 grid-to-carousel">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-telecommunications.svg',
							'alt' => '',
						),
						'title' => 'Education',
						'text'  => 'Protecting schools, universities, and online platforms.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1banking.svg',
							'alt' => '',
						),
						'title' => 'Finance & Banking',
						'text'  => 'Securing financial transactions and customer data.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-oil.svg',
							'alt' => '',
						),
						'title' => 'Oil & Gas',
						'text'  => 'Safeguarding SCADA/ICS systems with industrial cybersecurity.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1healthcare.svg',
							'alt' => '',
						),
						'title' => 'Healthcare',
						'text'  => 'Defending hospitals and clinics against data breaches.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1government.svg',
							'alt' => '',
						),
						'title' => 'Government & Public Sector',
						'text'  => 'Supporting agencies with compliance-ready, licensed Kaspersky software.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Kaspersky in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Kaspersky Partner',
					'text'  => 'Official licenses with deployment strategies tailored to Iraq’s needs.',
				),
				array(
					'title' => 'Local Insight with Security Expertise',
					'text'  => 'Deep knowledge of Iraq’s IT landscape, compliance, and sector-specific risks.',
				),
				array(
					'title' => 'End-to-End Services',
					'text'  => 'From licensing to deployment, monitoring, and incident response.',
				),
				array(
					'title' => 'Flexible Licensing & Ongoing Support',
					'text'  => 'Competitive annual and multi-year plans with updates, renewals, and rapid local support.',
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
			'title'      => 'Secure Your Business with Kaspersky in Iraq',
			'text'       => 'Protect your organization with licensed Kaspersky cybersecurity solutions from Osous Al Taqnia.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
