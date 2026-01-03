/**
 * Single Obraz JavaScript
 * 
 * Initializes Swiper carousels for image gallery and testimonials
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Image Gallery Swiper (if multiple images)
    const obrazSwiper = document.querySelector('.obraz-swiper');
    if (obrazSwiper) {
        new Swiper('.obraz-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // Keyboard control
            keyboard: {
                enabled: true,
            },
            
            // Lazy loading
            lazy: {
                loadPrevNext: true,
            },
        });
    }

    // Testimonials Swiper
    const opinieSwiper = document.querySelector('.opinie-swiper');
    if (opinieSwiper) {
        const swiper = new Swiper('.opinie-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            centeredSlides: true,
            
            // Responsive breakpoints
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    centeredSlides: false,
                },
                1024: {
                    slidesPerView: 3,
                    centeredSlides: false,
                },
            },
            
            // Auto height for varying content
            autoHeight: false,
            
            // Keyboard control
            keyboard: {
                enabled: true,
            },
        });

        // Custom navigation buttons
        const prevButton = document.querySelector('.opinie-prev');
        const nextButton = document.querySelector('.opinie-next');

        if (prevButton) {
            prevButton.addEventListener('click', function() {
                swiper.slidePrev();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function() {
                swiper.slideNext();
            });
        }
    }
});
