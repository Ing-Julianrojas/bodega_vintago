(function () {
    'use strict';

    function initProductGalleries() {
        document.querySelectorAll('.vintago-product-card__media').forEach(function (media) {
            if (media.dataset.galleryReady === 'true') {
                return;
            }

            var images = Array.from(media.querySelectorAll('img'));
            var dots = Array.from(media.querySelectorAll('.vintago-product-card__gallery i'));
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
                var card = grid.querySelector('.cat-card');
                var distance = card ? card.getBoundingClientRect().width + 12 : grid.clientWidth * 0.82;
                grid.scrollBy({ left: direction * distance, behavior: 'smooth' });
            }
            previous.addEventListener('click', function () { move(-1); });
            next.addEventListener('click', function () { move(1); });

            function advance() {
                var atEnd = grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 4;
                grid.scrollTo({ left: atEnd ? 0 : grid.scrollLeft + grid.clientWidth * 0.82, behavior: 'smooth' });
            }
            var autoplay = window.setInterval(advance, 4800);
            carousel.addEventListener('mouseenter', function () { window.clearInterval(autoplay); autoplay = null; });
            carousel.addEventListener('mouseleave', function () {
                if (!autoplay) autoplay = window.setInterval(advance, 4800);
            });
        });
    }

    function initCatalogCategoryCarousel() {
        document.querySelectorAll('.vintago-category-carousel').forEach(function (carousel) {
            var track = carousel.querySelector('.vintago-category-carousel__track');
            var previous = carousel.querySelector('.vintago-category-carousel__prev');
            var next = carousel.querySelector('.vintago-category-carousel__next');
            if (!track || !previous || !next || carousel.dataset.carouselReady === 'true') {
                return;
            }

            carousel.dataset.carouselReady = 'true';
            function move(direction) {
                var tile = track.querySelector('.vintago-category-tile');
                var distance = tile ? tile.getBoundingClientRect().width + 16 : track.clientWidth * 0.8;
                track.scrollBy({ left: direction * distance, behavior: 'smooth' });
            }
            function advance() {
                var atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
                track.scrollTo({ left: atEnd ? 0 : track.scrollLeft + track.clientWidth * 0.8, behavior: 'smooth' });
            }

            previous.addEventListener('click', function () { move(-1); });
            next.addEventListener('click', function () { move(1); });
            var autoplay = window.setInterval(advance, 5000);
            carousel.addEventListener('mouseenter', function () { window.clearInterval(autoplay); autoplay = null; });
            carousel.addEventListener('mouseleave', function () { if (!autoplay) autoplay = window.setInterval(advance, 5000); });
        });
    }

    function initWishlistButtons() {
        var badge = document.getElementById('vintagoWishlistCount');

        function updateBadge(count) {
            if (!badge) { return; }
            badge.textContent = count;
            badge.style.display = count > 0 ? '' : 'none';
        }

        document.querySelectorAll('.product-card__wishlist').forEach(function (btn) {
            if (btn.dataset.wishlistReady === 'true') { return; }
            btn.dataset.wishlistReady = 'true';

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (!window.vintagoWishlist) { return; }

                var productId = btn.getAttribute('data-product_id');
                var willActivate = !btn.classList.contains('is-active');
                var svg = btn.querySelector('svg');

                /* Actualización optimista: cambia la UI antes de la respuesta del servidor */
                btn.classList.toggle('is-active', willActivate);
                btn.setAttribute('aria-label', willActivate ? 'Quitar de favoritos' : 'Añadir a favoritos');
                btn.setAttribute('aria-pressed', willActivate ? 'true' : 'false');
                var label = btn.querySelector('.vintago-product-wishlist__label');
                if (label) { label.textContent = willActivate ? 'Quitar de favoritos' : 'Añadir a favoritos'; }
                if (svg) { svg.setAttribute('fill', willActivate ? 'currentColor' : 'none'); }
                if (badge) {
                    var current = parseInt(badge.textContent, 10) || 0;
                    updateBadge(Math.max(0, current + (willActivate ? 1 : -1)));
                }

                var body = new URLSearchParams();
                body.append('action', 'vintago_toggle_wishlist');
                body.append('nonce', window.vintagoWishlist.nonce);
                body.append('product_id', productId);

                fetch(window.vintagoWishlist.ajaxUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: body.toString()
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (!data || !data.success) { throw new Error('vintago_wishlist_error'); }
                        btn.classList.toggle('is-active', data.data.in_wishlist);
                        btn.setAttribute('aria-label', data.data.in_wishlist ? 'Quitar de favoritos' : 'Añadir a favoritos');
                        btn.setAttribute('aria-pressed', data.data.in_wishlist ? 'true' : 'false');
                        if (label) { label.textContent = data.data.in_wishlist ? 'Quitar de favoritos' : 'Añadir a favoritos'; }
                        if (svg) { svg.setAttribute('fill', data.data.in_wishlist ? 'currentColor' : 'none'); }
                        updateBadge(data.data.count);

                        var wishlistGrid = btn.closest('.vintago-wishlist-grid');
                        if (wishlistGrid && !data.data.in_wishlist) {
                            var card = btn.closest('.product-card');
                            if (card) {
                                card.style.transition = 'opacity .25s ease, transform .25s ease';
                                card.style.opacity = '0';
                                card.style.transform = 'scale(.94)';
                                setTimeout(function () { card.remove(); }, 250);
                            }
                        }
                    })
                    .catch(function () {
                        /* Revierte la UI si la petición falla */
                        btn.classList.toggle('is-active', !willActivate);
                        btn.setAttribute('aria-label', !willActivate ? 'Quitar de favoritos' : 'Añadir a favoritos');
                        btn.setAttribute('aria-pressed', !willActivate ? 'true' : 'false');
                        if (label) { label.textContent = !willActivate ? 'Quitar de favoritos' : 'Añadir a favoritos'; }
                        if (svg) { svg.setAttribute('fill', !willActivate ? 'currentColor' : 'none'); }
                    });
            });
        });
    }

    function initProductCardCards() {
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
                return i;
            }

            /* Puntos: tocar/click cambia de imagen — funciona sin hover (móvil) */
            dots.forEach(function (dot, i) {
                dot.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    show(i);
                });
            });

            var addButton = card.querySelector('.add-to-cart-btn');
            var quickAdd = card.querySelector('.product-card__quick-cart');
            var isAdded = false;

            function setAddedState(added) {
                isAdded = !!added;
                card.classList.toggle('is-added', isAdded);
                if (addButton) {
                    addButton.classList.toggle('is-added', isAdded);
                    addButton.setAttribute('aria-label', added ? 'Producto agregado al carrito' : 'Añadir al carrito');
                    if (added) {
                        addButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.5 9 16.5 19 6.5"/></svg>Agregado';
                    } else {
                        addButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>Añadir al carrito';
                    }
                }
                if (quickAdd) {
                    quickAdd.classList.toggle('is-added', isAdded);
                    quickAdd.setAttribute('aria-label', added ? 'Producto agregado al carrito' : 'Añadir al carrito');
                }
            }

            if (window.jQuery) {
                window.jQuery(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
                    var clickedButton = $button && $button.length ? $button[0] : null;
                    var targetCard = clickedButton && clickedButton.closest('.product-card');
                    if (targetCard && targetCard === card) {
                        setAddedState(true);
                    }
                });
                window.jQuery(document.body).on('removed_from_cart', function (event, fragments, cart_hash, $button) {
                    var clickedButton = $button && $button.length ? $button[0] : null;
                    var targetCard = clickedButton && clickedButton.closest('.product-card');
                    if (targetCard && targetCard === card) {
                        setAddedState(false);
                    }
                });
            }

            /* Swipe en la miniatura para recorrer todas las fotos en móvil */
            if (imgs.length > 1) {
                var touchStartX = 0;
                thumb.addEventListener('touchstart', function (e) {
                    touchStartX = e.changedTouches[0].clientX;
                }, { passive: true });
                thumb.addEventListener('touchend', function (e) {
                    var dx = e.changedTouches[0].clientX - touchStartX;
                    if (Math.abs(dx) < 30) { return; }
                    var current = imgs.findIndex(function (img) { return img.classList.contains('active'); });
                    if (current < 0) { current = 0; }
                    show(current + (dx < 0 ? 1 : -1));
                }, { passive: true });
            }

            /* Tarjeta clicable: abre el producto salvo que se toque el botón o los puntos */
            if (card.dataset.productUrl && card.dataset.navReady !== 'true') {
                card.dataset.navReady = 'true';
                function openProduct() { window.location.href = card.dataset.productUrl; }
                card.addEventListener('click', function (e) {
                    if (e.target.closest('.product-card__quick-cart, .product-card__wishlist, .add-to-cart-btn, .product-dots span')) {
                        return;
                    }
                    openProduct();
                });
                card.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        if (e.target.closest('.product-card__quick-cart, .product-card__wishlist, .add-to-cart-btn')) {
                            return;
                        }
                        e.preventDefault();
                        openProduct();
                    }
                });
            }
        });
    }

    function init() {
        [initProductGalleries, initCategoryCarousel, initCatalogCategoryCarousel, initProductCardCards, initWishlistButtons]
            .forEach(function (fn) {
                try { fn(); } catch (err) { console.error('Vintago catalog init error in ' + fn.name + ':', err); }
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
