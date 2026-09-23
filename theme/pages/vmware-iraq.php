<?php
/**
 * VMware Partner in Iraq
 *
 * Read from the reference page vmware-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'VMware Partner in Iraq',
			'text'  => 'Virtualization, multi-cloud, and digital workspace solutions with certified VMware expertise across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized VMware Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified VMware partner in Iraq, helping organizations across Baghdad, Basra, and Erbil modernize their IT infrastructure. From virtual servers and cloud integration to remote desktop environments, our VMware-certified engineers provide complete lifecycle support—from consultation and licensing to implementation and long-term management.<br/>‍<br/>We design solutions that reduce cost, increase agility, and maintain full compliance with Iraq’s operational and data security requirements.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-vmware.webp',
						'alt'    => 'VMware',
						'srcset' => 'assets/images/partner-bg-vmware-p-500.webp 500w, assets/images/partner-bg-vmware.webp 652w',
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
					<h2 class="h2">About VMware</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>VMware is a global leader in virtualization and cloud infrastructure, trusted by enterprises, public institutions, and service providers worldwide. With technologies like vSphere, NSX, vSAN, and Horizon, VMware empowers organizations to build efficient, resilient, and secure digital environments across on-premises and cloud platforms.<br/>‍<br/>VMware’s ecosystem supports rapid innovation, multi-cloud flexibility, and secure remote access, making it ideal for forward-thinking businesses in Iraq.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">VMware Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'VMware vSphere – Server Virtualization',
						'prose' => '<ul role="list"> <li>Optimize hardware use with industry-leading hypervisor technology<br/></li> <li>Simplify server provisioning and lifecycle management<br/></li> <li>High availability, performance tuning, and seamless scaling</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'VMware NSX – Network Security & Micro-Segmentation',
						'prose' => '<ul role="list"> <li>Enforce <strong>Zero Trust</strong> with logical network segmentation<br/></li> <li>Streamline networking with <strong>software-defined infrastructure<br/></strong></li> <li>Protect critical apps with east-west traffic control</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'VMware Cloud Foundation – Multi-Cloud & Hybrid Environments',
						'prose' => '<ul role="list"> <li>Manage workloads across <strong>AWS, Azure, Google Cloud</strong>, and on-premises<br/></li> <li>Unified operations and automation across environments<br/></li> <li>Support hybrid work and elastic scaling with full visibility</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'VMware vSAN – Hyperconverged Infrastructure (HCI)',
						'prose' => '<ul role="list"> <li>Software-defined storage integrated with vSphere</li> <li>Cost-effective scalability for growing infrastructure</li> <li>Ideal for edge, branch offices, and remote Iraqi deployments</li> </ul> <ul role="list">‍</ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'VMware SRM – Disaster Recovery & Business Continuity',
						'prose' => '<ul role="list"> <li>Automated <strong>Site Recovery Manager</strong> plans<br/></li> <li>Rapid failover and recovery with minimal disruption<br/></li> <li>Compliance-ready solutions for critical operations</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'VMware Horizon – Virtual Desktop Infrastructure (VDI)',
						'prose' => '<ul role="list"> <li>Secure and scalable <strong>remote desktop access<br/></strong></li> <li>Manage users and permissions from a central console<br/></li> <li>Perfect for hybrid or remote workforces in Iraq</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with VMware in Iraq</h2>
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
						'text'  => 'Flexible learning platforms and remote access solutions',
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
						'text'  => 'Resilient and compliant virtualization of core systems',
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
						'text'  => 'High-availability infrastructure in remote and field sites',
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
						'text'  => 'Secure access to medical data and hospital systems',
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
						'text'  => 'Secure virtualization across ministries and public platforms',
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
			'heading' => 'Why Choose Osous Al Taqnia for VMware in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified VMware Partner',
					'text'  => 'We are an official VMware reseller in Iraq, offering full licensing, technical deployment, and authorized support.',
				),
				array(
					'title' => 'Local Technical Expertise',
					'text'  => 'Our VMware-certified engineers operate from Baghdad, Basra, and Erbil, offering deep knowledge of local infrastructure, power conditions, and bandwidth limitations.',
				),
				array(
					'title' => 'End-to-End Virtualization Services',
					'text'  => 'We handle assessment, architecture, migration, security, training, and support, ensuring a smooth transformation from legacy systems.',
				),
				array(
					'title' => 'Proven Track Record',
					'text'  => 'We’ve delivered successful VMware implementations for ministries, banks, universities, and energy companies, solving real operational challenges.',
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
			'title'      => 'Contact Osous Al Taqnia – VMware Partner in Iraq',
			'text'       => 'Want to modernize your infrastructure or deploy cloud-first digital workspaces?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
