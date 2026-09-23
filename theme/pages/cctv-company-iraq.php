<?php
/**
 * Advanced CCTV Security Solutions in Iraq
 *
 * Read from the reference page cctv-company-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Advanced CCTV Security Solutions in Iraq',
		)
	);
	?>

	<section class="section padding-none">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2 c-on-primary">Cutting-Edge Surveillance Technology for Complete Protection</h2>
						<p class="p c-on-primary-subtle">In today's security-conscious environment, CCTV surveillance systems have become a critical component of comprehensive security strategies across Iraq. Whether for businesses, commercial properties, or residential spaces, these systems offer powerful monitoring capabilities that deter crime, provide evidence, and deliver peace of mind.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/technician-cctv-installation-baghdad-office.webp',
							'alt'    => 'Osous Al Taqnia technician installing advanced CCTV system in Baghdad office building',
							'srcset' => 'assets/images/technician-cctv-installation-baghdad-office-p-500.webp 500w, assets/images/technician-cctv-installation-baghdad-office.webp 652w',
							'sizes'  => '(max-width: 767px) 100vw, 651.9791870117188px',
						),
						'image h-50'
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Key Components of Effective Cloud Optimization</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Motion-Activated Systems',
						'text'    => 'Intelligent cameras that record only when movement is detected, optimizing storage while ensuring all relevant activity is captured. These systems reduce storage requirements while focusing on meaningful events.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Bullet Cameras',
						'text'  => 'Weatherproof designs with extended focal lengths, ideal for outdoor applications and perimeter security. Their visible presence serves as an effective deterrent while providing clear imaging.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Dome Cameras',
						'text'  => 'Compact, vandal-resistant housing that blends seamlessly into indoor environments while providing robust surveillance coverage. Their discreet design makes them ideal for retail, office, and indoor commercial spaces.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'PTZ (Pan-Tilt-Zoom) Cameras',
						'text'  => 'Versatile systems with the ability to pan, tilt, and zoom to cover wide areas from a single installation point. These high-resolution cameras can track movement and provide detailed imagery of large spaces.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'IP Network Cameras',
						'text'  => 'Modern network-connected cameras allowing seamless integration, high-quality images, and convenient remote access. These systems offer greater flexibility and expandability than traditional analog systems.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Thermal Imaging',
						'text'  => 'Specialized cameras that detect temperature differences and function effectively in zero-light conditions. These advanced systems are invaluable for high-security applications and challenging environmental conditions.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Night Vision Technology',
						'text'  => 'Ensuring continuous surveillance regardless of lighting conditions, these cameras provide clear video in low or no-light environments. Infrared capabilities extend monitoring capabilities around the clock.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Box Camera Solutions',
						'text'  => 'Rectangular designs with adjustable lenses, ideal for specific monitoring areas such as ATMs, entry points, or specific high-value locations. These customizable systems can be tailored to specific security requirements.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Specialty Surveillance',
						'text'  => 'For unique environments, options include explosion-proof, medical-grade, and solar-powered cameras designed for challenging conditions. These specialized systems address specific environmental and regulatory requirements.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section p-b-0">
		<div class="container-full c-surface-neutral">
			<div class="container padding-x-on-mobile">
				<div class="heading center">
					<h2 class="h2 a-c">Our Trusted Partners</h2>
				</div>
				<div class="grid-2">
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
							'text'    => 'As an authorized Hikvision partner in Iraq, Osous Al Taqnia provides access to industry-leading surveillance products with innovative technology and reliability. Their cameras offer Iraqi businesses exceptional image clarity and advanced AI capabilities for intelligent monitoring.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/highlight-card',
						null,
						array(
							'icon' => array(
								'src' => 'assets/images/partner-white-motorola.svg',
								'alt' => 'Motorola Solutions',
							),
							'link' => home_url( '/avigilon-iraq/' ),
							'text' => 'Our Motorola Solutions partnership delivers enterprise-grade security systems that integrate video, access control, and communications. Iraqi organizations benefit from Motorola\'s expertise and mission-critical performance for dependable protection of sensitive installations.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section p-y-1">
		<div class="container-full bg-img-10">
			<div class="container fit">
				<div class="heading center self-center span-2">
					<h2 class="h2 c-on-primary">Our Strategic Technology Partners</h2>
				</div>
				<div class="grid-4">
					<?php
					get_template_part(
						'includes/parts/photo-card',
						null,
						array(
							'title' => 'Expert Design & Installation',
							'text'  => 'Our experienced professionals specialize in creating customized security layouts based on thorough site assessments and specific client requirements throughout Iraq.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/photo-card',
						null,
						array(
							'title' => 'System Integration',
							'text'  => 'Seamless integration with existing security infrastructure, building management systems, and IT networks to create comprehensive security ecosystems tailored to Iraqi security standards.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/photo-card',
						null,
						array(
							'title' => 'Comprehensive Maintenance',
							'text'  => 'Regular service and support to ensure optimal system performance, including preventive maintenance, firmware updates, and technical troubleshooting for all clients across Iraq.',
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/photo-card',
						null,
						array(
							'title' => 'Ongoing Technical Support',
							'text'  => 'Dedicated assistance for system operators, ensuring proper usage and addressing any operational questions or concerns that arise in your Iraqi security operations.',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Take Control of Your Security Today',
			'text'       => 'Enhance your security infrastructure with professional CCTV solutions customized for your specific needs in Iraq. Osous Al Taqnia\'s security experts are ready to provide a comprehensive assessment and tailored recommendations for your property.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
