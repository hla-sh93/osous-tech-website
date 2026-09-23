<?php
/**
 * Veeam Partner in Iraq
 *
 * Read from the reference page veeam-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Veeam Partner in Iraq',
			'text'  => 'Reliable backup, replication, and recovery solutions for secure data protection across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Veeam Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified Veeam partner in Iraq, helping organizations protect their critical workloads across Baghdad, Basra, Erbil, and other regions. From physical servers to cloud workloads, we deliver complete data protection and disaster recovery using Veeam’s enterprise-grade tools.<br/>‍<br/>Our solutions help ensure business continuity, fast recovery, and compliance with Iraqi data protection needs—all while reducing complexity and downtime.</p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-veeam.webp',
						'alt'    => 'Veeam',
						'srcset' => 'assets/images/partner-bg-veeam-p-500.webp 500w, assets/images/partner-bg-veeam.webp 652w',
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
					<h2 class="h2">About Veeam</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Veeam is a global leader in Modern Data Protection and Cloud Data Management, trusted by over 450,000 businesses worldwide. Its unified platform supports backup, replication, and recovery across virtual machines, physical systems, SaaS platforms, and cloud environments—controlled via a single intuitive console.<br/>‍<br/>Known for its speed, scalability, and security, Veeam delivers the agility organizations need to safeguard their data and maintain continuous operations.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Veeam Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Veeam Availability Suite',
						'prose' => '<ul role="list"> <li>Combines <strong>Veeam Backup &amp; Replication</strong> with <strong>Veeam ONE<br/><br/></strong></li> <li>Centralized backup, replication, monitoring, and alerting<br/><br/></li> <li>Real-time analytics, SLA tracking, and proactive performance insights</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Veeam Backup & Replication',
						'prose' => '<ul role="list"> <li>Instant recovery for virtual machines and physical infrastructure<br/></li> <li>End-to-end encryption and ransomware defense<br/></li> <li>Replication to cloud or off-site environments for added redundancy</li> <li>Full support for <strong>VMware</strong>, <strong>Hyper-V</strong>, and physical servers<br/></li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Veeam ONE',
						'prose' => '<ul role="list"> <li>Full infrastructure monitoring and reporting<br/><br/></li> <li>Dashboards, alerts, and anomaly detection<br/><br/></li> <li>Resource optimization and compliance visibility</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Veeam Backup for Small Business',
						'prose' => '<ul role="list"> <li>Lightweight, cost-effective solution for smaller offices<br/><br/></li> <li>Protect files, apps, and full images with minimal setup<br/><br/></li> <li>Scalable as your business expands</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Veeam in Iraq</h2>
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
						'text'  => 'Backup solutions for universities and research centers',
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
						'text'  => 'Daily backups and secure archiving for compliance and continuity',
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
						'text'  => 'Backup of field data, SCADA systems, and HQ infrastructure',
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
						'text'  => 'Protection for EMRs, imaging systems, and healthcare databases',
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
						'text'  => 'Centralized, encrypted backups for public-sector IT',
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
			'heading' => 'Why Choose Osous Al Taqnia for Veeam in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Veeam Partner',
					'text'  => 'We are a licensed Veeam reseller offering official software, integration, and ongoing technical support in Iraq.',
				),
				array(
					'title' => 'Local Expertise & Deployment Support',
					'text'  => 'Our in-country teams in Baghdad, Basra, and Erbil ensure fast deployment, infrastructure alignment, and responsive support.',
				),
				array(
					'title' => 'Tailored Backup Strategies',
					'text'  => 'Whether you need full data center backup or a simple office setup, we customize every Veeam deployment to your specific business and regulatory needs.',
				),
				array(
					'title' => 'Full Lifecycle Services',
					'text'  => 'We manage everything from assessment and configuration to backup verification, recovery testing, and routine monitoring, ensuring your systems stay protected.',
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
			'title'      => 'Contact Osous Al Taqnia – Veeam Partner in Iraq',
			'text'       => 'Need a secure and scalable backup and disaster recovery solution?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
