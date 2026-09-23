<?php
/**
 * Microsoft Partner in Iraq
 *
 * Read from the reference page microsoft-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Microsoft Partner in Iraq',
			'text'  => 'Microsoft 365, Azure, Dynamics 365, and SharePoint solutions delivered with certified expertise across Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Microsoft Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a trusted Microsoft Partner and Cloud Solution Provider in Iraq. We help businesses and public institutions accelerate digital transformation using Microsoft’s full suite of cloud and enterprise tools. From Microsoft 365 to Azure and Dynamics 365, we design and implement integrated solutions that improve collaboration, optimize infrastructure, and ensure data security.<br/>‍<br/>Operating across Baghdad, Basra, and Erbil, we offer localized expertise, fast support, and end-to-end project delivery tailored to Iraq’s business landscape.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-microsoft.webp',
						'alt'    => 'Microsoft',
						'srcset' => 'assets/images/partner-bg-microsoft-p-500.webp 500w, assets/images/partner-bg-microsoft.webp 652w',
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
					<h2 class="h2">About Microsoft Solutions</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Microsoft is a global technology leader enabling organizations to work smarter, connect securely, and operate efficiently in a digital-first world. Its cloud ecosystem—including Microsoft 365, Azure, Dynamics 365, and Teams—powers millions of businesses globally with scalable tools for collaboration, data storage, enterprise resource planning, and cybersecurity.<br/>‍<br/>Through cloud-native applications, intelligent automation, and AI integration, Microsoft empowers teams to drive innovation while maintaining compliance and control.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Microsoft Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Microsoft 365 – Cloud Collaboration',
						'prose' => '<p>Enable secure, remote collaboration across teams:</p> <ul role="list"> <li>Access to Word, Excel, Outlook, Teams, OneDrive, and SharePoint</li> <li>Real-time file sharing, co-authoring, and mobile access</li> <li>Built-in compliance, identity protection, and secure Exchange Online email</li> <li>Best suited for SMEs, enterprises, and public-sector organizations</li> </ul> <p>‍</p>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Microsoft Azure – Scalable Cloud Infrastructure',
						'prose' => '<p>Migrate and manage applications with ease:</p> <ul role="list"> <li>Azure Virtual Machines, databases, storage, and networking<br/></li> <li>AI, machine learning, and IoT platform support</li> <li>Disaster recovery, secure backups, and hybrid cloud integration</li> <li>Cost-efficient and elastic cloud hosting for growing operations</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Microsoft Dynamics 365 – ERP & CRM Solutions',
						'prose' => '<p>Unify business operations under one cloud platform:</p> <ul role="list"> <li>Automate finance, sales, service, and operations</li> <li>Gain real-time insights with built-in analytics</li> <li>Personalize customer experiences</li> <li>Cloud-based and customizable for both SMEs and large enterprises</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Microsoft SharePoint – Document and Content Management',
						'prose' => '<p>Improve productivity through structured collaboration:</p> <ul role="list"> <li>Centralized content sharing and secure document access</li> <li>Workflow automation and version control</li> <li>Integrated with Teams and Office 365 suite</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Microsoft Teams – Unified Communication Hub',
						'prose' => '<p>Stay connected across locations and time zones</p> <ul role="list"> <li>Chat, video calls, and shared workspaces</li> <li>Integration with calendars and Office apps</li> <li>Teams Phone (Direct Routing) for business-grade calling</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Microsoft Defender & Sentinel – Enterprise Security',
						'prose' => '<p>Protect users, devices, and data:</p> <ul role="list"> <li>Endpoint protection and identity monitoring</li> <li>Threat detection and automated response</li> <li>Built-in compliance and advanced governance tools</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Microsoft in Iraq</h2>
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
						'text'  => 'Microsoft 365 and Teams for academic collaboration',
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
						'text'  => 'Cloud and endpoint security, plus CRM/ERP automation',
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
						'text'  => 'Azure hybrid solutions for remote field and HQ integration',
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
						'text'  => 'Compliance-ready cloud solutions for clinical data',
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
						'text'  => 'Secure digital transformation for ministries and agencies',
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
			'heading' => 'Why Choose Osous Al Taqnia for Microsoft in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Microsoft Partner',
					'text'  => 'Microsoft licenses us to deliver cloud, security, and enterprise solutions tailored to Iraq’s public and private sectors.',
				),
				array(
					'title' => 'Local Insight with Multilingual Support',
					'text'  => 'With teams based in Baghdad, Erbil, and Basra, we offer fast response times, Arabic and English support, and region-specific configurations.',
				),
				array(
					'title' => 'Complete Lifecycle Management',
					'text'  => 'We handle consultation, licensing, migration, deployment, and post-sales support, ensuring a seamless experience from day one.',
				),
				array(
					'title' => 'Cost-Effective Microsoft Licensing',
					'text'  => 'We help businesses select the most appropriate Microsoft 365 or Azure plans, optimizing usage while minimizing costs.',
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
			'title'      => 'Contact Osous Al Taqnia – Microsoft Partner in Iraq',
			'text'       => 'Looking to upgrade your productivity tools, modernize IT infrastructure, or implement secure remote work solutions?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
