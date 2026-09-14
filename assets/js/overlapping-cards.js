/* global gsap, ScrollTrigger */
/**
 * "Why us" card stack.
 *
 * Desktop only: the section pins while the page scrolls through it, and each
 * card in turn becomes the highlighted one while the cards before it fade out.
 * Below 992px the cards fall back to a plain grid (see sections.css).
 */
( function () {
	'use strict';

	if ( typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' ) {
		return;
	}

	gsap.registerPlugin( ScrollTrigger );

	const SECTION = '.section.overlapping-cards';
	const STEP = 300; // px of scroll spent on each card
	const OFFSET_X = 40; // fan-out per card
	const OFFSET_Y = 15;
	const FADE_STEP = 0.1;

	/*
	 * Refresh order matters as soon as another module pins something on the same page.
	 * A pin above this one adds height when it engages, so if it is measured second this
	 * pin's start is computed against a page that is about to grow -- and the pin fires
	 * early, over the section before it. Deriving the priority from document position
	 * makes the order independent of which module's script happens to load first.
	 */
	const pinned = document.querySelector( SECTION );
	const priority = pinned
		? -Math.round( pinned.getBoundingClientRect().top + window.scrollY )
		: 0;

	gsap.matchMedia().add( '(min-width: 992px)', function () {
		const cards = gsap.utils.toArray( '.card-overlapping-item' );
		if ( ! cards.length ) {
			return;
		}

		cards.forEach( function ( card, i ) {
			gsap.set( card, {
				x: i * OFFSET_X,
				y: i * OFFSET_Y,
				opacity: 1 - i * FADE_STEP,
				zIndex: cards.length - i,
			} );
		} );

		function update( activeIndex ) {
			cards.forEach( function ( card, i ) {
				const inner = card.querySelector( '.card-4' );
				const content = card.querySelector( '.content' );

				if ( inner ) {
					inner.classList.toggle( 'active2', i === activeIndex );
				}
				if ( content ) {
					gsap.to( content, { opacity: i === activeIndex ? 1 : 0 } );
				}

				gsap.to( card, {
					opacity: i < activeIndex ? 0 : 1,
					duration: 0.4,
					ease: 'power2.out',
				} );
			} );
		}

		const trigger = ScrollTrigger.create( {
			trigger: SECTION,
			start: 'top top',
			end() {
				return '+=' + ( cards.length - 1 ) * STEP;
			},
			pin: true,
			scrub: true,
			refreshPriority: priority,
			onUpdate( self ) {
				update( Math.floor( self.progress * ( cards.length - 1 ) ) );
			},
		} );

		update( 0 );

		return function cleanup() {
			trigger.kill();
			cards.forEach( function ( card ) {
				gsap.set( card, { clearProps: 'all' } );
				const inner = card.querySelector( '.card-4' );
				const content = card.querySelector( '.content' );
				if ( inner ) {
					inner.classList.remove( 'active2' );
				}
				if ( content ) {
					gsap.set( content, { clearProps: 'opacity' } );
				}
			} );
		};
	} );
} )();
