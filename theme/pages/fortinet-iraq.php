<?php
/**
 * Fortinet Partner in Iraq
 *
 * Read from the reference page fortinet-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Fortinet Partner in Iraq',
			'text'  => 'Certified Fortinet firewalls, secure networking, and cybersecurity solutions for businesses across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Fortinet Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Fortinet reseller in Iraq, serving businesses, government entities, and service providers across Baghdad, Basra, and Erbil.<br/>‍<br/>We supply genuine Fortinet hardware, software licenses, and professional services, covering the full project lifecycle from assessment and solution design to deployment, training, and ongoing support. Our focus is on delivering stable, compliant, and well-managed security environments.</p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-fortinet.webp',
						'alt'    => 'Fortinet',
						'srcset' => 'assets/images/partner-bg-fortinet-p-500.webp 500w, assets/images/partner-bg-fortinet.webp 652w',
						'sizes'  => '(max-width: 652px) 100vw, 652px',
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
					<h2 class="h2">About Fortinet Solutions</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Fortinet is a global cybersecurity provider trusted by organizations worldwide. Its integrated Security Fabric protects networks, endpoints, applications, and cloud environments through a unified and centrally managed approach.<br/>‍<br/>Fortinet solutions help organizations improve visibility, reduce cyber risk, and manage security more efficiently across on-premises and cloud environments.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Fortinet Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'FortiGate Next-Generation Firewalls (NGFW)',
						'prose' => '<ul role="list"> <li>Firewalls for branch offices, enterprises, data centers, and ISPs</li> <li>Advanced threat protection, secure VPNs, and traffic inspection</li> <li>Powered by FortiGuard threat intelligence</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Unified Threat Management (UTM)',
						'prose' => '<ul role="list"> <li>Integrated antivirus, IPS, web filtering, and email security</li> <li>Centralized control through a single platform</li> <li>Suitable for small and mid-sized organizations</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'FortiWeb – Web Application Firewall',
						'prose' => '<ul role="list"> <li>Protection for web applications and APIs</li> <li>Defense against OWASP Top 10 and application-level attacks</li> <li>Ideal for portals, e-commerce, and online services</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'FortiSwitch – Secure Network Switching',
						'prose' => '<ul role="list"> <li>Secure Layer 2/3 switching for access and core networks</li> <li>Central management through FortiGate</li> <li>Consistent policy enforcement across the network</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'FortiAP – Secure Wireless Access Points',
						'prose' => '<ul role="list"> <li>Managed Wi-Fi for offices, campuses, hotels, and enterprises</li> <li>Secure access with centralized control</li> <li>Seamless integration with Fortinet security infrastructure</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'FortiCloud – Cloud Management',
						'prose' => '<ul role="list"> <li>Centralized monitoring and management of Fortinet devices</li> <li>Designed for multi-branch and distributed environments</li> <li>Simplified configuration, reporting, and visibility</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Fortinet in Iraq</h2>
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
						'text'  => 'Protecting campus networks, online learning platforms, and student access.',
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
						'text'  => 'Securing financial systems with controlled access, encryption, and threat prevention.<br>',
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
						'text'  => 'Protecting remote operations, field sites, and critical infrastructure.<br>',
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
						'text'  => 'Safeguarding patient data and hospital networks through segmentation and monitoring.<br>',
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
						'text'  => 'Supporting secure, centrally managed infrastructure for ministries and public entities.<br>',
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
			'heading' => 'Why Choose Osous Al Taqnia for Fortinet in Iraq?',
			'items'   => array(
				array(
					'title' => 'Authorized Fortinet Partner<br>',
					'text'  => 'Osous Al Taqnia is a certified Fortinet reseller, providing access to genuine products, approved architectures, and manufacturer-aligned implementation standards.<br>',
				),
				array(
					'title' => 'Certified Engineers with Local Experience',
					'text'  => 'Our Fortinet-certified engineers have hands-on experience delivering cybersecurity projects across Iraq’s public and private sectors.',
				),
				array(
					'title' => 'Strong Local Presence Across Iraq',
					'text'  => 'With teams in Baghdad, Basra, and Erbil, we ensure fast response, on-site support, and a strong understanding of local infrastructure and requirements.',
				),
				array(
					'title' => 'Complete Lifecycle Delivery & Support',
					'text'  => 'We manage the full cybersecurity lifecycle from assessment and deployment to training, maintenance, and ongoing support, ensuring stable and secure operations.',
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
			'title'      => 'Contact Osous Al Taqnia – Fortinet Partner in Iraq',
			'text'       => 'Looking to secure your network or upgrade your firewall infrastructure in Iraq?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
