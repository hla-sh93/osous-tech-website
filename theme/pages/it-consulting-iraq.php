<?php
/**
 * IT Consulting
 *
 * Read from the reference page it-consulting-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'IT Consulting',
		)
	);
	?>

	<section>
		<div class="container">
			<div class="grid-1">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/it-consultants-iraq.webp',
						'alt'    => 'Osous Al Taqnia IT consultants providing strategic technology solutions in Iraq',
						'srcset' => 'assets/images/it-consultants-iraq-p-500.webp 500w, assets/images/it-consultants-iraq-p-800.webp 800w, assets/images/it-consultants-iraq.webp 1080w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.982666015625px',
					),
					'image-custom-1'
				);
				?>
				<div class="container custom-1">
					<div class="modal c-surface-primary c-on-surface-primary">
						<div class="content">
							<div class="grid-2">
								<h2 class="h2">IT Consulting Services Iraq: Strategic Technology Solutions for Business Transformation</h2>
								<p class="p">In today’s rapidly evolving digital landscape, businesses in Iraq require robust IT infrastructure to maintain competitive advantage and operational efficiency. Professional IT consulting services from Osous Al Taqnia provide the strategic guidance necessary to optimize technology investments, enhance security posture, and drive sustainable business growth.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="heading center">
				<div class="grid-2">
					<h2 class="h2">Comprehensive IT Consulting Services for Iraqi Businesses</h2>
					<p class="p-r-sm">Modern Iraqi enterprises face complex challenges managing their IT ecosystems. Osous Al Taqnia delivers expert solutions across cloud migration, cybersecurity, infrastructure optimization, and disaster recovery to address these increasingly complex technological demands.t</p>
				</div>
			</div>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-3">
				<div class="cell">
					<h3>Strategic Cybersecurity Consulting</h3>
					<p>Osous Al Taqnia provides vulnerability assessments, security policies, threat detection, audits, training, and incident response planning to protect sensitive data while ensuring compliance.</p>
				</div>
				<div class="cell">
					<h3>Cloud Architecture & Migration</h3>
					<p>Expert consulting with readiness assessments, architecture planning, seamless migration, and cost optimization to help organizations gain scalability and business agility.</p>
				</div>
				<div class="cell">
					<h3>Disaster Recovery Solutions</h3>
					<p>Comprehensive consulting including impact analysis, recovery strategies, automated backups, and 24/7 monitoring to minimize downtime and maintain operational continuity.</p>
				</div>
				<div class="cell">
					<h3>Network Infrastructure Optimization</h3>
					<p>Network assessment, scalable architecture design, bandwidth optimization, and proactive maintenance services for reliable connectivity and performance.</p>
				</div>
				<div class="cell">
					<h3>IT Relocation Management</h3>
					<p>Comprehensive planning, equipment inventory, minimal-downtime strategies, and post-relocation testing to execute complex relocations with minimal disruption.</p>
				</div>
				<div class="cell">
					<h3>Structured Cabling Services</h3>
					<p>Site surveys, custom cabling designs, professional installation, rigorous testing, and ongoing maintenance support for reliable physical IT infrastructure.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider c-border-neutral"></div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Core Benefits of Professional IT Consulting Services in Iraq</h2>
				<p class="p-r-sm c-on-secondary a-c">Modern Iraqi enterprises face complex challenges managing their IT ecosystems. Osous Al Taqnia delivers expert solutions across cloud migration, cybersecurity, infrastructure optimization, and disaster recovery to address these increasingly complex technological demands.t</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '01',
						'title'  => 'Cost Optimization',
						'text'   => 'Eliminates unnecessary expenses, allocates resources efficiently, and reduces downtime for improved operational performance.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '02',
						'title'  => 'Operational Productivity',
						'text'   => 'Streamlines workflows, removes bottlenecks, and implements tools that maximize technology utilization.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '03',
						'title'  => 'Security Mitigation',
						'text'   => 'Implements multi-layered protection, ensures compliance, and establishes robust data security frameworks.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '04',
						'title'  => 'Strategic Planning',
						'text'   => 'Creates business-aligned technology roadmaps and implements sustainable IT practices for long-term value.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '05',
						'title'  => 'Business Scalability',
						'text'   => 'Builds flexible architectures and cloud resources that adapt quickly to changing market demands.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h3 class="h3">Our Strategic Technology Partners</h3>
				<p class="p-r-sm a-c">Builds flexible architectures and cloud resources that adapt quickly to changing market demands.</p>
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
						'caption' => 'Osous Al Taqnia leverages Aruba’s intelligent networking solutions to create secure, high-performance wireless environments optimized for Iraq’s business needs.',
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
						'caption' => 'Our partnership with HPE enables delivery of enterprise-grade servers, storage, and hybrid cloud solutions tailored to the unique requirements of Iraqi organizations.',
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
						'caption' => 'Osous Al Taqnia implements Dell’s comprehensive technology stack to provide Iraqi businesses with reliable computing solutions from endpoints to data centers.',
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
						'caption' => 'Our Veeam-powered backup solutions ensure critical data protection, business continuity, and disaster recovery capabilities for Iraqi enterprises of all sizes.',
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
						'caption' => 'As a Microsoft partner, we deploy scalable cloud services, productivity tools, and enterprise solutions to drive digital transformation across Iraqi businesses.',
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
						'caption' => 'Osous Al Taqnia utilizes Acronis’ advanced cyber protection solutions to integrate backup, disaster recovery, and security for comprehensive data safety.',
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
						'caption' => 'Osous Al Taqnia deploys Citrix virtual desktop and application delivery solutions to enable secure remote work capabilities for Iraqi businesses.',
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
						'caption' => 'We implement VMware’s virtualization technologies to help Iraqi organizations optimize infrastructure utilization, improve flexibility, and reduce operational costs.',
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
							'alt' => 'Huawei ',
						),
						'link'    => home_url( '/huawei-iraq/' ),
						'caption' => 'Our partnership with Huawei brings cutting-edge networking, cloud, and smart device solutions to meet the evolving needs of Iraq’s technology landscape.',
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
							'alt' => 'Fortinet',
						),
						'link'    => 'https://www.fortinet.com/',
						'caption' => 'Osous Al Taqnia implements Fortinet’s comprehensive security fabric to protect Iraqi organizations from advanced threats across all network edges.',
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
						'caption' => 'We deliver Kaspersky’s intelligence-driven security solutions to provide Iraqi businesses with protection against sophisticated cybersecurity threats and vulnerabilities.',
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
						'caption' => 'Osous Al Taqnia deploys SentinelOne’s autonomous endpoint protection platform to deliver AI-powered prevention, detection, and response against cyber threats.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo'    => array(
							'src' => 'assets/images/partner-white-hikvision.svg',
							'alt' => 'Hikvision',
						),
						'link'    => home_url( '/avigilon-iraq/' ),
						'caption' => 'We integrate Hikvision’s advanced surveillance technologies to enhance physical security and enable smart monitoring solutions for Iraqi organizations.',
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
			'heading'   => 'Why Choose Osous Al Taqnia for IT Consulting',
			'text'      => 'In Iraq’s competitive business environment, Osous Al Taqnia’s professional IT consulting services provide critical advantages:',
			'textClass' => '',
			'tagName'   => 'div',
			'items'     => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Specialized Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Access to skilled professionals with deep technical knowledge and experience in Iraq’s business landscape',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Objective Assessment',
					'textStyle'  => 'small',
					'text'       => 'Unbiased evaluation of current systems and potential improvements tailored to Iraqi business requirements',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Best Practices',
					'textStyle'  => 'small',
					'text'       => 'Implementation of proven methodologies and industry standards adapted for the Iraqi market',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Risk Reduction',
					'textStyle'  => 'small',
					'text'       => 'Identification and mitigation of technology-related business risks specific to Iraq’s operating environment',
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
			'title'      => 'Contact Osous Al Taqnia Today',
			'text'       => 'Ready to transform your business through strategic IT consulting? Contact Osous Al Taqnia today to schedule a comprehensive assessment of your technology environment. Our expert consultants are ready to help your Iraqi business optimize technology investments, enhance operational efficiency, strengthen security, and position for sustainable growth.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
