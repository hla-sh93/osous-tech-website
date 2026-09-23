<?php
/**
 * Aruba Partner in Iraq
 *
 * Read from the reference page aruba-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Aruba Partner in Iraq',
			'text'  => 'Certified wireless, switching, and network security solutions tailored for growing businesses in Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Aruba Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an official Aruba partner in Iraq, delivering advanced wireless networking, SD-WAN, and secure infrastructure solutions that meet the demands of businesses across the country.<br/>‍<br/>From strategic consultation to installation and support, our local team helps organizations implement Aruba technology with confidence, whether it's improving campus Wi-Fi, enabling secure remote work, or building a zero-trust environment.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-aruba.webp',
						'alt'    => 'Aruba',
						'srcset' => 'assets/images/partner-bg-aruba-p-500.webp 500w, assets/images/partner-bg-aruba.webp 652w',
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
					<h2 class="h2">About Aruba Networks</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Aruba, a Hewlett Packard Enterprise company, is a leader in intelligent edge and cloud-managed networking. Aruba’s solutions are trusted globally for enabling secure, high-performance connectivity with AI-powered automation and threat response.<br/>‍<br/>Their systems support everything from small branch offices to large-scale Iraqi enterprises, streamlining IT operations across wired, wireless, and hybrid environments.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Aruba Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Aruba Wireless Access Points – Instant On Series',
						'prose' => '<p>Fast, scalable Wi-Fi tailored to businesses in Iraq: </p> <ul role="list"> <li><strong>AP11</strong> – Compact Wi-Fi for small offices and retail</li> <li>‍<strong>AP12</strong> – Enhanced capacity for clinics and coffee shops </li> <li>‍<strong>AP15</strong> – Ideal for startups and high-usage spaces </li> <li>‍<strong>AP17</strong> – Outdoor-rated for courtyards and open areas </li> <li>‍<strong>AP22</strong> – Wi-Fi 6 for schools, hotels, and coworking spaces </li> <li>‍<strong>AP11D</strong> – Wall-mount for compact hospitality installations</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Aruba Network Switches',
						'prose' => '<p>Performance-driven switching for Iraq’s modern IT setups:</p> <ul role="list"> <li>Non-blocking throughput up to 100GbE </li> <li>Aruba AOS-CX operating system </li> <li>Options for fixed or modular deployments </li> <li>Optimized for campuses, branches, and data centers</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Aruba Access Point Controllers & SD-WAN Gateways',
						'prose' => '<p>Cloud-controlled wireless and SD-WAN tools:</p> <ul role="list"> <li>Aruba 7205, 7240, and 7010 series controllers </li> <li>Aruba 9004 &amp; 9012 for SD-WAN/MPLS </li> <li>Features: zero-touch setup, LTE options, and smart roaming</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Aruba ClearPass – Network Access Control (NAC)',
						'prose' => '<p>Secure access built on zero-trust policies:</p> <ul role="list"> <li>Dynamic policy enforcement </li> <li>Real-time security monitoring </li> <li>Segmentation based on user identity</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Aruba EdgeConnect SD-WAN',
						'prose' => '<p>Support your hybrid teams with a cloud-ready WAN:</p> <ul role="list"> <li>Application-aware performance tuning </li> <li>Smart routing and traffic prioritization </li> <li>Easy-to-manage cloud-based control panel</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Aruba in Iraq</h2>
			</div>
			<div class="grid-3 carousel-in-mobile">
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
						'text'  => 'Campus-wide Wi-Fi and digital learning support',
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
						'text'  => 'Secure backups in hybrid infrastructures across remote Iraqi sites',
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
						'text'  => 'Secure access for patient and device data',
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
						'text'  => 'High-security, mission-critical networks',
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
			'heading' => 'Why Choose Osous Al Taqnia for Aruba in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Aruba Partner',
					'text'  => 'We are an authorized Aruba reseller in Iraq, providing genuine HPE-backed solutions and expert guidance tailored to each client’s network requirements.',
				),
				array(
					'title' => 'Local Expertise',
					'text'  => 'Our team understands the unique infrastructure, compliance needs, and operational challenges in Iraq. We design Aruba network solutions that align with your organization’s growth plans and IT environment.',
				),
				array(
					'title' => 'End-to-End Deployment',
					'text'  => 'From the initial site survey and planning phase through to product configuration, installation, integration, and monitoring, we manage every aspect of your Aruba deployment across Iraq.',
				),
				array(
					'title' => 'Competitive Pricing & Support',
					'text'  => 'Osous Al Taqnia offers official product warranties and fast delivery throughout Iraq. Our support packages are flexible and cost-effective, ensuring you receive the right balance of value, performance, and service reliability.',
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
			'title'      => 'Contact Osous Al Taqnia – Acronis Partner in Iraq',
			'text'       => 'Want to transform your business network with Aruba solutions?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
