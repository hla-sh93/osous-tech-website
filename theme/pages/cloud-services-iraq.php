<?php
/**
 * Cloud Services
 *
 * Read from the reference page cloud-services-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Cloud Services',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/microsoft-certified-cloud-solutions-iraq.webp',
					'alt'    => 'Osous Al-Taqnia Microsoft-certified cloud solutions in Iraq',
					'srcset' => 'assets/images/microsoft-certified-cloud-solutions-iraq-p-500.webp 500w, assets/images/microsoft-certified-cloud-solutions-iraq-p-800.webp 800w, assets/images/microsoft-certified-cloud-solutions-iraq.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 939.984375px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start"><strong>Leading Cloud Solutions in Iraq: Drive Growth with Secure, Scalable Technology</strong></h2>
				<p class="p-r-sm c-on-primary self-center">In Iraq's rapidly evolving digital economy, businesses in Baghdad, Erbil, and Basra are turning to cloud technology to reduce costs, enhance agility, and future-proof operations. Osous Al Taqnia, Iraq's premier Microsoft-certified cloud provider, delivers enterprise-grade solutions tailored to the unique needs of Iraqi organizations.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<h2 class="h2"><strong>Why Iraq Businesses Need Cloud Solutions</strong></h2>
				<p class="p">The Iraqi dynamic business landscape demands scalable, cost-efficient, and secure cloud solutions to drive innovation. Whether you're migrating to the cloud for the first time, optimizing existing cloud infrastructure, implementing multi-cloud strategies, or seeking cloud-based business intelligence solutions, partnering with the right cloud experts is critical for successful digital transformation.</p>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c"><strong>Key Benefits of Cloud Services in Iraq</strong></h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => '<strong>Cost Optimization</strong>',
						'text'    => 'Reduce expenses with pay-as-you-go models that eliminate hardware and software capital expenditure while lowering maintenance costs.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => '<strong>Enhanced Scalability</strong>',
						'text'  => 'Adapt resources to meet changing business demands without infrastructure investments, with access to virtually unlimited storage capacity.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => '<strong>Enterprise-Grade Security</strong>',
						'text'  => 'Protect data with advanced encryption technologies while ensuring compliance with Iraqi regulations through robust access controls.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => '<strong>Business Continuity</strong>',
						'text'  => 'Minimize downtime with automated backup systems and comprehensive protection against data loss through redundant storage.',
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
					<h2 class="h2"><strong>Our Specialized Cloud Services</strong></h2>
				</div>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline'    => false,
						'icon'       => array(
							'src' => 'assets/images/microsoft-azure.svg',
							'alt' => 'Microsoft Azure cloud services for Iraqi businesses',
						),
						'link'       => home_url( '/azure-cloud-iraq/' ),
						'fill'       => true,
						'title'      => 'Microsoft Azure Design & Deployment',
						'text'       => 'Our expert consultants work closely with your team to design, implement, and optimize Azure cloud solutions tailored to your specific business requirements. We provide comprehensive cloud readiness assessments, custom Azure architecture design, seamless migration services, performance monitoring, continuous optimization, and complete Azure infrastructure management and support.<br>',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/azure-cloud-iraq/' ),
					)
				);
				?>
				<div>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline'    => false,
							'icon'       => array(
								'src' => 'assets/images/cloud-optimization.png',
								'alt' => 'Cloud optimization services for Iraqi companies',
							),
							'link'       => home_url( '/cloud-optimization-iraq/' ),
							'fill'       => true,
							'title'      => 'Cloud Optimization Services',
							'text'       => 'Maximize your cloud investment with our comprehensive optimization services designed to reduce unnecessary cloud expenditure and implement cost management strategies. We enhance performance through architecture refinements, identify and eliminate resource wastage, and consolidate workloads for optimal efficiency across your entire cloud infrastructure.<br>',
							'buttonText' => 'Read more',
							'buttonUrl'  => home_url( '/cloud-optimization-iraq/' ),
						)
					);
					?>
				</div>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline'    => false,
						'icon'       => array(
							'src' => 'assets/images/microsoft-365-solutions.png',
							'alt' => 'Microsoft 365 implementation and support in Iraq',
						),
						'link'       => home_url( '/microsoft-office-365-iraq/' ),
						'fill'       => true,
						'title'      => '<strong>Microsoft 365 Solutions</strong>',
						'text'       => 'Transform your workplace productivity with Microsoft 365 implementation and support. Our services include complete Microsoft 365 deployment and migration, custom configuration and integration, user training and adoption support, security hardening, compliance configuration, and ongoing management with dedicated technical support to ensure maximum value from your Microsoft 365 investment.<br>',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/microsoft-office-365-iraq/' ),
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
			'heading' => '<strong>Why Choose Osous Al Taqnia as Your Cloud Solution Provider in Iraq?</strong>',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Microsoft-Centric Expertise</strong>',
					'textStyle'  => 'plain',
					'text'       => 'Our team specializes in Azure and Microsoft 365 technologies as a certified Microsoft partner with direct support channels ensuring prompt issue resolution.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Proprietary Tools</strong>',
					'textStyle'  => 'small',
					'text'       => 'We\'ve developed custom migration and monitoring solutions with automated deployment capabilities and industry-specific compliance frameworks for seamless cloud adoption.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Iraq-Focused Solutions</strong>',
					'textStyle'  => 'small',
					'text'       => 'Our solutions are designed specifically for Iraqi businesses with local data sovereignty compliance, Arabic language support, and deep expertise in regional regulations.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Dedicated Support</strong>',
					'textStyle'  => 'small',
					'text'       => 'Our Baghdad-based team provides 24/7 technical assistance with proactive monitoring and regular performance optimization to maximize your cloud investment.',
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
			'title'      => '<strong>Transform Your Business Today</strong>',
			'text'       => 'Partner with Osous Al Taqnia for cloud solutions tailored to the Iraqi market. Contact our specialists to learn how our services can give your business a competitive edge in today\'s digital economy.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
