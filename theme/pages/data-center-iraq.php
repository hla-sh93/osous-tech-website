<?php
/**
 * Data Center Solutions for Iraqi Businesses
 *
 * Read from the reference page data-center-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Data Center Solutions for Iraqi Businesses',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<div class="grid-2">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/data-center-iraq.webp',
						'alt'    => 'Data center solutions for Iraqi businesses by Osous Al Taqnia',
						'srcset' => 'assets/images/infrastructure.Advanced-Prefabricated01-p-500.webp 500w, assets/images/data-center-iraq.webp 653w',
						'sizes'  => '(max-width: 767px) 100vw, 652.9861450195312px',
					),
					'header-img-1'
				);
				?>
				<div class="cell self-center">
					<h2 class="h2 c-on-primary">Revolutionizing Iraq's Data Center Infrastructure with Smart Modular Solutions</h2>
					<p class="p c-on-secondary">In today's digital landscape, businesses across Iraq rely heavily on robust data center infrastructure to maintain competitive advantage. Osous Al Taqnia has positioned itself as a leader in providing comprehensive data center solutions that address the evolving needs of modern Iraqi enterprises, with a special focus on innovative prefabricated modular data centers.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-2 m-b-0">
		<div class="container">
			<div class="cell span-2">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/smart-modular-data-center-iraq.webp',
						'alt'    => 'Smart modular data center components optimized for Iraq\'s climate conditions',
						'srcset' => 'assets/images/smart-modular-data-center-iraq-p-500.webp 500w, assets/images/smart-modular-data-center-iraq-p-800.webp 800w, assets/images/smart-modular-data-center-iraq.webp 1080w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.982666015625px',
					),
					'image h-50'
				);
				?>
				<div class="spacer-1x"></div>
				<h2 class="h2 c-on-secondary">Transforming Your Iraqi Infrastructure with HCI Solutions</h2>
				<div class="grid-2">
					<p class="p c-on-secondary">The modern data center has become essential for Iraqi business operations. With virtualization transforming data environments, organizations need solutions delivering 24/7 availability, smaller footprints, lower OpEx, and simplified management.</p>
					<p class="p c-on-secondary">Osous Al Taqnia integrates servers, storage, virtualization, and backup systems with top-tier security measures specifically designed for Iraqi business environments.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2">Smart Modular Data Centers: Ready for Immediate Deployment Across Iraq</h2>
				<p class="p-r-sm">The cornerstone of Osous Al Taqnia's offering is their "Smart Modular Data Centers" – prefabricated, scalable solutions designed for immediate deployment and future expansion throughout Iraq. These next-generation data centers feature:</p>
				<div class="grid-3">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'fill'    => true,
							'title'   => 'Smart, Compact Integrated Components',
							'text'    => 'optimized for Iraqi facilities',
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
							'title'   => 'Comprehensive Hardware and Software Solutions',
							'text'    => 'for complete functionality',
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
							'title'   => 'Integrated Power Distribution Units (PDUs)',
							'text'    => 'designed for Iraq\'s infrastructure',
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
							'title'   => 'Uninterruptible Power Supply (UPS) Systems',
							'text'    => 'to manage Iraq\'s power challenges',
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
							'title'   => 'Advanced Monitoring Capabilities',
							'text'    => 'for real-time oversight in remote locations',
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
							'title'   => 'Efficient Cooling Infrastructure',
							'text'    => 'engineered for Iraq\'s climate conditions',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section c-surface-neutral">
		<div class="container">
			<div class="grid-2">
				<div class="modal">
					<div class="vflex">
						<h2 class="h2 c-on-secondary">Perfect Solutions for Space-Constrained Iraqi Environments</h2>
						<p class="p-r-sm c-on-secondary">Prefabricated Data Center Modules provide the ideal solution for Iraqi businesses operating with limited physical space while requiring dedicated facilities for constant monitoring and high security. These purpose-built environments offer:</p>
						<ul class="list c-on-secondary" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">Controlled operating environment resistant to Iraq's environmental challenges</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Customized design from the ground up for local Iraqi requirements</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Flexible installation options across various Iraqi locations</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Easily modifiable power, cooling, and wiring configurations to adapt to local infrastructure</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="modal c-surface-white">
					<div class="vflex">
						<h2 class="h2">Scalable Solutions for Organizations of All Sizes in Iraq</h2>
						<p class="p-r-sm">Osous Al Taqnia offers remarkable flexibility in their modular approach with:</p>
						<ul class="list" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">Single modules ideal for small and medium-sized data centers in emerging Iraqi markets</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Multiple module configurations for large-scale deployments in major Iraqi cities</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Solutions tailored for Iraqi government, finance, and other demanding sectors</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Specialized configurations addressing the unique challenges present in the Iraqi market</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container padding-x-on-mobile">
			<div class="grid-2">
				<div class="cell self-center">
					<h2 class="h2 a-c place-center">Strategic Technology Partnerships for Enhanced Iraqi Infrastructure</h2>
				</div>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/dell-partner.svg',
							'alt' => 'Dell',
						),
						'link'  => home_url( '/dell-iraq/' ),
						'title' => 'Enterprise-Grade Computing Infrastructure',
						'text'  => 'Dell provides the reliable servers, storage, and networking equipment that power our prefabricated data centers, delivering performance and redundancy for mission-critical applications across Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/highlight-card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/huawel-partner.svg',
							'alt' => 'Huawei',
						),
						'link'  => home_url( '/huawei-iraq/' ),
						'title' => 'Enterprise-Grade Computing Infrastructure',
						'text'  => 'Huawei\'s cutting-edge networking solutions ensure seamless connectivity throughout Iraq, providing high-bandwidth connections and intelligent management systems for distributed infrastructure.',
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
								'src' => 'assets/images/vertiv-partner.svg',
								'alt' => 'Vertiv power and cooling solutions for Iraq\'s challenging environment',
							),
							'link'    => home_url( '/vertiv-iraq/' ),
							'fill'    => true,
							'title'   => 'Power & Cooling Solutions Optimized for Iraqi Conditions',
							'text'    => 'Vertiv delivers reliable power and cooling solutions engineered for Iraq\'s challenging environment, ensuring optimal performance during unstable power conditions while minimizing energy consumption.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Your Data Center Needs',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => 'Local Iraqi Expertise',
					'textStyle'  => 'plain',
					'text'       => 'Deep understanding of Iraq\'s unique infrastructure challenges',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Rapid Deployment Across Iraq',
					'textStyle'  => 'small',
					'text'       => 'Get your data center operational in a fraction of the time',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'Scalable Design for Growth',
					'textStyle'  => 'small',
					'text'       => 'Grow your capacity without disrupting operations',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => 'End-to-End Support Throughout Iraq',
					'textStyle'  => 'small',
					'text'       => 'From initial consultation to ongoing maintenance',
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
			'title'      => 'Transform Your Data Center Infrastructure Today',
			'text'       => 'Ready to revolutionize your IT operations? Contact an Osous Al Taqnia expert today and discover how our prefabricated data center modules can transform your business infrastructure across Iraq.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
