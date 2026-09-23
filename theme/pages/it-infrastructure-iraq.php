<?php
/**
 * IT Infrastructure
 *
 * Read from the reference page it-infrastructure-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'IT Infrastructure',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/it-infrastructure-solutions-iraq.webp',
					'alt'    => 'Osous Al Taqnia enterprise IT infrastructure solutions in Iraq',
					'srcset' => 'assets/images/it-infrastructure-solutions-iraq-p-500.webp 500w, assets/images/it-infrastructure-solutions-iraq-p-800.webp 800w, assets/images/it-infrastructure-solutions-iraq.webp 1080w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 939.982666015625px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start"><strong>Transform Your Business with Enterprise-Grade IT Infrastructure in Iraq</strong></h2>
				<p class="p-r-sm c-on-primary self-center">In today's rapidly evolving digital landscape, having a robust IT infrastructure is no longer optional—it's essential for business growth and competitive advantage in Iraq's dynamic market. Osous Al Taqnia delivers comprehensive IT infrastructure services with tailored solutions that enhance operational efficiencies, boost productivity, accelerate time-to-market, and reduce overall costs for Iraqi businesses.</p>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c"><strong>Our Core IT Infrastructure Solutions</strong></h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'      => '<strong>Campus LAN &amp; Wi-Fi Solutions</strong>',
						'text'       => 'Maximize connectivity and productivity with our enterprise-grade Campus LAN and Wi-Fi solutions across Iraq. We design, implement, and manage high-performance wireless networks tailored to your specific business environment.Our Wi-Fi solutions work for diverse environments including corporate offices, educational institutions, and residential communities throughout Iraq, ensuring everyone stays connected with reliable, high-speed access.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/network-wifi-solutions-iraq/' ),
						'outline'    => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>HCI (Hyper-Converged Infrastructure) Solutions</strong>',
						'text'       => 'Enable unparalleled agility and efficiency with our unified Hyper-Converged Infrastructure solutions that provide seamless scalability and adaptability to evolving business needs in Iraq\'s growing technology sector.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/hci-solutions-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Servers and Storage Solutions</strong>',
						'text'       => 'Meet the growing data demands of your Iraqi business with our comprehensive server and storage solutions built for performance, security, and efficiency.With years of experience providing server solutions throughout Iraq, we\'ve gained extensive knowledge of various applications used by businesses today, allowing us to recommend and implement the perfect configuration for your needs.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/server-storage-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Virtualization Solutions</strong>',
						'text'       => 'Transform your IT infrastructure with our virtualization solutions that optimize resource utilization and provide greater flexibility for Iraqi organizations.Our virtualization expertise helps businesses across Iraq consolidate infrastructure, reduce costs, and improve operational efficiency while maintaining performance and reliability.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/virtualization-solutions-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Structured Cabling</strong>',
						'text'       => 'Build a reliable foundation for your IT infrastructure with our professional structured cabling services designed for Iraq\'s unique business environments.A well-designed structured cabling system ensures reliable connectivity and provides the backbone for all your technology solutions in any Iraqi facility.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/structured-cabling-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>CCTV Surveillance Solutions</strong>',
						'text'       => 'Protect your assets and enhance security with our comprehensive CCTV surveillance systems tailored for Iraqi businesses and institutions.Our expertise covers security planning, CCTV installations, and maintenance, helping you formulate effective surveillance systems customized to your specific needs throughout Iraq.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/cctv-company-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title'      => '<strong>Modular Data Center Solutions</strong>',
						'text'       => 'Deploy flexible, scalable data center infrastructure with our modular data center solutions designed for Iraq\'s evolving technology landscape.Our modular data center approach enables Iraqi businesses to adapt quickly to changing requirements while maintaining reliability and performance.',
						'buttonText' => 'Read more',
						'buttonUrl'  => home_url( '/data-center-iraq/' ),
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
			'heading' => '<strong>Why Choose Osous Al Taqnia for IT Infrastructure Services?</strong>',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Free Active Consultation</strong>',
					'textStyle'  => 'plain',
					'text'       => 'Get expert advice tailored to your specific business challenges in Iraq',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Certified Specialists</strong>',
					'textStyle'  => 'small',
					'text'       => 'Access our extensive pool of infrastructure specialists with deep knowledge of Iraqi market requirements',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Continuous Management</strong>',
					'textStyle'  => 'small',
					'text'       => 'Benefit from ongoing support, monitoring, and optimization for Iraqi businesses',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Cost Optimization</strong>',
					'textStyle'  => 'small',
					'text'       => 'Reduce infrastructure costs while improving performance for maximum ROI in Iraq',
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-neutral">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2"><strong>Build a Strong Backbone Infrastructure to Power Your Next-Generation Digital Services</strong></h2>
						<p class="p">Our ability to integrate solutions from many technology OEMs in tandem with our certified and qualified resources makes Osous Al Taqnia the ideal hybrid infrastructure solutions partner in Iraq. We help organizations across Iraq get the most out of their IT infrastructure by choosing solutions that combine in-depth industry expertise, cutting-edge technology, and best-in-class execution.</p>
					</div>
				</div>
				<div class="cell media-first">
					<?php
					osous_image(
						array(
							'src'    => 'assets/images/hyper-converged-infrastructure-iraqi-businesses.webp',
							'alt'    => 'Hyper-converged infrastructure solutions for Iraqi businesses',
							'srcset' => 'assets/images/cyber-security.Next-Generation-Firewall01-p-500.webp 500w, assets/images/cyber-security.Next-Generation-Firewall01-p-800.webp 800w, assets/images/hyper-converged-infrastructure-iraqi-businesses.webp 921w',
							'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 95vw, 920.9896240234375px',
						),
						'image h-50'
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
			'title'      => '<strong>Take the Next Step with Osous Al Taqnia</strong>',
			'text'       => 'Ready to transform your IT infrastructure in Iraq? <strong>Contact our experts today</strong> for a personalized consultation and discover how our tailored solutions can drive innovation and growth for your business throughout Iraq.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
