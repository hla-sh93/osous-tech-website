<?php
/**
 * Managed IT Services
 *
 * Read from the reference page managed-it-services-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Managed IT Services',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit">
			<div class="grid-2 gap-sm">
				<div class="cell">
					<div class="container c-surface-primary h-100 custom-section-1">
						<div class="content">
							<h2 class="h2 c-on-primary">Revolutionize Your Business with Professional Managed IT Services in Iraq</h2>
							<p class="p c-on-primary-subtle">In Iraq's competitive business environment, reliable IT infrastructure is essential for growth and success. Professional managed IT services in Iraq allow you to outsource technology management to specialized experts who maintain your systems at peak performance. This enables your team to focus on core business priorities while Osous Al Taqnia handles all technical aspects with tailored solutions that scale with your growing business needs.</p>
						</div>
					</div>
				</div>
				<div class="cell">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/managed-it-services-iraq.webp',
							'alt' => 'Managed IT Services Iraq by Osous Al-Taqnia technical support team',
						),
						'image h-100 radii-container'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">8 Key Benefits of Managed IT Services for Iraq Businesses</h2>
				<p class="p-r-sm c-on-secondary a-c">Modern Iraqi enterprises face complex challenges managing their IT ecosystems. Osous Al Taqnia delivers expert solutions across cloud migration, cybersecurity, infrastructure optimization, and disaster recovery to address these increasingly complex technological demands.t</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '01',
						'title'  => 'Proactive Monitoring',
						'text'   => 'Continuous tracking of networks and devices to prevent issues before they impact operations.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '02',
						'title'  => 'Infrastructure Management',
						'text'   => 'Regular health checks and security protocols that maximize efficiency and stability.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '03',
						'title'  => 'Data Protection',
						'text'   => 'Automated backups and recovery planning to minimize downtime during system failures or attacks.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '04',
						'title'  => 'Enhanced Security',
						'text'   => 'Multi-layered protection including audits, monitoring, and employee awareness training against cyber threats.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '05',
						'title'  => '24/7 Support',
						'text'   => 'Round-the-clock helpdesk with remote and on-site troubleshooting for all technical issues.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '06',
						'title'  => 'Strategic Planning',
						'text'   => 'Technology roadmaps and digital transformation guidance aligned with your business goals.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '07',
						'title'  => 'Scalable Solutions',
						'text'   => 'Flexible services and enterprise-grade technologies that grow with your changing business needs.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '08',
						'title'  => 'Cost Efficiency',
						'text'   => 'Predictable monthly expenses without staffing costs, reducing downtime losses and optimizing investments.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Our Comprehensive Managed IT Service Solutions in Iraq</h2>
			</div>
			<div class="divider c-border-neutral"></div>
			<div class="spacer-3x"></div>
			<div class="grid-3 gap-md">
				<div class="cell">
					<h3>Professional Managed IT Support Services</h3>
					<p>Our support team ensures smooth technology operations with prompt troubleshooting, regular updates, proactive monitoring, and comprehensive hardware support to minimize business disruptions.</p>
				</div>
				<div class="cell">
					<h3>Advanced Managed IT Solutions</h3>
					<p>We optimize your technology processes through early issue identification and strategic solutions including stack optimization, automation, infrastructure modernization, and performance monitoring.</p>
				</div>
				<div class="cell">
					<h3>Expert IT Consulting Services</h3>
					<p>Our consultancy aligns technology with business goals through infrastructure assessment, improvement recommendations, strategic roadmapping, vendor management, and digital transformation guidance.</p>
				</div>
				<div class="cell">
					<h3>Reliable IT Infrastructure Services</h3>
					<p>We maintain your infrastructure foundation with comprehensive server management, network optimization, storage solutions, hardware procurement, and continuous monitoring services.</p>
				</div>
				<div class="cell">
					<h3>Professional SOC as a Service</h3>
					<p>Our Security Operations Center provides 24/7 monitoring, threat detection, incident investigation, compliance reporting, and security improvement recommendations to protect against cyber threats.</p>
				</div>
				<div class="cell">
					<h3>Flexible Cloud Services</h3>
					<p>We simplify cloud computing with expert migration, multi-cloud management, security compliance, cost optimization, and performance monitoring for secure, efficient operations.</p>
				</div>
				<div class="cell">
					<h3>Robust Email Security Services</h3>
					<p>Protect communication channels with advanced threat protection, anti-phishing measures, encryption, spam filtering, and user awareness training against email-borne risks.</p>
				</div>
				<div class="cell">
					<h3>Comprehensive Network Security Services</h3>
					<p>Defend network infrastructure with firewall management, intrusion prevention, VPN implementation, traffic analysis, and security patch management against unauthorized access.</p>
				</div>
				<div class="cell">
					<h3>Reliable Data Backup Services</h3>
					<p>Ensure data protection with automated backups, secure storage, data encryption, recovery testing, and comprehensive reporting for quick restoration when needed.</p>
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
			'heading' => 'Why Choose Osous Al Taqnia for Managed IT Services in Iraq?',
			'tagName' => 'div',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Deep Local Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Our team understands the unique challenges of Iraq\'s technology landscape',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Comprehensive Service Portfolio',
					'textStyle'  => 'small',
					'text'       => 'From infrastructure to security, we provide end-to-end IT management',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Proven Track Record',
					'textStyle'  => 'small',
					'text'       => 'Successful partnerships with businesses across various Iraqi industries',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Tailored Solutions',
					'textStyle'  => 'small',
					'text'       => 'Customized service plans designed specifically for your business requirements',
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
			'title'      => 'Elevate Your Business Potential with Professional Managed IT Services',
			'text'       => 'Contact our team today to discover how Osous Al Taqnia\'s managed IT services can help your Iraq business achieve greater efficiency, enhanced security, and sustainable growth through optimized technology solutions',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
