(function () {
    'use strict';

    function initHomeHeroSlider() {
        var root = document.querySelector('.bns-home-hero-slider__carousel');
        if (!root || typeof Swiper === 'undefined') {
            return;
        }

        var slideCount = root.querySelectorAll('.swiper-slide').length;
        if (slideCount === 0) {
            return;
        }

        var swiper = new Swiper(root, {
            slidesPerView: 1,
            spaceBetween: 0,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoHeight: true,
            speed: 700,
            loop: slideCount > 1,
            autoplay: slideCount > 1 ? {
                delay: 6000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            } : false,
            pagination: {
                el: '.bns-home-hero-slider__pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.bns-home-hero-slider__nav--next',
                prevEl: '.bns-home-hero-slider__nav--prev',
            },
            watchOverflow: true,
            observer: true,
            observeParents: true,
            on: {
                init: function () {
                    this.updateAutoHeight(0);
                },
                slideChangeTransitionEnd: function () {
                    this.updateAutoHeight(300);
                },
                resize: function () {
                    this.updateAutoHeight(0);
                },
            },
        });

        root.querySelectorAll('.bns-home-hero-slider__img').forEach(function (img) {
            if (img.complete) {
                swiper.updateAutoHeight(0);
                return;
            }

            img.addEventListener('load', function () {
                swiper.updateAutoHeight(300);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHomeHeroSlider);
    } else {
        initHomeHeroSlider();
    }
})();
