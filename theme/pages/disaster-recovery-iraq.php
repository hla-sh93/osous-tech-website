<?php
/**
 * Disaster Recovery
 *
 * Read from the reference page disaster-recovery-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Disaster Recovery',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit">
			<div class="grid-2 gap-1x">
				<div class="cell self-center-tablet">
					<div class="container c-surface-primary h-100 custom-section-1">
						<div class="content">
							<h2 class="h2 c-on-primary">Disaster Recovery Solutions in Iraq</h2>
							<p class="p c-on-primary-subtle">In Iraq's rapidly evolving technology landscape, data loss is simply unacceptable. The value of organizational data and the inability to recreate it makes disaster recovery solutions an essential component of any business continuity strategy. Whether you're facing natural disasters, hardware failures, data breaches, or ransomware attacks, having the right disaster recovery plan ensures your critical operations remain available and your Iraqi business stays resilient.</p>
						</div>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/disaster-recovery-iraqi-businesses.webp',
							'alt'    => 'disaster recovery solutions for Iraqi businesses ',
							'srcset' => 'assets/images/data-protection.Disaster-Recovery-p-500.webp 500w, assets/images/disaster-recovery-iraqi-businesses.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						),
						'image h-100 radii-container'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Key Benefits of Cloud Backup for Iraqi Businesses</h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/icon-planning.svg',
							'alt' => '',
						),
						'fill'    => true,
						'title'   => 'Comprehensive Assessment & Plannin',
						'text'    => 'Thorough evaluation of critical workloads with recovery plans tailored for Iraqi business operations',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-recovery.svg',
							'alt' => '',
						),
						'title' => 'Customized Recovery Objectives',
						'text'  => 'RTO and RPO strategies aligned with your specific budget, resources and infrastructure requirements',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-automated.svg',
							'alt' => '',
						),
						'title' => 'Automated Systems',
						'text'  => 'Rapid failover capabilities and sequential recovery protocols to minimize operational disruption',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-protection.svg',
							'alt' => '',
						),
						'title' => 'Certified Protection',
						'text'  => 'Daily-tested solutions with continuous data protection ensuring reliable performance when needed',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="heading center span-2">
				<h2 class="h2 a-c">Strategic <span class="highlight">Business Benefits </span>of <span class="thin">Implementing</span> Disaster Recovery Solutions</h2>
			</div>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-3">
				<div class="cell span-mobile-2">
					<h3>Data Loss Prevention</h3>
					<p>Protect against data loss from human error, technological failures, cyberattacks, or natural disasters by maintaining redundant storage at secondary locations across Iraq, significantly reducing business interruptions.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Minimized Downtime</h3>
					<p>Keep your Iraqi business running with minimal disruption during disaster events, allowing employees to continue their operations and preventing profit loss in Iraq's competitive market.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Single Point of Failure Protection</h3>
					<p>Eliminate vulnerabilities from single points of failure—whether devices, buildings, or geographic areas in Iraq—through redundancy and geographical distribution of your systems.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Enhanced Customer Satisfaction</h3>
					<p>Build customer loyalty and a positive reputation through quick recovery and minimal service disruptions, demonstrating reliability even during crisis situations to your Iraqi customer base.</p>
				</div>
				<div class="cell span-mobile-2">
					<h3>Rapid Service Restoration</h3>
					<p>Quickly restore mission-critical services to normal operations with a well-defined DR plan, drastically reducing recovery time after any disaster event affecting businesses in Iraq.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider c-border-neutral"></div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading a-c">
					<h2 class="h2 a-c place-center">Leading Cloud Backup Solutions in Iraq</h2>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/hpe-partner.svg',
								'alt' => 'HPE',
							),
							'link'    => home_url( '/hpe-iraq/' ),
							'fill'    => true,
							'text'    => 'Dell delivers high-performance infrastructure that forms the backbone of our network solutions in Iraq, ensuring scalability and long-term value with hardware that evolves alongside your business needs.',
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
							'text' => 'Offers unified protection for cloud, virtual and physical workloads with simplified backup, recovery, and replication capabilities through a single console.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/partner-white-veeam.svg',
								'alt' => 'Veeam',
							),
							'link' => home_url( '/veeam-iraq/' ),
							'text' => 'Provides reliable cloud backup solutions for Iraqi businesses with seamless Microsoft ecosystem integration, enterprise-grade security, and flexible recovery options for virtual machines, databases, and applications.',
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
								'src' => 'assets/images/partner-logo05.svg',
								'alt' => 'VMware',
							),
							'link'    => home_url( '/vmware-iraq/' ),
							'fill'    => true,
							'text'    => 'Dell delivers high-performance infrastructure that forms the backbone of our network solutions in Iraq, ensuring scalability and long-term value with hardware that evolves alongside your business needs.',
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
								'src' => 'assets/images/partner-dark-citrix.svg',
								'alt' => 'Citrix',
							),
							'link'    => home_url( '/citrix-iraq/' ),
							'fill'    => true,
							'text'    => 'Dell delivers high-performance infrastructure that forms the backbone of our network solutions in Iraq, ensuring scalability and long-term value with hardware that evolves alongside your business needs.',
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
			'heading' => 'Why Choose Osous Al Taqnia?',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Unmatched Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Proven track record throughout Iraq with qualified experts who understand complex IT environments',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Dedicated Support',
					'textStyle'  => 'small',
					'text'       => 'Trustworthy, experienced engineers committed to solving your IT issues promptly',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Tailored Solutions',
					'textStyle'  => 'small',
					'text'       => 'Customized disaster recovery plans that meet your specific Iraqi business needs and budget',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Rigorous Testing',
					'textStyle'  => 'small',
					'text'       => 'Solutions automatically configured and tested daily to ensure they perform when needed',
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
			'title'      => 'Take Action to Protect Your Business Today',
			'text'       => 'Don\'t wait for disaster to strike—contact Osous Al Taqnia now to secure your Iraqi business with our comprehensive disaster recovery solutions. Protect your critical data, minimize downtime, and ensure business continuity with one call.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
