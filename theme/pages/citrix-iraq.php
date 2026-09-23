<?php
/**
 * Citrix Partner in Iraq
 *
 * Read from the reference page citrix-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Citrix Partner in Iraq',
			'text'  => 'Secure digital workspaces, virtualization, and remote access solutions tailored for businesses in Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Citrix Reseller in Iraq</h2>
						<p>Osous Al Taqnia is a certified Citrix partner in Iraq, delivering secure digital workspace, virtualization, and remote access solutions to both public and private sector organizations. Our Citrix-certified engineers help streamline IT operations, enhance end-user experience, and maintain strong security across geographically dispersed teams.<br/>‍<br/>Whether you need to virtualize desktops, enable secure app access, or manage hybrid work environments, we tailor each Citrix deployment to suit Iraq’s infrastructure realities and regulatory requirements.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-citrix.webp',
						'alt'    => 'Citrix',
						'srcset' => 'assets/images/partner-bg-citrix-p-500.webp 500w, assets/images/partner-bg-citrix.webp 652w',
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
					<h2 class="h2">About Citrix</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Citrix is a global leader in secure digital workspace technology, empowering employees to work from anywhere without compromising performance or security. Known for its robust virtualization, cloud delivery, and zero-trust architecture, Citrix simplifies IT while strengthening cybersecurity.<br/>‍<br/>Organizations across the world rely on Citrix to manage remote access, optimize performance, and ensure safe, consistent user experiences across devices and networks.<br/></p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Citrix Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Virtualization and Desktop as a Service (DaaS)',
						'text'  => 'Citrix DaaS enables secure, cloud-hosted or on-premise delivery of virtual desktops and applications to any device. This flexible approach reduces IT infrastructure complexity and allows centralized management while improving user mobility and security.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Citrix Workspace Management',
						'text'  => 'A centralized platform that integrates applications, files, and desktops into a secure digital workspace. Citrix Workspace enhances visibility and simplifies IT management, enabling teams in Iraq to access resources from anywhere with full control and oversight.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Secure Access & Networking (Citrix Secure Private Access + SD-WAN)',
						'text'  => 'Citrix’s zero-trust network access solutions replace legacy VPNs with adaptive, identity-driven security policies. Combined with SD-WAN and NetScaler, these technologies ensure fast, reliable application delivery and secure access across multiple sites in Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Citrix Analytics and Performance Monitoring',
						'text'  => 'With real-time analytics, administrators can monitor user behavior, detect anomalies, and track performance. These insights help IT teams identify threats early, resolve issues quickly, and maintain a high-quality user experience.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Citrix Hypervisor (XenServer)',
						'text'  => 'This enterprise-grade virtualization platform supports large-scale deployments of applications, desktops, and workloads. Citrix Hypervisor is ideal for businesses in Iraq requiring flexible and scalable compute environments across on-premise and hybrid cloud infrastructures.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Citrix in Iraq</h2>
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
						'text'  => 'Citrix powers virtual labs and e-learning platforms for universities and institutions throughout Iraq.',
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
						'text'  => 'Benefit from encrypted multi-device access to critical banking applications and records.',
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
						'text'  => 'Citrix enables high-performance virtual environments for field operations and corporate headquarters.',
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
						'text'  => 'Use Citrix to securely access and manage sensitive data across hospitals, clinics, and remote offices.',
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
						'text'  => 'relies on Citrix for secure desktop access and centralized IT operations that support hybrid teams.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Citrix in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Citrix Expertise',
					'text'  => 'Our engineering team holds Citrix certifications and has hands-on experience in managing complex deployments involving virtual apps, remote desktops, and secure access solutions.',
				),
				array(
					'title' => 'Local Insight with Global Standards',
					'text'  => 'Operating across Baghdad, Basra, and Erbil, we design solutions that meet Iraq’s infrastructure needs while aligning with Citrix’s globally proven architecture and security practices.',
				),
				array(
					'title' => 'End-to-End Workspace Solutions',
					'text'  => 'We provide complete lifecycle services—from initial consultation and licensing to deployment, user onboarding, and long-term support. This ensures your Citrix environment is secure, scalable, and fully aligned with business goals.',
				),
				array(
					'title' => 'Reliable Support & Competitive Licensing',
					'text'  => 'As an authorized Citrix partner, we offer genuine licensing, competitive pricing models, and in-country support to help you manage costs while maintaining system integrity and availability.',
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
			'title'      => 'Contact Osous Al Taqnia – Citrix Partner in Iraq',
			'text'       => 'Looking to deploy virtual desktops or enable secure remote access across your organization in Iraq?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
