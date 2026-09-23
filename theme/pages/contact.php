<?php
/**
 * Transform Your Iraqi Business with Professional IT Services
 *
 * Read from the reference page contact.html by _tools/gen_pages.py. From here on this
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
		'includes/sections/contact',
		null,
		array(
			'title'     => 'Transform Your Iraqi Business with Professional IT Services',
			'subtitle'  => 'Request a Free IT Consultation',
			'phones'    => array(
				array(
					'label'  => 'IRAQ',
					'number' => '+964 750 841 7028',
				),
				array(
					'label'  => 'UAE',
					'number' => '+971 58 532 5606',
				),
			),
			'email'     => 'info@osoustaqnia.com',
			'formTitle' => 'LEAVE YOUR REQUEST',
			'formText'  => 'We will get back to you first thing in the morning!',
			'anchor'    => 'inquiry',
		)
	);
	?>
</main>
<?php
get_template_part( 'includes/footer' );
