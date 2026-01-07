/**
 * Featured Paintings Slider
 *
 * Responsive Swiper carousel with peek effect
 * Mobile: 1 slide + peek
 * Desktop: 3 slides + peek with navigation arrows
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const featuredSwiper = document.querySelector('.featured-paintings-swiper');
    if (!featuredSwiper) return;

    const swiper = new Swiper('.featured-paintings-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 36,
        centeredSlides: false,
        slidesPerGroup: 1,

        // Responsive breakpoints
        breakpoints: {
            768: {
                slidesPerView: 2.2,
                spaceBetween: 48,
            },
            1024: {
                slidesPerView: 3.2,
                spaceBetween: 64,
            }
        },

        // Navigation arrows
        navigation: {
            nextEl: '.featured-paintings-next',
            prevEl: '.featured-paintings-prev',
        },

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

        // Events
        on: {
            init: function() {
                // Force overflow visible after init
                this.el.style.overflow = 'visible';
            },
            resize: function() {
                // Keep overflow visible on resize
                this.el.style.overflow = 'visible';
            }
        }
    });
});
