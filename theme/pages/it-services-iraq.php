<?php
/**
 * IT Services
 *
 * Read from the reference page it-services-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'IT Services',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/cybersecurity-consulting-iraq.webp',
					'alt'    => 'Cybersecurity consulting session at Osous Al Taqnia',
					'srcset' => 'assets/images/cybersecurity-consulting-iraq-p-500.webp 500w, assets/images/cybersecurity-consulting-iraq-p-800.webp 800w, assets/images/cybersecurity-consulting-iraq.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 939.984375px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start"><strong>Comprehensive IT &amp; Technology Services in Iraq</strong></h2>
				<p class="p-r-sm c-on-primary self-center">At Osous Al Taqnia, we deliver enterprise-grade technology solutions tailored to Iraq's unique business environment. Our comprehensive service portfolio empowers organizations across Iraq to transform their IT infrastructure into a strategic business asset that drives growth, enhances security, and improves operational efficiency.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<div class="cell">
					<h2 class="h2"><strong>Managed IT Services in Iraq</strong></h2>
					<div class="_w-max-200">
						<?php osous_button( 'Read More', home_url( '/managed-it-services-iraq/' ) ); ?>
					</div>
				</div>
				<p class="p">Transform your Iraqi business operations with our comprehensive managed IT services that deliver 24/7 proactive monitoring, enhanced security, data protection, and round-the-clock support. Our professional solutions include IT support, infrastructure management, SOC services, cloud computing, email security, network management, and complete data backup and recovery.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-neutral">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2"><strong>Consulting Services in Iraq</strong></h2>
						<p class="p">Navigate Iraq's complex technology landscape with our strategic IT consulting services that optimize costs, enhance productivity, mitigate security risks, and enable business scalability. Our expertise encompasses cybersecurity consulting, cloud architecture, disaster recovery, network optimization, IT relocation management, structured cabling services, and maintenance contract optimization tailored to Iraq's competitive market.</p>
						<div class="_w-max-200">
							<?php osous_button( 'Read More', home_url( '/it-consulting-iraq/' ) ); ?>
						</div>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/consulting-services-iraq.webp',
							'alt' => 'Consulting Services in Iraq',
						),
						'image h-50'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container fit">
			<div class="grid-2">
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/it-technician-network-iraq.webp',
							'alt' => 'IT technician configuring network infrastructure in Iraq',
						),
						'image h-50'
					);
					?>
				</div>
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2"><strong>IT Annual Maintenance Contract (AMC) Services in Iraq</strong></h2>
						<p class="p">Prevent costly downtime with our professional IT AMC services featuring industry-leading response times, 24/7 support, proactive maintenance, dedicated teams, and comprehensive security. Our core offerings include network infrastructure management, workstation support, security services, cloud management, data protection, and responsive help desk support—all backed by our 30-day money-back guarantee.</p>
						<div class="_w-max-200">
							<?php osous_button( 'Read More', home_url( '/it-amc-iraq/' ) ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section c-surface-neutral">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2"><strong>ICT Solutions in Iraq</strong></h2>
						<p class="p">Empower your Iraqi organization with our comprehensive ICT solutions that serve as the backbone for modern business operations. We provide network infrastructure optimization, cloud computing solutions, unified communications systems, IT management and support, advanced cybersecurity solutions, and data center services—enabling your company to streamline processes, enhance communication, and make data-driven decisions for measurable business outcomes.</p>
						<div class="_w-max-200">
							<?php osous_button( 'Read More', home_url( '/ict-solutions-iraq/' ) ); ?>
						</div>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/cloud-computing-dashboard-iraq.webp',
							'alt' => 'Cloud computing dashboard for Iraqi business',
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
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => '<strong>Why Choose Osous Al Taqnia for IT Infrastructure Services?</strong>',
			'tagName' => 'div',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Deep Local Expertise</strong>',
					'textStyle'  => 'plain',
					'text'       => 'Our team understands the unique challenges of Iraq\'s technology landscape',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Comprehensive Service Portfolio</strong>',
					'text'       => 'From infrastructure to security, we provide end-to-end IT management',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Localized Support</strong>',
					'text'       => 'Responsive local support with appropriate response times',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Risk Reduction</strong>',
					'text'       => 'Identification and mitigation of technology-related business risks',
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
			'title'      => '<strong>Transform Your Business with Osous Al Taqnia</strong>',
			'text'       => 'Contact our team today to discover how our tailored technology solutions can address your specific business challenges and accelerate your growth in Iraq\'s dynamic market. Our certified experts will develop a customized technology roadmap that aligns with your strategic objectives and empowers your organization to achieve sustainable success.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
