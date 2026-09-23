<?php
/**
 * Network & Wi-Fi Solutions for Modern Iraqi Enterprises
 *
 * Read from the reference page network-wifi-solutions-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Infrastructure',
			'title'   => 'Network & Wi-Fi Solutions for Modern Iraqi Enterprises',
		)
	);
	?>

	<section>
		<div class="container">
			<div class="grid-1">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/network-infrastructure-solutions-iraqi-enterprises.webp',
						'alt'    => 'network infrastructure solutions for Iraqi enterprises',
						'srcset' => 'assets/images/network-infrastructure-solutions-iraqi-enterprises-p-500.webp 500w, assets/images/network-infrastructure-solutions-iraqi-enterprises-p-800.webp 800w, assets/images/network-infrastructure-solutions-iraqi-enterprises.webp 1080w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 940.0000610351562px',
					),
					'image-custom-1'
				);
				?>
				<div class="container custom-1">
					<div class="modal c-surface-primary c-on-surface-primary">
						<div class="content">
							<h2 class="h2">Driving Connectivity, Security, and Mobility with Next-Generation Networking Solutions in Iraq</h2>
							<div class="grid-2">
								<p class="p">In today's digital-first business environment across Iraq, robust network infrastructure and seamless wireless connectivity are no longer optional—they're essential for growth, productivity, and competitive advantage.</p>
								<p class="p">Osous Al Taqnia's comprehensive network and mobility solutions are designed to help infrastructure leaders throughout Iraq streamline operations, improve connectivity, and ensure secure access across their organizations.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section full c-surface-secondary">
		<div class="container padding-x-on-mobile">
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Complete Network and Mobility Solutions for Future-Ready Iraqi Enterprises</h2>
				<p class="p-r-sm c-on-secondary a-c">Our networking and mobility solutions create robust, secure infrastructure that drives productivity and seamless connectivity for forward-thinking organizations throughout Iraq.</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Network Infrastructure Optimization',
						'text'  => 'We deliver tailored network infrastructures with high-performance LANs and scalable WANs optimized for peak performance in Iraqi business environments. Our future-proof designs evolve with your business, ensuring long-term value from your network investments.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Secure and Scalable SD-WAN Solutions',
						'text'  => 'Osous Al Taqnia\'s SD-WAN solutions enhance connectivity across distributed locations in Iraq while prioritizing critical applications. Iraqi organizations benefit from improved security, simplified management, reduced costs, and secure communication across all environments.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Mobility Solutions for a Flexible Iraqi Workforce',
						'text'  => 'Empower employees throughout Iraq to work effectively from anywhere with secure, comprehensive mobility solutions. We provide robust MDM, secure data access, enterprise-grade security for remote work, and seamless BYOD support that maintains consistent user experiences across Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Unified Communication Solutions',
						'text'  => 'Our integrated platforms combine voice, video, messaging and collaboration tools into cohesive ecosystems that streamline communication across teams and devices in Iraq, improving efficiency and accelerating decision-making through real-time collaboration.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Network Security & Compliance',
						'text'  => 'Osous Al Taqnia implements comprehensive security with enterprise-grade firewalls, intrusion prevention, and proactive threat monitoring. Our solutions ensure regulatory compliance and robust protection for increasingly distributed workforces across Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Cloud-Integrated Network Solutions',
						'text'  => 'As cloud adoption accelerates in Iraq, we create seamless connectivity between on-premises and cloud environments with optimized performance and enhanced security. Our expertise supports efficient hybrid network management and strategic cloud migrations for Iraqi businesses.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Why Choose Osous Al-Taqnia's Network and Wi-Fi Solutions?</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Expertise & Innovation',
						'text'  => 'With years of experience in delivering innovative networking and mobility solutions across Iraq, our specialists have a proven track record in implementing single and multi-site wireless deployments across various Iraqi industries including offices, hospitality, engineering, leisure, and health sectors.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Customized Solutions for Iraqi Businesses',
						'text'  => 'We understand that every Iraqi business is unique. Our solutions are customized to meet your specific requirements, ensuring that your network and mobility systems align with your strategic goals—from simple Wi-Fi access for small offices in Baghdad to extensive, complex Wi-Fi access, mesh, and point-to-point solutions across multiple Iraqi locations.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'End-to-End Support Throughout Iraq',
						'text'  => 'From initial consultation to ongoing management and optimization, Osous Al-Taqnia provides comprehensive support at every stage of your network and mobility journey, ensuring maximum uptime and performance for businesses throughout Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Scalability & Flexibility for Growing Iraqi Enterprises',
						'text'  => 'Our solutions grow with your business, adapting to changing needs and ensuring you\'re always equipped to handle the demands of Iraq\'s dynamic marketplace.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Security & Compliance for Iraqi Regulations',
						'text'  => 'Our solutions are designed to keep your data and networks secure while ensuring compliance with Iraqi industry standards and regulatory requirements.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading center">
					<h2 class="h2 a-c">Our Strategic Technology Partners in Iraq</h2>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-dell-dark.svg',
								'alt' => 'Dell',
							),
							'link'    => home_url( '/dell-iraq/' ),
							'fill'    => true,
							'text'    => 'Dell delivers high-performance infrastructure that forms the backbone of our network solutions in Iraq, ensuring scalability and long-term value with hardware that evolves alongside your business needs.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/huawel-partner.svg',
								'alt' => 'Huawei ',
							),
							'link' => home_url( '/huawei-iraq/' ),
							'text' => 'Huawei\'s innovative technologies power our cloud-integrated network solutions in Iraq, enabling seamless connectivity between on-premises and cloud environments with optimized performance and enhanced security for Iraqi businesses.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/partner-white-fortinet.svg',
								'alt' => 'Fortinet',
							),
							'link' => home_url( '/fortinet-iraq/' ),
							'text' => 'Fortinet provides the robust security architecture behind our network solutions in Iraq, featuring enterprise-grade firewalls and proactive threat monitoring that ensure regulatory compliance for distributed workforces across the country.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-dark-aruba.svg',
								'alt' => 'Aruba',
							),
							'link'    => home_url( '/aruba-iraq/' ),
							'fill'    => true,
							'text'    => 'Aruba offers affordable, secure wireless solutions across Iraq with integrated access points that are simple to set up and manage. Their technology provides automatic rogue intrusion detection and elimination while scaling seamlessly with your business growth in the Iraqi market.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/partner-dark-hikvision.svg',
								'alt' => 'Hikvision',
							),
							'link'    => home_url( '/avigilon-iraq/' ),
							'fill'    => true,
							'text'    => 'Hikvision delivers state-of-the-art video surveillance and security solutions that integrate seamlessly with our network infrastructure across Iraq. Their innovative security technologies provide comprehensive protection for businesses and organizations throughout the country with reliable monitoring capabilities and advanced analytics.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<div class="flex-v">
					<h2 class="h2"><strong>Get Connected Today in Iraq</strong></h2>
				</div>
				<p class="p">Get up to speed with Osous Al Taqnia's Wi-Fi and networking solutions that work for your community's education, residential, and business needs across Iraq. Whether you're looking to enhance campus connectivity in Baghdad, optimize your corporate network in Basra, or implement secure mobility solutions in Erbil, our team of experts is ready to help you achieve seamless connectivity and flexibility.</p>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Transform Your Iraqi Network Infrastructure',
			'text'       => 'Talk to Osous Al Taqnia experts today to transform your network infrastructure for the digital age in Iraq.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
