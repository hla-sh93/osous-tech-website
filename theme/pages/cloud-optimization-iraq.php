<?php
/**
 * Cloud Optimization Services in Iraq
 *
 * Read from the reference page cloud-optimization-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Cloud Optimization',
			'title'   => 'Cloud Optimization Services in Iraq',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit c-surface-primary">
			<div class="grid-3">
				<div class="cell self-center span-2">
					<div class="content">
						<h2 class="h2 c-on-primary">What is Cloud Optimization?</h2>
						<p class="p c-on-primary-subtle">Cloud optimization is the strategic process of correctly allocating cloud resources to maximize performance and minimize costs. It ensures your business workloads receive precisely the right resources as application needs evolve, eliminating waste from over-provisioning while maintaining optimal performance.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/cloud-optimization.webp',
							'alt'    => 'Cloud optimization diagram showing cost savings for Iraqi businesses',
							'srcset' => 'assets/images/cloud-optimization-p-500.webp 500w, assets/images/cloud-optimization.webp 653w',
							'sizes'  => '(max-width: 767px) 100vw, 652.9861450195312px',
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
			<div class="grid-2">
				<div class="cell">
					<h2>Why Cloud Optimization Matters for Iraqi Businesses</h2>
					<h2 class="h2 thin">The Growing Challenge of Cloud Spend Management in Iraq</h2>
				</div>
				<p class="p">As organizations across Iraq scale their cloud presence, managing associated costs becomes increasingly complex. According to recent market research, global cloud spending continues to grow rapidly, with Iraqi businesses seeing similar concerning trends:</p>
			</div>
			<div class="spacer-3x"></div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'stat'    => '30-45%',
						'text'    => 'of cloud spend is typically wasted on unused or underutilized resources',
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
						'stat'    => '70%',
						'text'    => 'of businesses report exceeding their cloud budgets regularly',
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
						'stat'    => '82%',
						'text'    => 'of organizations lack the tools and expertise to properly optimize their cloud environments',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section full c-surface-secondary">
		<div class="container">
			<div class="heading">
				<h2 class="h2 c-on-secondary a-c">Key Components of Effective Cloud Optimization</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '01',
						'title'  => 'Resource Right-Sizing',
						'text'   => 'of cloud spend is typically wasted on unused or underutilized resources',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '02',
						'title'  => 'Reserved Instance & Savings Plan Management',
						'text'   => 'Leveraging provider discounts for committed usage through strategic planning and regular review of utilization rates helps Iraqi businesses achieve predictable cloud costs.<br>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '03',
						'title'  => 'Automated Scaling Solutions',
						'text'   => 'Implementing elastic resources that scale based on demand ensures appropriate capacity during both peak and low-usage periods, optimizing both performance and cost.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '04',
						'title'  => 'Storage Optimization',
						'text'   => 'Managing data storage costs through tiered strategies, lifecycle policies, and compression technologies significantly reduces unnecessary cloud spending.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '05',
						'title'  => 'Cost Visibility and Governance',
						'text'   => 'Maintaining comprehensive visibility into cloud spend with cost allocation, budget tracking, and policy enforcement creates accountability across your organization.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Why Cloud OptimizatWhy Choose Osous Al Taqnia for Cloud Optimizationion Matters for Iraqi Businesses</h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/property-14.svg',
							'alt' => '',
						),
						'fill'    => true,
						'title'   => 'Iraqi Cloud Expertise',
						'text'    => 'Our specialists understand the unique challenges of cloud infrastructure in Iraq\'s business environment',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-12.svg',
							'alt' => '',
						),
						'title' => 'Proprietary Optimization Methodology',
						'text'  => 'We employ tested frameworks specifically designed for Iraqi businesses',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-12.svg',
							'alt' => '',
						),
						'title' => 'Rapid ROI',
						'text'  => 'Our services typically deliver return on investment within 3-6 months',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-11.svg',
							'alt' => '',
						),
						'title' => 'Proven Results',
						'text'  => 'We consistently generate 30-60% cost savings for businesses across Iraq',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">The Future of Cloud Optimization in Iraq</h2>
						<p class="p">As technologies evolve, Iraqi businesses implementing comprehensive cloud optimization strategies can achieve immediate cost savings while building a foundation for long-term efficiency. Effective cloud optimization creates the agility, performance, and financial discipline needed to thrive in Iraq's competitive marketplace.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/azure-cloud-optimization-tools.webp',
							'alt'    => 'Azure cloud optimization tools for Iraqi organizations',
							'srcset' => 'assets/images/cloud-service.Cloud-Optimization02-p-500.webp 500w, assets/images/azure-cloud-optimization-tools.webp 650w',
							'sizes'  => '(max-width: 767px) 100vw, 649.982666015625px',
						),
						'image h-50'
					);
					?>
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
