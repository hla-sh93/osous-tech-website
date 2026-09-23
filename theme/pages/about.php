<?php
/**
 * Osous Al Taqnia ICT & Cybersecurity Experts in Iraq
 *
 * Read from the reference page about.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Osous Al Taqnia ICT & Cybersecurity Experts in Iraq',
		)
	);
	?>

	<section class="section c-surface-white">
		<div class="container">
			<div class="heading center span-2">
				<h2 class="h2 a-c"><strong>Who We Are</strong></h2>
			</div>
			<div class="grid-3 gap-md">
				<div class="cell">
					<p>At Osous Al Taqnia, we don’t just keep pace with digital transformation — we lead it in Iraq.</p>
				</div>
				<div class="cell">
					<p>Since 2020, we've been a trusted ICT and cybersecurity partner for public and private sector organizations across Iraq.</p>
				</div>
				<div class="cell">
					<p>Our goal is to guide businesses toward digital maturity through innovative, secure, and results-driven IT solutions.</p>
				</div>
			</div>
		</div>
	</section>

	<div class="divider c-border-neutral"></div>

	<section class="section c-surface-white">
		<div class="container">
			<div class="heading center span-2">
				<h2 class="h2 a-c"><strong>Why We Exist </strong></h2>
			</div>
			<div class="grid-3 gap-md">
				<div class="cell">
					<p>Iraq’s evolving digital landscape demands tailored, robust, and future-focused technology solutions.</p>
				</div>
				<div class="cell">
					<p>We provide comprehensive ICT services that ensure your systems are secure, scalable, and ready for growth.</p>
				</div>
				<div class="cell">
					<p>Whether upgrading legacy infrastructure or developing new systems, we are your reliable digital transformation partner in Iraq.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section p-b-0 c-surface-secondary bg-img-1">
		<div class="container">
			<div class="spacer-5x"></div>
			<div class="heading center">
				<h2 class="h2 c-on-secondary a-c"><strong>Our Value Proposition</strong></h2>
			</div>
			<div class="grid-4">
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '01',
						'title'  => '<strong>Smart</strong>',
						'text'   => 'Our solutions are adaptive and designed for long-term value.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '02',
						'title'  => '<strong>Secure</strong>',
						'text'   => 'We prioritize system integrity and data protection at every level.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '03',
						'title'  => '<strong>Scalable</strong>',
						'text'   => 'Flexible infrastructure that evolves with your business in Iraq.',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/numbered-card',
					null,
					array(
						'number' => '04',
						'title'  => '<strong>Strategic</strong>',
						'text'   => 'Deep expertise aligned with your organizational objectives.',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section p-y-0 c-surface-neutral">
		<div class="container">
			<div class="grid-2">
				<div class="modal bg-img-1">
					<div class="vflex">
						<h2 class="h3 c-on-secondary"><strong>Our Mission Statement</strong></h2>
						<ul class="list c-on-secondary" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">We enable businesses across Iraq to succeed in the digital economy.</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">With local insight and global technologies, we deliver agile and high-impact ICT and cybersecurity solutions.</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Our services drive resilience, performance, and sustainable growth.</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="modal c-surface-white">
					<div class="vflex">
						<h2 class="h3">Our Vision</h2>
						<ul class="list" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">We envision a future where every organization in Iraq is digitally confident, cyber-secure, and prepared for tomorrow.</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">With a growing footprint across the country, we aim to lead Iraq’s digital transformation journey.</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">We are committed to shaping smarter businesses, safer communities, and a more connected Iraq.</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="heading center">
				<h2 class="h2"><strong>Our Technology Partners</strong></h2>
			</div>
			<div class="grid-5">
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-aruba.svg',
							'alt' => 'Aruba',
						),
						'link' => home_url( '/aruba-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-hewlett.svg',
							'alt' => 'HPE',
						),
						'link' => home_url( '/hpe-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/microsoft-endpoint-manager-iraq.svg',
							'alt' => 'Microsoft',
						),
						'link' => home_url( '/microsoft-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/dell-partner.svg',
							'alt' => 'Dell',
						),
						'link' => home_url( '/dell-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-veeam.svg',
							'alt' => 'Veeam',
						),
						'link' => home_url( '/veeam-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-email-security02.svg',
							'alt' => 'Acronis',
						),
						'link' => home_url( '/acronis-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/corning.svg',
							'alt' => 'Corning',
						),
						'link' => home_url( '/corning-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-citrix.svg',
							'alt' => 'Citrix',
						),
						'link' => home_url( '/citrix-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/huawel-partner.svg',
							'alt' => 'Huawei',
						),
						'link' => home_url( '/huawei-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-fortinet.svg',
							'alt' => 'Fortinet',
						),
						'link' => home_url( '/fortinet-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/kaspersky-endpoint-security-iraq.svg',
							'alt' => 'Kapsersky',
						),
						'link' => home_url( '/kaspersky-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-sentinelone.svg',
							'alt' => 'SentinelOne',
						),
						'link' => home_url( '/sentinelone-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-vertiv.svg',
							'alt' => 'vertiv',
						),
						'link' => home_url( '/vertiv-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-hikvision.svg',
							'alt' => 'Hikvision',
						),
						'link' => home_url( '/avigilon-iraq/' ),
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/partner',
					null,
					array(
						'logo' => array(
							'src' => 'assets/images/partner-white-motorola.svg',
							'alt' => 'Motorola Solutions',
						),
						'link' => home_url( '/avigilon-iraq/' ),
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="heading center">
				<h2 class="h2"><strong>Industries We Serve in Iraq</strong></h2>
				<p class="p-r-sm a-c">We deliver tailored ICT and cybersecurity solutions for a wide range of industries in Iraq:</p>
			</div>
			<div class="grid-3">
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1banking.svg',
							'alt' => '',
						),
						'title' => 'Banking & Finance',
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
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/property-1education.svg',
							'alt' => '',
						),
						'title' => 'Education & e-Learning',
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
						'title' => 'Healthcare & Medical Systems',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-transportation.svg',
							'alt' => '',
						),
						'title' => 'Transportation & Logistics',
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
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-manufacturing.svg',
							'alt' => '',
						),
						'title' => 'Manufacturing & Industrial Operations',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-media.svg',
							'alt' => '',
						),
						'title' => 'Media & Entertainment',
					)
				);
				?>
				<?php
				get_template_part(
					'includes/parts/card',
					null,
					array(
						'icon'  => array(
							'src' => 'assets/images/icon-telecommunications_1.svg',
							'alt' => '',
						),
						'title' => 'Telecommunications & ISPs',
					)
				);
				?>
			</div>
		</div>
	</section>

	<section class="section p-y-0 c-surface-neutral">
		<div class="container">
			<div class="grid-2">
				<?php
				osous_image(
					array(
						'src'    => 'assets/images/cybersecurity-experts-managing-firewall-systems-iraqi-enterprise.webp',
						'alt'    => 'Cybersecurity experts managing firewall systems for Iraqi enterprise',
						'srcset' => 'assets/images/cybersecurity-experts-managing-firewall-systems-iraqi-enterprise-p-500.webp 500w, assets/images/cybersecurity-experts-managing-firewall-systems-iraqi-enterprise.webp 652w',
						'sizes'  => '(max-width: 767px) 100vw, 651.99658203125px',
					)
				);
				?>
				<div class="modal c-surface-white">
					<div class="vflex">
						<h2 class="h3">Core Services Overview</h2>
						<ul class="list" role="list">
							<li class="list-item-bullet">
								<div class="t-r-md">Digital Transformation Consulting</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">ICT Infrastructure Development</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Complete Cybersecurity Frameworks</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Cloud Solutions Customized for Iraq</div>
							</li>
							<li class="list-item-bullet">
								<div class="t-r-md">Disaster Recovery & Business Continuity</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_template_part( 'includes/footer' );
