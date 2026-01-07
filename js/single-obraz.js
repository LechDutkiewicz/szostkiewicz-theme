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

    // GLightbox initialization for image zoom/lightbox
    const lightboxImages = document.querySelectorAll('.lightbox-image');

    if (lightboxImages.length > 0 && typeof GLightbox !== 'undefined') {
        // Set appropriate image URL based on screen size
        lightboxImages.forEach(img => {
            const fullUrl = img.dataset.full;
            const mobileUrl = img.dataset.mobile;
            const isMobile = window.innerWidth < 1024;
            const targetUrl = isMobile ? mobileUrl : fullUrl;

            // Set data-glightbox attribute with the appropriate URL
            img.setAttribute('data-glightbox', 'type: image');
            img.setAttribute('href', targetUrl);
        });

        // Initialize GLightbox
        const lightbox = GLightbox({
            selector: '.lightbox-image',
            touchNavigation: true,
            loop: true,
            zoomable: true,
            draggable: true,
            closeButton: true,
            closeOnOutsideClick: true,
        });
    }
});
