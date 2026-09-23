<?php
/**
 * ICT solutions
 *
 * Read from the reference page ict-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Services',
			'title'   => 'ICT solutions',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<div class="cell">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/ict-solutions-iraq.webp',
							'alt'    => 'Osous Al Taqnia ICT solutions powering Iraqi business growth',
							'srcset' => 'assets/images/services.ICT-solutions06-p-500.webp 500w, assets/images/ict-solutions-iraq.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2 c-on-primary">Comprehensive ICT Solutions in Iraq</h2>
						<p class="p c-on-primary-subtle">In today’s rapidly evolving digital landscape, businesses in Iraq are increasingly turning to Information and Communication Technology (ICT) solutions to stay competitive, enhance operational efficiency, and drive sustainable growth. As a commercial hub known for innovation and forward-thinking business practices, Iraq’s enterprises require sophisticated ICT infrastructure that can support their ambitious goals while navigating the unique challenges of the region’s business environment.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Understanding ICT Solutions and Their Impact on Iraqi Businesses</h2>
			</div>
			<div class="grid-2">
				<p class="p">Information and Communication Technology encompasses the integrated systems that facilitate information processing, storage, retrieval, and communication across an organization.</p>
				<p class="p">In Iraq’s dynamic market, effective ICT solutions serve as the backbone for business operations, enabling companies to streamline processes, enhance communication, and make data-driven decisions that lead to measurable business outcomes.</p>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/split-section',
		null,
		array(
			'title' => 'Essential ICT Services for Iraqi Businesses',
			'items' => array(
				array(
					'title' => 'Network Infrastructure and Optimization',
					'text'  => 'Strong network infrastructure forms the backbone of effective ICT solutions in Iraq’s competitive landscape. Optimized networks provide seamless connectivity, reduced latency, efficient bandwidth usage, and support for modern technologies including IoT devices. Companies implementing these solutions experience notable productivity improvements as employees spend more time on value-adding activities.',
					'image' => array(
						'src'    => 'assets/images/secure-network-infrastructure-baghdad.webp',
						'alt'    => 'Secure network infrastructure implementation in Baghdad office',
						'srcset' => 'assets/images/services.ICT-solutions07-p-500.webp 500w, assets/images/services.ICT-solutions07-p-800.webp 800w, assets/images/secure-network-infrastructure-baghdad.webp 922w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 921.9791870117188px',
					),
				),
				array(
					'title' => 'Cloud Computing Solutions',
					'text'  => 'Iraqi businesses increasingly adopt cloud solutions for their unmatched flexibility and scalability. These services reduce hardware costs, enable on-demand resource scaling, improve disaster recovery capabilities, and facilitate collaboration. Cloud solutions particularly benefit companies in Iraq’s business hub that operate across multiple locations or work with international partners.',
					'image' => array(
						'src'    => 'assets/images/cloud-computing-services-iraq.webp',
						'alt'    => 'Cloud computing services for Iraqi enterprises',
						'srcset' => 'assets/images/services.ICT-solutions05-p-500.webp 500w, assets/images/services.ICT-solutions05-p-800.webp 800w, assets/images/cloud-computing-services-iraq.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 920.9896240234375px',
					),
				),
				array(
					'title' => 'Unified Communications Systems',
					'text'  => 'Effective communication is vital in Iraq’s diverse business environment. Unified systems integrate video conferencing, messaging, email, file sharing, and mobile access into one cohesive platform. This integration is especially valuable for overcoming language barriers and managing communication across different time zones.',
					'image' => array(
						'src'    => 'assets/images/unified-communications-iraq.webp',
						'alt'    => 'Unified communications systems for diverse Iraqi workplaces',
						'srcset' => 'assets/images/services.ICT-solutions04-p-500.webp 500w, assets/images/services.ICT-solutions04-p-800.webp 800w, assets/images/unified-communications-iraq.webp 922w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 921.9791870117188px',
					),
				),
				array(
					'title' => 'IT Management and Support',
					'text'  => 'Professional IT management ensures system reliability and optimal performance through continuous monitoring, preventative maintenance, rapid issue response, and strategic planning. Iraqi businesses gain access to specialized expertise without maintaining large internal IT teams, allowing them to focus on core operations.',
					'image' => array(
						'src'    => 'assets/images/it-management-support-iraq.webp',
						'alt'    => 'IT Management and Support',
						'srcset' => 'assets/images/services.ICT-solutions03-p-500.webp 500w, assets/images/services.ICT-solutions03-p-800.webp 800w, assets/images/it-management-support-iraq.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 920.9896240234375px',
					),
				),
				array(
					'title' => 'Cybersecurity Solutions',
					'text'  => 'As digital threats increase, robust security measures are essential for Iraq’s businesses. Comprehensive protection includes advanced firewalls, intrusion detection, endpoint security, employee training, and incident response capabilities. Iraq’s growing position as a financial center makes businesses particularly attractive targets, necessitating sophisticated protection.',
					'image' => array(
						'src'    => 'assets/images/cybersecurity-protection-iraq.webp',
						'alt'    => 'Cybersecurity protection for Iraqi businesses',
						'srcset' => 'assets/images/services.ICT-solutions01-p-500.webp 500w, assets/images/services.ICT-solutions01-p-800.webp 800w, assets/images/cybersecurity-protection-iraq.webp 922w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 921.9791870117188px',
					),
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h3">Our Strategic Technology Partners</h2>
			</div>
			<div class="partner-cards-wrapper">
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-aruba.svg',
							'alt' => 'Aruba',
						),
						'link'    => home_url( '/aruba-iraq/' ),
						'caption' => 'Aruba delivers intelligent edge networking solutions with secure, high-performance connectivity across wired, wireless, and WAN environments for Iraqi businesses.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-hewlett.svg',
							'alt' => 'HPE',
						),
						'link'    => home_url( '/hpe-iraq/' ),
						'caption' => 'HPE provides enterprise-grade servers, storage, and hybrid cloud solutions that optimize performance and efficiency while reducing operational costs.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/dell-partner.svg',
							'alt' => 'Dell',
						),
						'link'    => home_url( '/dell-iraq/' ),
						'caption' => 'Dell offers cutting-edge computing, storage, and networking solutions enabling digital transformation through reliable, scalable infrastructure for maximum productivity.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-veeam.svg',
							'alt' => 'Veeam',
						),
						'link'    => home_url( '/veeam-iraq/' ),
						'caption' => 'Veeam delivers robust backup and recovery solutions that safeguard critical information across virtual, physical, and cloud environments with minimal downtime.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/microsoft-endpoint-manager-iraq.svg',
							'alt' => 'Microsoft',
						),
						'link'    => home_url( '/microsoft-iraq/' ),
						'caption' => 'Aruba delivers intelligent edge networking solutions with secure, high-performance connectivity across wired, wireless, and WAN environments for Iraqi businesses.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-acronis-white.svg',
							'alt' => 'Acronis',
						),
						'link'    => home_url( '/acronis-iraq/' ),
						'caption' => 'Veeam delivers robust backup and recovery solutions that safeguard critical information across virtual, physical, and cloud environments with minimal downtime.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-citrix.svg',
							'alt' => 'Citrix',
						),
						'link'    => home_url( '/citrix-iraq/' ),
						'caption' => 'Citrix delivers digital workspace solutions for secure, remote access to applications and data, enhancing productivity regardless of device or location.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-vmware.svg',
							'alt' => 'VMware',
						),
						'link'    => home_url( '/vmware-iraq/' ),
						'caption' => 'VMware enables applications across any cloud environment, optimizing infrastructure efficiency with consistent operations, management, and security across hybrid deployments.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/huawel-partner.svg',
							'alt' => 'Huawei',
						),
						'link'    => home_url( '/huawei-iraq/' ),
						'caption' => 'Huawei offers advanced networking, storage, and cloud infrastructure solutions delivering high-performance connectivity and computing power for digital transformation initiatives.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-fortinet.svg',
							'alt' => '',
						),
						'link'    => home_url( '/fortinet-iraq/' ),
						'caption' => 'Fortinet secures Iraqi organizations with comprehensive cybersecurity solutions providing advanced threat intelligence and automated protection against sophisticated attacks.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/kaspersky-endpoint-security-iraq.svg',
							'alt' => 'Kaspersky',
						),
						'link'    => home_url( '/kaspersky-iraq/' ),
						'caption' => 'Kaspersky safeguards businesses with advanced endpoint protection and threat detection, preventing and remediating cyber threats while minimizing performance impact.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-sentinelone.svg',
							'alt' => 'SentinelOne',
						),
						'link'    => home_url( '/sentinelone-iraq/' ),
						'caption' => 'SentinelOne delivers AI-powered cybersecurity with autonomous prevention, detection, and response capabilities against ransomware and sophisticated attacks with minimal intervention.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section full c-surface-secondary">
		<div class="container padding-x-on-mobile">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Why Choose Osous Al Taqnia for ICT Solutions in Iraq</h2>
				<p class="p-r-sm c-on-secondary a-c">Leveraging provider discounts for committed usage through strategic planning and regular review of utilization rates helps Iraqi businesses achieve predictable cloud costs.</p>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '01',
						'title'  => 'Industry Experience and Expertise',
						'text'   => 'Osous Al Taqnia brings demonstrated experience across multiple industries, ensuring solutions address the unique challenges of your specific business environment.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '02',
						'title'  => 'Comprehensive Service Offerings',
						'text'   => 'We deliver end-to-end solutions rather than isolated components, preventing compatibility issues and ensuring cohesive technology implementation.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '03',
						'title'  => 'Proven Track Record',
						'text'   => 'Our extensive portfolio of successful projects and long-term client relationships demonstrates our reliability and consistent performance in the Iraqi market.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '04',
						'title'  => 'Localized Support Capabilities',
						'text'   => 'Osous Al Taqnia offers responsive local support with appropriate response times. In Iraq’s evolving business environment, our timely technical assistance minimizes disruptions to your operations.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container bg-17">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Ready to Transform Your Business with Advanced ICT Solutions?</h2>
				<p class="p c-on-secondary a-c">Contact Osous Al Taqnia today to discover how our tailored ICT services can address your specific business challenges and accelerate your growth in Iraq’s dynamic market. Our team of certified experts is ready to develop a customized technology roadmap that aligns with your strategic objectives.</p>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Transform Your Iraqi Business with Professional IT Services',
			'text'       => 'Choosing the right IT partner is key in Iraq’s fast-moving market. Whether you’re launching new services, upgrading infrastructure, or improving cybersecurity, Osous Al Taqnia offers the solutions and local support to help your business grow.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
