<?php
/**
 * Red Hat Partner in Iraq
 *
 * Read from the reference page red-hat-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Red Hat Partner in Iraq',
			'text'  => 'Enterprise open-source solutions, Linux platforms, and cloud-native technologies delivered with certified expertise across Iraq.',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Red Hat Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Red Hat Partner in Iraq, trusted by businesses and government institutions to modernize IT infrastructure and accelerate digital transformation. Through Red Hat’s enterprise-grade open-source portfolio, we deliver secure, scalable, and cost-efficient solutions that meet Iraq’s growing digital needs.<br/>‍<br/>With local operations in Baghdad, Basra, and Erbil, we provide Iraqi organizations with hands-on expertise, responsive support, and end-to-end project execution tailored to the region’s business environment.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-redhat.webp',
						'alt'    => 'Red Hat',
						'srcset' => 'assets/images/partner-bg-redhat-p-500.webp 500w, assets/images/partner-bg-redhat.webp 652w',
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
					<h2 class="h2">About Red Hat Solutions</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Red Hat is a global leader in enterprise open-source software, recognized for its Linux platforms, hybrid cloud technologies, containerization, and Kubernetes orchestration.<br/>‍<br/>Its portfolio covers key enterprise solutions such as Red Hat Enterprise Linux (RHEL), OpenShift, Ansible Automation, and Hybrid Cloud Infrastructure. These offerings provide businesses with secure, stable, and scalable platforms to run applications efficiently across physical servers, virtual environments, and multi-cloud setups.<br/>‍<br/>By operating with an open-source, community-driven model, Red Hat continues to deliver secure, innovative, and reliable solutions that power industries worldwide.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Red Hat Solutions Offered by Osous Al Taqnia in Iraq</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Red Hat Enterprise Linux (RHEL)',
						'prose' => '<ul role="list"> <li>Trusted and stable OS for mission-critical workloads</li> <li>Runs on physical, virtual, and cloud environments</li> <li>Built-in compliance, lifecycle management, and enterprise security</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Red Hat OpenShift – Kubernetes & Container Platform',
						'prose' => '<ul role="list"> <li>Enterprise-ready Kubernetes platform for DevOps and CI/CD</li> <li>Deploy and scale cloud-native apps across hybrid and multi-cloud environments</li> <li>Simplifies automation, security, and modernization of application development</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Red Hat in Iraq</h2>
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
						'text'  => 'Open-source platforms for universities, research, and e-learning scalability',
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
						'text'  => 'Secure, compliant infrastructure for core banking and mission-critical apps',
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
						'text'  => 'Hybrid solutions connecting remote field sites with central HQ systems',
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
						'text'  => 'Compliance-ready platforms for patient data and hospital management',
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
						'text'  => 'Secure open-source modernization for ministries and agencies',
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
			'heading' => 'Why Choose Osous Al Taqnia as Your Red Hat Partner in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Red Hat Reseller',
					'text'  => 'Officially licensed to deliver enterprise Linux, cloud, and automation solutions.',
				),
				array(
					'title' => 'Local Expertise & Bilingual Suppor',
					'text'  => 'Presence in Baghdad, Basra, and Erbil with Arabic and English assistance.',
				),
				array(
					'title' => 'End-to-End Project Delivery',
					'text'  => 'From consultation and licensing to deployment and long-term support.',
				),
				array(
					'title' => 'Cost-Effective Licensing',
					'text'  => 'Guidance on selecting the right Red Hat subscriptions to maximize performance and minimize costs.',
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
			'title'      => 'Contact Osous Al Taqnia – Red Hat Partner in Iraq',
			'text'       => 'Ready to modernize IT infrastructure, deploy hybrid cloud solutions, or enable open-source transformation?<br>',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
