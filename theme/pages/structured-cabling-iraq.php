<?php
/**
 * Structured Cabling Solutions in Iraq: Enterprise-Grade Infrastructure for Modern Businesses
 *
 * Read from the reference page structured-cabling-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Structured Cabling Solutions in Iraq: Enterprise-Grade Infrastructure for Modern Businesses',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start">Premium Structured Cabling Services for Iraqi Businesses</h2>
				<p class="p-r-sm c-on-primary self-center">Are network reliability issues affecting your business operations? Is your current infrastructure struggling to keep pace with increasing data demands? Osous Al-Taqnia delivers enterprise-grade structured cabling solutions in Iraq and across the country, providing the robust foundation your organization needs to thrive in today's digital landscape.</p>
			</div>
			<div class="spacer-3x"></div>
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/technicians-structured-cabling-baghdad.webp',
					'alt'    => 'Certified technicians installing structured cabling in Baghdad office',
					'srcset' => 'assets/images/technicians-structured-cabling-baghdad-p-500.webp 500w, assets/images/technicians-structured-cabling-baghdad-p-800.webp 800w, assets/images/technicians-structured-cabling-baghdad.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.96533203125px',
				),
				'header-img-1'
			);
			?>
		</div>
	</section>

	<section>
		<div class="container-default">
			<div class="heading">
				<h2 class="h2">Iraq's Premier Structured Cabling Company</h2>
			</div>
			<div class="grid-2">
				<p class="p">With over a decade of experience serving businesses throughout Iraq, Osous Al Taqnia has established itself as a leading structured cabling provider.</p>
				<p class="p">Our team of certified technicians delivers customized solutions designed specifically for the unique requirements of your facility and business operations.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Iraq's Premier Structured Cabling Company</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Network & Data Cabling',
						'text'    => 'Our expert technicians install high-performance Category 6A, 6, and 5e cabling systems to support your data networks, ensuring optimal speed and reliability for your critical business applications.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'title'   => 'Fiber Optic Cabling & Splicing',
						'text'    => 'We specialize in fiber optic installations for high-bandwidth applications, providing single-mode and multi-mode fiber solutions, fusion splicing for minimal signal loss, OTDR testing and certification, and end-to-end fiber network design.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'title'   => 'WiFi Infrastructure Cabling',
						'text'    => 'Power your wireless network with proper infrastructure support through strategic access point placement and cabling, PoE (Power over Ethernet) implementation, heat mapping for optimal coverage, and future-ready design for evolving WiFi standards.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'title'   => 'CCTV & Security System Cabling',
						'text'    => 'Our compliant CCTV cabling solutions ensure your surveillance systems operate at peak performance with HD and 4K camera support, PoE and traditional power options, centralized monitoring infrastructure, and integration with existing security systems.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'title'   => 'Telephony & Voice Cabling',
						'text'    => 'Support your communication systems with reliable voice infrastructure including IP telephony cabling, traditional analog systems, PBX connectivity, and call center infrastructure.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'fill'    => true,
						'title'   => 'Audiovisual Cabling',
						'text'    => 'Create immersive experiences with professional AV infrastructure for conference room systems, digital signage connectivity, presentation technology infrastructure, and integrated control systems.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section>
		<div class="container-sm">
			<div class="grid-2">
				<div class="cell">
					<h2 class="h2">Our Strategic Partners</h2>
					<p>Our partnership with Corning, the renowned pioneer in fiber optic technology, enables Osous Al Taqnia to provide superior fiber infrastructure solutions. Corning's industry-leading optical products form the backbone of our high-bandwidth installations, delivering exceptional reliability for today's demanding data transmission requirements.</p>
				</div>
				<div class="modal bg-img-partner">
					<?php
					osous_image(
						array(
							'src' => 'assets/images/corning.svg',
							'alt' => 'Corning',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/pinned-list',
		null,
		array(
			'heading' => 'Why Choose Osous Al Taqnia for Your Structured Cabling Needs in Iraq',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Expert Certification',
					'textStyle'  => 'plain',
					'text'       => 'Our certified technicians hold industry-recognized certifications including BICSI and manufacturer credentials, ensuring expert implementation.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Premium Components',
					'textStyle'  => 'small',
					'text'       => 'We use only premium components from trusted manufacturers, backed by comprehensive warranties.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Proven Experience',
					'textStyle'  => 'small',
					'text'       => 'With over 10 successful projects completed across Iraq, our experience includes 20+ data cabling implementations, 15+ fiber optic projects, and 10+ network room setups.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Superior Service',
					'textStyle'  => 'small',
					'text'       => 'Our service prioritizes clear communication, strict adherence to timelines, and 24/7 emergency support availability.',
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
			'title'      => 'Ready to Transform Your Network Infrastructure?',
			'text'       => 'Contact Osous Al Taqnia today to discuss your structured cabling requirements. Our experts will help you design and implement a solution that enhances performance, increases reliability, and provides the foundation for your organization\'s continued success.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
