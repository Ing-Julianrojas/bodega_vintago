<?php
/**
 * Vintago Bodega — single-product.php  (CORREGIDO v2)
 * ────────────────────────────────────────────────────
 * FASE 2 FIX: Galería WooCommerce con miniaturas interactuables,
 *             efecto neón al seleccionar y fallback JS nativo si
 *             FlexSlider / Photoswipe no cargan correctamente.
 *
 * FASE 4 FIX: Selector de cantidad con botones +/− neón independientes.
 */
if (!defined('ABSPATH')) { exit; }

get_header();
while (have_posts()) : the_post();
    global $product;
    $product = wc_get_product(get_the_ID());
    if (!$product) { continue; }

    $returns = get_page_by_path('reembolso_devoluciones');
    if (!$returns) { $returns = get_page_by_title('Política de devoluciones y reembolsos'); }
    $privacy = get_page_by_path('privacy-policy');
    if (!$privacy) { $privacy = get_page_by_title('Privacy Policy'); }
    $returns_url = $returns ? get_permalink($returns) : home_url('/reembolso_devoluciones/');
    $privacy_url = $privacy ? get_permalink($privacy) : home_url('/privacy-policy/');

?>
<main class="vintago-product-page">
    <div class="vintago-product-page__inner">
        <?php woocommerce_breadcrumb(); ?>
        <div class="vintago-product-layout">

            <!-- ══ GALERÍA ══════════════════════════════════════════ -->
            <section class="vintago-product-gallery" id="vintagoGallery">
                <?php
                $gallery_ids = array_merge(array($product->get_image_id()), $product->get_gallery_image_ids());
                $gallery_ids = array_values(array_unique(array_filter(array_map('absint', $gallery_ids))));
                ?>
                <div class="vintago-gallery-main">
                    <?php if (count($gallery_ids) > 1) : ?>
                        <button type="button" class="vintago-gallery-arrow vintago-gallery-prev" aria-label="Imagen anterior">&#8592;</button>
                        <button type="button" class="vintago-gallery-arrow vintago-gallery-next" aria-label="Imagen siguiente">&#8594;</button>
                    <?php endif; ?>
                    <?php foreach ($gallery_ids as $index => $image_id) : ?>
                        <a class="vintago-gallery-slide<?php echo $index === 0 ? ' is-active' : ''; ?>" href="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>" data-gallery-index="<?php echo esc_attr($index); ?>">
                            <?php echo wp_get_attachment_image($image_id, 'large', false, array('alt' => get_the_title(), 'loading' => 'eager', 'decoding' => 'async')); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php if (count($gallery_ids) > 1) : ?>
                    <div class="vintago-gallery-thumbs" role="list">
                        <?php foreach ($gallery_ids as $index => $image_id) : ?>
                            <button type="button" class="vintago-gallery-thumb<?php echo $index === 0 ? ' is-active' : ''; ?>" data-gallery-index="<?php echo esc_attr($index); ?>" aria-label="<?php echo esc_attr(sprintf('Ver imagen %d', $index + 1)); ?>">
                                <?php echo wp_get_attachment_image($image_id, 'thumbnail', false, array('alt' => '')); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- ══ SUMMARY ══════════════════════════════════════════ -->
            <section class="vintago-product-summary">
                <p class="vintago-product-kicker">Producto Vintago</p>
                <h1><?php the_title(); ?></h1>
                <div class="vintago-product-rating"><?php woocommerce_template_single_rating(); ?></div>
                <div class="vintago-product-price"><?php woocommerce_template_single_price(); ?></div>
                <!--
                  FASE 4 FIX — Selector de cantidad con botones +/− neón.
                  WooCommerce renderiza el <form> con .quantity .qty dentro.
                  Envolvemos con nuestro container y añadimos JS para los botones.
                -->
                <div class="vintago-product-purchase" id="vintagoPurchase">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                    <?php
                    global $product;
                    $pid = $product ? $product->get_id() : 0;
                    $in_wishlist = $pid ? vintago_is_wishlisted($pid) : false;
                    ?>
                    <button type="button"
                        class="vintago-product-wishlist product-card__wishlist <?php echo $in_wishlist ? 'is-active' : ''; ?>"
                        data-product_id="<?php echo esc_attr($pid); ?>"
                        aria-label="<?php echo $in_wishlist ? 'Quitar de favoritos' : 'Añadir a favoritos'; ?>"
                        aria-pressed="<?php echo $in_wishlist ? 'true' : 'false'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                        <span class="vintago-product-wishlist__label"><?php echo $in_wishlist ? 'Quitar de favoritos' : 'Añadir a favoritos'; ?></span>
                    </button>
                </div>

                <div class="vintago-trust-points">
                    <span><b>Garantía</b> Compra respaldada</span>
                    <span><b>Envío rápido</b> Bogotá y Colombia</span>
                    <span><b>Soporte</b> Atención por WhatsApp</span>
                </div>

                <div class="vintago-product-accordions">
                    <?php if (trim(wp_strip_all_tags(get_the_content()))) : ?>
                    <details><summary>Descripción y especificaciones</summary><div><?php the_content(); ?></div></details>
                    <?php endif; ?>
                    <details><summary>Tiempo de entrega</summary><div><p>Despachamos desde Bogotá. El tiempo estimado depende de la ciudad, la transportadora y la confirmación del pago. Te enviaremos la información de seguimiento cuando el pedido sea despachado.</p></div></details>
                    <details><summary>Políticas de devolución</summary><div><p>Consulta las condiciones completas en nuestra <a href="<?php echo esc_url($returns_url); ?>">Política de devoluciones y reembolsos</a>.</p></div></details>
                    <details><summary>Política de privacidad</summary><div><p>Conoce cómo tratamos tus datos en nuestra <a href="<?php echo esc_url($privacy_url); ?>">Política de privacidad</a>.</p></div></details>
                </div>
            </section>
        </div>

        <section class="vintago-related-products">
            <h2>También te puede interesar</h2>
            <?php woocommerce_output_related_products(); ?>
        </section>
    </div><!-- /.vintago-product-page__inner -->
</main>
<script>
(function(){
 function resetGalleryTransform(){
   var gallery = document.querySelector('.woocommerce-product-gallery');
   if (!gallery) return;

   var wrapper = gallery.querySelector('.woocommerce-product-gallery__wrapper');
   if (wrapper) {
     wrapper.style.transform = 'translate3d(0, 0, 0)';
     wrapper.style.left = '0';
     wrapper.style.right = '0';
     wrapper.style.width = '100%';
     wrapper.style.maxWidth = '100%';
   }

   var viewport = gallery.querySelector('.flex-viewport');
   if (viewport) {
     viewport.style.transform = 'translate3d(0, 0, 0)';
     viewport.style.left = '0';
     viewport.style.right = '0';
     viewport.style.width = '100%';
   }

   gallery.querySelectorAll('.woocommerce-product-gallery__image').forEach(function(slide){
     slide.style.position = 'absolute';
     slide.style.left = '0';
     slide.style.top = '0';
     slide.style.opacity = slide.classList.contains('flex-active-slide') ? '1' : '0';
     slide.style.visibility = slide.classList.contains('flex-active-slide') ? 'visible' : 'hidden';
     slide.style.display = 'block';
     slide.style.pointerEvents = slide.classList.contains('flex-active-slide') ? 'auto' : 'none';
   });
 }

 function ensureGalleryVisibility(){
   var gallery = document.querySelector('.woocommerce-product-gallery');
   if (!gallery) return;

   var slides = Array.prototype.slice.call(gallery.querySelectorAll('.woocommerce-product-gallery__image'));
   if (slides.length > 1) {
     var activeIndex = slides.findIndex(function(slide) { return slide.classList.contains('flex-active-slide'); });
     if (activeIndex < 0) {
       activeIndex = 0;
     }
     slides.forEach(function(slide, index) {
       var isActive = index === activeIndex;
       slide.classList.toggle('flex-active-slide', isActive);
       slide.style.display = isActive ? 'block' : 'none';
       slide.style.opacity = isActive ? '1' : '0';
       slide.style.visibility = isActive ? 'visible' : 'hidden';
       slide.style.maxHeight = '100%';
       slide.style.height = '100%';
       slide.style.left = '0';
       slide.style.top = '0';
       slide.style.zIndex = isActive ? '1' : '0';
       slide.style.pointerEvents = isActive ? 'auto' : 'none';
       var img = slide.querySelector('img');
       if (img) {
         img.style.display = isActive ? 'block' : 'none';
         img.style.opacity = isActive ? '1' : '0';
         img.style.visibility = isActive ? 'visible' : 'hidden';
         img.style.maxHeight = '100%';
       }
     });
   }

   gallery.querySelectorAll('.flex-control-thumbs li, .flex-control-thumbs li img').forEach(function(node){
     node.style.display = 'block';
     node.style.opacity = '1';
     node.style.visibility = 'visible';
   });

   var active = gallery.querySelector('.flex-active-slide, .flex-control-thumbs li img.flex-active');
   if (active && active.closest('.flex-control-thumbs')) {
     active.closest('li').style.borderColor = '#00e5c3';
   }

   if (window.jQuery) {
     window.jQuery('.woocommerce-product-gallery').trigger('woocommerce_gallery_changed');
   }
 }

 function init(){
   ensureGalleryVisibility();
   window.setTimeout(ensureGalleryVisibility, 250);
   window.setTimeout(ensureGalleryVisibility, 700);
   window.setTimeout(ensureGalleryVisibility, 1400);
 }

 if (document.readyState === 'loading') {
   document.addEventListener('DOMContentLoaded', init);
 } else {
   init();
 }
 window.addEventListener('load', init);
 window.addEventListener('resize', ensureGalleryVisibility);
 document.addEventListener('click', function(event){
   if (event.target && (event.target.closest('.flex-control-thumb') || event.target.closest('.flex-control-thumbs li') || event.target.closest('.woocommerce-product-gallery__image'))) {
     window.setTimeout(ensureGalleryVisibility, 120);
   }
 });
})();
</script>

<script>
(function(){
    var gallery = document.getElementById('vintagoGallery');
    if (!gallery) return;
    var slides = gallery.querySelectorAll('.vintago-gallery-slide');
    var thumbs = gallery.querySelectorAll('.vintago-gallery-thumb');
    var current = 0;
    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach(function(slide, i) { slide.classList.toggle('is-active', i === current); });
        thumbs.forEach(function(thumb, i) {
            var active = i === current;
            thumb.classList.toggle('is-active', active);
            thumb.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }
    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() { show(parseInt(thumb.getAttribute('data-gallery-index'), 10) || 0); });
    });
    var previous = gallery.querySelector('.vintago-gallery-prev');
    var next = gallery.querySelector('.vintago-gallery-next');
    var auto = null;

    function startAuto() {
        if (!slides.length || slides.length < 2 || auto) {
            return;
        }
        auto = window.setInterval(function () {
            show(current + 1);
        }, 3200);
    }

    function stopAuto() {
        if (auto) {
            window.clearInterval(auto);
            auto = null;
        }
    }

    if (previous) previous.addEventListener('click', function() { stopAuto(); show(current - 1); startAuto(); });
    if (next) next.addEventListener('click', function() { stopAuto(); show(current + 1); startAuto(); });

    gallery.addEventListener('mouseenter', stopAuto);
    gallery.addEventListener('mouseleave', startAuto);
    gallery.addEventListener('focusin', stopAuto);
    gallery.addEventListener('focusout', startAuto);

    startAuto();
})();
</script>

<!-- ═══════════════════════════════════════════════════════════════════
     ESTILOS CRÍTICOS — GALERÍA + SELECTOR DE CANTIDAD NEÓN
     (en línea para garantizar que se apliquen antes del repintado)
════════════════════════════════════════════════════════════════════ -->
<style id="vintago-single-critical">
/* ── Galería nativa de WooCommerce ─────────────────────────────── */
.vintago-product-gallery .woocommerce-product-gallery {
    width: 100% !important; float: none !important; margin: 0 !important;
}
.vintago-product-gallery .flex-viewport,
.vintago-product-gallery .woocommerce-product-gallery__wrapper {
    aspect-ratio: 1 / 1;
    background: var(--bg-card, #16161f);
    border-radius: 16px;
    overflow: hidden;
}
.vintago-product-gallery .woocommerce-product-gallery__image { height: 100%; }
.vintago-product-gallery .woocommerce-product-gallery__image a,
.vintago-product-gallery .woocommerce-product-gallery__image:not(.zoomImg) a {
    display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;
}
.vintago-product-gallery .woocommerce-product-gallery__image img {
    display: block; width: auto !important; height: auto !important;
    max-width: 100% !important; max-height: 100% !important;
    object-fit: contain;
}
.vintago-product-gallery .woocommerce-product-gallery__image .zoomImg {
    width: auto !important; height: auto !important;
    max-width: none !important; max-height: none !important;
}
.vintago-product-gallery .flex-viewport,
.vintago-product-gallery .woocommerce-product-gallery__wrapper {
    position: relative !important;
    overflow: hidden !important;
}

.vintago-product-gallery .woocommerce-product-gallery__image,
.vintago-product-gallery .woocommerce-product-gallery__image.flex-active-slide,
.vintago-product-gallery .flex-viewport .woocommerce-product-gallery__image,
.vintago-product-gallery .woocommerce-product-gallery__image > a,
.vintago-product-gallery .woocommerce-product-gallery__image img,
.vintago-product-gallery .flex-control-thumbs li,
.vintago-product-gallery .flex-control-thumbs li img {
    display: block !important;
    max-height: 100% !important;
}

.vintago-product-gallery .woocommerce-product-gallery__wrapper {
    width: 100% !important;
    transform: none !important;
    display: block !important;
}

.vintago-product-gallery .woocommerce-product-gallery__image {
    position: absolute !important;
    inset: 0 !important;
    left: 0 !important;
    top: 0 !important;
    width: 100% !important;
    float: none !important;
    margin-right: 0 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
    z-index: 0 !important;
    transform: none !important;
}

.vintago-product-gallery .woocommerce-product-gallery__image.flex-active-slide,
.vintago-product-gallery .woocommerce-product-gallery__image[style*="display: block"] {
    left: 0 !important;
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
    z-index: 1 !important;
}

.vintago-product-gallery .woocommerce-product-gallery__image:not(.flex-active-slide) {
    display: block !important;
}

/* Miniaturas nativas de FlexSlider */
.vintago-product-gallery .flex-control-thumbs {
    display: flex !important;
    flex-wrap: wrap;
    gap: 10px;
    padding: 0;
    margin-top: 12px !important;
    list-style: none !important;
}
.vintago-product-gallery .flex-control-thumbs li {
    display: block;
    width: 70px !important;
    height: 70px;
    margin: 0 !important;
    padding: 0;
    list-style: none !important;
    border: 1.5px solid var(--border, #2a2a3a);
    border-radius: 8px;
    overflow: hidden;
    background: var(--bg-card, #16161f);
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.vintago-product-gallery .flex-control-thumbs li:hover {
    border-color: var(--accent, #00e5c3);
}
.vintago-product-gallery .flex-control-thumbs li img {
    width: 100%; height: 100%;
    object-fit: contain;
    padding: 5px;
    opacity: .6;
    transition: opacity .2s;
}
.vintago-product-gallery .flex-control-thumbs li img.flex-active {
    opacity: 1;
}
/* Borde neón en miniatura activa */
.vintago-product-gallery .flex-control-thumbs li:has(img.flex-active) {
    border-color: var(--accent, #00e5c3) !important;
    box-shadow: 0 0 10px rgba(0,229,195,.35) !important;
}

/* ── FASE 4 — Selector de cantidad neón ───────────────────────── */
.vintago-product-purchase .quantity {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0 !important;
    margin-right: 10px !important;
    border: 1.5px solid rgba(0,229,195,.5) !important;
    border-radius: 10px !important;
    overflow: hidden !important;
    background: transparent !important;
}
.vintago-qty-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 42px;
    border: none;
    background: transparent;
    color: var(--accent, #00e5c3);
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s, color .15s;
    flex-shrink: 0;
}
.vintago-qty-btn:hover {
    background: rgba(0,229,195,.12);
}
.vintago-product-purchase .quantity .qty {
    width: 44px !important;
    min-height: 42px !important;
    padding: 0 4px !important;
    border: none !important;
    border-left: 1px solid rgba(0,229,195,.25) !important;
    border-right: 1px solid rgba(0,229,195,.25) !important;
    border-radius: 0 !important;
    background: transparent !important;
    color: var(--text, #f0f0f5) !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    text-align: center !important;
    box-shadow: none !important;
    -moz-appearance: textfield !important;
}
.vintago-product-purchase .quantity .qty::-webkit-inner-spin-button,
.vintago-product-purchase .quantity .qty::-webkit-outer-spin-button {
    -webkit-appearance: none;
}

/* Botón "Añadir al carrito" — jerarquía neón */
.vintago-product-purchase .single_add_to_cart_button,
.vintago-product-purchase .button {
    min-height: 44px !important;
    padding: 0 18px !important;
    border: none !important;
    border-radius: 10px !important;
    background: var(--accent, #00e5c3) !important;
    color: #0a0a0f !important;
    font-size: 13px !important;
    font-weight: 800 !important;
    letter-spacing: .02em !important;
    text-transform: none !important;
    box-shadow: 0 0 14px rgba(0,229,195,.3), 0 3px 10px rgba(0,0,0,.35) !important;
    transition: background .2s, box-shadow .2s, transform .15s !important;
    cursor: pointer !important;
}
.vintago-product-purchase .single_add_to_cart_button:hover,
.vintago-product-purchase .button:hover {
    background: #00c9aa !important;
    box-shadow: 0 0 28px rgba(0,229,195,.55), 0 6px 20px rgba(0,0,0,.45) !important;
    transform: translateY(-2px) !important;
}
.vintago-product-purchase .single_add_to_cart_button:active {
    transform: translateY(0) !important;
    box-shadow: 0 0 12px rgba(0,229,195,.3) !important;
}

/* "Comprar ahora" — variante outline neón */
.vintago-buy-now {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 44px !important;
    margin: 8px 0 0 8px !important;
    padding: 0 24px !important;
    border: 1.5px solid var(--accent, #00e5c3) !important;
    border-radius: 10px !important;
    background: transparent !important;
    color: var(--accent, #00e5c3) !important;
    font-size: 14px !important;
    font-weight: 800 !important;
    letter-spacing: .04em !important;
    text-transform: uppercase !important;
    transition: background .2s, color .2s, box-shadow .2s !important;
}
.vintago-buy-now:hover {
    background: var(--accent, #00e5c3) !important;
    color: #0a0a0f !important;
    box-shadow: 0 0 18px rgba(0,229,195,.35) !important;
}

/* Botón de favoritos junto al carrito */
.vintago-product-wishlist {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;
    min-height: 44px !important;
    margin: 8px 0 0 8px !important;
    padding: 0 18px !important;
    border: 1.5px solid var(--border, #242a2e) !important;
    border-radius: 10px !important;
    background: transparent !important;
    color: var(--text-muted, #93a0a6) !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: border-color .2s, color .2s !important;
}
.vintago-product-wishlist:hover {
    border-color: #ff5a82 !important;
    color: #ff5a82 !important;
}
.vintago-product-wishlist.is-active {
    border-color: #ff5a82 !important;
    color: #ff5a82 !important;
    background: rgba(255,90,130,.1) !important;
}

/* Responsive single product */
@media (max-width: 520px) {
    .vintago-product-purchase .quantity { margin-right: 0 !important; margin-bottom: 10px !important; }
    .vintago-product-purchase .button,
    .vintago-product-purchase .single_add_to_cart_button,
    .vintago-buy-now,
    .vintago-product-wishlist { width: 100% !important; margin: 8px 0 0 0 !important; }
    .vintago-product-gallery .flex-control-thumbs li { width: 58px !important; height: 58px !important; }
}
</style>

<script>
(function () {
    'use strict';

    function initGallery() {
        var gallery    = document.querySelector('.vintago-product-gallery');
        var flexSlider = gallery && gallery.querySelector('.flexslider');
        if (!flexSlider || !window.jQuery) { return; }
        jQuery('.woocommerce-product-gallery').on('woocommerce_gallery_init_zoom woocommerce_gallery_changed', function () {
            var activeLi = jQuery(this).find('.flex-control-thumbs li img.flex-active').closest('li');
            jQuery(this).find('.flex-control-thumbs li').css('border-color', '');
            activeLi.css('border-color', '#00e5c3');
        });
    }

    /* ════════════════════════════════════════════════════════════════
       FASE 4 — Botones +/− para el input de cantidad nativo de WC
    ════════════════════════════════════════════════════════════════ */
    function initQtyButtons() {
        var qtyWrappers = document.querySelectorAll('.vintago-product-purchase .quantity');
        qtyWrappers.forEach(function (wrap) {
            if (wrap.dataset.qtyReady === 'true') { return; }
            wrap.dataset.qtyReady = 'true';

            var input = wrap.querySelector('input.qty');
            if (!input) { return; }

            // Crear botones
            var btnMinus = document.createElement('button');
            btnMinus.type = 'button';
            btnMinus.className = 'vintago-qty-btn vintago-qty-minus';
            btnMinus.setAttribute('aria-label', 'Reducir cantidad');
            btnMinus.textContent = '−';

            var btnPlus = document.createElement('button');
            btnPlus.type = 'button';
            btnPlus.className = 'vintago-qty-btn vintago-qty-plus';
            btnPlus.setAttribute('aria-label', 'Aumentar cantidad');
            btnPlus.textContent = '+';

            // Insertar antes y después del input
            wrap.insertBefore(btnMinus, input);
            wrap.appendChild(btnPlus);

            var min  = parseFloat(input.min)  || 1;
            var max  = parseFloat(input.max)  || Infinity;
            var step = parseFloat(input.step) || 1;

            btnMinus.addEventListener('click', function () {
                var val = parseFloat(input.value) || min;
                val = Math.max(min, val - step);
                input.value = val;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });

            btnPlus.addEventListener('click', function () {
                var val = parseFloat(input.value) || min;
                val = Math.min(max, val + step);
                input.value = val;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    }

    function init() {
        initGallery();
        initQtyButtons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Re-ejecutar tras variaciones de WooCommerce (producto variable)
    if (window.jQuery) {
        jQuery(document.body).on('found_variation reset_data', function () {
            initQtyButtons();
        });
    }
})();
</script>
<script>
jQuery(function ($) {
    $(document.body).on('added_to_cart', function () {
        var btn = $('.vintago-product-purchase .single_add_to_cart_button');
        if (!btn.length) { return; }
        var original = btn.text();
        btn.text('✓ Agregado').addClass('is-added');
        setTimeout(function () { btn.text(original).removeClass('is-added'); }, 1800);
    });
});
</script>
<?php endwhile; get_footer(); ?>
