<?php
/**
 * Juniper Partner in Iraq
 *
 * Read from the reference page juniper-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Juniper Partner in Iraq',
			'text'  => 'Enterprise networking, security, and cloud solutions delivered with certified expertise across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Juniper Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified Juniper Partner in Iraq. As an authorized Juniper reseller and distributor, we provide enterprise clients, government entities, and public institutions across Iraq with the full range of Juniper products and solutions.<br/>‍<br/>With a strong presence in Baghdad, Basra, and Erbil, our team delivers expert consultation, fast local support, and end-to-end project implementation. From initial design and licensing to deployment and ongoing management, Osous Al Taqnia ensures smooth delivery of Juniper solutions tailored to your business needs.‍<br/>We design solutions that reduce cost, increase agility, and maintain full compliance with Iraq’s operational and data security requirements.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-juniper.webp',
						'alt'    => 'Juniper',
						'srcset' => 'assets/images/partner-bg-juniper-p-500.webp 500w, assets/images/partner-bg-juniper.webp 652w',
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
					<h2 class="h2">About Juniper Networks Solutions</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Juniper Networks is a global leader in advanced networking technologies, helping organizations build secure, scalable, and high-performance IT infrastructure. Its portfolio includes enterprise routing, switching, software-defined networking (SDN), and connected security for cloud-ready environments.<br/>‍<br/>By working with Juniper and Osous Al Taqnia, businesses in Iraq gain access to open and secure network solutions that lower costs, boost performance, and support long-term digital transformation.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Juniper Solutions Offered by Osous Al Taqnia in Iraq</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Juniper Network Switches',
						'prose' => '<ul role="list"> <li>Carrier-class Ethernet switches for offices, campuses, and data centers</li> <li>High-density hardware that reduces capital and operational costs</li> <li>Wide range of models, including EX series, PoE switches, and modular options</li> <li>Built to deliver reliable performance for businesses of any size</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Juniper Routers',
						'prose' => '<ul role="list"> <li>Powered by Junos OS for consistent and reliable performance</li> <li>Solutions for enterprises, mobile operators, and service providers</li> <li>Improve network resilience, traffic optimization, and scalability</li> <li>Easy integration for seamless transformation</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Juniper Firewalls',
						'prose' => '<ul role="list"> <li>High-performance protection for enterprise networks and cloud environments</li> <li>Unified Threat Management (UTM) to safeguard users and applications</li> <li>Advanced routing features with uncompromised security</li> <li>Multiple hardware options tailored to different industries</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Juniper Connected Security',
						'prose' => '<ul role="list"> <li>Extends protection to every point of connection: user, application, and infrastructure</li> <li>AI-driven visibility into devices and network health</li> <li>Defends against evolving threats without slowing down operations</li> <li>Combines networking with security for consistent protection</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Juniper in Iraq</h2>
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
						'text'  => 'Build secure campus networks and protect digital learning platforms',
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
						'text'  => 'Ensure compliance, safeguard transactions, and secure customer data',
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
						'text'  => 'Enable field connectivity, protect infrastructure, and ensure secure operations<br>',
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
						'text'  => ' Protect sensitive patient information with reliable digital healthcare systems',
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
						'text'  => 'Strengthen secure communications and modernize digital services',
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
						'title' => 'Telecom & Service Providers',
						'text'  => 'Deliver high-speed, reliable networking for better customer experiences',
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
			'heading' => 'Why Choose Osous Al Taqnia for Juniper in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Juniper Partner',
					'text'  => 'We are an officially certified Juniper reseller, authorized to deliver, configure, and support advanced networking, security, and data center solutions with warranty and trusted local backing.',
				),
				array(
					'title' => 'Local Presence with Market Insight',
					'text'  => 'With offices in Baghdad and Erbil, our team combines deep knowledge of Iraq’s IT environment with on-the-ground availability to support organizations across sectors.',
				),
				array(
					'title' => 'End-to-End Project Lifecycle',
					'text'  => 'From consultation and network design to licensing, deployment, and ongoing support, we provide complete lifecycle management to ensure long-term performance and reliability.',
				),
				array(
					'title' => 'lexible and Cost-Effective Licensing',
					'text'  => 'Our licensing models are designed with business needs in mind, offering scalable and cost-effective options to maximize value and simplify growth.',
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
			'title'      => 'Contact Osous Al Taqnia – Juniper Partner in Iraq',
			'text'       => 'Looking for a trusted Juniper Partner in Iraq? Osous Al Taqnia is here to help your organization design, deploy, and manage secure and scalable Juniper solutions.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
