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

    // Helper function to update slide opacity based on visibility
    function updateSlideOpacity(swiper) {
        const isDesktop = window.innerWidth >= 768;
        const visibleSlides = isDesktop ? 3 : 1; // How many slides should have full opacity
        const activeIndex = swiper.activeIndex;

        swiper.slides.forEach((slide, index) => {
            // Check if this slide is within the visible range
            if (index >= activeIndex && index < activeIndex + visibleSlides) {
                slide.style.opacity = '1';
            } else {
                slide.style.opacity = '0.5';
            }
        });
    }

    const swiper = new Swiper('.featured-paintings-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 36,
        centeredSlides: false,
        slidesPerGroup: 1,

        // Responsive breakpoints
        breakpoints: {
            768: {
                slidesPerView: 3.2,
                spaceBetween: 64,
                slidesPerGroup: 1,
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
                // Set initial opacity
                updateSlideOpacity(this);
            },
            slideChange: function() {
                // Update opacity when slide changes
                updateSlideOpacity(this);
            },
            resize: function() {
                // Keep overflow visible on resize
                this.el.style.overflow = 'visible';
                // Update opacity on resize (mobile/desktop switch)
                updateSlideOpacity(this);
            }
        }
    });
});
