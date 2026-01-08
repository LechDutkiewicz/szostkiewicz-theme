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
        const slideCount = obrazSwiper.querySelectorAll('.swiper-slide').length;

        new Swiper('.obraz-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: slideCount > 1, // Only loop if more than 1 slide

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

    // Photoswipe initialization for image zoom/lightbox
    // Dynamic import for ES modules
    const initPhotoSwipe = async () => {
        try {
            const [PhotoSwipeModule, PhotoSwipeLightboxModule] = await Promise.all([
                import('https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/photoswipe.esm.min.js'),
                import('https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/photoswipe-lightbox.esm.min.js')
            ]);

            const PhotoSwipe = PhotoSwipeModule.default;
            const PhotoSwipeLightbox = PhotoSwipeLightboxModule.default;

            const lightbox = new PhotoSwipeLightbox({
                gallery: '.pswp-gallery',
                children: '.pswp-gallery__item',
                pswpModule: PhotoSwipe,

                // Zoom options
                zoom: true,
                maxZoomLevel: 4, // Allow 4x zoom
                initialZoomLevel: 'fit', // Fit entire image to viewport initially
                secondaryZoomLevel: 2, // Double-click zooms to 2x

                // UI options
                showHideAnimationDuration: 333,
                closeOnVerticalDrag: true,
                pinchToClose: true,

                // Controls
                closeTitle: 'Zamknij (Esc)',
                zoomTitle: 'Powiększ',
                arrowPrevTitle: 'Poprzedni',
                arrowNextTitle: 'Następny',

                // Padding around image
                padding: { top: 50, bottom: 50, left: 50, right: 50 },
            });

            lightbox.init();
            console.log('PhotoSwipe initialized successfully');
        } catch (error) {
            console.error('Failed to load PhotoSwipe:', error);
        }
    };

    // Initialize PhotoSwipe
    initPhotoSwipe();
});
