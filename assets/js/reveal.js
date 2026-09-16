/**
 * Scroll reveals and the magnetic float.
 *
 * Elements listed in REVEAL_SELECTOR start hidden (see animations.css) and play
 * their entrance once, the first time they cross into the viewport.
 */
( function () {
	'use strict';

	const REVEAL_SELECTOR = [
		'.h1',
		'.h2',
		'.h3',
		'.p',
		'.p-r-sm',
		'.paragraph-base',
		'.btn',
		'.icon-24',
		'.input',
		'.t-r-sm',
		'.t-r-md',
		'.t-r-lg',
		'.t-m-md',
		'.t-m-lg',
		'.t-b-lg',
		'.navbar-brand',
		'.divider',
	].join( ', ' );

	const REVEALED = 'is-revealed';
	const targets = document.querySelectorAll( REVEAL_SELECTOR );

	if ( ! ( 'IntersectionObserver' in window ) ) {
		targets.forEach( function ( el ) {
			el.classList.add( REVEALED );
		} );
	} else {
		const observer = new window.IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}
					entry.target.classList.add( REVEALED );
					observer.unobserve( entry.target ); // reveals play once
				} );
			},
			{ threshold: 0 }
		);

		targets.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/* ---------------------------------------------------------------- magnetic */

	const magnets = document.querySelectorAll( '.magnetic' );
	if ( ! magnets.length ) {
		return;
	}

	const DRIFT = 20; // px of travel either side of centre
	let pending = false;
	const pointer = { x: 0, y: 0 };

	function apply() {
		pending = false;
		const x = ( ( pointer.x / window.innerWidth ) * 2 - 1 ) * DRIFT;
		const y = ( ( pointer.y / window.innerHeight ) * 2 - 1 ) * DRIFT;
		magnets.forEach( function ( el ) {
			el.style.transform =
				'translate3d(' +
				x.toFixed( 2 ) +
				'px,' +
				y.toFixed( 2 ) +
				'px,0)';
		} );
	}

	window.addEventListener(
		'mousemove',
		function ( event ) {
			pointer.x = event.clientX;
			pointer.y = event.clientY;
			if ( pending ) {
				return;
			}
			pending = true;
			window.requestAnimationFrame( apply );
		},
		{ passive: true }
	);
} )();
