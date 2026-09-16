/**
 * Navigation
 *
 * Desktop mega-menus open on hover (handled entirely in CSS); this script adds
 * the click/keyboard equivalents, the mobile drawer and the mobile accordions.
 */
( function () {
	'use strict';

	const TABLET = window.matchMedia( '(max-width: 991px)' );
	const OPEN = 'is-open';

	const drawer = document.querySelector( '.navbar-menu' );
	const burger = document.querySelector( '[data-menu-toggle]' );
	const dropdowns = document.querySelectorAll( '[data-dropdown]' );

	/* ---------------------------------------------------------------- helpers */

	function setOpen( el, open, toggle ) {
		el.classList.toggle( OPEN, open );
		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', String( open ) );
		}
	}

	function closeAllDropdowns( except ) {
		dropdowns.forEach( function ( dd ) {
			if ( dd !== except ) {
				setOpen(
					dd,
					false,
					dd.querySelector( '[data-dropdown-toggle]' )
				);
			}
		} );
	}

	function closeDrawer() {
		if ( ! drawer ) {
			return;
		}
		setOpen( drawer, false, burger );
		if ( burger ) {
			burger.classList.remove( OPEN );
		}
		closeAllDropdowns();
	}

	/* --------------------------------------------------------- mobile drawer */

	if ( burger && drawer ) {
		burger.addEventListener( 'click', function () {
			const open = ! drawer.classList.contains( OPEN );
			setOpen( drawer, open, burger );
			burger.classList.toggle( OPEN, open );
			if ( ! open ) {
				closeAllDropdowns();
			}
		} );
	}

	/* ------------------------------------------------------------- dropdowns */

	dropdowns.forEach( function ( dd ) {
		const toggle = dd.querySelector( '[data-dropdown-toggle]' );
		if ( ! toggle ) {
			return;
		}

		toggle.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			const open = ! dd.classList.contains( OPEN );
			closeAllDropdowns( dd );
			setOpen( dd, open, toggle );
		} );

		toggle.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Enter' && event.key !== ' ' ) {
				return;
			}
			event.preventDefault();
			toggle.click();
		} );
	} );

	/* ------------------------------------------- mobile accordions (per list) */

	document
		.querySelectorAll( '[data-accordion-toggle]' )
		.forEach( function ( toggle ) {
			toggle.addEventListener( 'click', function ( event ) {
				if ( ! TABLET.matches ) {
					return;
				} // desktop lists are always expanded
				event.preventDefault();
				toggle.parentElement.classList.toggle( OPEN );
			} );
		} );

	/* ------------------------------------------------------- global dismissal */

	document.addEventListener( 'click', function ( event ) {
		if ( ! event.target.closest( '.navbar' ) ) {
			closeAllDropdowns();
			if ( TABLET.matches ) {
				closeDrawer();
			}
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' ) {
			closeAllDropdowns();
			closeDrawer();
		}
	} );

	/* Leaving the mobile range resets everything the drawer changed. */
	TABLET.addEventListener( 'change', function ( event ) {
		if ( ! event.matches ) {
			closeDrawer();
		}
	} );
} )();
