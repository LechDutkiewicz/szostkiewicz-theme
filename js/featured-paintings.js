/**
 * Featured Paintings Mobile Slider
 *
 * Initializes Swiper carousel for mobile with peek effect
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const featuredSwiper = document.querySelector('.featured-paintings-swiper');
    if (featuredSwiper && window.innerWidth < 768) {
        new Swiper('.featured-paintings-swiper', {
            slidesPerView: 1.2,
            spaceBetween: 20,
            centeredSlides: false,

            // Keyboard control
            keyboard: {
                enabled: true,
            },

            // Touch gestures
            touchRatio: 1,

            // Resistance when swiping
            resistance: true,
            resistanceRatio: 0.85,

            // Slide effect
            effect: 'slide',

            // Watch for resize
            observer: true,
            observeParents: true,
        });
    }
});
