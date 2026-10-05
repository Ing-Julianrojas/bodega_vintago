(function () {
    'use strict';

    function initProductGalleries() {
        document.querySelectorAll('.vintago-product-card__media').forEach(function (media) {
            if (media.dataset.galleryReady === 'true') {
                return;
            }

            var images = Array.from(media.querySelectorAll('img'));
            var dots = Array.from(media.querySelectorAll('.vintago-product-card__gallery i'));
            var next = media.querySelector('.vintago-product-card__next');
            if (images.length < 2) {
                return;
            }

            media.dataset.galleryReady = 'true';
            var index = 0;
            var timer = null;

            function showImage(nextIndex) {
                images[index].classList.remove('is-active');
                if (dots[index]) {
                    dots[index].classList.remove('is-active');
                }
                index = (nextIndex + images.length) % images.length;
                images[index].classList.add('is-active');
                if (dots[index]) {
                    dots[index].classList.add('is-active');
                }
            }

            function start() {
                if (!timer) {
                    timer = window.setInterval(function () {
                        showImage(index + 1);
                    }, 3200);
                }
            }

            function stop() {
                if (timer) {
                    window.clearInterval(timer);
                    timer = null;
                }
            }

            media.addEventListener('mouseenter', stop);
            media.addEventListener('mouseleave', start);
            media.addEventListener('focusin', stop);
            media.addEventListener('focusout', start);

            /* Botón "siguiente" y swipe táctil comparten el MISMO índice
               que el autoplay, para no desincronizarse nunca. */
            if (next) {
                next.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    stop();
                    showImage(index + 1);
                    start();
                });
            }

            var startX = 0;
            media.addEventListener('touchstart', function (e) {
                startX = e.touches[0].clientX;
                stop();
            }, { passive: true });
            media.addEventListener('touchend', function (e) {
                var dx = e.changedTouches[0].clientX - startX;
                if (Math.abs(dx) > 40) {
                    showImage(dx < 0 ? index + 1 : index - 1);
                }
                start();
            }, { passive: true });

            start();
        });
    }

    function initCategoryCarousel() {
        document.querySelectorAll('.cat-carousel').forEach(function (carousel) {
            var grid = carousel.querySelector('.cat-grid');
            var previous = carousel.querySelector('.cat-carousel-prev');
            var next = carousel.querySelector('.cat-carousel-next');
            if (!grid || !previous || !next || carousel.dataset.carouselReady === 'true') {
                return;
            }

            carousel.dataset.carouselReady = 'true';
            function move(direction) {
                grid.scrollBy({ left: direction * grid.clientWidth * 0.82, behavior: 'smooth' });
            }
            previous.addEventListener('click', function () { move(-1); });
            next.addEventListener('click', function () { move(1); });

            var autoplay = window.setInterval(function () {
                var atEnd = grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 4;
                grid.scrollTo({ left: atEnd ? 0 : grid.scrollLeft + grid.clientWidth, behavior: 'smooth' });
            }, 4800);
            carousel.addEventListener('mouseenter', function () { window.clearInterval(autoplay); });
            carousel.addEventListener('mouseleave', function () {
                autoplay = window.setInterval(function () {
                    var atEnd = grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 4;
                    grid.scrollTo({ left: atEnd ? 0 : grid.scrollLeft + grid.clientWidth, behavior: 'smooth' });
                }, 4800);
            });
        });
    }

    function initLegacyProductCards() {
        document.querySelectorAll('.product-card').forEach(function (card) {
            var thumb = card.querySelector('.product-thumb');
            if (!thumb || thumb.dataset.dotsReady === 'true') {
                return;
            }

            var imgs = Array.from(thumb.querySelectorAll('img'));
            var dots = Array.from(thumb.querySelectorAll('.product-dots span'));
            thumb.dataset.dotsReady = 'true';

            function show(nextIndex) {
                if (!imgs.length) { return; }
                var i = ((nextIndex % imgs.length) + imgs.length) % imgs.length;
                imgs.forEach(function (img, idx) { img.classList.toggle('active', idx === i); });
                dots.forEach(function (dot, idx) { dot.classList.toggle('active', idx === i); });
            }

            dots.forEach(function (dot, i) {
                dot.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    show(i);
                });
            });

            if (card.dataset.productUrl && card.dataset.navReady !== 'true') {
                card.dataset.navReady = 'true';
                card.addEventListener('click', function (e) {
                    if (e.target.closest('.product-card__quick-cart, .add-to-cart-btn, .product-dots span')) {
                        return;
                    }
                    window.location.href = card.dataset.productUrl;
                });
            }
        });
    }

    function init() {
        initProductGalleries();
        initCategoryCarousel();
        initLegacyProductCards();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
