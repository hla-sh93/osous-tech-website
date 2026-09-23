<?php
/**
 *  Acronis Partner in Iraq
 *
 * Read from the reference page acronis-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => ' Acronis Partner in Iraq',
			'text'  => 'Certified cybersecurity and backup solutions for enterprises, SMBs, and institutions across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Acronis Reseller in Iraq</h2>
						<p> Osous Al Taqnia is a certified Acronis partner in Iraq, delivering proven cybersecurity, backup, and disaster recovery solutions for businesses, schools, government agencies, and more.<br/>Our locally based, Acronis-certified engineers provide full support—from planning and deployment to management—helping Iraqi organizations protect their data and stay resilient in the face of cyber threats.<br/>Whether you're defending against ransomware, securing hybrid cloud systems, or automating backup strategies, we tailor Acronis deployments to Iraq’s evolving IT environment.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-acronis.webp',
						'alt'    => 'Acronis',
						'srcset' => 'assets/images/partner-bg-acronis-p-500.webp 500w, assets/images/partner-bg-acronis.webp 652w',
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
					<h2 class="h2">About Acronis</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Acronis is a global cyber protection provider, combining modern data backup with cutting-edge cybersecurity and recovery tools. Their unified platform protects against data loss, malware, unauthorized access, and system downtime across on-premise, cloud, and hybrid environments.<br/>‍<br/>Trusted by businesses, managed service providers (MSPs), and public institutions worldwide, Acronis solutions provide:<br/> • AI-based threat prevention<br/> • Real-time detection and response<br/> • Full data and system restore<br/> • Centralized management for endpoints</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Acronis Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Acronis Cyber Protect Cloud',
						'prose' => '<p>All-in-one solution for:</p> <ul role="list"> <li> Antivirus</li> <li> Anti-malware</li> <li> Endpoint protection</li> <li> Backup &amp; disaster recovery</li> </ul> <p>Ideal for Iraqi MSPs and IT teams needing streamlined cybersecurity with minimal complexity.</p> <p>‍</p>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Acronis Cyber Files Cloud',
						'text'  => 'Enterprise-grade file sharing and synchronization across teams, devices, and offices—perfect for mobile workforces and hybrid teams in Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Acronis Cyber Backup & Disaster Recovery',
						'prose' => '<p>Includes:</p> <ul role="list"> <li>Full-image and incremental backups</li> <li>Ransomware prevention</li> <li>Instant system recovery</li> <li>Support for physical, virtual, and cloud environments</li> </ul> <p>‍</p>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Acronis Snap Deploy',
						'text'  => 'Mass deployment of OS and software to multiple machines—used widely by Iraqi schools, training centers, and corporate IT labs.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Acronis DeviceLock DLP',
						'prose' => '<p>Prevent data leaks at the endpoint level with granular controls for:</p> <ul role="list"> <li> USB/block storage access</li> <li> Print monitoring</li> <li> Screen capture blocking</li> <li> User activity auditing</li> </ul> <p>Used in Iraqi finance, education, and public sector organizations.</p> <p>‍</p>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Acronis Detection and Response (ADR)',
						'text'  => 'Real-time, AI-driven threat detection and response.<br> Minimizes risk by identifying and neutralizing threats before they spread critical for protecting sensitive data in Iraq’s growing digital landscape.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Acronis in Iraq</h2>
			</div>
			<div class="grid-3 carousel-in-mobile">
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
						'text'  => 'Centralized device deployment, student data security, and cloud backup',
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
						'text'  => 'Ransomware protection and endpoint-level data leak prevention',
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
						'text'  => 'Secure backups in hybrid infrastructures across remote Iraqi sites',
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
						'text'  => 'HIPAA-compliant backups and secure storage of patient records',
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
						'text'  => 'Compliant backup, endpoint control, and cyber resilience',
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
			'heading' => 'Why Choose Osous Al Taqnia for Acronis in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Acronis Expertise',
					'text'  => 'Our team includes Acronis-certified engineers who are well-versed in deploying and managing Acronis solutions across physical, virtual, and cloud environments. We tailor each implementation to meet the unique data protection and cybersecurity needs of businesses in Iraq.',
				),
				array(
					'title' => 'Local Insight with Global Support',
					'text'  => 'With a strong presence in Baghdad, Basra, and Erbil, we provide timely, on-the-ground support backed by international standards. Our local knowledge allows us to respond quickly to infrastructure challenges while delivering globally recognized solutions.',
				),
				array(
					'title' => 'End-to-End Cyber Protection Services',
					'text'  => 'We handle everything from pre-sales consultation and system design to installation, training, and post-sales maintenance. Whether you\'re backing up critical data or defending against ransomware, we manage the full lifecycle of your Acronis deployment.',
				),
				array(
					'title' => 'Trusted Delivery and Competitive Pricing',
					'text'  => 'As an authorized Acronis partner, we ensure access to official licensing, product updates, and partner-exclusive pricing. Clients across Iraq trust us to deliver secure, scalable solutions with flexible service and support packages.',
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
			'title'      => 'Contact Osous Al Taqnia – Acronis Partner in Iraq',
			'text'       => 'Ready to protect your systems with industry-proven cyber protection?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
