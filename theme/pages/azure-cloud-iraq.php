<?php
/**
 * Microsoft Azure Cloud Management & Migration Services in Iraq
 *
 * Read from the reference page azure-cloud-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Cloud Services',
			'title'   => 'Microsoft Azure Cloud Management & Migration Services in Iraq',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container">
			<div class="grid-2 gap-1x">
				<div class="cell self-center-tablet">
					<div class="container c-surface-primary h-100">
						<div class="content">
							<h2 class="h2 c-on-primary">Expert Azure Cloud Solutions for Iraqi Businesses</h2>
							<p class="p c-on-primary-subtle">In today's rapidly evolving digital landscape, businesses in Iraq need robust, scalable, and secure cloud solutions to stay competitive. Microsoft Azure offers industry-leading capabilities for cloud computing, but maximizing its potential requires expertise and strategic implementation. Osous Al Taqnia's professional Azure cloud management services provide the guidance and support necessary to optimize your Azure environment for performance, security, and cost-efficiency.</p>
						</div>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/azure-cloud-management-services.webp',
							'alt'    => 'Osous Al Taqnia\'s Azure cloud management services in Iraq',
							'srcset' => 'assets/images/cloud-service.Microsoft-Azure-Cloud01-p-500.webp 500w, assets/images/azure-cloud-management-services.webp 653w',
							'sizes'  => '(max-width: 653px) 100vw, 653px',
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
			<div class="heading">
				<h2 class="h2"><strong>Enterprise-Grade Solutions for Business Applications</strong></h2>
			</div>
			<div class="grid-2">
				<div class="cell">
					<h3 class="h3">Centralized Backup Solutions</h3>
					<p class="p c-on-neutral-subtle">Protect your data with Azure Backup's cost-effective, one-click protection for VMs, files, and SQL databases. Enjoy offloaded on-premises backup, unlimited data transfer, centralized monitoring, and automatic storage management.</p>
				</div>
				<div class="cell">
					<h3 class="h3"><strong> Web Services Hosting</strong></h3>
					<p class="p c-on-neutral-subtle">Focus on website features rather than infrastructure with Azure App Service. Host web applications, REST APIs, and mobile back ends through a unified interface with support for multiple development languages and effortless scaling.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section full c-surface-neutral">
		<div class="container">
			<div class="heading">
				<h2 class="h2"><strong>Key Features of Professional Azure Cloud Management</strong></h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Continuous Compliance',
						'text'    => 'Gain better control of your security protocols and ensure your Azure Cloud Services comply with all regional and industry-standard regulations in Iraq.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Future-Proof Infrastructure',
						'text'  => 'Build a scalable, flexible Azure Cloud Infrastructure that grows with your business and easily adapts to changing needs.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Easy Deployment',
						'text'  => 'Deploy, monitor, and manage your Azure Cloud Services centrally from anywhere, simplifying management across cloud and physical environments.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Seamless Hybrid Environment',
						'text'  => 'Connect your team with secure, advanced technology solutions that enable productivity from anywhere, anytime.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'Integrated Security',
						'text'  => 'Implement fully integrated security features and protocols to protect against cyber-attacks and ransomware.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'fill'  => true,
						'title' => 'AI and Machine Learning Integration',
						'text'  => 'Gain daily, deep insights into your systems and network health with Azure\'s advanced learning solutions to predict and prevent future failures.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-sm c-border-neutral">
			<div class="grid-2">
				<div class="modal bg-azura">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/partner-azure.svg',
							'alt' => 'Certified Microsoft Azure partner in Iraq',
						),
						'icon-160'
					);
					?>
					<h2 class="h2 c-on-secondary">Our Microsoft Azure Partnership</h2>
				</div>
				<p class="p c-on-neutral-subtle self-center">As a certified Microsoft Azure partner in Iraq, Osous Al Taqnia receives direct access to Microsoft's resources, specialized training, and priority support. This partnership ensures we deliver premium Azure solutions with access to early-release features and expert technical support.</p>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading'   => 'Why Choose Osous Al Taqnia\'s Azure Cloud Management Services?',
			'text'      => 'Azure cloud management services offer comprehensive support throughout your cloud journey, providing:',
			'textClass' => 'p c-on-neutral-subtle',
			'items'     => array(
				array( 'title' => '24/7 Comprehensive Support for your critical infrastructure needs' ),
				array( 'title' => 'Expert Maintenance throughout your Azure migration and beyond' ),
				array( 'title' => 'Strategic Guidance and Analysis of your cloud operations' ),
				array( 'title' => 'Deployment Optimization to maximize performance and minimize costs' ),
			),
		)
	);
	?>

	<section class="section bg-img-15 h-75">
		<div class="container">
			<div class="grid-2">
				<div class="cell">
					<div class="content">
						<h2 class="h3 c-on-secondary">Transform Your Business with Azure Cloud Solutions in Iraq</h2>
						<p class="p c-on-secondary">Whether you're just beginning your cloud journey or looking to optimize an existing Azure environment, Osous Al Taqnia's professional cloud management services provide the expertise and support needed to maximize your investment in Microsoft Azure.</p>
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
			'title'      => '<strong>Contact Iraq\'s Leading Azure Cloud Experts Today</strong>',
			'text'       => 'Get faster, fully managed access to Azure integrated tools and create a seamless work environment for your organization. Contact Osous Al Taqnia, Iraq\'s trusted Azure solutions provider, today to begin your transformation.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
