<?php
/**
 * HCI Solutions
 *
 * Read from the reference page hci-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Infrastructure',
			'title'   => 'HCI Solutions',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit">
			<div class="grid-2 gap-1x">
				<div class="cell self-center-tablet">
					<div class="container c-surface-primary h-100 custom-section-1">
						<div class="content">
							<h2 class="h2 c-on-primary">Transforming Enterprise IT in Iraq</h2>
							<p class="p c-on-primary-subtle">This next-generation technology creates a simplified yet powerful foundation for modern enterprise applications, cloud services, and digital transformation initiatives across Iraq. With HCI, organizations can build, scale, and manage their IT infrastructure with unprecedented agility and efficiency. Osous Al-Taqnia brings these cutting-edge solutions to Iraqi businesses ready to modernize their IT infrastructure.</p>
						</div>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/hci-diagram-iraq.webp',
							'alt'    => 'Hyper-converged infrastructure diagram showing simplified IT management for Iraqi enterprises',
							'srcset' => 'assets/images/infrastructure.HCI-Solutions01-p-500.webp 500w, assets/images/hci-diagram-iraq.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						),
						'image h-100 radii-container'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="h-75">
		<div class="container-full h-100 bg-img-9">
			<div class="grid-2 h-100">
				<div class="flex-v a-c-c self-end-tablet">
					<div class="container fit">
						<?php
						get_template_part(
							'includes/parts/photo-card',
							null,
							array(
								'title' => 'Why Iraqi Businesses Are Adopting HCI Solutions',
								'text'  => 'Traditional data center architectures no longer meet the demands of Iraq\'s dynamic business environment. They require substantial capital investment, demand specialized expertise across multiple domains, create scaling challenges through disruptive upgrades, and often result in resource silos and underutilization. Iraqi businesses are turning to HCI solutions to address these critical infrastructure limitations and position themselves for digital success.',
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Real-World Applications of HCI Solutions in Iraq</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Data Center Modernization',
						'text'    => 'HCI provides an ideal foundation for Iraqi organizations looking to transition from legacy infrastructure to modern, software-defined data centers. This transformation enables greater efficiency, improved performance, and enhanced agility while reducing overall complexity.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Virtual Desktop Infrastructure (VDI)',
						'text'  => 'HCI excels at supporting virtual desktop deployments, enabling secure, seamless access to desktops and applications from any device across Iraq. Organizations can rapidly scale their VDI environment to accommodate remote work initiatives while maintaining centralized control and security.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Hybrid Cloud Deployment',
						'text'  => 'By bridging on-premises infrastructure with cloud services, HCI creates a flexible foundation for hybrid cloud strategies in Iraq. Organizations can easily move workloads between environments, leverage cloud services, and optimize resource allocation based on cost and performance requirements.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Business Continuity and Disaster Recovery',
						'text'  => 'HCI solutions integrate robust data protection capabilities, including automated failover and rapid recovery. This built-in resilience ensures critical business operations can continue even in the face of hardware failures or site-wide disasters common in challenging environments.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Application Modernization',
						'text'  => 'Modern applications demand infrastructure that can keep pace with rapid development cycles and changing requirements. HCI accelerates application deployment and updates while providing the flexibility to support diverse workloads from traditional enterprise applications to cloud-native services for Iraqi businesses.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-2 m-b-0">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Transforming Your Iraqi Infrastructure with HCI Solutions</h2>
			</div>
			<div class="grid-2">
				<div class="cell">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/hci-dashboard-iraq.webp',
							'alt'    => 'Dashboard showing unified management of HCI solutions for Iraqi businesses',
							'srcset' => 'assets/images/hci-dashboard-iraq-p-500.webp 500w, assets/images/hci-dashboard-iraq.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						)
					);
					?>
					<div class="spacer-1x"></div>
					<p class="p c-on-secondary">Digital transformation requires adaptable infrastructure. HCI provides agility, efficiency, and scalability for modern applications in Iraq without compromising control, costs, or security.</p>
				</div>
				<div class="cell">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/hci-implementation-team-iraqi-business.webp',
							'alt'    => 'HCI implementation team working with Iraqi business clients',
							'srcset' => 'assets/images/infrastructure.HCI-Solutions03-p-500.webp 500w, assets/images/hci-implementation-team-iraqi-business.webp 638w',
							'sizes'  => '(max-width: 767px) 100vw, 637.9861450195312px',
						)
					);
					?>
					<div class="spacer-1x"></div>
					<p class="p c-on-secondary">Organizations adopting HCI can scale workloads easily, accelerate development cycles, optimize IT resource allocation, reduce operational complexity, and build a foundation for innovation throughout Iraq.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section top-tight">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading center">
					<h2 class="h2 a-c place-center">Our <span class="highlight">Strategic Partners</span></h2>
					<p class="p-r-sm a-c">Osous Al-Taqnia partners with industry leaders to deliver premium HCI solutions across Iraq.</p>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-logo05.svg',
								'alt' => 'VMware',
							),
							'link'    => home_url( '/vmware-iraq/' ),
							'fill'    => true,
							'text'    => 'Our VMware partnership enables delivery of industry-leading virtualization technology that powers our HCI solutions. We implement optimized VMware vSAN environments for simplified management and create flexible hybrid cloud deployments for Iraqi enterprises.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/dell-partner.svg',
								'alt' => 'Dell',
							),
							'link' => home_url( '/dell-iraq/' ),
							'text' => 'As a certified Dell partner, we provide Dell EMC VxRail solutions offering faster deployment, continuous innovation through PowerEdge platforms, and seamless VMware integration for Iraqi businesses requiring enterprise-grade virtualized applications.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/partner-white-hewlett.svg',
								'alt' => 'HPE',
							),
							'link' => home_url( '/hpe-iraq/' ),
							'text' => 'As an authorized HPE partner, we deliver HPE SimpliVity solutions combining infrastructure, advanced data services, and AI-driven operations at reduced costs. Our certified specialists provide built-in data protection, one-click updates, and HPE InfoSight predictive analytics.<br>',
						)
					);
					?>
					<div class="row-tablet-3">
						<?php
						get_template_part(
							'includes/parts/card',
							null,
							array(
								'outline' => false,
								'icon'    => array(
									'src' => 'assets/images/partner-dark-citrix.svg',
									'alt' => 'Citrix',
								),
								'link'    => home_url( '/citrix-iraq/' ),
								'fill'    => true,
								'text'    => 'Through our Citrix partnership, we enhance HCI offerings with VDI solutions providing secure access to applications from any device. We help Iraqi organizations implement scalable remote work environments while maintaining centralized control and security.',
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Choose Osous Al-Taqnia for HCI Solutions',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Local Iraqi Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Our certified professionals understand Iraqi business challenges and provide tailored local support.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Comprehensive Service Portfolio',
					'textStyle'  => 'small',
					'text'       => 'We deliver end-to-end services from assessment to implementation and ongoing management.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Proven Track Record',
					'textStyle'  => 'small',
					'text'       => 'Our successful implementations across Iraqi sectors consistently deliver measurable efficiency and performance gains.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Strategic Partnerships',
					'textStyle'  => 'small',
					'text'       => 'Our certified partnerships with leading HCI providers ensure superior technical expertise and investment support.',
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
			'title'      => 'Transform Your Iraqi Business with Professional IT Services',
			'text'       => 'Choosing the right IT partner is key in Iraq’s fast-moving market. Whether you’re launching new services, upgrading infrastructure, or improving cybersecurity, Osous Al-Taqnia offers the solutions and local support to help your business grow.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
