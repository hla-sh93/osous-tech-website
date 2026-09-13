<?php
/**
 * The blog: a card, and the pager under the grid.
 *
 * The static reference page is a snapshot of the first twelve posts as production had
 * them, so this markup has to come out the same as that snapshot for the checks to keep
 * meaning something — and then go on working for the hundred posts that follow.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The picture for a post card.
 *
 * A featured image when the post has one, which is what the real posts carry once they
 * are imported. The `_osous_thumb` meta is the fallback for the seeded posts, whose
 * pictures live in the theme's uploads folder rather than the Media Library; it holds the
 * `src`, `srcset` and `alt` exactly as the reference page has them.
 *
 * @param int $post_id Post.
 * @return string
 */
function osous_post_thumb( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail(
			$post_id,
			'large',
			array( 'class' => 'blog-image-thumb' )
		);
	}

	$thumb = get_post_meta( $post_id, '_osous_thumb', true );
	if ( ! is_array( $thumb ) || empty( $thumb['src'] ) ) {
		return '';
	}

	$srcset = empty( $thumb['srcset'] ) ? '' : ' srcset="' . osous_srcset( $thumb['srcset'] ) . '"';

	return sprintf(
		'<img src="%s" alt="%s" class="blog-image-thumb"%s>',
		esc_url( osous_url( $thumb['src'] ) ),
		esc_attr( isset( $thumb['alt'] ) ? $thumb['alt'] : '' ),
		$srcset // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above.
	);
}

/**
 * One post, as it appears in the grid.
 *
 * @param int|WP_Post $post Post.
 */
function osous_blog_card( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}
	?>
	<div>
		<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="blog-card card-media">
			<div class="blog-image-thumb-container">
				<?php echo osous_post_thumb( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="spacer-1x"></div>
			<div class="t-r-sm c-on-neutral-subtle"><?php echo esc_html( get_the_date( '', $post ) ); ?></div>
			<h2 class="h2"><?php echo esc_html( get_the_title( $post ) ); ?></h2>
		</a>
	</div>
	<?php
}

/**
 * The pager under the grid.
 *
 * `the_posts_pagination()` would be shorter and would emit a `<nav>` full of classes this
 * design has no styles for. `paginate_links()` gives the same links as data, and the
 * markup around them stays the design's own.
 */
function osous_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'array',
			'mid_size'  => 1,
			'end_size'  => 1,
			'prev_text' => __( 'Previous', 'osous' ),
			'next_text' => __( 'Next', 'osous' ),
		)
	);

	if ( ! $links ) {
		return;
	}
	?>
	<div class="pagination-wrapper pagination">
		<?php
		foreach ( $links as $link ) {
			/*
			 * paginate_links() marks the current page and the ellipsis with its own
			 * classes. Re-dressing them here keeps the button styling in one vocabulary:
			 * `.btn.neutral` for a page, `.current` for this one, and the ellipsis left
			 * as it is because the design styles `.page-numbers.dots` directly.
			 */
			if ( false !== strpos( $link, 'page-numbers dots' ) ) {
				echo '<span class="page-numbers dots">&hellip;</span>';
				continue;
			}

			$text = wp_strip_all_tags( $link );

			if ( false !== strpos( $link, 'current' ) ) {
				printf( '<span class="btn neutral current"><div class="t-r-md">%s</div></span>', esc_html( $text ) );
				continue;
			}

			$href = '';
			if ( preg_match( '/href=[\'"]([^\'"]+)/', $link, $found ) ) {
				$href = $found[1];
			}
			printf( '<a class="btn neutral" href="%s">%s</a>', esc_url( $href ), esc_html( $text ) );
		}
		?>
	</div>
	<?php
}

/**
 * The band at the top of the blog and of every post.
 *
 * The same shape as a page header section, built here rather than stored as blocks: a
 * post's title is its title, and asking somebody to keep a header section in step with it
 * would be a trap.
 *
 * The eyebrow and its rule are optional because the reference blog page has neither --
 * just the heading. A post uses its date there, which is the shape every other page
 * header on the site has.
 *
 * @param string $eyebrow Small line above the rule, or empty for none.
 * @param string $title   The heading.
 */
function osous_page_header( $eyebrow, $title ) {
	?>
	<section class="section header">
		<div class="container h-100">
			<div class="header-contents">
				<?php if ( '' !== $eyebrow ) : ?>
					<div class="t-r-md c-on-neutral-subtle"><?php echo esc_html( $eyebrow ); ?></div>
					<div class="_w-max-200">
						<div class="divider gradient-1"></div>
					</div>
				<?php endif; ?>
				<div>
					<h1 class="h2 a-c"><?php echo esc_html( $title ); ?></h1>
				</div>
			</div>
		</div>
	</section>
	<?php
}
