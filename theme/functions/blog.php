<?php
/**
 * The blog: the post's picture, the pager, and the page header for posts and archives.
 *
 * The blog is the one part of the site that is a query rather than a page file: posts
 * are written in the editor, listed by index.php and archive.php through
 * includes/loops/content.php, and read on single.php.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A post's picture for the grid.
 *
 * The featured image when the post has one. Until the live posts are imported, the
 * placeholder posts carry their picture as a path into the theme instead, so the grid
 * looks as the reference does.
 *
 * @param int $post_id The post.
 * @return string Escaped markup, or nothing.
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
		$srcset
	);
}

/**
 * The pager under a grid of posts, as the design draws it: grey buttons, the current
 * page as one that does not link.
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
 * The page header of an archive: what kind of archive, and its name.
 */
function osous_archive_header() {
	$eyebrow = '';
	$title   = '';

	if ( is_category() ) {
		$eyebrow = __( 'Category', 'osous' );
		$title   = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$eyebrow = __( 'Tag', 'osous' );
		$title   = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$eyebrow = __( 'Author', 'osous' );
		$title   = get_the_author_meta( 'display_name', get_queried_object_id() );
	} elseif ( is_year() ) {
		$eyebrow = __( 'Year', 'osous' );
		$title   = get_the_date( 'Y' );
	} elseif ( is_month() ) {
		$eyebrow = __( 'Month', 'osous' );
		$title   = get_the_date( 'F Y' );
	} elseif ( is_day() ) {
		$eyebrow = __( 'Day', 'osous' );
		$title   = get_the_date();
	} else {
		$title = wp_strip_all_tags( get_the_archive_title() );
	}

	osous_page_header( $eyebrow, $title );
}

/**
 * The page header, for the templates that build it from the query.
 *
 * @param string $eyebrow The small line above the title.
 * @param string $title   The title.
 */
function osous_page_header( $eyebrow, $title ) {
	get_template_part(
		'includes/sections/page-header',
		null,
		array(
			'eyebrow' => $eyebrow,
			'title'   => $title,
		)
	);
}
