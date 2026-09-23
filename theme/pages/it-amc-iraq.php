<?php
/**
 * IT AMC
 *
 * Read from the reference page it-amc-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Services',
			'title'   => 'IT AMC',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/it-amc-services-iraq.webp',
					'alt'    => 'it amc services iraq',
					'srcset' => 'assets/images/it-amc-services-iraq-p-500.webp 500w, assets/images/it-amc-services-iraq-p-800.webp 800w, assets/images/it-amc-services-iraq.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.96533203125px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start">Why Your Business Needs Professional IT AMC Services in Iraq</h2>
				<p class="p-r-sm c-on-primary self-center">In today's digital landscape, IT failures can severely impact your business operations and damage your reputation. Professional IT AMC services in Iraq provide the preventative care your technology investments need to avoid costly downtime.An Annual Maintenance Contract (AMC) ensures your systems remain operational, secure, and optimized year-round through proactive management rather than reactive problem-solving after disruptions occur.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<h2 class="h2">Osous Al Taqnia: <span class="thin">Iraq's Premier IT AMC Service Provider</span></h2>
				<p class="p">With nearly a decade of experience delivering exceptional IT support in Iraq, Osous Al Taqnia has established itself as a leading provider of comprehensive IT AMC solutions. Our team of certified professionals specializes in creating customized maintenance contracts that align perfectly with your business requirements and budget constraints.</p>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Choose <span class="highlight">Osous Al Taqnia </span>for Your <span class="underline">IT AMC Needs in Iraq?</span>',
			'items'   => array(
				array(
					'title'     => 'Industry-Leading Response Times',
					'textStyle' => 'plain',
					'text'      => 'Guaranteed 10-minute response from expert engineers when IT issues arise',
				),
				array(
					'title'     => 'Round-the-Clock Support',
					'textStyle' => 'small',
					'text'      => '24/7 monitoring and fully managed IT support ensures your systems never experience extended downtime',
				),
				array(
					'title'     => 'Dedicated Support Team',
					'textStyle' => 'small',
					'text'      => 'Each client receives a dedicated IT manager and engineer to ensure consistent, personalized service',
				),
				array(
					'title'     => 'Robust Security Solutions',
					'textStyle' => 'small',
					'text'      => 'Comprehensive cybersecurity strategies protect your network from evolving threats',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container">
				<div class="heading">
					<h2 class="h2">Our Core IT AMC Service Offerings Include</h2>
				</div>
				<div class="grid-3">
					<?php
					get_template_part(
						'includes/parts/numbered-card',
						null,
						array(
							'title'   => 'Network Infrastructure Management',
							'text'    => 'We provide complete network monitoring, configuration, optimization, and performance enhancement services to keep your business connected and efficient.',
							'outline' => true,
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'title' => 'Workstation and Server Support',
							'text'  => 'Our team handles all hardware troubleshooting, system updates, application support, and lifecycle management for your workstations and servers.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'title' => 'Comprehensive Security Services',
							'text'  => 'We implement multi-layered security with firewalls, anti-virus protection, email security, intrusion prevention, and regular vulnerability assessments.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'title' => 'Cloud Services Management',
							'text'  => 'Our experts manage cloud migrations, hybrid environments, SaaS applications, and storage solutions with continuous performance optimization.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'title' => 'Data Protection and Recovery',
							'text'  => 'We ensure your data remains safe with automated backups, offsite storage, disaster recovery planning, and reliable restoration services.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'title' => 'IT Help Desk Support',
							'text'  => 'Our responsive help desk delivers remote assistance, account management, communication tools support, and new employee technology setup.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/cybersecurity-protection-iraq.webp',
							'alt'    => 'IT technician monitoring dashboard in Iraq',
							'srcset' => 'assets/images/services.ICT-solutions01-p-500.webp 500w, assets/images/services.ICT-solutions01-p-800.webp 800w, assets/images/cybersecurity-protection-iraq.webp 922w',
							'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 921.9791870117188px',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Take Control of Your Security Today</h2>
						<p class="p">Professional IT AMC services offer significant advantages: cost efficiency through shared resources, predictable monthly expenses for better budgeting, freedom to focus on core business activities, access to specialized expertise without hiring costs, enhanced cybersecurity protection, and improved business continuity with minimal downtime. Osous Al Taqnia delivers these benefits through customized support packages that align with your business goals and technology requirements.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-secondary">
			<div class="container">
				<div class="heading center">
					<h2 class="h2 c-on-secondary a-c">Getting Started with Osous Al Taqnia's Managed IT AMC Services</h2>
					<p class="p-r-sm c-on-secondary a-c">Beginning your journey toward optimized IT operations is simple:</p>
				</div>
				<div class="grid-4">
					<?php
					get_template_part(
						'includes/parts/numbered-card',
						null,
						array(
							'number' => '01',
							'title'  => 'Free Consultation',
							'text'   => 'Schedule your complimentary 30-minute IT consultation',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/numbered-card',
						null,
						array(
							'number' => '02',
							'title'  => 'Custom Proposal',
							'text'   => 'Receive a tailored IT AMC proposal based on your specific requirements',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/numbered-card',
						null,
						array(
							'number' => '03',
							'title'  => 'Seamless Onboarding',
							'text'   => 'Experience a smooth transition to managed IT support with minimal disruption',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/numbered-card',
						null,
						array(
							'number' => '04',
							'title'  => 'Ongoing Partnership',
							'text'   => 'Enjoy peace of mind with continuous, proactive IT management',
						)
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
			'title'      => '<strong>Contact Osous Al Taqnia Today for Premier IT AMC Services in Iraq</strong>',
			'text'       => 'Don\'t wait for IT problems to disrupt your business. Contact Osous Al Taqnia now for a free 30-minute consultation and customized quote tailored to your specific needs. Join our satisfied clients across Iraq and transform your IT operations with our professional managed support services.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
