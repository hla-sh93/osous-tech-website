<?php
/**
 * Cloud Backup
 *
 * Read from the reference page cloud-backup-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Cloud Backup',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/cloud-backup-iraq.webp',
						'alt'    => 'Osous Al Taqnia cloud backup infrastructure serving Iraqi businesses',
						'srcset' => 'assets/images/data-protection.Cloud-Backup01-p-500.webp 500w, assets/images/cloud-backup-iraq.webp 652w',
						'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
					),
					'header-img-1'
				);
				?>
				<div class="cell self-center">
					<h2 class="h2 c-on-primary">Cloud Backup Solutions in Iraq</h2>
					<p class="p c-on-secondary">Data protection is essential for Iraqi businesses today. Cloud backup offers a modern solution that transfers data to managed service providers, ensuring security and recoverability against threats. As more Iraqi organizations adopt these technologies to maintain competitiveness and compliance, understanding the benefits and implementation strategies becomes crucial for success in the local business landscape.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<h2 class="h2">Understanding Cloud Backup vs. Cloud Storage</h2>
				<p class="p">While cloud storage focuses on file accessibility and collaboration with limited security features, cloud backup is purpose-built for data protection with automated backups, advanced encryption, comprehensive versioning, and regulatory compliance capabilities. For Iraqi businesses, cloud backup provides substantially stronger protection for critical business data.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<h2 class="h2">Understanding Cloud Backup vs. Cloud Storage</h2>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/property-1enhanced-security.svg',
							'alt' => '',
						),
						'fill'    => true,
						'title'   => 'Enhanced Security and Compliance',
						'text'    => 'Cloud backup provides end-to-end encryption, compliance with Iraq\'s data protection laws, and robust protection against cyber threats.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1cost-efficiency.svg',
							'alt' => '',
						),
						'title' => 'Cost Efficiency',
						'text'  => 'Eliminates expenses for on-premises hardware, dedicated IT staff, and physical storage while converting capital expenditure to predictable operational costs.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1business-agility.svg',
							'alt' => '',
						),
						'title' => 'Business Agility',
						'text'  => 'Enables rapid deployment, seamless scaling, and quicker protection of new workloads to meet changing business demands.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1distaster-recovery.svg',
							'alt' => '',
						),
						'title' => 'Disaster Recovery',
						'text'  => 'Offers resilience through geographically dispersed data centers, rapid recovery capabilities, and protection against both physical and cyber threats relevant to Iraq\'s unique challenges.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="grid-2">
					<h2 class="h2 a-c place-center">Leading Cloud Backup Solutions in Iraq</h2>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon'  => array(
								'src' => 'assets/images/partner-white-veeam.svg',
								'alt' => 'Veeam',
							),
							'link'  => home_url( '/veeam-iraq/' ),
							'title' => 'Veeam Backup & Replication',
							'text'  => 'Offers unified protection for cloud, virtual and physical workloads with simplified backup, recovery, and replication capabilities through a single console.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon'  => array(
								'src' => 'assets/images/microsoft-endpoint-manager-iraq.svg',
								'alt' => 'Microsoft',
							),
							'link'  => home_url( '/microsoft-iraq/' ),
							'title' => 'Microsoft Azure Backup',
							'text'  => 'Provides reliable cloud backup solutions for Iraqi businesses with seamless Microsoft ecosystem integration, enterprise-grade security, and flexible recovery options for virtual machines, databases, and applications.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-dark-acronis.svg',
								'alt' => 'Acronis',
							),
							'link'    => home_url( '/acronis-iraq/' ),
							'fill'    => true,
							'title'   => 'Acronis Cloud Backup',
							'text'    => 'Dell delivers high-performance infrastructure that forms the backbone of our network solutions in Iraq, ensuring scalability and long-term value with hardware that evolves alongside your business needs.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<h2 class="h2 span-2">Understanding Modern Endpoint Security Challenges</h2>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-3">
				<div class="cell span-mobile-2">
					<h3>Assessing Requirements</h3>
					<p>Identify critical data, determine recovery objectives, evaluate compliance needs, consider bandwidth limitations, and assess existing infrastructure integration requirements.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Security Considerations</h3>
					<p>Data security remains a top concern for businesses in Iraq. When implementing cloud backup, ensure end-to-end encryption, strong access controls, regular audits, and evaluate provider security certifications.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Testing and Validation</h3>
					<p>Establish regular recovery testing schedules, document procedures, conduct scenario-based testing, verify application consistency, and continuously improve processes based on results.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider c-border-neutral"></div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Choose Osous Al-Taqnia for Your Cloud Backup Needs',
			'items'   => array(
				array(
					'title'     => 'Local Expertise',
					'textStyle' => 'plain',
					'text'      => 'Our team understands Iraq\'s unique business environment and compliance requirements.',
				),
				array(
					'title'     => 'Comprehensive Solutions',
					'textStyle' => 'small',
					'text'      => 'We offer end-to-end cloud backup services customized to your specific industry needs.',
				),
				array(
					'title'     => 'Dedicated Support',
					'textStyle' => 'small',
					'text'      => 'Our 24/7 Iraqi-based technical support ensures your data is always protected and recoverable.',
				),
				array(
					'title'     => 'Proven Track Record',
					'textStyle' => 'small',
					'text'      => 'We\'ve successfully implemented cloud backup solutions for hundreds of Iraqi businesses across multiple sectors.',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">The Strategic Value of Cloud Backup</h2>
						<p class="p">Cloud backup has evolved from a technical solution to a strategic business enabler for Iraqi organizations. By implementing robust cloud backup strategies, businesses can protect digital assets, ensure continuity, reduce costs, and maintain regulatory compliance. Whether you're a small business or large enterprise, solutions like those offered by Osous Al-Taqnia provide scalable, reliable protection tailored to Iraq's unique business environment.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/cloud-backup-security-iraq.webp',
							'alt'    => 'Cloud backup security features protecting Iraqi business data',
							'srcset' => 'assets/images/data-protection.Cloud-Backup02-p-500.webp 500w, assets/images/cloud-backup-security-iraq.webp 650w',
							'sizes'  => '(max-width: 767px) 100vw, 649.982666015625px',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell row-tablet-4">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/technical-support-team-iraq.webp',
							'alt'    => 'Osous Al Taqnia technical support team in Iraq',
							'srcset' => 'assets/images/data-protection.Cloud-Backup03-p-500.webp 500w, assets/images/technical-support-team-iraq.webp 650w',
							'sizes'  => '(max-width: 767px) 100vw, 649.982666015625px',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Take Action to Protect Your Business Data Today</h2>
						<p class="p">Don't wait until data loss impacts your business. Contact Osous Al-Taqnia today for a free consultation and discover how our cloud backup solutions can safeguard your critical business information while optimizing costs and ensuring compliance with Iraqi regulations.</p>
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
