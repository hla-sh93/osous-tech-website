<?php
/**
 * Backup Solutions
 *
 * Read from the reference page backup-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Data protection',
			'title'   => 'Backup Solutions',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Backup and Replication Solutions for Iraqi Businesses</h2>
						<p class="p">Cloud optimization is the strategic process of correctly allocating cloud resources to maximize performance and minimize costs. It ensures your business workloads receive precisely the right resources as application needs evolve, eliminating waste from over-provisioning while maintaining optimal performance.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/backup-replication-iraq.webp',
							'alt'    => 'Enterprise backup and replication solutions for Iraqi businesses',
							'srcset' => 'assets/images/data-protection.Backup-Solutions01-p-500.webp 500w, assets/images/backup-replication-iraq.webp 652w',
							'sizes'  => '(max-width: 652px) 100vw, 652px',
						),
						'image h-50'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">The True Cost of Data Loss and Downtime for Iraqi Organizations</h2>
				<p class="p a-c">Data is your organization's most valuable asset. When inaccessible or lost, it creates devastating consequences including financial losses, employee productivity decline, client dissatisfaction, missed sales opportunities, and reputation damage.</p>
				<div class="spacer-3x"></div>
				<div class="divider c-border-neutral"></div>
				<div class="spacer-3x"></div>
			</div>
			<div class="grid-2">
				<p class="t-b-lg a-c span-2 justify-center">Studies paint a concerning picture of the business landscape:</p>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'text' => 'Organizations lose approximately 312 hours annually through unplanned downtime',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'text'    => '53% of businesses face technology-related performance issues on a daily basis',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'text'    => '65% of companies report that human error is the most common cause of downtime incidents',
						'outline' => true,
					)
				);
				?>
				<div class="row-tablet-3-4">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'text' => 'The average time to recover from a significant data loss incident is 26 hours',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container">
				<div class="heading center">
					<h2 class="h2 a-c">Comprehensive Backup and Replication Solutions from Osous Al Taqnia</h2>
					<p class="p a-c">Enterprise-grade backup and disaster recovery solutions provide peace of mind through cutting-edge technology designed to protect your most valuable digital assets. Our solutions include:</p>
				</div>
				<div class="grid-2">
					<div class="card c-white">
						<h3 class="h3">Ransomware Distribution</h3>
						<ul class="list" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">Network Attached Storage (NAS) systems for flexible, accessible backup</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Storage Area Networks (SANs) for high-performance enterprise storage</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Virtual environment protection ensuring all virtual machines are properly secured</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Physical infrastructure backup for traditional server environments</div>
							</li>
						</ul>
					</div>
					<div class="card c-white">
						<h3 class="h3">Real-Time Protection and Recovery</h3>
						<ul class="list" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">Real-time data archiving capabilities for continuous protection</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Easy restoration mechanisms to quickly recover from incidents</div>
							</li>
						</ul>
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
			'heading' => 'Key Benefits of Modern Backup Solutions for Iraqi Businesses',
			'text'    => 'Full data protection is achieved through the strategic combination of hardware and software elements working in harmony. Osous Al-Taqnia\'s backup solutions provide:',
			'items'   => array(
				array(
					'title'     => 'Real-time data protection',
					'textStyle' => 'plain',
					'text'      => 'keeping information backed up continuously',
				),
				array(
					'title'     => 'Reduced financial risk',
					'textStyle' => 'small',
					'text'      => 'by preventing costly data loss scenarios',
				),
				array(
					'title'     => 'Accelerated recovery times',
					'textStyle' => 'small',
					'text'      => 'minimizing business disruption',
				),
				array(
					'title'     => 'Automated backup processes',
					'textStyle' => 'small',
					'text'      => 'ensuring consistent protection',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container-full img-4">
			<div class="container fit">
				<div class="heading self-center span-2">
					<h2 class="h2 c-on-primary">Our Strategic Technology Partners</h2>
				</div>
				<div class="grid-2">
					<div>
						<?php
						get_template_part(
							'includes/parts/photo-card',
							null,
							array(
								'icon' => array(
									'src' => 'assets/images/partner-white-veeam.svg',
									'alt' => 'Veeam ',
								),
								'link' => home_url( '/veeam-iraq/' ),
								'text' => 'Osous Al Taqnia partners with Veeam to deliver industry-leading backup and replication solutions for virtual, physical, and cloud-based workloads. Veeam\'s Availability Suite enables us to provide Iraqi businesses with reliable data protection, fast recovery options, and comprehensive visibility across your entire IT environment, ensuring 24/7/365 availability of critical business services.',
							)
						);
						?>
					</div>
					<div>
						<?php
						get_template_part(
							'includes/parts/photo-card',
							null,
							array(
								'icon' => array(
									'src' => 'assets/images/partner-email-security02.svg',
									'alt' => 'Acronis',
								),
								'link' => home_url( '/acronis-iraq/' ),
								'text' => 'As an Acronis partner, Osous Al Taqnia offers next-generation cyber protection solutions that integrate reliable backup with advanced anti-malware technologies. Acronis\' innovative approach combines data protection and cybersecurity into a single solution, helping Iraqi organizations defend against modern threats while ensuring fast, flexible recovery options for all workloads.',
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
			<div class="grid-2">
				<div class="card c-surface-neutral">
					<div class="flex-v a-l-c h-100">
						<h2 class="h2 thin">As Iraq's trusted data security partner, Osous Al Taqnia brings unmatched expertise to your protection challenges:</h2>
					</div>
				</div>
				<div>
					<div class="card c-white">
						<div class="t-b-lg">Local presence with deep understanding of Iraq's business environment</div>
					</div>
					<div class="card c-surface-neutral">
						<div class="t-b-lg">Certified backup and replication specialists trained in the latest technologies</div>
					</div>
					<div class="card c-white">
						<div class="t-b-lg">Vendor-agnostic approach recommending solutions based on your needs, not partnerships</div>
					</div>
					<div class="card c-surface-neutral">
						<div class="t-b-lg">24/7 monitoring and support from our Iraq-based operations center</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container bg-12">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Take Action Now to Protect Your Iraqi Business</h2>
				<div class="_w-max-200">
					<div class="divider gradient-1"></div>
				</div>
				<div class="spacer-3x"></div>
				<p class="p c-on-secondary a-c">Don't wait for disaster to strike. Implement robust backup and replication solutions today to ensure business continuity and maintain your competitive edge in Iraq's evolving digital economy.</p>
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
