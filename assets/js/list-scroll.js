/* global gsap, ScrollTrigger */
/**
 * Pinned "why choose us" list.
 *
 * Each item highlights while it sits in the middle of the viewport, and on
 * wide screens the heading beside the list stays pinned until the last item
 * has passed. Below 769px the heading scrolls normally.
 */
( function () {
	'use strict';

	if ( typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' ) {
		return;
	}

	const items = gsap.utils.toArray( '.list-item' );
	const heading = document.querySelector(
		'.list-scroll .grid-2 > .cell:first-child'
	);
	if ( ! items.length || ! heading ) {
		return;
	}

	gsap.registerPlugin( ScrollTrigger );

	/*
	 * Refresh order matters as soon as another module pins something on the same page.
	 * A pin above this one adds height when it engages, so if it is measured second this
	 * pin's start is computed against a page that is about to grow -- and the pin fires
	 * early, over the section before it. Deriving the priority from document position
	 * makes the order independent of which module's script happens to load first.
	 */
	const priority = -Math.round(
		heading.getBoundingClientRect().top + window.scrollY
	);

	// One item highlighted at a time.
	items.forEach( function ( item ) {
		ScrollTrigger.create( {
			trigger: item,
			start: 'top center',
			end: 'bottom center',
			toggleClass: { targets: item, className: 'active' },
		} );
	} );

	// Hold the heading in place until the last item is done.
	gsap.matchMedia().add( '(min-width: 769px)', function () {
		const pin = ScrollTrigger.create( {
			trigger: heading,
			start: 'top center',
			endTrigger: items[ items.length - 1 ],
			end: 'bottom center',
			pin: true,
			pinSpacing: false,
			anticipatePin: 1,
			refreshPriority: priority,
		} );
		return function () {
			pin.kill();
		};
	} );

	window.addEventListener( 'resize', function () {
		ScrollTrigger.refresh();
	} );

	// Late-loading images shift the page; without this the trigger positions
	// keep the heights measured at first paint and the pin lands in the wrong place.
	window.addEventListener( 'load', function () {
		ScrollTrigger.refresh();
	} );
} )();
