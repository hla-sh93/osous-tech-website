<?php
/**
 * Navigation rendering.
 *
 * The mega menu is three levels deep and its markup does not resemble the nested <ul>
 * that Walker_Nav_Menu produces, so rather than fight the walker this builds a plain
 * tree from the menu items and prints the markup directly. It is far shorter, and the
 * output matches the static reference exactly.
 *
 * @package Osous
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Leaf links in a dropdown are chunked into columns of this many. */
const OSOUS_MENU_COLUMN_SIZE = 6;

/**
 * Build a nested tree of menu items for a location.
 *
 * @param string $location Registered menu location.
 * @return array<int,object> Top-level items, each with a ->children array.
 */
function osous_menu_tree( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( ! $items ) {
		return array();
	}

	$by_id = array();
	foreach ( $items as $item ) {
		$item->children       = array();
		$by_id[ $item->ID ]   = $item;
	}

	$tree = array();
	foreach ( $items as $item ) {
		$parent = (int) $item->menu_item_parent;
		if ( $parent && isset( $by_id[ $parent ] ) ) {
			$by_id[ $parent ]->children[] = $item;
		} else {
			$tree[] = $item;
		}
	}

	return $tree;
}

/**
 * `aria-current="page"` when this is the page being looked at, and nothing otherwise.
 *
 * Screen readers announce the current page from this, and it is the only way a link in
 * the menu says "you are here" -- the design has no visual active state. The static pages
 * carry it on the top-level items, on the leaf links inside the dropdowns and on both
 * Contact buttons, so all three need it.
 *
 * @param int    $object_id The page a link points at.
 * @param string $type      Menu item object type, when the caller has one.
 * @return string
 */
function osous_aria_current( $object_id, $type = 'page' ) {
	$current = ( (int) $object_id === get_queried_object_id() && 'page' === $type );

	return $current ? ' aria-current="page"' : '';
}

/**
 * The caret that sits in the dropdown trigger's right gutter.
 */
function osous_menu_caret() {
	?>
	<span class="icon-caret hide-on-tablet" aria-hidden="true">
		<svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 5.25 7 9.25l4-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="square"/></svg>
	</span>
	<?php
}

/**
 * One leaf link inside a dropdown column.
 *
 * @param object $item Menu item.
 */
function osous_menu_leaf( $item ) {
	?>
	<a href="<?php echo esc_url( $item->url ); ?>" class="menu-list-item"<?php echo osous_aria_current( $item->object_id, $item->object ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/arrow-right.png' ) ); ?>" alt="" class="icon-12"><div><?php echo esc_html( $item->title ); ?></div></a>
	<?php
}

/**
 * A titled column: a second-level item that has children of its own.
 *
 * The title is printed twice on purpose — the desktop copy links through to the
 * section's own page, while the tablet copy is the accordion handle.
 *
 * The reference markup is inconsistent here: three of its four columns also carry a
 * `.menu-list-icon` inside the desktop copy, and one does not. That icon can never be
 * seen — above 991px `.menu-list-icon` is `display: none`, and at or below it the whole
 * `.hide-on-tablet` anchor is hidden — so this emits the clean shape for every column.
 * It accounts for 9 fewer elements than the static reference, with no visual difference.
 *
 * @param object $item Menu item with children.
 */
function osous_menu_titled_column( $item ) {
	?>
	<div class="dd-menu-list">
		<div class="dd-menu-list-toggle" data-accordion-toggle>
			<a href="<?php echo esc_url( $item->url ); ?>" class="menu-list-item menu-title hide-on-tablet"><div><?php echo esc_html( $item->title ); ?></div></a>
			<a href="#" class="menu-list-item menu-title show-on-tablet">
				<div><?php echo esc_html( $item->title ); ?></div>
				<div class="menu-list-icon"><div class="accordion-icon-line-v"></div><div class="accordion-icon-line-h"></div></div>
			</a>
		</div>
		<div class="menu-list">
			<?php
			foreach ( $item->children as $child ) {
				osous_menu_leaf( $child );
			}
			?>
		</div>
	</div>
	<?php
}

/**
 * The panel under a top-level dropdown.
 *
 * Two shapes, decided by the menu structure rather than by configuration: if the
 * second level has children of its own, each becomes a titled column (Solutions).
 * If the second level is all leaves, they are chunked into columns (Services,
 * Partners) so a long list does not run off the panel.
 *
 * @param object $item Top-level item with children.
 * @param string $id   Panel id, referenced by the trigger's aria-controls.
 */
function osous_menu_panel( $item, $id ) {
	$has_groups = false;
	foreach ( $item->children as $child ) {
		if ( $child->children ) {
			$has_groups = true;
			break;
		}
	}
	?>
	<div class="menu" id="<?php echo esc_attr( $id ); ?>">
		<?php
		if ( $has_groups ) {
			foreach ( $item->children as $child ) {
				osous_menu_titled_column( $child );
			}
		} else {
			foreach ( array_chunk( $item->children, OSOUS_MENU_COLUMN_SIZE ) as $column ) {
				?>
				<div class="dd-menu-list">
					<div class="menu-list">
						<?php
						foreach ( $column as $child ) {
							osous_menu_leaf( $child );
						}
						?>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
	<?php
}

/**
 * The whole primary menu.
 *
 * A top-level item with children becomes a dropdown; without, a plain link. Add the
 * CSS class `hide-on-desktop` to a menu item in the admin and it behaves exactly as it
 * does in the static markup.
 */
function osous_primary_menu() {
	$tree = osous_menu_tree( 'primary' );
	if ( ! $tree ) {
		return;
	}

	foreach ( $tree as $item ) {
		$extra = implode( ' ', array_filter( (array) $item->classes ) );

		if ( ! $item->children ) {
			/*
			 * `aria-current` only, matching the reference. The design does define
			 * `.nav-link.is-current`, but neither Webflow nor WordPress ever emits the
			 * class, so the live nav has no active-item highlight. Adding ' is-current'
			 * to the class below switches it on, at the cost of a visible difference.
			 */
			?>
			<div class="menu-item <?php echo esc_attr( $extra ); ?>">
				<a href="<?php echo esc_url( $item->url ); ?>" class="nav-link"<?php echo osous_aria_current( $item->object_id, $item->object ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item->title ); ?></a>
			</div>
			<?php
			continue;
		}

		$id = 'menu-' . sanitize_title( $item->title );
		?>
		<div class="dd-menu-item <?php echo esc_attr( $extra ); ?>" data-dropdown>
			<div class="menu-item-toggle" data-dropdown-toggle role="button" tabindex="0" aria-expanded="false" aria-controls="<?php echo esc_attr( $id ); ?>">
				<?php osous_menu_caret(); ?>
				<span class="nav-link"><?php echo esc_html( $item->title ); ?></span>
				<div class="menu-list-icon">
					<div class="accordion-icon-line-v bg-white"></div>
					<div class="accordion-icon-line-h bg-white"></div>
				</div>
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/menu-hover-bg.svg' ) ); ?>" alt="" class="menu-item-bg">
			</div>
			<?php osous_menu_panel( $item, $id ); ?>
		</div>
		<?php
	}
}

/**
 * A flat list of footer links.
 *
 * @param string $location  Registered menu location.
 * @param string $css_class Class for each anchor.
 */
function osous_footer_links( $location, $css_class = 'link c-on-secondary' ) {
	foreach ( osous_menu_tree( $location ) as $item ) {
		?>
		<a href="<?php echo esc_url( $item->url ); ?>" class="<?php echo esc_attr( $css_class ); ?>"><?php echo esc_html( $item->title ); ?></a>
		<?php
	}
}
