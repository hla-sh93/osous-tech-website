<?php
/**
 * Sophos Partner in Iraq
 *
 * Read from the reference page sophos-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Sophos Partner in Iraq',
			'text'  => 'Enterprise cybersecurity, firewall protection, and endpoint security delivered with certified expertise across Iraq<br>',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Sophos Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Platinum Sophos Partner in Iraq. We provide organizations across Baghdad, Basra, Erbil, and beyond with the full range of Sophos security solutions—from firewalls and endpoint protection to cloud-based management and advanced threat intelligence.<br/>‍<br/>With a dedicated team for license renewals, order processing, and technical support, we ensure fast delivery, smooth renewals, and reliable after-sales service. Our local presence in Iraq enables us to deliver responsive support and tailored cybersecurity solutions for businesses of all sizes.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-sophos.webp',
						'alt'    => 'Sophos',
						'srcset' => 'assets/images/partner-bg-sophos-p-500.webp 500w, assets/images/partner-bg-sophos.webp 652w',
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
					<h2 class="h2">About Sophos Solutions</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>For over 30 years, Sophos has been protecting businesses worldwide, securing more than 100 million users in 150 countries. Its integrated security ecosystem covers every part of an organization’s IT environment—from laptops, desktops, and mobile devices to servers, email, and cloud platforms.<br/>‍<br/>As networks grow in complexity, Sophos focuses on making security simple, integrated, and effective. With Sophos Central, businesses in Iraq can manage their entire cybersecurity stack from a single cloud dashboard—reducing complexity while improving protection.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Sophos Solutions Offered by Osous Al Taqnia in Iraq</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Sophos Firewall (XG / XGS Series)',
						'prose' => '<ul role="list"> <li>Enterprise-grade protection with advanced intrusion prevention and threat detection</li> <li>Full visibility into applications, users, and risks across your network</li> <li>Integrated cloud management via Sophos Central</li> <li>Scalable security with options for wireless protection and connectivity modules</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Sophos Endpoint Protection (Intercept X & XDR)',
						'prose' => '<ul role="list"> <li>AI-powered defense against ransomware, malware, and zero-day threats</li> <li>Real-time intelligence sharing between firewalls and endpoints</li> <li>Advanced features like extended detection and response (XDR)</li> <li>Centralized control for desktops, servers, and mobile devices</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Sophos Email Security',
						'prose' => '<ul role="list"> <li>Cloud-based protection against phishing, spam, and malware</li> <li>Security awareness and phishing simulation tools</li> <li>Easy management through Sophos Central</li> <li>Builds a culture of email security across organizations</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Sophos Wireless & Switch Solutions',
						'prose' => '<ul role="list"> <li>Secure APX series wireless access points with encrypted connectivity</li> <li>High-performance Sophos Switches with VLAN tagging and link aggregation</li> <li>Centralized cloud management for Wi-Fi and network switching</li> <li>Reliable and scalable infrastructure for growing businesses</li> </ul> <ul role="list">‍</ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Sophos Unified Threat Management (UTM)',
						'prose' => '<ul role="list"> <li>All-in-one modular appliance with firewall, VPN, intrusion prevention, and web filtering</li> <li>Simplified interface for policy creation and risk control</li> <li>Clear reporting for performance and security insights</li> <li>Cost-effective solution for small to midsize businesses</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Sophos Central Cloud Management',
						'prose' => '<ul role="list"> <li>Manage all Sophos products from one cloud-based console</li> <li>Automated alerts, firmware updates, and backup management</li> <li>Advanced reporting tools for full visibility into network and security activity</li> <li>Flexible, scalable platform for distributed IT environments</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Sophos in Iraq</h2>
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
						'text'  => 'Provide secure campus-wide connectivity and safeguard student and staff devices.',
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
						'text'  => ' Ensure compliance, protect transactions, and secure sensitive customer data.',
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
						'text'  => 'Protect industrial networks (SCADA/ICS) and enable secure operations across remote sites.',
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
						'text'  => 'Safeguard patient records, support telehealth, and ensure uninterrupted access to medical systems.',
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
						'text'  => 'Secure communication systems, protect citizen data, and simplify IT security management.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-telecommunications_1.svg',
							'alt' => '',
						),
						'title' => 'Telecom & ISPs',
						'text'  => 'Strengthen backbone infrastructure and deliver reliable, secure services.',
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
			'heading' => 'Why Choose Osous Al Taqnia as Your Sophos Partner in Iraq?',
			'items'   => array(
				array(
					'title' => 'Authorized Sophos Partner',
					'text'  => 'We are an officiall certified Sophos Reseller in Iraq, equipped with certified engineers to deliver, configure, and support advanced cybersecurity solutions backed by vendor assurance.',
				),
				array(
					'title' => 'Local Expertise and Responsive Support',
					'text'  => 'With a strong local presence, our team provides fast technical assistance, license renewals, and on-the-ground expertise tailored to the needs of organizations across Iraq.',
				),
				array(
					'title' => 'End-to-End Security Lifecycle',
					'text'  => 'From consultation and solution design to deployment, training, and ongoing maintenance, we cover the full cybersecurity lifecycle to ensure strong and consistent protection.',
				),
				array(
					'title' => 'Cost-Effective Licensing and Promotions',
					'text'  => 'We provide flexible licensing options with access to Sophos bundles, promotions, and renewal advantages helping businesses get the most value from their investment.',
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
			'title'      => 'Contact Osous Al Taqnia – Sophos Partner in Iraq',
			'text'       => 'Ready to strengthen your cybersecurity with Sophos in Iraq?<br>Our experts are here to guide you in selecting, deploying, and managing the right solutions for your business.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
