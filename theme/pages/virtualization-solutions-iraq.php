<?php
/**
 * Virtualization Solutions: Maximize Your IT Infrastructure Efficiency in Iraq
 *
 * Read from the reference page virtualization-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Virtualization Solutions: Maximize Your IT Infrastructure Efficiency in Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/hci-dashboard-iraq.webp',
						'alt'    => 'Virtualization infrastructure diagram showing multiple VMs on a single server in Iraq',
						'srcset' => 'assets/images/hci-dashboard-iraq-p-500.webp 500w, assets/images/hci-dashboard-iraq.webp 652w',
						'sizes'  => '(max-width: 652px) 100vw, 652px',
					),
					'header-img-1'
				);
				?>
				<div class="cell self-center">
					<h2 class="h2 c-on-primary">The Power of Virtualization for Modern Iraqi Businesses</h2>
					<p class="p c-on-secondary">In today's competitive landscape, maximizing IT resources while minimizing costs is essential for business growth in Iraq. Virtualization technology transforms infrastructure management by enabling organizations to run multiple virtual environments on a single physical server. Most Iraqi businesses operate servers at just 15-30% capacity, creating significant waste. Virtualization eliminates this inefficiency, allowing each virtual environment to function independently with its own operating system and applications, dramatically improving resource utilization and operational effectiveness.v</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Enterprise-Grade Solutions for Business Applications</h2>
			</div>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<div class="cell">
					<h3 class="h3">Centralized Backup Solutions</h3>
					<p class="p">Protect your data with Azure Backup's cost-effective, one-click protection for VMs, files, and SQL databases. Enjoy offloaded on-premises backup, unlimited data transfer, centralized monitoring, and automatic storage management.</p>
				</div>
				<div class="cell">
					<h3 class="h3">Centralized Backup Solutions</h3>
					<p class="p">Focus on website features rather than infrastructure with Azure App Service. Host web applications, REST APIs, and mobile back ends through a unified interface with support for multiple development languages and effortless scaling.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider c-border-neutral"></div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-2">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Business Benefits of Implementing Virtualization Solutions</h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '01',
						'title'  => 'Reduced Capital and Operating Expenses',
						'text'   => 'By consolidating servers onto fewer physical machines, Iraqi organizations decrease hardware costs by up to 50%, reduce data center space requirements, lower power and cooling expenses, and minimize maintenance costs.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '02',
						'title'  => 'Enhanced Business Continuity',
						'text'   => 'Virtualization enables rapid VM backup and restoration, quick recovery times, simplified disaster recovery planning, and reduced downtime during maintenance through features like live migration.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '03',
						'title'  => 'Improved IT Operational Efficiency',
						'text'   => 'Administrative productivity increases through centralized management of virtual resources, automated deployment, streamlined maintenance processes, and simplified troubleshooting.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '04',
						'title'  => 'Greater Business Agility',
						'text'   => 'Organizations in Iraq gain competitive advantages through faster application deployment, simplified testing environments, improved response to changing business demands, and seamless scalability.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container padding-x-on-mobile">
			<div class="grid-2">
				<div class="cell self-center">
					<h2 class="h2 a-c place-center">Leading Virtualization Platforms for Iraqi Businesses</h2>
					<p class="p-r-sm a-c">Osous Al Taqnia partners with industry leaders to deliver premium storage solutions across Iraq and the Middle East.</p>
				</div>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'icon'    => array(
							'src' => 'assets/images/partner-white-vmware.svg',
							'alt' => 'VMware',
						),
						'link'    => home_url( '/vmware-iraq/' ),
						'text'    => 'VMware leads the Iraqi market with enterprise-grade solutions that run multiple VMs on physical servers via a hypervisor. Benefits include flexibility for VM deployment, reduced hardware costs, simplified management through vCenter, and increased reliability with vMotion.',
						'outline' => false,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'icon'    => array(
							'src' => 'assets/images/microsoft-endpoint-manager-iraq.svg',
							'alt' => 'Microsoft ',
						),
						'link'    => home_url( '/microsoft-iraq/' ),
						'text'    => 'Microsoft\'s Hyper-V provides comprehensive virtualization capabilities that integrate with existing Microsoft infrastructure. This lightweight hypervisor supports multiple operating systems on single servers and offers cost-effective virtualization with Windows licensing benefits.',
						'outline' => false,
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
								'alt' => 'Citrix ',
							),
							'link'    => home_url( '/citrix-iraq/' ),
							'text'    => 'Citrix excels in secure application and desktop delivery across Iraq\'s business environments. Their platform specializes in remote access to virtual workspaces, optimized performance over challenging networks, and centralized management—ideal for organizations prioritizing workforce mobility and consistent application experience.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Choose Osous Al Taqnia for Your Structured Cabling Needs in Iraq',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Proven Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Our team of certified virtualization specialists has implemented successful solutions across diverse Iraqi business sectors.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Customized Approach',
					'textStyle'  => 'small',
					'text'       => 'We design virtualization strategies tailored to your specific business requirements and existing infrastructure.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Comprehensive Support',
					'textStyle'  => 'small',
					'text'       => 'From initial assessment through implementation and ongoing management, our support team ensures smooth operations.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Local Understanding',
					'textStyle'  => 'small',
					'text'       => 'As an Iraqi company, we understand the unique challenges and requirements of the local business environment.',
				),
			),
		)
	);
	?>

	<section class="h-75">
		<div class="container-full bg-img-16">
			<div class="grid-2 h-100">
				<div class="flex-v a-c-c self-end-tablet">
					<div class="container fit">
						<?php
						get_template_part(
							'includes/parts/photo-card',
							null,
							array(
								'title' => 'Transform Your IT Infrastructure TodayTransform Your IT Infrastructure Today',
								'text'  => 'Virtualization fundamentally changes how Iraqi organizations manage IT resources, delivering cost reduction, enhanced performance, and operational flexibility through efficient hardware utilization. Don\'t let underutilized servers drain your budget—virtualize your infrastructure to reduce downtime, enhance resiliency, and maximize investments.',
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
