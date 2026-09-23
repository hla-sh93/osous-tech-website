<?php
/**
 * Microsoft Dynamics 365 in Iraq
 *
 * Read from the reference page microsoft-dynamics-365-iraq.html by _tools/gen_pages.py. From here on this
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
			'title'   => 'Microsoft Dynamics 365 in Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-primary">
			<?php
			osous_image(
				array(
					'src'    => 'assets/images/microsoft-dynamic-1.webp',
					'alt'    => 'consultants implementing ERP system in Erbil',
					'srcset' => 'assets/images/microsoft-dynamic-1-p-500.webp 500w, assets/images/microsoft-dynamic-1-p-800.webp 800w, assets/images/microsoft-dynamic-1-p-1080.webp 1080w, assets/images/microsoft-dynamic-1.webp 1352w',
					'sizes'  => '(max-width: 767px) 100vw, (max-width: 991px) 727.96875px, 939.984375px',
				),
				'header-img-1'
			);
			?>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 c-on-primary self-start"><strong>Empowering Businesses in Iraq with Microsoft Dynamics 365</strong></h2>
				<p class="p-r-sm c-on-primary self-center">At Osous Al Taqnia, we help organizations across Iraq, from small businesses to large enterprises, simplify and modernize their operations using Microsoft Dynamics 365. Whether your goal is to streamline financial processes, boost sales performance, or deliver exceptional customer service, our solutions are tailored to meet the realities of the Iraqi business environment.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="grid-2">
				<h2 class="h2"><strong>Why Iraq Businesses Need Cloud Solutions</strong></h2>
				<p class="p">The Iraqi dynamic business landscape demands scalable, cost-efficient, and secure cloud solutions to drive innovation. Whether you're migrating to the cloud for the first time, optimizing existing cloud infrastructure, implementing multi-cloud strategies, or seeking cloud-based business intelligence solutions, partnering with the right cloud experts is critical for successful digital transformation.</p>
			</div>
		</div>
	</section>

	<section class="section full">
		<div class="container">
			<div class="heading center">
				<h2 class="h2 a-c"><strong>Why Microsoft Dynamics 365 Matters for Iraqi Businesses</strong></h2>
			</div>
			<div class="grid-2">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title'   => '<strong>Integrated Business Operations</strong>',
						'text'    => 'Connect finance, sales, operations, and customer service in one system to remove silos and improve collaboration across your company.',
						'outline' => true,
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => '<strong>AI-Driven Insights</strong>',
						'text'  => 'Use advanced analytics and AI tools to make smarter, data-backed decisions in real time.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => '<strong>Cost Savings and Efficiency</strong>',
						'text'  => 'Automate repetitive tasks to cut overhead costs and improve productivity.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'title' => '<strong>Seamless Microsoft Integration</strong>',
						'text'  => 'Dynamics 365 works natively with familiar Microsoft 365 apps such as Outlook, Teams, and SharePoint, while also integrating with third-party systems.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container padding-x-on-mobile">
			<div class="heading center">
				<?php
				osous_image(
					array(
						'src' => 'assets/images/microsoft-dynamics-365_package.svg',
						'alt' => 'Microsoft Dynamics 365 ERP deployment in Baghdad',
					),
					'microsoft_dynamics_icon'
				);
				?>
				<h2 class="h2 a-c"><strong>Microsoft Dynamics 365 Solutions in Iraq</strong></h2>
				<p class="p a-c">At Osous Al Taqnia, we deliver both ERP and CRM solutions built for Iraqi businesses.</p>
			</div>
			<div class="spacer-3x"></div>
			<div class="grid-2">
				<h2 class="h2 a-c span-2 place-center"><strong>ERP Solutions</strong></h2>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/businesscentral_scalable-1.svg',
							'alt' => '',
						),
						'link'    => home_url( '/cloud-optimization-iraq/' ),
						'title'   => 'Dynamics 365 Business Central',
						'text'    => 'Designed for SMEs, it unifies finance, inventory, and operations.',
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
							'src' => 'assets/images/financeoperations_scalable-1.svg',
							'alt' => '',
						),
						'link'    => home_url( '/azure-cloud-iraq/' ),
						'fill'    => true,
						'title'   => 'Dynamics 365 Finance',
						'text'    => 'Provides financial visibility, compliance, and support for enterprise-scale operations.<br>',
					)
				);
				?>
				<div class="row-mobile-5">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/supplychainmanagement_scalable-1.svg',
								'alt' => '',
							),
							'link'    => home_url( '/microsoft-office-365-iraq/' ),
							'fill'    => true,
							'title'   => '<strong>Dynamics 365 Supply Chain Management</strong>',
							'text'    => 'Enhances logistics, warehousing, and production efficiency.<br>',
						)
					);
					?>
				</div>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/clip-path-group.svg',
							'alt' => '',
						),
						'link'    => home_url( '/microsoft-office-365-iraq/' ),
						'title'   => 'Dynamics 365 Project Operations',
						'text'    => 'Ensures projects stay on track, on budget, and properly resourced.<br>',
					)
				);
				?>
			</div>
			<div class="spacer-8"></div>
			<div class="grid-2">
				<h2 class="h2 a-c span-2 place-center"><strong>CRM Solutions</strong></h2>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/sales_scalable-1.svg',
							'alt' => '',
						),
						'link'    => home_url( '/cloud-optimization-iraq/' ),
						'title'   => 'Dynamics 365 Sales',
						'text'    => 'Helps sales teams close deals faster with AI insights.',
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
							'src' => 'assets/images/customerserviceinsights_scalable-1.svg',
							'alt' => '',
						),
						'link'    => home_url( '/azure-cloud-iraq/' ),
						'fill'    => true,
						'title'   => 'Dynamics 365 Customer Insights',
						'text'    => ' Provides a full view of customer behavior for more personalized service.',
					)
				);
				?>
				<div class="row-mobile-5">
					<?php
					get_template_part(
						'includes/parts/card',
						null,
						array(
							'outline' => false,
							'icon'    => array(
								'src' => 'assets/images/marketing_scalable-1.svg',
								'alt' => '',
							),
							'link'    => home_url( '/microsoft-office-365-iraq/' ),
							'fill'    => true,
							'title'   => '<strong>Dynamics 365 Marketing</strong>',
							'text'    => 'Streamlines campaigns and improves customer engagement.<br>',
						)
					);
					?>
				</div>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'outline' => false,
						'icon'    => array(
							'src' => 'assets/images/fieldservice_scalable-1.svg',
							'alt' => '',
						),
						'link'    => home_url( '/microsoft-office-365-iraq/' ),
						'title'   => 'Dynamics 365 Field Service',
						'text'    => 'Equips field teams with mobile apps and IoT-powered features.<br>',
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
							'src' => 'assets/images/corehr_scalable-1.svg',
							'alt' => '',
						),
						'link'    => home_url( '/microsoft-office-365-iraq/' ),
						'title'   => 'Dynamics 365 Human Resources',
						'text'    => 'Simplifies employee management and career development.<br>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with  in Iraq</h2>
			</div>
			<div class="grid-3 grid-to-carousel">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon.svg',
							'alt' => '',
						),
						'title' => 'Manufacturing & Distribution',
						'text'  => 'Manufacturing & Distribution',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon_1.svg',
							'alt' => '',
						),
						'title' => 'Finance & Banking',
						'text'  => 'Strengthen compliance, reporting, and client relationship management.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-1_1.svg',
							'alt' => '',
						),
						'title' => 'Oil & Gas',
						'text'  => 'Optimize workforce planning, compliance, and asset management.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-1.svg',
							'alt' => '',
						),
						'title' => 'Retail & E-Commerce',
						'text'  => 'Deliver unified, cross-channel shopping experiences.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-2.svg',
							'alt' => '',
						),
						'title' => 'Public Sector & NGOs',
						'text'  => 'Improve service delivery, monitoring, and reporting for greater accountability.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-3.svg',
							'alt' => '',
						),
						'title' => 'Construction & Engineering',
						'text'  => 'Easily manage projects, procurement, and financial operations.',
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
			'heading' => '<strong>Why Choose Osous Al Taqnia as Your Microsoft Dynamics 365 Partner in Iraq?</strong>',
			'items'   => array(
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Certified Microsoft Partner</strong>',
					'textStyle'  => 'plain',
					'text'       => 'We follow Microsoft’s best practices to ensure successful ERP and CRM deployments.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Local Expertise</strong>',
					'textStyle'  => 'small',
					'text'       => 'Our consultants understand Iraq’s regulatory and business environment.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Industry-Specific Customization</strong>',
					'textStyle'  => 'small',
					'text'       => 'We tailor solutions for key industries like oil, banking, retail, logistics, and more.',
				),
				array(
					'titleStyle' => 'bold',
					'title'      => '<strong>Dedicated Support and Training</strong>',
					'textStyle'  => 'small',
					'text'       => 'We provide continuous support and equip your team with the training needed for long-term success.',
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
			'title'      => '<strong>Get Started with Osous Al Taqnia</strong>',
			'text'       => 'Work with our experts to implement Microsoft Dynamics 365 solutions designed for the Iraqi business landscape. Whether you’re in Baghdad, Erbil, or Basra, we’ll help you build a connected, smarter business.',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
