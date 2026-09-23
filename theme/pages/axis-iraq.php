<?php
/**
 * Axis Partner in Iraq
 *
 * Read from the reference page axis-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Axis Partner in Iraq',
			'text'  => 'Intelligent video surveillance and network security solutions delivered with certified expertise across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Axis Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Axis Communications Partner in Iraq, trusted by organizations across Baghdad, Basra, and Erbil to deliver professional IP video surveillance and network security systems.<br/>‍<br/> As a certified reseller, we handle everything from consultation and installation to integration and long-term support, ensuring each project is efficient, compliant, and built to last.</p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-axis.webp',
						'alt'    => 'Axis',
						'srcset' => 'assets/images/partner-bg-axis-p-500.webp 500w, assets/images/partner-bg-axis.webp 652w',
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
					<h2 class="h2">About Axis Communications</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Axis Communications is a global leader in network video technology, known for developing reliable IP-based surveillance systems that redefine how organizations protect people and assets.<br/>‍<br/>Its open-platform solutions include network cameras, video management software, access control, and network audio, all designed to integrate easily with existing infrastructure. Axis helps organizations strengthen security, improve efficiency, and maintain long-term reliability across their operations.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Axis Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Axis Network Cameras',
						'prose' => '<p>High-quality cameras engineered for clear, consistent video performance.</p> <ul role="list"> <li>Range includes fixed, dome, bullet, panoramic, and thermal models<br/></li> <li>Lightfinder and WDR ensure clarity even in challenging lighting<br/></li> <li>Built-in analytics for motion detection and behavior alerts<br/></li> <li>Ideal for both indoor and outdoor monitoring</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Axis Video Management Software (VMS)',
						'prose' => '<p>Simple and secure monitoring at scale.</p> <ul role="list"> <li>Centralized video control for single or multiple sites<br/></li> <li>Smart search and event-based recording<br/></li> <li>Fully compatible with ONVIF and third-party devices<br/></li> <li>Scalable for small offices or enterprise networks</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Axis Access Control & Intercom Systems',
						'prose' => '<p>Secure access for people and property.</p> <ul role="list"> <li>Network door controllers and card readers<br/></li> <li>Remote verification via two-way communication<br/></li> <li>Seamless integration with Axis cameras and VMS<br/></li> <li>Perfect for high-security environments and campuses</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Axis Network Audio Systems',
						'prose' => '<p>Combine visual and audio deterrence for complete situational control.</p> <ul role="list"> <li>IP speakers for live or scheduled announcements<br/></li> <li>Audio integration with cameras for active deterrence<br/></li> <li>Managed through the Axis software interface<br/></li> <li>Useful for retail, public spaces, and industrial facilities</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Axis Analytics & System Devices',
						'prose' => '<p>Smart analytics to enhance safety and efficiency.</p> <ul role="list"> <li>People counting and occupancy tracking<br/></li> <li>Queue and flow monitoring for public or retail spaces<br/></li> <li>Integration with switches, encoders, and I/O modules<br/></li> <li>Helps optimize security operations and business insights</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Axis in Iraq</h2>
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
						'text'  => 'Secure campuses and student facilities with integrated video and access control.',
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
						'text'  => 'Protect staff, customers, and assets through continuous surveillance and analytics.',
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
						'text'  => 'Explosion-protected and thermal cameras for hazardous environments and remote operations',
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
						'text'  => 'Ensure patient safety and compliance in hospitals and clinics.',
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
						'title' => 'Government & Public Safety',
						'text'  => 'Reliable surveillance for critical infrastructure, borders, and city monitoring.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Axis in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Axis Partner',
					'text'  => 'Authorized to supply and support Axis products throughout Iraq.',
				),
				array(
					'title' => 'Local Presence',
					'text'  => 'Offices in Baghdad, Basra, and Erbil offering fast response and support.',
				),
				array(
					'title' => 'Full Lifecycle Support',
					'text'  => 'From planning and installation to maintenance and system upgrades.',
				),
				array(
					'title' => 'Customized Deployments',
					'text'  => 'Solutions tailored to your site layout and risk level.',
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
			'title'      => 'Contact Osous Al Taqnia – Axis Partner in Iraq',
			'text'       => 'Looking to implement Axis CCTV and security solutions for your organization?<br>',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
