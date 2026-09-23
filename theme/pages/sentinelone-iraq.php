<?php
/**
 * SentinelOne Partner in Iraq
 *
 * Read from the reference page sentinelone-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'SentinelOne Partner in Iraq',
			'text'  => 'AI-powered endpoint protection and XDR solutions for fast, autonomous cybersecurity across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized SentinelOne Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a trusted SentinelOne partner in Iraq, delivering intelligent cybersecurity solutions that protect organizations in real time. Using the Singularity XDR platform and AI-driven defense, we support government agencies, energy companies, financial institutions, and enterprises across Iraq in detecting and stopping ransomware, advanced persistent threats, and zero-day attacks.<br/>‍<br/>Our in-country teams based in Baghdad, Erbil, and Basra ensure end-to-end service, covering deployment, threat monitoring, and rapid incident response.</p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-sentinelone.webp',
						'alt'    => 'SentinelOne',
						'srcset' => 'assets/images/partner-bg-sentinelone-p-500.webp 500w, assets/images/partner-bg-sentinelone.webp 652w',
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
					<h2 class="h2">About SentinelOne</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>SentinelOne is a global leader in autonomous cybersecurity, offering AI-powered protection across endpoints, cloud, identity, and networks. Its flagship Singularity XDR platform consolidates threat prevention, detection, and response into a single, real-time interface.<br/>‍<br/>Recognized by Gartner, MITRE ATT&amp;CK, and Forrester, SentinelOne is trusted by security teams worldwide to reduce dwell time, enhance visibility, and accelerate response.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">SentinelOne Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Singularity XDR – Unified Extended Detection & Response',
						'prose' => '<ul role="list"> <li>End-to-end detection, investigation, and response across endpoints, identity, cloud, and email<br/></li> <li>Automated threat containment in seconds</li> <li>Centralized visibility for managing distributed attack surfaces in Iraq<br/></li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Autonomous AI Threat Detection',
						'prose' => '<ul role="list"> <li>Machine-learning models to detect threats without manual input<br/></li> <li>Stops ransomware, phishing, zero-day malware, and insider threats<br/></li> <li>Behavioral analytics and predictive prevention are built into every endpoint</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Seamless Deployment & Integration',
						'prose' => '<ul role="list"> <li>Quick deployment across thousands of devices<br/></li> <li>Integrates with SIEM, SOAR, and existing cybersecurity stacks<br/></li> <li>Minimal resource usage with fast setup—ideal for Iraqi IT teams</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Cloud & Hybrid Workload Security',
						'prose' => '<ul role="list"> <li>Protects workloads in AWS, Azure, and hybrid environments</li> <li>Secure identity, access, and runtime behaviors</li> <li>Designed for Iraq’s evolving enterprise and public cloud ecosystems</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with SentinelOne in Iraq</h2>
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
						'text'  => 'Cloud security and identity protection for academic networks',
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
						'text'  => 'Compliance-ready solutions to safeguard sensitive financial data',
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
						'text'  => 'Real-time endpoint protection for field operations and data centers',
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
						'text'  => 'AI-powered defense against ransomware in clinical systems',
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
						'text'  => 'Infrastructure-wide protection for ministries and national services',
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
			'heading' => 'Why Choose Osous Al Taqnia for SentinelOne in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified SentinelOne Partner',
					'text'  => 'We are an authorized reseller offering licensed SentinelOne solutions, technical assistance, and full access to SentinelOne support resources.',
				),
				array(
					'title' => 'Local Insight with National Reach',
					'text'  => 'Our cybersecurity teams in Baghdad, Basra, and Erbil understand Iraq’s infrastructure, data privacy laws, and compliance needs, delivering tailored protection.',
				),
				array(
					'title' => 'End-to-End Cybersecurity Management',
					'text'  => 'We oversee every stage—from system design and rollout to real-time alerting, fine-tuning, and support—so your security posture stays current.',
				),
				array(
					'title' => 'Proven Success Across Critical Sectors',
					'text'  => 'We’ve successfully deployed SentinelOne in oil fields, banks, government institutions, and healthcare providers across Iraq, maintaining 24/7 security visibility.',
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
			'title'      => 'Contact Osous Al Taqnia – SentinelOne Partner in Iraq',
			'text'       => 'Want to detect and neutralize threats before they impact your operations?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
