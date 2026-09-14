/* global Swiper */
/**
 * Industries carousel — free-width slides, looping, with the leading card
 * carrying the `active` treatment.
 */
( function () {
	'use strict';

	if ( typeof Swiper === 'undefined' ) {
		return;
	}
	if ( ! document.querySelector( '.swiper-container' ) ) {
		return;
	}

	function highlight( swiper ) {
		swiper.slides.forEach( function ( slide ) {
			const card = slide.querySelector( '.card-4' );
			if ( card ) {
				card.classList.remove( 'active' );
			}
		} );

		const active = swiper.slides[ swiper.activeIndex ];
		const card = active && active.querySelector( '.card-4' );
		if ( card ) {
			card.classList.add( 'active' );
		}
	}

	new Swiper( '.swiper-container', {
		slidesPerView: 'auto',
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		on: {
			slideChange() {
				highlight( this );
			},
		},
	} );
} )();
