<?php
/**
 * Avigilon Partner in Iraq
 *
 * Read from the reference page avigilon-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Avigilon Partner in Iraq',
			'text'  => 'Advanced video surveillance, analytics, and access control solutions delivered with certified expertise across Iraq.',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Avigilon Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Avigilon Partner in Iraq, trusted by businesses and government institutions to enhance physical security and operational awareness. Through Avigilon’s intelligent video and access control technologies, we deliver reliable, scalable, and cost-effective solutions tailored to Iraq’s unique security landscape.<br/><br/>With local operations in Baghdad, Basra, and Erbil, our engineers provide hands-on expertise, rapid support, and full project execution  from consultation and design to installation and maintenance ensuring every client receives a complete, high-performance Avigilon system.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-avigilon.webp',
						'alt'    => 'Avigilon',
						'srcset' => 'assets/images/partner-bg-avigilon-p-500.webp 500w, assets/images/partner-bg-avigilon.webp 652w',
						'sizes'  => '(max-width: 652px) 100vw, 652px',
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
					<h2 class="h2">About Avigilon Solutions</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Avigilon, part of Motorola Solutions, is a global leader in intelligent video surveillance and access control technology. Its systems combine artificial intelligence, high-definition imaging, and cloud-based management to help organizations detect and respond to security events quickly and accurately.<br/><br/>Built for reliability and scalability, Avigilon solutions deliver exceptional image clarity, real-time analytics, and secure remote monitoring. They integrate seamlessly with Motorola CommandCentral, giving users a unified view for improved decision-making and faster incident response.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Avigilon Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Avigilon Video Security Systems',
						'prose' => '<ul role="list"> <li>Full range of HD and Ultra-HD cameras for any environment<br/></li> <li>AI-powered analytics for proactive threat detection<br/></li> <li>Smart bandwidth optimization for efficient data storage</li> <li>Remote access via <strong>Avigilon Control Center (ACC)</strong></li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Avigilon Control Center (ACC) Software',
						'prose' => '<ul role="list"> <li>Intuitive video management for multiple locations<br/></li> <li>Smart search and facial recognition features<br/></li> <li>Role-based user management for security compliance<br/></li> <li>Seamless integration with alarm and building systems</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Avigilon Access Control Manager (ACM)',
						'prose' => '<ul role="list"> <li>Centralized control for employees and visitors<br/></li> <li>Cloud or on-premise deployment flexibility<br/></li> <li>Unified operation with Avigilon video systems<br/></li> <li>Ideal for offices, hospitals, and government buildings</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Avigilon Artificial Intelligence (AI) Appliance',
						'prose' => '<ul role="list"> <li>Add AI to existing camera networks<br/></li> <li>Real-time alerts for critical events<br/></li> <li>Quick search by appearance or vehicle type<br/></li> <li>Upgrade legacy systems with intelligent capabilities</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Avigilon H4 Multisensor Cameras',
						'prose' => '<ul role="list"> <li>Multiple sensors to cover large or complex areas<br/></li> <li>Built-in AI for precise monitoring<br/></li> <li>Self-learning technology reduces blind spots<br/></li> <li>Perfect for high-traffic or perimeter security</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Avigilon Blue Cloud Service Platform',
						'prose' => '<ul role="list"> <li>Cloud-based monitoring and device management<br/></li> <li>Remote system updates and diagnostics<br/></li> <li>Lower maintenance costs and increased uptime<br/></li> <li>Secure access for managing multiple sites in Iraq</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Avigilon in Iraq</h2>
			</div>
			<div class="grid-3 grid-to-carousel">
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
						'text'  => 'Ensure student and staff safety through centralized monitoring and discreet surveillance.',
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
						'text'  => 'Safeguard branches, ATMs, and vaults with HD monitoring and intelligent access control.',
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
						'text'  => 'Protect remote facilities and pipelines with durable, AI-enabled surveillance designed for extreme conditions.',
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
						'text'  => 'Protect hospitals and clinics while maintaining privacy and operational continuity.',
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
						'title' => 'Government and Defense',
						'text'  => 'Support national agencies with reliable systems for checkpoints, borders, and secure facilities.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Avigilon in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Avigilon Partner',
					'text'  => 'Authorized to design, supply, and maintain Avigilon systems across Iraq.',
				),
				array(
					'title' => 'Local Expertise',
					'text'  => 'Teams based in Baghdad, Basra, and Erbil deliver rapid, reliable, and culturally aware support.',
				),
				array(
					'title' => 'End-to-End Delivery',
					'text'  => 'We handle every stage from survey and system design to installation, testing, and maintenance.',
				),
				array(
					'title' => 'Customized Solutions',
					'text'  => 'Each Avigilon system is adapted to the specific security challenges of your sector.',
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
			'title'      => 'Contact Osous Al Taqnia – Avigilon Partner in Iraq',
			'text'       => 'Ready to improve your organization’s security with Avigilon solutions?<br> Our certified specialists will help you plan, design, and deploy the right system for your needs.<br>',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
