<?php
/**
 * Vertiv Partner in Iraq
 *
 * Read from the reference page vertiv-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Vertiv Partner in Iraq',
			'text'  => 'Power, cooling, and data center infrastructure solutions built for Iraq’s critical environments',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Vertiv Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified Vertiv partner in Iraq, providing integrated power, cooling, and IT infrastructure solutions for mission-critical environments. From telecom towers to data centers and oilfield operations, we help clients across Baghdad, Basra, and Erbil ensure system uptime, resilience, and future scalability.<br/>‍<br/>We deliver Vertiv hardware and software backed by local deployment teams, ensuring fast implementation and long-term support across Iraq’s complex operating environments.</p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-vertive.webp',
						'alt'    => 'Vertiv',
						'srcset' => 'assets/images/partner-bg-vertive-p-500.webp 500w, assets/images/partner-bg-vertive.webp 652w',
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
					<h2 class="h2">About Vertiv</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Vertiv is a global provider of digital infrastructure technologies, supporting over 130 countries with over three decades of experience. The company specializes in power protection, precision cooling, edge computing, and infrastructure management, with a clear focus on high-availability environments.<br/>‍<br/>Trusted by global telecom providers, governments, and critical industries, Vertiv solutions are engineered to perform under pressure, with a focus on uptime, scalability, and operational efficiency.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Vertiv Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Data Center Infrastructure',
						'prose' => '<ul role="list"> <li><strong>Modular and prefabricated data centers<br/></strong></li> <li>Rack systems, PDU (power distribution units), and enclosures<br/></li> <li>Environmental monitoring and secure cabling<br/></li> <li>Fully integrated turnkey deployments</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Power Protection & UPS Systems',
						'prose' => '<ul role="list"> <li><strong>Online double-conversion UPS systems<br/></strong></li> <li>Rack/tower UPS for critical business operations<br/></li> <li>Battery monitoring and predictive maintenance<br/></li> <li>Backup power is designed for unstable grid conditions in Iraq</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Precision Cooling Systems',
						'prose' => '<ul role="list"> <li>In-row, perimeter, and overhead cooling<br/></li> <li>DX (Direct Expansion) and chilled water cooling systems<strong><br/></strong></li> <li>Intelligent airflow control and environmental optimization<br/></li> <li>Designed for Iraq’s climate and high-density IT deployments</li> </ul> <p>‍</p>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'IT Infrastructure & Edge Computing',
						'prose' => '<ul role="list"> <li>Micro data centers for remote and harsh environments<strong><br/></strong></li> <li>Ruggedized cabinets and compact cooling/power modules<br/></li> <li>Ideal for oil &amp; gas, telecom base stations, and field offices<strong><br/></strong></li> <li>Centralized control for distributed IT assets</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Monitoring & Management Platforms',
						'prose' => '<ul role="list"> <li>Real-time power, cooling, and environment monitoring</li> <li>Remote diagnostics and <strong>predictive analytics</strong>‍</li> <li>Centralized dashboard for enterprise-scale visibility</li> <li>Maintenance alerts and SLA-based performance tools</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Vertiv in Iraq</h2>
			</div>
			<div class="grid-3 grid-to-carousel">
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
						'text'  => 'High-availability systems to prevent downtime in critical banking operations',
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
						'text'  => 'Rugged edge deployments for isolated sites and control stations',
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
						'text'  => 'Secure and uninterrupted power for hospitals and clinical IT systems',
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
						'text'  => 'National data and e-government infrastructure with SLA compliance',
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
			'heading' => 'Why Choose Osous Al Taqnia for Vertiv in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Vertiv Partner',
					'text'  => 'We supply licensed Vertiv products and solutions with full technical support, installation, and warranty-backed service across Iraq.',
				),
				array(
					'title' => 'Proven Local Deployment Experience',
					'text'  => 'With a deep track record in Iraq’s infrastructure sector, our team ensures Vertiv systems meet local compliance, electrical standards, and environmental demands.',
				),
				array(
					'title' => 'Full-Service Project Delivery',
					'text'  => 'From system design and procurement to installation and ongoing support, we manage every aspect of your power and infrastructure project.',
				),
				array(
					'title' => 'Sector-Specific Solutions',
					'text'  => 'We tailor every Vertiv deployment to your industry’s needs—whether it’s telecom scalability, banking uptime, or oil field resiliency.',
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
			'title'      => 'Contact Osous Al Taqnia – Vertiv Partner in Iraq',
			'text'       => 'Looking to upgrade your infrastructure with reliable power and thermal solutions?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
