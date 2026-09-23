<?php
/**
 * The band at the top of a page: a small line above a rule, the title, and a line of
 * text.
 *
 * Called as: get_template_part( 'includes/sections/page-header', null, array( 'eyebrow' => …, 'title' => … ) );
 *
 * @package Osous
 *
 * @var array $args {
 *     @type string $eyebrow The small line above the title; empty for none.
 *     @type string $title   The title.
 *     @type string $text    A line under the title; empty for none.
 * }
 */

$osous_args = wp_parse_args(
	$args,
	array(
		'eyebrow' => '',
		'title'   => '',
		'text'    => '',
	)
);
?>
<section class="section header">
	<div class="container h-100">
		<div class="header-contents">
			<?php if ( '' !== $osous_args['eyebrow'] ) : ?>
				<div class="t-r-md c-on-neutral-subtle"><?php echo osous_inline( $osous_args['eyebrow'] ); ?></div>
				<div class="_w-max-200"><div class="divider gradient-1"></div></div>
			<?php endif; ?>
			<div>
				<h1 class="h2 a-c"><?php echo osous_inline( $osous_args['title'] ); ?></h1>
				<?php if ( '' !== $osous_args['text'] ) : ?>
					<p class="p-r-sm c-on-neutral a-c"><?php echo osous_inline( $osous_args['text'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
