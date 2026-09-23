<?php
/**
 * Data Protection
 *
 * Read from the reference page data-protection-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Data Protection',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/data-protection-iraqi-businesses.webp',
					'alt'    => 'data protection solutions for Iraqi businesses',
					'srcset' => 'assets/images/data-protection-iraqi-businesses-p-500.webp 500w, assets/images/data-protection-iraqi-businesses-p-800.webp 800w, assets/images/data-protection-iraqi-businesses.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.96533203125px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start"><strong>Data Protection Solutions for Iraq's Businesses</strong></h2>
				<p class="p-r-sm c-on-primary self-center">In today's digital landscape, businesses across Iraq face increasing challenges in securing sensitive information against sophisticated cyber threats. Osous Al Taqnia provides enterprise-grade security solutions tailored to businesses of all sizes, protecting your valuable data from both external threats and internal vulnerabilities.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<div class="hflex">
					<h2 class="h2"><strong>Why Data Protection Matters for Iraqi Businesses</strong></h2>
					<p class="p-r-sm">The digital transformation accelerating across Iraq's business sector brings tremendous opportunities—but also significant risks. Consider these relevant statistics:</p>
				</div>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'stat'    => '35%',
						'title'   => 'The cost of cyberattacks in Iraq increased by 35% in 2024 compared to previous years',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'stat'    => '73%',
						'title'   => '73% of Iraqi businesses reported experiencing at least one security incident in the past 12 months',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'stat'    => '85%',
						'title'   => 'Ransomware attacks targeting Iraqi organizations rose by 85% in 2024, with critical infrastructure being the primary target',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'stat'    => '2024 update',
						'title'   => 'New Iraqi data protection regulations implemented in 2024 have increased compliance obligations and penalties',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c"><strong>Our Comprehensive Data Protection Services</strong></h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'      => '<strong>Enterprise-Grade Cloud Backup Solutions</strong>',
						'text'       => 'Our cloud backup services deliver secure, automated protection for your critical business data with real-time synchronization, end-to-end encryption, and rapid recovery capabilities. These solutions scale efficiently across workstations, remote devices, and server environments to ensure business continuity.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/cloud-backup-iraq/' ),
						'outline'    => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Comprehensive Backup and Replication Solutions</strong>',
						'text'       => 'Our enterprise-grade backup and replication solutions protect your valuable digital assets through advanced technology. We implement NAS systems for flexible access, SANs for high-performance storage, and comprehensive protection for both virtual and physical environments. With real-time archiving and efficient restoration processes, your business can quickly recover from any data incident.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/backup-solutions-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Business Continuity &amp; Disaster Recovery</strong>',
						'text'       => 'Our comprehensive disaster recovery solutions minimize downtime from cyberattacks, hardware failures, and natural disasters while optimizing costs. We ensure business operations continue smoothly with minimal disruption.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/disaster-recovery-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Advanced Data Loss Prevention (DLP)</strong>',
						'text'       => 'Our Data Loss Prevention solutions protect sensitive information from unauthorized access through content inspection, policy-based controls, and user behavior analysis. We integrate with your existing security infrastructure to provide comprehensive protection across your entire digital ecosystem, from on-premises systems to cloud services and mobile devices.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/dlp-solutions-iraq/' ),
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
			'heading' => '<strong>Why Choose Osous Al Taqnia for Data Protection in Iraq</strong>',
			'text'    => 'As Iraq\'s trusted data security partner, Osous Al Taqnia brings unmatched expertise to your protection challenges:',
			'items'   => array(
				array(
					'textStyle' => 'bold',
					'text'      => 'Local presence with deep understanding of regional business and regulatory environments',
				),
				array(
					'textStyle' => 'bold',
					'text'      => 'Certified security professionals trained in the latest protection technologies',
				),
				array(
					'textStyle' => 'bold',
					'text'      => 'Vendor-agnostic approach recommending solutions based on your needs, not partnerships',
				),
				array(
					'textStyle' => 'bold',
					'text'      => '24/7 monitoring and support from our Iraq-based security operations center',
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
			'title'      => '<strong>Get Started with Osous Al Taqnia Today</strong>',
			'text'       => 'Begin your data protection journey with a comprehensive security assessment to identify vulnerabilities, followed by strategy development, implementation, and ongoing management.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
