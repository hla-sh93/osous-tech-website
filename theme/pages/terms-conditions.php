<?php
/**
 * Terms & Conditions
 *
 * Read from the reference page terms-conditions.html by _tools/gen_pages.py. From here on this
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
			'title' => 'Terms & Conditions',
		)
	);
	?>

	<section class="section">
		<div class="container fit c-surface-white">
			<div class="content">
				<div class="t-b-md"><strong class="highlight">Last updated December 12, 2023</strong></div>
				<div class="spacer-2x"></div>
				<p class="paragraph-2">This privacy notice for OSOUS TECHNOLOGY (“<strong>we</strong>,” “<strong>us</strong>,” or “<strong>our</strong>“), describes how and why we might collect, store, use, and/or share (“<strong>process</strong>“) your information when you use our services (“<strong>Services</strong>“), such as when you:<br/></p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="_w-max-200">
				<div class="divider gradient-1"></div>
			</div>
			<div>
				<h1>Heading 1</h1>
				<h2>Heading 2</h2>
				<h3>Heading 3</h3>
				<h4>Heading 4</h4>
				<h5>Heading 5</h5>
				<h6>Heading 6</h6>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
				<blockquote>Block quote</blockquote>
				<p>Ordered list</p>
				<ol role="list">
				<li>Item 1</li>
				<li>Item 2</li>
				<li>Item 3</li> </ol>
				<p>Unordered list</p>
				<ul role="list">
				<li>Item A</li>
				<li>Item B</li>
				<li>Item C</li> </ul>
				<p> <a href="https://university.webflow.com/lesson/add-and-nest-text-links-in-webflow">Text link</a> </p>
				<p><strong>Bold text</strong></p>
				<p><em>Emphasis</em></p>
				<p><sup>Superscript</sup></p>
				<p><sub>Subscript</sub></p>
			</div>
		</div>
	</section>
</main>
<?php
get_template_part( 'includes/footer' );
