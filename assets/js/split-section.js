/**
 * Split section — the feature cards scroll through a fixed-height window.
 *
 * On desktop the section pins while the page scrolls 500px per card; each card
 * that reaches the middle of the viewport slides the wrapper up by one full
 * card and lights its indicator. Below 992px the pin is dropped and the cards
 * simply stack down the page.
 */
(function () {
  'use strict';

  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  var section = document.querySelector('.split-section');
  if (!section) return;

  gsap.registerPlugin(ScrollTrigger);

  var SCROLL_PER_CARD = 500;
  var SLIDE_DURATION = 0.6;

  gsap.matchMedia().add('(min-width: 992px)', function () {
    var wrapper = section.querySelector('.feature-card-wrapper');
    var cards = gsap.utils.toArray('.feature-card', section);
    var indicators = gsap.utils.toArray('.indicator', section);
    if (!wrapper || !cards.length) return;

    function showSlide(index) {
      gsap.to(wrapper, {
        yPercent: -100 * index,
        duration: SLIDE_DURATION,
        ease: 'power2.inOut'
      });
      indicators.forEach(function (indicator, i) {
        indicator.classList.toggle('active', i === index);
      });
    }

    var triggers = [ScrollTrigger.create({
      trigger: section,
      start: 'center center',
      end: function () { return '+=' + cards.length * SCROLL_PER_CARD; },
      pin: true,
      scrub: true
    })];

    cards.forEach(function (card, i) {
      triggers.push(ScrollTrigger.create({
        trigger: card,
        start: 'top center',
        end: 'bottom center',
        onEnter: function () { showSlide(i); },
        onEnterBack: function () { showSlide(i); }
      }));
    });

    showSlide(0);

    // Leaving the range: drop the triggers and the transforms they applied, so
    // the stacked mobile layout starts from a clean slate.
    return function () {
      triggers.forEach(function (trigger) { trigger.kill(); });
      gsap.set(wrapper, { clearProps: 'all' });
      indicators.forEach(function (indicator) { indicator.classList.remove('active'); });
    };
  });

  window.addEventListener('resize', function () { ScrollTrigger.refresh(); });

  // Late-loading images shift the page; without this the pin is measured
  // against the heights the section had at first paint.
  window.addEventListener('load', function () { ScrollTrigger.refresh(); });
})();
