<?php
/**
 * Iraq's Leading IT &amp; Cybersecurity Solutions Provider
 *
 * Read from the reference page index.html by _tools/gen_pages.py. From here on this
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
		'includes/sections/hero',
		null,
		array(
			'title' => 'Iraq\'s <span class="highlight">Leading IT</span> &amp; <span class="underline">Cybersecurity</span> <span class="thin">Solutions</span> <span>Provider</span>',
			'text'  => 'Delivering enterprise-grade technology services and advanced cybersecurity protection tailored for Iraq\'s unique business environment. Transform your IT infrastructure into a secure, strategic business asset.',
			'logos' => array(
				array(
					'src' => 'assets/images/partner-white-hikvision.svg',
					'alt' => 'Hikvision',
				),
				array(
					'src' => 'assets/images/partner-white-vmware.svg',
					'alt' => 'Vmware',
				),
				array(
					'src' => 'assets/images/partner-white-hewlett.svg',
					'alt' => 'HPE Enterprise',
				),
				array(
					'src' => 'assets/images/partner-white-veeam.svg',
					'alt' => 'Veeam',
				),
				array(
					'src' => 'assets/images/kaspersky-endpoint-security-iraq.svg',
					'alt' => 'Kaspersky',
				),
				array(
					'src' => 'assets/images/microsoft-endpoint-manager-iraq.svg',
					'alt' => 'Microsoft',
				),
				array(
					'src' => 'assets/images/huawel-partner.svg',
					'alt' => 'Huawei',
				),
				array(
					'src' => 'assets/images/partner-white-fortinet.svg',
					'alt' => 'Fortinet',
				),
				array(
					'src' => 'assets/images/partner-white-citrix.svg',
					'alt' => 'Citrix',
				),
				array(
					'src' => 'assets/images/partner-white-aruba.svg',
					'alt' => 'Aruba',
				),
				array(
					'src' => 'assets/images/dell-partner.svg',
					'alt' => 'Dell',
				),
				array(
					'src' => 'assets/images/partner-acronis-white.svg',
					'alt' => 'Acronis',
				),
				array(
					'src' => 'assets/images/partner-white-vertiv.svg',
					'alt' => 'Vertiv',
				),
				array(
					'src' => 'assets/images/partner-white-sentinelone.svg',
					'alt' => 'SentinelOne',
				),
				array(
					'src' => 'assets/images/corning.svg',
					'alt' => 'Corning',
				),
				array(
					'src' => 'assets/images/partner-white-motorola.svg',
					'alt' => 'Motorola Solutions',
				),
			),
		)
	);
	?>

	<section>
		<div class="container-full fit c-surface-neutral">
			<div class="grid-2">
				<div class="flex-v a-c-c self-center">
					<div class="content">
						<h2 class="h2">Advanced IT & Cybersecurity Solutions for Iraq's Digital Transformation</h2>
						<p class="p">Osous Al Taqnia delivers comprehensive IT security and technology services specifically designed for Iraq's challenging business environment. As the trusted cybersecurity partner for leading organizations across Baghdad, Erbil, and Basra, we transform your IT infrastructure into a secure business asset that drives growth, strengthens your security posture, and improves operational efficiency.</p>
					</div>
				</div>
				<div class="grid-3">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/home-03.webp',
							'alt'    => 'Osous Al Taqnia professional IT services team in Iraq',
							'srcset' => 'assets/images/home-03-p-500.webp 500w, assets/images/home-03-p-800.webp 800w, assets/images/home-03.webp 809w',
							'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 809px',
						),
						'span-3'
					);
					?>
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/home-02.webp',
							'alt'    => 'Advanced cybersecurity services for businesses across Iraq',
							'srcset' => 'assets/images/home-02-p-500.webp 500w, assets/images/home-02.webp 505w',
							'sizes'  => '(max-width: 505px) 100vw, 505px',
						),
						'span-2'
					);
					?>
					<?php
					osous_image(
						array(
							'src' => 'assets/images/home-01.webp',
							'alt' => '24/7 managed IT support services for Iraqi organizations',
						),
						'h-100'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section bg-img-5">
		<div class="container">
			<div class="heading">
				<h2 class="h2 c-on-secondary">Our Comprehensive IT Services</h2>
			</div>
			<div class="grid-2">
				<div class="cell justify-start">
					<h3 class="h3 c-on-secondary"><span class="highlight">Enterprise IT Infrastructure</span></h3>
					<p class="p c-on-secondary">Osous Al Taqnia delivers comprehensive IT security and technology services specifically designed for Iraq's challenging business environment. As the trusted cybersecurity partner for leading organizations across Baghdad, Erbil, and Basra, we transform your IT infrastructure into a secure business asset that drives growth, strengthens your security posture, and improves operational efficiency.</p>
					<div class="hflex">
						<?php osous_button( 'Explore IT Infrastructure Solutions', home_url( '/it-infrastructure-iraq/' ), 'text' ); ?>
					</div>
				</div>
				<div class="cell justify-start">
					<h3 class="h3 c-on-secondary"><span class="highlight">Advanced Cybersecurity Services</span></h3>
					<p class="p c-on-secondary">Protect your critical digital assets with our comprehensive security services including Next-Gen Firewalls, Endpoint Protection, Email Security, and advanced SOC services tailored specifically for Iraqi businesses and threats.</p>
					<div class="hflex">
						<?php osous_button( 'Discover Cybersecurity Solutions', home_url( '/cybersecurity-iraq/' ), 'text' ); ?>
					</div>
				</div>
				<div class="cell justify-start">
					<h3 class="h3 c-on-secondary"><span class="highlight">Complete Data Protection</span></h3>
					<p class="p c-on-secondary">Safeguard your business-critical information with enterprise-grade backup solutions, disaster recovery planning, and advanced data loss prevention strategies designed to meet Iraqi regulatory requirements</p>
					<div class="hflex">
						<?php osous_button( 'Learn About Data Protection', home_url( '/data-protection-iraq/' ), 'text' ); ?>
					</div>
				</div>
				<div class="cell justify-start">
					<h3 class="h3 c-on-secondary"><span class="highlight">Microsoft Cloud Solutions</span></h3>
					<p class="p c-on-secondary">Accelerate your digital transformation with our Microsoft-certified cloud services, including Azure implementation, Microsoft 365 deployment, and cloud optimization services backed by our Baghdad-based support team.</p>
					<div class="hflex">
						<?php osous_button( 'Explore Cloud Solutions', home_url( '/cloud-services-iraq/' ), 'text' ); ?>
					</div>
				</div>
				<div class="cell justify-start">
					<h3 class="h3 c-on-secondary"><span class="highlight">Professional IT Services</span></h3>
					<p class="p c-on-secondary">Focus on your core business objectives while we handle your complete technology needs with 24/7 proactive monitoring, maintenance, and dedicated support from IT professionals who understand Iraq's business landscape.</p>
					<div class="hflex">
						<?php osous_button( 'Discover IT Services', home_url( '/it-services-iraq/' ), 'text' ); ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/card-stack',
		null,
		array(
			'title' => 'Why Leading Organizations Throughout Iraq Choose Osous Al Taqnia',
			'items' => array(
				array(
					'number' => '01',
					'media'  => 'none',
					'title'  => 'Proven Clients',
					'text'   => 'Trusted by major businesses across Iraq',
				),
				array(
					'number' => '02',
					'media'  => 'none',
					'title'  => 'Iraq-Based Support',
					'text'   => '24/7 technical help from Baghdad',
				),
				array(
					'number' => '03',
					'media'  => 'none',
					'title'  => 'Microsoft Partner',
					'text'   => 'Officially recognized for cloud deployment',
				),
				array(
					'number' => '04',
					'media'  => 'none',
					'title'  => 'Certified Professionals',
					'text'   => 'Industry-certified team of experts',
				),
				array(
					'number' => '05',
					'media'  => 'none',
					'title'  => 'All-in-One Services',
					'text'   => 'From infrastructure to cloud and cybersecurity',
				),
				array(
					'number' => '06',
					'media'  => 'none',
					'title'  => 'Local Experience',
					'text'   => 'In-depth knowledge of Iraq’s business challenges',
				),
			),
		)
	);
	?>

	<?php
	get_template_part(
		'includes/sections/industries-carousel',
		null,
		array(
			'title' => 'Industry-Specific IT Security Solutions for Iraq',
			'text'  => 'We deliver tailored cybersecurity solutions for Iraq\'s key economic sectors:',
			'items' => array(
				array(
					'icon'  => array(
						'src' => 'assets/images/property-1government.svg',
						'alt' => '',
					),
					'title' => 'Government & Public Sector',
					'text'  => 'Secure solutions for Iraqi government agencies and ministries.',
				),
				array(
					'icon'  => array(
						'src' => 'assets/images/property-1banking.svg',
						'alt' => '',
					),
					'title' => 'Banking & Finance',
					'text'  => 'High-compliance systems for financial institutions across Iraq.<br>',
				),
				array(
					'icon'  => array(
						'src' => 'assets/images/property-1healthcare.svg',
						'alt' => '',
					),
					'title' => 'Healthcare',
					'text'  => 'Secure patient data and improve clinical workflows for Iraqi hospitals.',
				),
				array(
					'icon'  => array(
						'src' => 'assets/images/property-1book.svg',
						'alt' => '',
					),
					'title' => 'Education',
					'text'  => 'IT infrastructure and digital tools for schools and universities in Iraq.',
				),
				array(
					'icon'  => array(
						'src' => 'assets/images/property-1oil-gas.svg',
						'alt' => '',
					),
					'title' => 'Oil & Gas',
					'text'  => 'Industrial-grade IT solutions for Iraq’s energy and petroleum sectors.',
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
			'title'      => 'Transform Your Iraqi Business with Professional IT Services',
			'text'       => 'Choosing the right IT partner is key in Iraq’s fast-moving market. Whether you’re launching new services, upgrading infrastructure, or improving cybersecurity, Osous Al Taqnia offers the solutions and local support to help your business grow.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
