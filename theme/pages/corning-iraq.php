<?php
/**
 * Corning Partner in Iraq
 *
 * Read from the reference page corning-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Corning Partner in Iraq',
			'text'  => 'High-performance fiber optic and copper cabling solutions for networks across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Corning Cabling Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified supplier of Corning network infrastructure products in Iraq, delivering trusted fiber optic and copper cabling solutions for telecom providers, enterprises, and industrial clients. Our team provides in-country technical guidance and support, ensuring that each deployment is efficient, high-performance, and future-ready.<br/>‍<br/>From structured cabling in corporate buildings to FTTH implementations and data center connectivity, our solutions are built on Corning quality and supported by local expertise at every stage.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-corning.webp',
						'alt'    => 'Corning',
						'srcset' => 'assets/images/partner-bg-corning-p-500.webp 500w, assets/images/partner-bg-corning.webp 652w',
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
					<h2 class="h2">About Corning</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Corning is a global innovator in optical communications and glass science, with more than 169 years of experience in material development and advanced networking technology. Its fiber and copper cabling systems power critical infrastructure worldwide—including telecom backbones, data centers, industrial systems, and smart cities.<br/>‍<br/>Corning’s products are known for their superior durability, high transmission capacity, and scalability, making them the ideal choice for businesses, governments, and service providers seeking long-term network performance.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Corning Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Copper Cabling Systems',
						'text'  => 'We offer a wide range of structured copper solutions, including Cat5E, Cat6, Cat6A, and Cat7 cabling options, suitable for modern LAN and voice/data networks. Our inventory includes patch cords, faceplates, keystone jacks, cable organizers, and both 24-port and 48-port patch panels to support comprehensive network infrastructure',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Fiber Optic Cabling Solutions',
						'text'  => 'Osous Al Taqnia supplies reliable and scalable Corning fiber solutions for core, access, and distribution layers. These include FTTH cables ranging from 2 to 48 cores in both single-mode and multimode, OM2/OM3/OM4 patch cords, pigtails, and pre-terminated panels. We also provide indoor and outdoor enclosures, breakout boxes, and Micro ODFs to support complex fiber installations.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Connectivity & Deployment Accessories',
						'text'  => 'We stock a full range of deployment tools and accessories to streamline installation and performance. This includes fiber splicing kits, enclosures, and essential tools for structured cabling projects across data centers, campus networks, and telecom environments. Our solutions address backbone, horizontal, and telecom room connectivity needs.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Corning in Iraq</h2>
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
						'text'  => 'We deliver secure and scalable cabling systems for schools, universities, and research institutions, supporting high-speed connectivity for digital learning and administration.',
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
						'text'  => 'Our fiber and cabling solutions help financial institutions build reliable, high-performance networks to handle secure transactions and data-intensive operations.',
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
						'text'  => 'In demanding environments like refineries and remote oil fields, we provide rugged, high-capacity fiber infrastructure built to withstand harsh conditions and support critical communications.',
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
						'text'  => 'Hospitals and medical centers rely on our structured cabling and fiber systems to ensure fast, uninterrupted access to digital records, imaging, and telemedicine services.',
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
						'text'  => 'We support government agencies with future-ready fiber and cabling infrastructure that enables secure communication, data exchange, and e-services across departments and locations.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Corning in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Corning Supply Partner',
					'text'  => 'We are an authorized distributor of Corning infrastructure products in Iraq, offering genuine materials with full product warranties and verified quality assurance.',
				),
				array(
					'title' => 'Local Insight with Technical Guidance',
					'text'  => 'With a deep understanding of Iraq’s unique network infrastructure requirements, we provide expert planning, product recommendations, and deployment support aligned with international best practices.',
				),
				array(
					'title' => 'End-to-End Delivery and Support',
					'text'  => 'From the initial consultation through delivery and installation, we manage every aspect of your structured cabling project. Whether supporting a telecom expansion or upgrading an enterprise network, our team ensures smooth execution.',
				),
				array(
					'title' => 'Ready Stock and Fast Delivery',
					'text'  => 'To meet tight project timelines, we maintain stocked inventory in Iraq and provide fast, reliable delivery services across Baghdad, Basra, Erbil, and surrounding areas.',
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
			'title'      => 'Contact Osous Al Taqnia – Corning Partner in Iraq',
			'text'       => 'Looking for structured cabling or fiber optic solutions for your network infrastructure in Iraq?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
