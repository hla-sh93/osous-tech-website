<?php
/**
 * Microsoft 365 Suite: Do More with Less for Iraqi Businesses
 *
 * Read from the reference page microsoft-office-365-iraq.html by _tools/gen_pages.py. From here on this
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
			'eyebrow' => 'Cloud Services',
			'title'   => 'Microsoft 365 Suite: Do More with Less for Iraqi Businesses',
		)
	);
	?>

	<section>
		<div class="container">
			<div class="grid-1">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/microsoft-365-business-solutions-iraq.webp',
						'alt'    => 'Microsoft 365 business solutions in Iraq by Osous Al Taqnia',
						'srcset' => 'assets/images/microsoft-36509-p-500.webp 500w, assets/images/microsoft-36509-p-800.webp 800w, assets/images/microsoft-365-business-solutions-iraq.webp 890w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 890px',
					),
					'image-custom-1'
				);
				?>
				<div class="container custom-1">
					<div class="modal c-surface-primary c-on-surface-primary">
						<div class="content">
							<h2 class="h2">Transform Your Business with Microsoft 365's Enterprise Solutions</h2>
							<div class="grid-2">
								<p class="p">Microsoft 365 Suite transforms ordinary MS Office into a powerful business solution by integrating chat, calls, online meetings, and collaboration tools like Teams, OneDrive, and Office apps, all with advanced security features.</p>
								<p class="p">This comprehensive platform offers cost-effective solutions for both remote and onsite work, safeguarding business data while streamlining IT management for organizations of all sizes across Iraq.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part(
		'includes/sections/split-section',
		null,
		array(
			'title' => 'Key Components of Effective Cloud Optimization',
			'items' => array(
				array(
					'title'      => 'Future-Proof Infrastructure',
					'text'       => 'Build a scalable, flexible Azure Cloud Infrastructure that grows with your business and easily adapts to changing needs.',
					'image'      => array(
						'src'    => 'assets/images/customized-implementation.webp',
						'alt'    => '',
						'srcset' => 'assets/images/microsoft-36506-p-500.webp 500w, assets/images/microsoft-36506-p-800.webp 800w, assets/images/customized-implementation.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 921px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => 'Accessibility Across Iraq for Maximum Flexibility',
					'text'       => 'Access your files and data across desktops, laptops, and tablets throughout Iraq and beyond, providing unmatched flexibility and scalability for your Iraqi business operations.',
					'image'      => array(
						'src'    => 'assets/images/comprehensive-support.webp',
						'alt'    => '',
						'srcset' => 'assets/images/microsoft-36504-p-500.webp 500w, assets/images/microsoft-36504-p-800.webp 800w, assets/images/comprehensive-support.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 921px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => 'Advanced Security and Data Protection',
					'text'       => 'Microsoft 365 Business Premium offers comprehensive solutions to protect and secure your data, with Microsoft handling IT and providing protection against emerging threats in the Iraqi cybersecurity landscape.',
					'image'      => array(
						'src'    => 'assets/images/maximized-roi.webp',
						'alt'    => '',
						'srcset' => 'assets/images/microsoft-36503-p-500.webp 500w, assets/images/microsoft-36503-p-800.webp 800w, assets/images/maximized-roi.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 921px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => 'Seamless Cloud Integration for Iraqi Organizations',
					'text'       => 'All documents are always on the cloud, allowing users to access them from any Microsoft 365-supported device anywhere in Iraq without third-party storage systems.',
					'image'      => array(
						'src'    => 'assets/images/local-expertise.webp',
						'alt'    => '',
						'srcset' => 'assets/images/microsoft-36502-p-500.webp 500w, assets/images/microsoft-36502-p-800.webp 800w, assets/images/local-expertise.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 921px',
					),
					'mediaFirst' => true,
				),
				array(
					'title'      => 'Cost-Effective Subscription Model',
					'text'       => 'Replace the one-time perpetual license fee with a flexible yearly/monthly subscription that includes regular updates and new features as they\'re released, optimized for Iraqi business budgets.',
					'image'      => array(
						'src'    => 'assets/images/microsoft-36501.webp',
						'alt'    => '',
						'srcset' => 'assets/images/microsoft-36501-p-500.webp 500w, assets/images/microsoft-36501-p-800.webp 800w, assets/images/microsoft-36501.webp 921w',
						'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 921px',
					),
					'mediaFirst' => true,
				),
			),
		)
	);
	?>

	<section class="section">
		<div class="container">
			<div class="content">
				<h2 class="h2">The Business Impact of <span class="highlight">Microsoft 365 </span>for Iraqi Organizations</h2>
				<div class="grid-2">
					<p class="h3 thin">Microsoft 365 elevates Iraqi businesses to the next level by enabling effective collaboration, streamlining communications, and providing enterprise-level security. enhances productivity through integrated tools,</p>
					<p class="h3 thin">ensures business continuity with cloud-based workflows optimized for Iraq's infrastructure, and delivers significant cost savings by consolidating multiple solutions into one comprehensive platform for the Iraqi market.</p>
				</div>
			</div>
			<div class="spacer-3x"></div>
			<div class="divider gradient-1"></div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading">
				<h2 class="h2 a-c">Key Features and Applications</h2>
				<div class="a-c">Microsoft 365 delivers a complete business toolkit:</div>
			</div>
			<div class="grid-4">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/logo-exchange-online.png',
							'alt' => 'Exchange Online',
						),
						'title' => 'Exchange Online',
						'text'  => 'Professional email and calendaring tailored for Iraqi businesses',
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
							'src' => 'assets/images/logo-microsoft-teams.png',
							'alt' => 'Microsoft Teams',
						),
						'fill'    => true,
						'title'   => 'Microsoft Teams',
						'text'    => 'Integrated communication and collaboration hub connecting teams across Baghdad, Basra, and beyond',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/microsoft-365-solutions.png',
							'alt' => 'Office apps',
						),
						'title' => 'Office Apps',
						'text'  => 'Word, Excel, PowerPoint with real-time co-authoring for seamless teamwork',
					)
				);
				?>
				<div class="cell row-tablet-2 row-mobile-4">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/cloud-optimization.png',
								'alt' => 'OneDrive',
							),
							'fill'    => true,
							'title'   => 'OneDrive for Business',
							'text'    => 'Secure cloud storage with advanced sharing options optimized for Iraq\'s connectivity',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-sm">
			<div class="flex-h">
				<?php
				osous_image(
					array(
						'src' => 'assets/images/icon-lamp.svg',
						'alt' => '',
					),
					'icon-128'
				);
				?>
				<div class="h3">Pro Tip: Choose from various plans tailored specifically to your Iraqi business needs, enhancing both productivity and connectivity while addressing local requirements.</div>
			</div>
		</div>
	</section>

	<section class="section m-b-0">
		<div class="container-full c-surface-neutral">
			<div class="container">
				<div class="heading center">
					<h2 class="h2 a-c">Why Choose Osous Al Taqnia?</h2>
					<p class="p a-c">Leveraging Microsoft 365's powerful capabilities requires proficient engineers who know the platform inside and out. Osous Al Taqnia's team of exceptionally skilled Iraqi engineers and administrators offers:</p>
				</div>
				<div class="grid-2">
					<?php
					get_template_part(
						'includes/parts/overlay-card',
						null,
						array(
							'number' => '01',
							'title'  => 'Customized Implementation',
							'text'   => 'Configure Microsoft 365 to meet your specific business requirements in Iraq\'s unique business environment',
							'image'  => array(
								'src'    => 'assets/images/microsoft-36510.webp',
								'alt'    => '',
								'srcset' => 'assets/images/microsoft-36510-p-500.webp 500w, assets/images/microsoft-36510-p-800.webp 800w, assets/images/microsoft-36510.webp 890w',
								'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 890px',
							),
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/overlay-card',
						null,
						array(
							'number' => '02',
							'title'  => 'Comprehensive Support',
							'text'   => 'Ongoing assistance with all subscription features from our Baghdad-based support team',
							'image'  => array(
								'src'    => 'assets/images/microsoft-36505.webp',
								'alt'    => '',
								'srcset' => 'assets/images/82f39ff146efd71424ea1f7177d45d7c_microsoft-36505-p-500.webp 500w, assets/images/82f39ff146efd71424ea1f7177d45d7c_microsoft-36505-p-800.webp 800w, assets/images/82f39ff146efd71424ea1f7177d45d7c_microsoft-36505-p-1080.webp 1080w, assets/images/microsoft-36505.webp 1328w',
								'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 890px',
							),
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/overlay-card',
						null,
						array(
							'number' => '03',
							'title'  => 'Maximized ROI',
							'text'   => 'Ensure you\'re getting maximum value from your Microsoft 365 investment',
							'image'  => array(
								'src'    => 'assets/images/microsoft-36508.webp',
								'alt'    => '',
								'srcset' => 'assets/images/microsoft-36508-p-500.webp 500w, assets/images/microsoft-36508-p-800.webp 800w, assets/images/microsoft-36508.webp 890w',
								'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 890px',
							),
						)
					);
					?>
					<?php
					get_template_part(
						'includes/parts/overlay-card',
						null,
						array(
							'number' => '04',
							'title'  => 'Local Expertise',
							'text'   => 'Our Iraqi team understands the specific needs and challenges of businesses operating in Iraq',
							'image'  => array(
								'src'    => 'assets/images/microsoft-36507.webp',
								'alt'    => '',
								'srcset' => 'assets/images/microsoft-36507-p-500.webp 500w, assets/images/microsoft-36507-p-800.webp 800w, assets/images/microsoft-36507.webp 890w',
								'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.984375px, 890px',
							),
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
