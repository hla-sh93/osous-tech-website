<?php
/**
 * Dell Partner in Iraq
 *
 * Read from the reference page dell-iraq.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Dell Partner in Iraq',
			'text'  => 'Certified servers, storage, and enterprise IT solutions for government and business sectors in Iraq',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white elevation">
			<div class="grid-2">
				<div class="cell self-center">
					<div class="content">
						<h2 class="h2">Authorized Dell Reseller in Iraq</h2>
						<p>Osous Al Taqnia is an authorized Dell Technologies partner in Iraq, delivering enterprise-grade infrastructure solutions including servers, storage systems, networking, and data center platforms. We support organizations across Baghdad, Erbil, and Basra, helping them modernize and operate stable IT environments using Dell Technologies.<br/>‍<br/>Whether deploying a new data center or expanding existing infrastructure, our team manages the entire project lifecycle, from technical assessment and procurement through deployment, configuration, and post-installation support, ensuring reliability, compliance, and long-term performance.<br/></p>
					</div>
				</div>
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/partner-bg-dell.webp',
						'alt'    => 'Dell',
						'srcset' => 'assets/images/partner-bg-dell-p-500.webp 500w, assets/images/partner-bg-dell.webp 652w',
						'sizes'  => '(max-width: 652px) 100vw, 652px',
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
					<h2 class="h2">About Dell Technologies</h2>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				</div>
				<p>Dell Technologies is a global provider of enterprise IT infrastructure, supporting organizations with dependable compute, storage, networking, and data center platforms. Dell solutions are widely adopted in environments that require consistent performance, security, and scalability.<br/>‍<br/>Its portfolio includes PowerEdge servers, PowerStore and Unity storage, hyperconverged infrastructure, and integrated data center management tools. Dell Technologies supports both on-premises and hybrid environments, enabling organizations to operate and grow with confidence.</p>
			</div>
		</div>
	</section>

	<section class="section c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c">Dell Solutions Offered by Osous Al Taqnia</h2>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Dell PowerEdge Servers',
						'prose' => '<p>Osous Al Taqnia supplies the full range of Dell PowerEdge servers to support diverse enterprise workloads.</p> <ul role="list"> <li>Tower and rack servers for branch offices and data centers</li> <li>Architecture and sizing based on application and workload needs</li> <li>Support for virtualization, databases, and core business systems</li> <li>Accessories including racks, rails, and redundant power components</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Dell EMC Storage Systems',
						'prose' => '<p>We deliver scalable Dell EMC storage solutions designed for structured and unstructured data.</p> <ul role="list"> <li>Unity and PowerStore platforms (hybrid and all-flash)</li> <li>SAN and NAS storage for enterprise and regulated environments</li> <li>Built-in backup, replication, and data protection capabilities</li> <li>Designed for future expansion and long-term capacity planning</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Dell Networking & HCI  Solutions',
						'prose' => '<p>Dell networking and HCI solutions simplify data center operations while maintaining performance.</p> <ul role="list"> <li>Enterprise switches and networking components</li> <li>Hyperconverged platforms integrated with virtualization environments</li> <li>Centralized management and high availability</li> <li>Suitable for data centers, edge sites, and distributed locations</li> </ul>',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'title' => 'Dell Hard Drives & Storage Media',
						'prose' => '<p>We provide Dell-certified storage media for server and data center environments.</p> <ul role="list"> <li>High-performance SSDs for latency-sensitive workloads</li> <li>High-capacity HDDs for archival and bulk storage</li> <li>Dual-drive configurations for balanced performance and capacity</li> <li>Compatible with Dell servers and storage platforms</li> </ul>',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container grid-to-carousel">
			<div class="heading center">
				<h2 class="h2 a-c">Industries We Support with Dell in Iraq</h2>
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
						'text'  => 'Dell servers and storage support campus systems, learning platforms, and academic data environments.',
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
						'text'  => 'High-availability infrastructure supporting core banking, secure data storage, and regulatory compliance.',
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
						'text'  => 'Robust compute and storage platforms for field operations, analytics, and headquarters systems.',
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
						'text'  => 'Secure infrastructure for patient data, hospital systems, and clinical applications.',
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
						'text'  => 'Reliable servers and storage platforms for ministries, public institutions, and national IT projects.',
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
			'heading' => 'Why Choose Osous Al Taqnia for Dell in Iraq?',
			'items'   => array(
				array(
					'title' => 'Certified Dell Partner',
					'text'  => 'Authorized Dell Technologies reseller in Iraq providing genuine hardware, licensing, and vendor-backed support.',
				),
				array(
					'title' => 'Local Insight with Global Quality',
					'text'  => 'Dell solutions designed with an understanding of Iraq’s operational, security, and compliance requirements.',
				),
				array(
					'title' => 'End-to-End Lifecycle Management',
					'text'  => 'Assessment, design, procurement, deployment, and ongoing support handled by one accountable partner.',
				),
				array(
					'title' => 'In-Country Support & Fast Response',
					'text'  => 'Local technical teams and stock availability across Baghdad, Basra, and Erbil.',
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
			'title'      => 'Contact Osous Al Taqnia – Dell Partner in Iraq',
			'text'       => 'Need Dell PowerEdge servers, enterprise storage, or data center infrastructure in Iraq?',
			'buttonText' => 'Requst Consulation',
			'anchor'     => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
