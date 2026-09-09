/**
 * Pinned "why choose us" list.
 *
 * Each item highlights while it sits in the middle of the viewport, and on
 * wide screens the heading beside the list stays pinned until the last item
 * has passed. Below 769px the heading scrolls normally.
 */
(function () {
  'use strict';

  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  var items = gsap.utils.toArray('.list-item');
  var heading = document.querySelector('.list-scroll .grid-2 > .cell:first-child');
  if (!items.length || !heading) return;

  gsap.registerPlugin(ScrollTrigger);

  // One item highlighted at a time.
  items.forEach(function (item) {
    ScrollTrigger.create({
      trigger: item,
      start: 'top center',
      end: 'bottom center',
      toggleClass: { targets: item, className: 'active' }
    });
  });

  // Hold the heading in place until the last item is done.
  gsap.matchMedia().add('(min-width: 769px)', function () {
    var pin = ScrollTrigger.create({
      trigger: heading,
      start: 'top center',
      endTrigger: items[items.length - 1],
      end: 'bottom center',
      pin: true,
      pinSpacing: false,
      anticipatePin: 1
    });
    return function () { pin.kill(); };
  });

  window.addEventListener('resize', function () { ScrollTrigger.refresh(); });

  // Late-loading images shift the page; without this the trigger positions
  // keep the heights measured at first paint and the pin lands in the wrong place.
  window.addEventListener('load', function () { ScrollTrigger.refresh(); });
})();
