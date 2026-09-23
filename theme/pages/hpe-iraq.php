<?php
/**
 * HPE Partner in Iraq
 *
 * Read from the reference page hpe-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'HPE Partner in Iraq',
			'text'  => 'Certified HPE infrastructure, storage, and cloud solutions for enterprises across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized HPE Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified Hewlett Packard Enterprise (HPE) partner in Iraq, offering comprehensive infrastructure and cloud solutions designed for digital transformation.<br/>‍<br/>Our local team delivers intelligent edge-to-cloud technologies that help Iraqi organizations modernize their IT systems, enhance security, and improve performance, while aligning with local infrastructure realities.<br/>‍<br/>Whether you're upgrading data storage, optimizing campus networks, or deploying secure hybrid cloud platforms, we deliver HPE solutions tailored to the Iraqi market.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-hpe.webp',
						'alt'    => 'HPE',
						'srcset' => 'assets/images/partner-bg-hpe-p-500.webp 500w, assets/images/partner-bg-hpe.webp 652w',
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
					<h2 class="h2">About HPE</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Hewlett Packard Enterprise (HPE) is a global innovator in data-driven IT solutions that enable digital transformation across industries.<br/>HPE’s portfolio supports secure, agile operations through:<br/> • AI-powered infrastructure<br/> • Edge-to-cloud integration<br/> • Scalable storage<br/> • Real-time analytics and automation<br/>‍<br/>From large enterprises to public sector institutions, HPE helps organizations across Iraq move faster, work smarter, and stay resilient in hybrid environments.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">HPE Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'HPE Data Storage Solutions',
						'prose' => '<p>Advanced storage for today’s data challenges: </p> <ul role="list"> <li>Unified DataOps for simplified management </li> <li>Seamless edge-to-cloud data mobility </li> <li>AI-driven storage for analytics and enterprise applications </li> <li>Flexible storage for government, education, oil &amp; gas, and banking sectors in Iraq</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'HPE MyRoom – Secure Collaboration Platform',
						'prose' => '<p>Cloud-based communication platform for remote teams:</p> <ul role="list"> <li>Always-on conferencing and collaboration </li> <li>AES 256-bit encrypted communication </li> <li>Ideal for IT training, government meetings, and field teams in Iraq</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'HPE Servers & IT Infrastructure',
						'prose' => '<p>Full range of computing, networking, and wireless systems:</p> <ul role="list"> <li><strong>HPE Servers:</strong> High-performance for critical workloads </li> <li>‍<strong>HPE Switches:</strong> Enterprise-grade switching for network scalability </li> <li>‍<strong>HPE Wireless:</strong> Campus-wide connectivity for offices and institutions </li> <li>‍<strong>HPE Workstations &amp; Accessories:</strong> Trusted hardware for field and office use</li> </ul> <p>All solutions are cloud-optimized with AI for automation, monitoring, and predictive insights.</p>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with HPE in Iraq</h2>
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
						'text'  => 'Collaborative tools and server infrastructure for universities and schools',
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
						'text'  => 'Scalable storage and secure network setups for financial institutions',
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
						'text'  => 'Resilient compute and storage systems for high-volume data and operations',
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
						'text'  => 'Always-on infrastructure for patient data, compliance, and continuity',
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
						'text'  => 'Secure and compliant digital infrastructure for public services',
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
			'heading' => 'Why Choose Osous Al Taqnia for HPE in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified HPE Expertise',
					'text'  => 'Our engineers are fully trained and certified by Hewlett Packard Enterprise, ensuring expert-level design, configuration, deployment, and support that align with Iraq’s infrastructure and compliance requireme',
				),
				array(
					'title' => 'Local Insight with Global Standards',
					'text'  => 'With deep knowledge of the Iraqi market and operational challenges, we deliver HPE solutions that meet international best practices while being tailored to local IT needs and regulatory frameworks.',
				),
				array(
					'title' => 'End-to-End HPE Services',
					'text'  => 'We provide complete support across the full solution lifecycle. This includes initial needs assessment, HPE product procurement, deployment and configuration, and long-term maintenance and optimization.',
				),
				array(
					'title' => 'Enterprise-Focused Delivery',
					'text'  => 'We work with Iraq’s largest sectors—such as oil & gas, finance, government, and education—to deliver robust and scalable HPE systems that support critical operations, long-term growth, and digital transformation.',
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
			'title'      => 'Contact Osous Al Taqnia – HPE Partner in Iraq',
			'text'       => 'Looking to upgrade your IT infrastructure with certified HPE solutions?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
