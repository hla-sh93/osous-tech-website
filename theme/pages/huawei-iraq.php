<?php
/**
 * Huawei Partner in Iraq
 *
 * Read from the reference page huawei-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Huawei Partner in Iraq',
			'text'  => 'Scalable enterprise networking, wireless, and security solutions delivered with certified Huawei support across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Huawei Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Huawei partner in Iraq, delivering enterprise-grade networking, wireless, and cybersecurity solutions to both public institutions and private organizations. We serve clients across Baghdad, Erbil, and Basra with genuine Huawei hardware, licensed software, and comprehensive implementation support.<br/>‍<br/>Whether you're building a multi-campus network, optimizing core routing, or rolling out SD-WAN, our team ensures every Huawei solution is secure, scalable, and tailored to your operational and regulatory needs.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-huawel.webp',
						'alt'    => 'Huawei',
						'srcset' => 'assets/images/partner-bg-huawel-p-500.webp 500w, assets/images/partner-bg-huawel.webp 652w',
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
					<h2 class="h2">About Huawei</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Huawei is a global leader in ICT infrastructure and smart devices, offering high-performance technologies that drive digital transformation in enterprise and service provider environments.<br/>‍<br/>From campus networks and cloud-ready data centers to advanced wireless systems and firewalls, Huawei solutions are built for agility, automation, and security. Their products are trusted worldwide in education, government, telecom, and industrial sectors for delivering efficient and intelligent network infrastructure.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Huawei Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Huawei Switches',
						'text'  => 'We supply Huawei’s intelligent Layer 2/3 switches for data centers, campus networks, and SMB environments. These switches support high port density, PoE, VLANs, and traffic optimization. Options include chassis-based aggregation switches and compact models for agile deployment across enterprises in Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Huawei Routers',
						'text'  => 'Huawei routers are ideal for edge and core routing with SD-WAN capabilities. These devices support IP/MPLS, VPN, QoS, and high-throughput performance for secure and resilient network connectivity across multi-branch organizations and remote sites.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Huawei Wireless LAN (Wi-Fi 6)',
						'text'  => 'Huawei Wi-Fi 6 access points deliver seamless roaming, enhanced user capacity, and built-in security. Centralized wireless LAN controllers support large-scale deployments across universities, commercial buildings, and campuses.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Huawei Security Appliances',
						'text'  => 'Next-generation firewalls, IPS systems, and secure gateways provide comprehensive protection for enterprise networks. Powered by AI and threat analytics, these appliances offer deep inspection and automated responses to emerging cybersecurity threats.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Huawei CloudCampus Solution',
						'text'  => 'Huawei CloudCampus is a cloud-managed network solution designed for smart buildings and large campuses. It offers unified wired/wireless control, visualized policy enforcement, AI-powered troubleshooting, and centralized configuration across devices',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Huawei SDN and SD-WAN',
						'text'  => 'Huawei’s SD-WAN and SDN platforms enable dynamic routing, WAN optimization, and centralized management. These technologies support hybrid cloud environments and distributed businesses with improved bandwidth efficiency and network visibility.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Network Management Tools',
						'text'  => 'We deploy Huawei’s network management systems for real-time monitoring, fault detection, automation, and smart policy control. These tools simplify large-scale network operations across enterprises and government networks in Iraq.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Huawei in Iraq</h2>
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
						'text'  => 'Schools and universities benefit from Huawei’s Wi-Fi 6 solutions and cloud-based network controls, enabling seamless campus connectivity and simplified IT management.',
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
						'text'  => 'Banks and financial institutions use Huawei’s secure routers and firewalls to maintain high-performance networks that meet regulatory and cybersecurity requirements.',
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
						'text'  => 'From control rooms to remote field sites, Huawei’s industrial-grade networking equipment ensures stable communications and reliable data transmission in harsh environments.',
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
						'text'  => 'Hospitals and medical centers rely on Huawei’s network infrastructure to support secure access to patient records, real-time monitoring systems, and uninterrupted connectivity for clinical operations.',
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
						'text'  => 'We deliver secure switching, next-generation firewalls, and centralized network management systems to support reliable and protected communication across ministries and public offices.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Huawei in Iraq?',
			'items'   => array(
				array(
					'title' => 'Authorized Huawei Partner',
					'text'  => 'Osous Al Taqnia is a certified Huawei reseller, authorized to supply, configure, and support enterprise networking, wireless, and cybersecurity solutions with warranty and in-country technical backing.',
				),
				array(
					'title' => 'Local Insight with Enterprise Focus',
					'text'  => 'Our team brings a deep understanding of Iraq’s IT landscape, compliance needs, and infrastructure challenges. We tailor Huawei solutions to serve both public and private sector organizations at scale.',
				),
				array(
					'title' => 'End-to-End Project Support',
					'text'  => 'We offer full-service project execution—from product scoping and network design to deployment, training, and post-sales support—ensuring a stable and future-ready network foundation.',
				),
				array(
					'title' => 'Fast Delivery and Technical Assurance',
					'text'  => 'With offices in Baghdad, Basra, and Erbil, we guarantee fast delivery timelines, on-site technical support, and access to Huawei-certified engineers for smooth project execution.',
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
			'title'      => 'Contact Osous Al Taqnia – Huawei Partner in Iraq',
			'text'       => 'Looking for Huawei enterprise switches, wireless LAN systems, or security appliances for your network in Iraq?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
