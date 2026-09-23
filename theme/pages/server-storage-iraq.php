<?php
/**
 * Server Storage Solutions in Iraq: Reliable, Scalable & Future-Proof
 *
 * Read from the reference page server-storage-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Server Storage Solutions in Iraq: Reliable, Scalable & Future-Proof',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/server-storage-iraq.webp',
					'alt'    => 'Enterprise server storage solutions by Osous Al Taqnia for Iraqi businesses',
					'srcset' => 'assets/images/server-storage-iraq-p-500.webp 500w, assets/images/server-storage-iraq-p-800.webp 800w, assets/images/server-storage-iraq.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.982666015625px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start">The Backbone of Business Continuity in the Digital Age</h2>
				<p class="p-r-sm c-on-primary self-center">In today's data-driven business landscape, efficient and reliable storage solutions are no longer optional—they're mission-critical. As organizations in Iraq and across the Middle East navigate digital transformation, the demand for robust server storage infrastructure continues to grow exponentially.</p>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c">Comprehensive Enterprise Storage Solutions for Evolving Businesses</h2>
				<p class="p-r-sm a-c">Osous Al Taqnia offers server storage solutions that boost productivity and can be expanded along with the growth of businesses without compromising performance.</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => 'Direct-Attached Storage (DAS)',
						'text'    => 'Perfect for single-user systems and small-scale businesses, DAS connects storage devices like SSDs or hard drives directly to servers or workstations. This solution is ideal for applications requiring faster access to smaller data volumes with minimal complexity.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Network-Attached Storage (NAS)',
						'text'  => 'This centralized storage system provides granular data access using common file-sharing protocols (NFS, SMB/CIFS, FTP). NAS solutions simplify management, offer excellent scalability, and support multiple users and client devices—making them perfect for small to medium-sized organizations looking to centralize their data resources.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => 'Storage Area Network (SAN)',
						'text'  => 'Designed for mission-critical applications and large-scale enterprises, SANs create high-speed networks of storage devices for low-latency data access. Using Fibre Channel, iSCSI, or FCoE for server interconnections, these solutions excel in environments where performance cannot be compromised.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section p-y-1">
		<div class="container-full bg-img-8">
			<div class="container">
				<div class="hflex flex-v-on-mobile">
					<div class="heading center self-center span-2">
						<h2 class="h2 c-on-primary">Our Strategic Technology Partners</h2>
					</div>
					<div class="grid-2">
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
		</div>
	</section>

	<section class="section">
		<div class="container padding-x-on-mobile">
			<div class="grid-2">
				<div class="cell self-center">
					<h2 class="h2 place-center">Our Strategic Technology Partners</h2>
					<p class="p-r-sm a-c">Osous Al Taqnia partners with industry leaders to deliver premium storage solutions across Iraq and the Middle East.</p>
				</div>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'icon' => array(
							'src' => 'assets/images/dell-partner.svg',
							'alt' => 'Dell',
						),
						'link' => home_url( '/dell-iraq/' ),
						'text' => 'Through Dell Technologies, we offer PowerStore, PowerMax, and Unity XT solutions that excel in hybrid cloud environments. Their advanced data reduction technologies and seamless scalability deliver the enterprise-grade reliability and cost efficiency our clients demand.',
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
							'alt' => 'Huawei',
						),
						'link' => home_url( '/huawei-iraq/' ),
						'text' => 'As a certified Huawei partner, we implement their OceanStor and FusionStorage solutions, combining exceptional performance with competitive pricing. Huawei\'s all-flash arrays and strong regional presence ensure optimal performance and outstanding local support for Middle Eastern enterprises.',
					)
				);
				?>
				<div class="row-tablet-3">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/hpe-partner.svg',
								'alt' => 'HPE',
							),
							'link'    => home_url( '/hpe-iraq/' ),
							'fill'    => true,
							'text'    => 'Our HPE partnership provides access to Primera, Nimble Storage, and Alletra systems with 100% availability guarantees. Their AI-driven platforms predict and prevent issues before they impact operations, perfectly aligning with our future-proof infrastructure commitment.<br>',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="h-75">
		<div class="container-full bg-img-7">
			<div class="grid-2 h-100">
				<div class="flex-v a-c-c self-end-tablet">
					<div class="container fit">
						<?php
						get_template_part(
							'includes/parts/photo-card',
							null,
							array(
								'title' => 'Empower Your Business with Next-Generation Storage Infrastructure',
								'text'  => 'In today\'s competitive business environment, your data storage infrastructure can be either a limitation or a strategic advantage. With our comprehensive range of enterprise server storage solutions, we help businesses in Iraq and across the Middle East transform their data management capabilities—creating resilient, high-performance foundations for sustainable growth and innovation.',
							)
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/call-to-action',
		null,
		array(
			'title'      => 'Take the Next Step in Your Digital Transformation',
			'text'       => 'Contact our storage experts today to discover how our customized solutions can address your specific challenges and empower your digital transformation journey.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
