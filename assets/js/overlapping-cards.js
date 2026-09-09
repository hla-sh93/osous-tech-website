/**
 * "Why us" card stack.
 *
 * Desktop only: the section pins while the page scrolls through it, and each
 * card in turn becomes the highlighted one while the cards before it fade out.
 * Below 992px the cards fall back to a plain grid (see sections.css).
 */
(function () {
  'use strict';

  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  gsap.registerPlugin(ScrollTrigger);

  var SECTION = '.section.overlapping-cards';
  var STEP = 300;          // px of scroll spent on each card
  var OFFSET_X = 40;       // fan-out per card
  var OFFSET_Y = 15;
  var FADE_STEP = 0.1;

  gsap.matchMedia().add('(min-width: 992px)', function () {
    var cards = gsap.utils.toArray('.card-overlapping-item');
    if (!cards.length) return;

    cards.forEach(function (card, i) {
      gsap.set(card, {
        x: i * OFFSET_X,
        y: i * OFFSET_Y,
        opacity: 1 - i * FADE_STEP,
        zIndex: cards.length - i
      });
    });

    function update(activeIndex) {
      cards.forEach(function (card, i) {
        var inner = card.querySelector('.card-4');
        var content = card.querySelector('.content');

        if (inner) inner.classList.toggle('active2', i === activeIndex);
        if (content) gsap.to(content, { opacity: i === activeIndex ? 1 : 0 });

        gsap.to(card, {
          opacity: i < activeIndex ? 0 : 1,
          duration: 0.4,
          ease: 'power2.out'
        });
      });
    }

    var trigger = ScrollTrigger.create({
      trigger: SECTION,
      start: 'top top',
      end: function () { return '+=' + (cards.length - 1) * STEP; },
      pin: true,
      scrub: true,
      onUpdate: function (self) {
        update(Math.floor(self.progress * (cards.length - 1)));
      }
    });

    update(0);

    return function cleanup() {
      trigger.kill();
      cards.forEach(function (card) {
        gsap.set(card, { clearProps: 'all' });
        var inner = card.querySelector('.card-4');
        var content = card.querySelector('.content');
        if (inner) inner.classList.remove('active2');
        if (content) gsap.set(content, { clearProps: 'opacity' });
      });
    };
  });
})();
