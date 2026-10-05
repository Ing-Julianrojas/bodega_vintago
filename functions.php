<?php
/**
 * Vintago Bodega Child Theme - functions.php
 * Tema hijo de Astra para bodega.vintago.com.co
 */

/* ============================================
   1. ENQUEUE ESTILOS CORRECTAMENTE
============================================ */
add_action('wp_enqueue_scripts', function() {
    // Versión de caché: usa la fecha de modificación real del archivo.
    // Si por lo que sea no se puede leer (permisos, ruta, etc.), usa la
    // hora actual para NUNCA quedarse pegado en un ?ver= fijo que el
    // navegador o el caché del hosting sirvan para siempre.
    $style_path = get_stylesheet_directory() . '/style.css';
    $style_ver  = @filemtime($style_path);
    if (!$style_ver) { $style_ver = time(); }

    $extras_path = get_stylesheet_directory() . '/vintago-extras.css';
    $extras_ver  = @filemtime($extras_path);
    if (!$extras_ver) { $extras_ver = time(); }

    // Primero carga el CSS del padre Astra
    wp_enqueue_style(
        'astra-child-parent',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme()->parent()->get('Version')
    );

    // Luego carga el CSS del child (sobreescribe Astra)
    wp_enqueue_style(
        'astra-child',
        get_stylesheet_uri(),
        ['astra-child-parent'],
        $style_ver
    );

    // Google Fonts: Inter
    wp_enqueue_style(
        'vintago-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [],
        null
    );

    // CSS extras: paginación, páginas legales, shop mejorado
    wp_enqueue_style(
        'vintago-extras',
        get_stylesheet_directory_uri() . '/vintago-extras.css',
        ['astra-child'],
        $extras_ver
    );

    wp_enqueue_script(
        'vintago-catalog',
        get_stylesheet_directory_uri() . '/assets/vintago-catalog.js',
        ['jquery', 'wc-add-to-cart'],
        filemtime(get_stylesheet_directory() . '/assets/vintago-catalog.js'),
        true
    );

    wp_localize_script('vintago-catalog', 'vintagoWishlist', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('vintago_wishlist_nonce'),
    ]);

    // Asegura que los fragments del carrito WooCommerce carguen
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
        if (is_product()) {
            wp_enqueue_script('wc-single-product');
        }
        wp_localize_script('wc-cart-fragments', 'vintagoShop', [
            'searchUrl' => home_url('/'),
        ]);
    }
}, 20);

add_action('wp_head', function () {
   $title = wp_get_document_title();
   $site_name = get_bloginfo('name');
   $description = get_bloginfo('description');

   if (empty($description)) {
       $description = 'Bodega Vintago ofrece tecnología, hogar, belleza y productos para mayoristas en Bogotá con envíos a todo Colombia.';
   }

   if (is_singular()) {
       $title = get_the_title();
       $excerpt = get_the_excerpt();
       if (!empty($excerpt)) {
           $description = wp_strip_all_tags($excerpt);
       }
   } elseif (is_product_category()) {
       $term = get_queried_object();
       if ($term && !empty($term->description)) {
           $description = wp_strip_all_tags($term->description);
       }
   }

   $og_image = home_url('/og-image.svg');
   $canonical_url = home_url(add_query_arg([], wp_unslash($_SERVER['REQUEST_URI'] ?? '/')));
   $social_profiles = [
       'https://www.facebook.com/bodegavintago?locale=es_LA',
       'https://www.instagram.com/bodegavintago/',
   ];
   $organization_schema = [
       '@context' => 'https://schema.org',
       '@type' => 'Organization',
       'name' => $site_name,
       'url' => home_url('/'),
       'logo' => $og_image,
       'sameAs' => $social_profiles,
   ];

   echo '<meta name="description" content="' . esc_attr($description) . '" />';
   echo '<meta property="og:type" content="website" />';
   echo '<meta property="og:locale" content="es_LA" />';
   echo '<meta property="og:title" content="' . esc_attr($title) . '" />';
   echo '<meta property="og:description" content="' . esc_attr($description) . '" />';
   echo '<meta property="og:url" content="' . esc_url($canonical_url) . '" />';
   echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />';
   echo '<meta property="og:image" content="' . esc_url($og_image) . '" />';
   echo '<meta property="article:publisher" content="https://www.facebook.com/bodegavintago?locale=es_LA" />';
   echo '<meta name="twitter:card" content="summary_large_image" />';
   echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />';
   echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />';
   echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />';
   echo '<link rel="me" href="https://www.facebook.com/bodegavintago?locale=es_LA" />';
   echo '<link rel="me" href="https://www.instagram.com/bodegavintago/" />';
   echo '<script type="application/ld+json">' . wp_json_encode($organization_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}, 20);

add_action('send_headers', function () {
   if (!headers_sent()) {
       if (is_ssl() || (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')) {
           header('Strict-Transport-Security: max-age=31536000; includeSubDomains', true);
       }

       header('X-Frame-Options: SAMEORIGIN', true);
       header('Referrer-Policy: strict-origin-when-cross-origin', true);
   }
}, 1);

add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', function () {
   return [];
});

add_filter('rest_endpoints', function ($endpoints) {
   if (isset($endpoints['/wp/v2/users'])) {
       unset($endpoints['/wp/v2/users']);
   }

   return $endpoints;
});

/* ============================================
   CHECKOUT — LOCALIDAD Y BARRIO PARA BOGOTÁ
============================================ */
add_filter('woocommerce_checkout_fields', function ($fields) {
    $localities = array(
        'usaquen' => 'Usaquén', 'chapinero' => 'Chapinero', 'santafe' => 'Santa Fe',
        'sancristobal' => 'San Cristóbal', 'usme' => 'Usme', 'tunjuelito' => 'Tunjuelito',
        'bosa' => 'Bosa', 'kennedy' => 'Kennedy', 'fontibon' => 'Fontibón',
        'engativa' => 'Engativá', 'suba' => 'Suba', 'barriosunidos' => 'Barrios Unidos',
        'teusaquillo' => 'Teusaquillo', 'losmartires' => 'Los Mártires',
        'antonionarino' => 'Antonio Nariño', 'puentearanda' => 'Puente Aranda',
        'candelaria' => 'La Candelaria', 'rafaeluribe' => 'Rafael Uribe Uribe',
        'ciudadbolivar' => 'Ciudad Bolívar', 'sumapaz' => 'Sumapaz',
    );

    $fields['billing']['billing_locality'] = array(
        'label'    => 'Localidad (Bogotá)',
        'type'     => 'select',
        'required' => false,
        'class'    => array('form-row-wide', 'vintago-locality-field'),
        'priority' => 75,
        'options'  => array_merge(array('' => 'Selecciona tu localidad'), $localities),
    );
    $fields['billing']['billing_neighborhood'] = array(
        'label'       => 'Barrio',
        'type'        => 'text',
        'required'    => false,
        'class'       => array('form-row-wide', 'vintago-neighborhood-field'),
        'priority'    => 76,
        'placeholder' => 'Ej: Chapinero Alto',
    );
    return $fields;
});

// Guarda los valores en el pedido
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (!empty($_POST['billing_locality'])) {
        update_post_meta($order_id, '_billing_locality', sanitize_text_field($_POST['billing_locality']));
    }
    if (!empty($_POST['billing_neighborhood'])) {
        update_post_meta($order_id, '_billing_neighborhood', sanitize_text_field($_POST['billing_neighborhood']));
    }
});

// Muestra los datos en el admin del pedido
add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
    $locality = get_post_meta($order->get_id(), '_billing_locality', true);
    $neighborhood = get_post_meta($order->get_id(), '_billing_neighborhood', true);
    if ($locality) echo '<p><strong>Localidad:</strong> ' . esc_html($locality) . '</p>';
    if ($neighborhood) echo '<p><strong>Barrio:</strong> ' . esc_html($neighborhood) . '</p>';
});

// Muestra/oculta la localidad solo cuando el estado seleccionado es Bogotá D.C.
add_action('wp_footer', function () {
    if (!is_checkout()) return;
    ?>
    <script>
    jQuery(function($){
        function toggleLocality(){
            var state = $('#billing_state').val() || '';
            var isBogota = state === 'DC' || /bogot/i.test($('#billing_state option:selected').text());
            $('#billing_locality_field, #billing_neighborhood_field').toggle(isBogota);
        }
        $(document.body).on('change', '#billing_state', toggleLocality);
        $(document.body).on('updated_checkout', toggleLocality);
        toggleLocality();
    });
    </script>
    <?php
});

add_filter('woocommerce_gallery_image_size', function() {
    return 'large';
}, 20);

add_filter('woocommerce_gallery_thumbnail_size', function() {
    return 'woocommerce_thumbnail';
}, 20);

/* ============================================
   FIX SHOP — MOSTRAR TODOS LOS PRODUCTOS EN /tienda/
   WooCommerce a veces no ejecuta su propio query en
   temas hijos; esto lo fuerza correctamente.
============================================ */
add_action('pre_get_posts', function($query) {
    if (!$query->is_main_query()) {
        return;
    }
    // Actuar en la tienda y en categorías, incluyendo productos de subcategorías.
    $is_catalog_query = function_exists('is_shop') && is_shop();
    if (function_exists('is_product_category') && is_product_category()) {
        $is_catalog_query = true;
        $category_id = absint(get_queried_object_id());
        if ($category_id) {
            $tax_query = (array) $query->get('tax_query');
            $category_clause_found = false;
            foreach ($tax_query as $tax_index => $tax_clause) {
                if (is_array($tax_clause) && isset($tax_clause['taxonomy']) && $tax_clause['taxonomy'] === 'product_cat') {
                    $tax_query[$tax_index]['include_children'] = true;
                    $category_clause_found = true;
                }
            }
            if (!$category_clause_found) {
                $tax_query[] = array(
                    'taxonomy'         => 'product_cat',
                    'field'            => 'term_id',
                    'terms'            => $category_id,
                    'include_children' => true,
                );
            }
            $query->set('tax_query', $tax_query);
        }
    }
    if (!$is_catalog_query) {
        return;
    }
    // Aseguramos que el tipo de post sea product y que estén publicados
    if ($query->get('post_type') !== 'product') {
        $query->set('post_type', 'product');
    }
    $query->set('post_status', 'publish');
    // Productos por página (respeta el ajuste de WooCommerce)
    $query->set('posts_per_page', 24);
});

/* ============================================
   MINI-CARRITO LATERAL GLOBAL
============================================ */
function vintago_side_cart_markup() {
    if (!function_exists('WC') || !WC()->cart) {
        return '';
    }
    if (WC()->cart->is_empty()) {
        return '<p class="vintago-side-cart__empty">No hay productos en el carrito.</p>';
    }

    ob_start();
    echo '<ul class="vintago-mini-cart">';
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        if (!$product || !$product->exists() || $cart_item['quantity'] < 1) {
            continue;
        }
        $product_url = $product->is_visible() ? $product->get_permalink($cart_item) : '';
        $remove_url = wc_get_cart_remove_url($cart_item_key);
        echo '<li class="vintago-mini-cart__item">';
        echo '<a class="vintago-mini-cart__remove remove remove_from_cart_button" href="' . esc_url($remove_url) . '" aria-label="' . esc_attr(sprintf('Eliminar %s del carrito', $product->get_name())) . '" data-product_id="' . esc_attr($product->get_id()) . '" data-cart_item_key="' . esc_attr($cart_item_key) . '">&times;</a>';
        if ($product_url) {
            echo '<a class="vintago-mini-cart__image" href="' . esc_url($product_url) . '">' . $product->get_image('woocommerce_thumbnail') . '</a>';
            echo '<a class="vintago-mini-cart__name" href="' . esc_url($product_url) . '">' . esc_html($product->get_name()) . '</a>';
        } else {
            echo '<span class="vintago-mini-cart__image">' . $product->get_image('woocommerce_thumbnail') . '</span>';
            echo '<span class="vintago-mini-cart__name">' . esc_html($product->get_name()) . '</span>';
        }
        echo '<span class="vintago-mini-cart__price">' . wp_kses_post(WC()->cart->get_product_price($product)) . '</span>';
        echo '<div class="vintago-mini-cart__qty" data-cart_item_key="' . esc_attr($cart_item_key) . '">';
        echo '<button type="button" class="vintago-mini-cart__qty-btn" data-action="minus" aria-label="Reducir cantidad">&minus;</button>';
        echo '<span class="vintago-mini-cart__qty-value">' . esc_html($cart_item['quantity']) . '</span>';
        echo '<button type="button" class="vintago-mini-cart__qty-btn" data-action="plus" aria-label="Aumentar cantidad">+</button>';
        echo '</div>';
        echo '</li>';
    }
    echo '</ul>';
    return ob_get_clean();
}

add_action('wp_footer', function() {
    static $rendered = false;
    if ($rendered) {
        return;
    }
    if (!class_exists('WooCommerce') || is_cart() || is_checkout() || (function_exists('is_account_page') && is_account_page())) {
        return;
    }
    $rendered = true;

    $cart_url = wc_get_cart_url();
    $checkout_url = wc_get_checkout_url();
    $whatsapp_number = '573127558773';
    $whatsapp_message = 'Hola, quiero más información sobre un producto de Bodega Vintago.';
    if (is_product()) {
        $whatsapp_message = 'Hola, quiero más información sobre "' . get_the_title() . '" de Bodega Vintago.';
    }
    $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . rawurlencode($whatsapp_message);
    ?>
    <div class="vintago-side-cart" id="vintagoSideCart" aria-hidden="true">
        <div class="vintago-side-cart__shade" data-vintago-cart-close></div>
        <aside class="vintago-side-cart__panel" aria-label="Carrito de compras">
            <div class="vintago-side-cart__head">
                <h2>Tu carrito</h2>
                <button class="vintago-side-cart__close" type="button" data-vintago-cart-close aria-label="Cerrar carrito">&times;</button>
            </div>
            <div class="vintago-side-cart__content" data-vintago-cart-content><?php echo vintago_side_cart_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
            <div class="vintago-side-cart__footer">
                <div class="vintago-side-cart__total"><span>Subtotal</span><strong data-vintago-cart-total><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></strong></div>
                <div class="vintago-side-cart__actions">
                    <a href="<?php echo esc_url($cart_url); ?>">Ver carrito</a>
                    <a href="<?php echo esc_url($checkout_url); ?>">Finalizar compra</a>
                </div>
            </div>
        </aside>
    </div>
    <div id="whatsapp-widget-root">
        <div id="chat-bubble" class="whatsapp-widget whatsapp-widget-right whatsapp-widget-visible" aria-label="Open WhatsApp chat">
            <a id="whatsapp-link" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Send a message via WhatsApp" style="position: relative; display: flex;">
                <div style="--whatsapp-link-width: 80px; --whatsapp-link-height: 80px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 175.216 175.552" aria-hidden="true" focusable="false">
                        <defs>
                            <linearGradient id="default-icon-b" x1="85.915" x2="86.535" y1="32.567" y2="137.092" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#57d163"></stop>
                                <stop offset="1" stop-color="#23b33a"></stop>
                            </linearGradient>
                            <filter id="a" width="1.115" height="1.114" x="-.057" y="-.057" color-interpolation-filters="sRGB">
                                <feGaussianBlur stdDeviation="3.531"></feGaussianBlur>
                            </filter>
                        </defs>
                        <path fill="#b3b3b3" d="m54.532 138.45 2.235 1.324c9.387 5.571 20.15 8.518 31.126 8.523h.023c33.707 0 61.139-27.426 61.153-61.135.006-16.335-6.349-31.696-17.895-43.251A60.75 60.75 0 0 0 87.94 25.983c-33.733 0-61.166 27.423-61.178 61.13a60.98 60.98 0 0 0 9.349 32.535l1.455 2.312-6.179 22.558zm-40.811 23.544L24.16 123.88c-6.438-11.154-9.825-23.808-9.821-36.772.017-40.556 33.021-73.55 73.578-73.55 19.681.01 38.154 7.669 52.047 21.572s21.537 32.383 21.53 52.037c-.018 40.553-33.027 73.553-73.578 73.553h-.032c-12.313-.005-24.412-3.094-35.159-8.954zm0 0" filter="url(#a)"></path>
                        <path fill="#fff" d="m12.966 161.238 10.439-38.114a73.42 73.42 0 0 1-9.821-36.772c.017-40.556 33.021-73.55 73.578-73.55 19.681.01 38.154 7.669 52.047 21.572s21.537 32.383 21.53 52.037c-.018 40.553-33.027 73.553-73.578 73.553h-.032c-12.313-.005-24.412-3.094-35.159-8.954z"></path>
                        <path fill="url(#default-icon-b)" d="M87.184 25.227c-33.733 0-61.166 27.423-61.178 61.13a60.98 60.98 0 0 0 9.349 32.535l1.455 2.313-6.179 22.558 23.146-6.069 2.235 1.324c9.387 5.571 20.15 8.517 31.126 8.523h.023c33.707 0 61.14-27.426 61.153-61.135a60.75 60.75 0 0 0-17.895-43.251 60.75 60.75 0 0 0-43.235-17.928z"></path>
                        <path fill="#fff" fill-rule="evenodd" d="M68.772 55.603c-1.378-3.061-2.828-3.123-4.137-3.176l-3.524-.043c-1.226 0-3.218.46-4.902 2.3s-6.435 6.287-6.435 15.332 6.588 17.785 7.506 19.013 12.718 20.381 31.405 27.75c15.529 6.124 18.689 4.906 22.061 4.6s10.877-4.447 12.408-8.74 1.532-7.971 1.073-8.74-1.685-1.226-3.525-2.146-10.877-5.367-12.562-5.981-2.91-.919-4.137.921-4.746 5.979-5.819 7.206-2.144 1.381-3.984.462-7.76-2.861-14.784-9.124c-5.465-4.873-9.154-10.891-10.228-12.73s-.114-2.835.808-3.751c.825-.824 1.838-2.147 2.759-3.22s1.224-1.84 1.836-3.065.307-2.301-.153-3.22-4.032-10.011-5.666-13.647"></path>
                    </svg>
                </div>
            </a>
            <div class="chat-fade-wrapper closing" data-position="right"></div>
        </div>
    </div>
    <div class="vintago-whatsapp-chat" id="vintagoWhatsAppChat" hidden>
        <div class="vintago-whatsapp-chat__head">
            <strong>Escríbenos por WhatsApp</strong>
            <button type="button" data-vintago-whatsapp-close aria-label="Cerrar WhatsApp">&times;</button>
        </div>
        <p>Cuéntanos qué producto necesitas y te ayudamos.</p>
        <textarea data-vintago-whatsapp-message rows="4"><?php echo esc_textarea($whatsapp_message); ?></textarea>
        <button type="button" class="vintago-whatsapp-chat__send" data-vintago-whatsapp-send>Enviar mensaje</button>
    </div>
    <script>
    (function($){
        var cartPanel = $('#vintagoSideCart');
        var cartTrigger = $('[data-vintago-cart-open]');
        var cartCloseBtn = $('[data-vintago-cart-close]');

        function vintagoCartOpen(){
            if (!cartPanel.length) return;
            cartPanel.addClass('is-open').attr('aria-hidden', 'false');
            $('body').addClass('vintago-cart-open');
        }

        function vintagoCartClose(){
            if (!cartPanel.length) return;
            cartPanel.removeClass('is-open').attr('aria-hidden', 'true');
            $('body').removeClass('vintago-cart-open');
        }

        function vintagoSyncCartCount(){
            var count = 0;
            var items = cartPanel.find('.woocommerce-mini-cart-item, .vintago-mini-cart__item');
            if (items.length) {
                items.each(function(){
                    var qtyText = $(this).find('.quantity, .vintago-mini-cart__qty-value').text();
                    var match = qtyText.match(/(\d+)/);
                    var qty = match ? parseInt(match[1], 10) : 1;
                    count += qty;
                });
            }
            if (!count && typeof wc_cart_params !== 'undefined' && wc_cart_params && wc_cart_params.cart_count !== undefined) {
                count = parseInt(wc_cart_params.cart_count, 10) || 0;
            }
            $('#vintagoCartCount, #cartCount').text(count);
            if (count > 0) {
                $('#vintagoCartCount, #cartCount').addClass('has-items');
            } else {
                $('#vintagoCartCount, #cartCount').removeClass('has-items');
            }
        }

        if (cartPanel.length) {
            cartTrigger.on('click', function(event){
                event.preventDefault();
                event.stopPropagation();
                if (cartPanel.hasClass('is-open')) {
                    vintagoCartClose();
                    return;
                }
                vintagoCartOpen();
            });

            cartCloseBtn.on('click', function(event){
                event.preventDefault();
                vintagoCartClose();
            });

            $(document).on('click', function(event){
                if (!$(event.target).closest('.vintago-side-cart__panel, [data-vintago-cart-open]').length && cartPanel.hasClass('is-open')) {
                    vintagoCartClose();
                }
            });

            $(document).on('click', '.vintago-side-cart .remove', function(event){
                event.preventDefault();
                setTimeout(function(){
                    $(document.body).trigger('wc_fragment_refresh');
                    vintagoSyncCartCount();
                }, 250);
            });

            $(document).on('click', '.vintago-mini-cart__qty-btn', function(event){
                event.preventDefault();
                var btn = $(this);
                var wrap = btn.closest('.vintago-mini-cart__qty');
                var cartItemKey = wrap.data('cart_item_key');
                var direction = btn.data('action');
                if (!cartItemKey || btn.prop('disabled')) return;

                wrap.closest('.vintago-mini-cart').css('opacity', 0.6).css('pointer-events', 'none');

                $.post(window.vintagoWishlist ? window.vintagoWishlist.ajaxUrl : woocommerce_params.ajax_url, {
                    action: 'vintago_update_cart_qty',
                    nonce: window.vintagoWishlist ? window.vintagoWishlist.nonce : '',
                    cart_item_key: cartItemKey,
                    direction: direction
                }).done(function(response){
                    if (response && response.success) {
                        $('[data-vintago-cart-content]').replaceWith('<div data-vintago-cart-content>' + response.data.content + '</div>');
                        $('[data-vintago-cart-total]').text(response.data.total.replace(/<[^>]+>/g, ''));
                        $('#vintagoCartCount, #cartCount').text(response.data.count).toggleClass('has-items', response.data.count > 0);
                        $(document.body).trigger('wc_fragment_refresh');
                    }
                }).fail(function(){
                    vintagoCartClose();
                });
            });

            $(document.body)
                .on('added_to_cart removed_from_cart updated_cart_totals wc_fragments_refreshed', function(){
                    setTimeout(vintagoSyncCartCount, 120);
                });

            $(document.body).on('added_to_cart', function(){
                vintagoCartOpen();
            });

            vintagoSyncCartCount();
        }

        var whatsappChat = $('#vintagoWhatsAppChat');
        var whatsappLink = $('#whatsapp-link');
        var whatsappBubble = $('#chat-bubble');

        function openChat(){
            whatsappChat.prop('hidden', false).addClass('is-visible');
            whatsappChat.find('textarea').trigger('focus');
        }

        function closeChat(){
            whatsappChat.removeClass('is-visible').prop('hidden', true);
        }

        whatsappBubble.on('click', function(event){
            event.preventDefault();
            openChat();
        });

        whatsappLink.on('click', function(event){
            event.preventDefault();
            openChat();
        });

        $(document).on('click', '[data-vintago-whatsapp-close]', function(){
            closeChat();
        });

        $(document).on('click', '[data-vintago-whatsapp-send]', function(){
            var message = $.trim(whatsappChat.find('textarea').val());
            if (!message) {
                whatsappChat.find('textarea').trigger('focus');
                return;
            }
            window.open('https://wa.me/<?php echo esc_js($whatsapp_number); ?>?text=' + encodeURIComponent(message), '_blank', 'noopener,noreferrer');
            closeChat();
        });

        $(document).on('keyup', function(event){
            if (event.key === 'Escape') {
                closeChat();
                vintagoCartClose();
            }
        });

        $(document).on('click', function(event){
            if (!$(event.target).closest('#chat-bubble, #vintagoWhatsAppChat').length) {
                closeChat();
            }
        });
    })(jQuery);
    </script>
    <?php
}, 30);

add_action('woocommerce_review_order_before_payment', function() {
    echo '<div class="vintago-shipping-notice"><strong>Envío por cuenta del cliente</strong><span>El valor se calcula según la ciudad de destino. Despachamos por Inter Rapidísimo y enviaremos la guía de seguimiento por WhatsApp cuando el pedido sea despachado.</span></div>';
});

add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
    if (class_exists('WooCommerce') && WC()->cart) {
        $fragments['[data-vintago-cart-content]'] = '<div data-vintago-cart-content>' . vintago_side_cart_markup() . '</div>';
        $fragments['[data-vintago-cart-total]'] = '<strong data-vintago-cart-total>' . wp_kses_post(WC()->cart->get_cart_subtotal()) . '</strong>';
    }
    return $fragments;
});

/* Cambiar cantidad de un producto en el mini-carrito lateral (+/-) por AJAX */
add_action('wp_ajax_vintago_update_cart_qty', 'vintago_update_cart_qty_ajax');
add_action('wp_ajax_nopriv_vintago_update_cart_qty', 'vintago_update_cart_qty_ajax');
function vintago_update_cart_qty_ajax() {
    check_ajax_referer('vintago_wishlist_nonce', 'nonce');
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error(['message' => 'Carrito no disponible'], 400);
    }
    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field(wp_unslash($_POST['cart_item_key'])) : '';
    $action_dir    = isset($_POST['direction']) ? sanitize_text_field(wp_unslash($_POST['direction'])) : '';
    $cart          = WC()->cart->get_cart();
    if (!$cart_item_key || !isset($cart[$cart_item_key])) {
        wp_send_json_error(['message' => 'Producto no encontrado en el carrito'], 400);
    }
    $current_qty = (int) $cart[$cart_item_key]['quantity'];
    $new_qty     = $action_dir === 'minus' ? max(0, $current_qty - 1) : $current_qty + 1;

    if ($new_qty === 0) {
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        WC()->cart->set_quantity($cart_item_key, $new_qty, true);
    }
    WC()->cart->calculate_totals();

    wp_send_json_success([
        'content' => vintago_side_cart_markup(),
        'total'   => wp_kses_post(WC()->cart->get_cart_subtotal()),
        'count'   => WC()->cart->get_cart_contents_count(),
    ]);
}


add_action('woocommerce_after_add_to_cart_button', function() {
    global $product;
    if (!$product || !$product->is_purchasable() || !$product->is_in_stock() || !$product->is_type('simple')) {
        return;
    }
    $checkout_url = wc_get_checkout_url();
    echo '<a class="vintago-buy-now" href="' . esc_url(add_query_arg(['add-to-cart' => $product->get_id(), 'quantity' => 1], $checkout_url)) . '">Comprar ahora</a>';
});

add_filter('woocommerce_package_rates', function($rates, $package) {
    $destination = isset($package['destination']) ? $package['destination'] : [];
    $city = isset($destination['city']) ? remove_accents(strtolower((string) $destination['city'])) : '';
    $state = isset($destination['state']) ? strtoupper((string) $destination['state']) : '';
    $is_bogota = $state === 'DC' || strpos($city, 'bogota') !== false;
    $cost = $is_bogota ? 10000 : 18000;
    $label = $is_bogota ? 'Inter Rapidísimo · Bogotá' : 'Inter Rapidísimo · Otras ciudades';
    $new_rate = new WC_Shipping_Rate('vintago_interrapidisimo', $label, $cost, [], 'vintago_interrapidisimo');
    return ['vintago_interrapidisimo' => $new_rate];
}, 20, 2);

/* ============================================
   PDF DE NORMAS PARA MAYORISTAS
============================================ */
add_action('customize_register', function($wp_customize) {
    $wp_customize->add_section('vintago_wholesale', [
        'title'    => 'Bodega Vintago',
        'priority' => 30,
    ]);
    $wp_customize->add_setting('vintago_wholesale_pdf_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, 'vintago_wholesale_pdf_url', [
        'label'       => 'PDF de normas mayoristas',
        'description' => 'Sube aquí el PDF oficial y publícalo para mostrarlo en la página Mayoristas.',
        'section'     => 'vintago_wholesale',
        'mime_type'   => 'application/pdf',
    ]));
    $wp_customize->add_setting('vintago_trending_product_ids', [
        'default'           => '',
        'sanitize_callback' => function($value) {
            return implode(',', array_filter(array_map('absint', explode(',', (string) $value))));
        },
    ]);
    $wp_customize->add_control('vintago_trending_product_ids', [
        'label'       => 'Productos en tendencia',
        'description' => 'Escribe los IDs separados por comas. Si queda vacío se muestran los más vendidos.',
        'section'     => 'vintago_wholesale',
        'type'        => 'text',
    ]);
});

add_action('wp_head', function() {
    $custom_css = '.vintago-side-cart .woocommerce-mini-cart__total,.vintago-side-cart .woocommerce-mini-cart__buttons{display:none!important}';
    if (is_front_page()) {
        $custom_css .= ' .cart-bar,.cart-float-btn,.vintago-side-cart__trigger{display:none!important;}';
    }
    if (is_cart() || is_checkout()) {
        $custom_css .= ' .vintago-side-cart,.vintago-side-cart__trigger,.vintago-whatsapp-float,.cart-bar,.cart-float-btn{display:none!important;}';
        $custom_css .= ' .woocommerce-notices-wrapper,.woocommerce-cart-form,.woocommerce-checkout,.woocommerce-cart .col2-set,.woocommerce-checkout .col2-set,.woocommerce-cart .cart_totals,.woocommerce-checkout .checkout-sidebar{max-width:1180px;margin:0 auto;}';
        $custom_css .= ' body.woocommerce-checkout,body.page-template-page-finalizar-compra,body.woocommerce-cart{background:#0a0a0f!important;color:#f0f0f5!important;} body.woocommerce-checkout #content,body.woocommerce-checkout #primary,body.woocommerce-cart #content,body.woocommerce-cart #primary{background:transparent!important;}';
        $custom_css .= ' .woocommerce table.shop_table{border:1px solid #2a2a3a;border-radius:16px;overflow:hidden;background:#16161f;color:#f0f0f5;}';
        $custom_css .= ' .woocommerce .button,.woocommerce button.button,.woocommerce input.button,.woocommerce #place_order{background:#00e5c3;color:#0a0a0f;border:0;border-radius:12px;font-weight:700;padding:14px 22px;}';
    }
    echo '<style>' . $custom_css . '</style>';
}, 99);

/* ============================================
   2. FIX CARRITO - AJAX REMOVE ITEM
============================================ */
// Asegura que el nonce de WooCommerce esté disponible para JS
add_action('wp_footer', function() {
    if (class_exists('WooCommerce')) {
        ?>
        <script>
        jQuery(function($) {
            function vintagoRefreshCartState() {
                $(document.body).trigger('update_checkout');
                $(document.body).trigger('wc_fragment_refresh');
                $(document.body).trigger('wc_fragments_refreshed');
                setTimeout(function() {
                    var subtotal = $('strong[data-vintago-cart-total]').first();
                    if (subtotal.length && typeof wc_cart_params !== 'undefined' && wc_cart_params.cart_total) {
                        subtotal.text(wc_cart_params.cart_total);
                    }
                    var cartContent = $('[data-vintago-cart-content]').first();
                    if (cartContent.length && typeof wc_cart_params !== 'undefined') {
                        cartContent.find('.woocommerce-mini-cart__empty-message').length || cartContent.find('.woocommerce-mini-cart').length;
                    }
                }, 120);
            }

            $(document.body)
                .on('added_to_cart', vintagoRefreshCartState)
                .on('removed_from_cart', vintagoRefreshCartState)
                .on('updated_cart_totals', vintagoRefreshCartState)
                .on('wc_fragments_refreshed', function() {
                    var subtotal = $('strong[data-vintago-cart-total]').first();
                    if (subtotal.length && typeof wc_cart_params !== 'undefined' && wc_cart_params.cart_total) {
                        subtotal.text(wc_cart_params.cart_total);
                    }
                });

            $(document).on('click', 'a.remove', function() {
                setTimeout(vintagoRefreshCartState, 180);
            });

            function vintagoCartQuantityControls() {
                $('.woocommerce-cart .quantity').each(function() {
                    var quantity = $(this);
                    if (quantity.find('.vintago-cart-qty-btn').length || !quantity.find('input.qty').length) {
                        return;
                    }
                    quantity.prepend('<button type="button" class="vintago-cart-qty-btn vintago-cart-qty-minus" aria-label="Reducir cantidad">−</button>');
                    quantity.append('<button type="button" class="vintago-cart-qty-btn vintago-cart-qty-plus" aria-label="Aumentar cantidad">+</button>');
                });
            }

            $(document).on('click', '.vintago-cart-qty-btn', function() {
                var button = $(this);
                var input = button.siblings('input.qty');
                if (!input.length) return;
                var value = parseFloat(input.val()) || 1;
                var min = parseFloat(input.attr('min')) || 1;
                var max = parseFloat(input.attr('max')) || Infinity;
                value += button.hasClass('vintago-cart-qty-plus') ? 1 : -1;
                value = Math.max(min, Math.min(max, value));
                input.val(value).trigger('change');
                $('.woocommerce-cart-form button[name="update_cart"]').prop('disabled', false).trigger('click');
            });

            vintagoCartQuantityControls();
            $(document.body).on('updated_wc_div updated_cart_totals', vintagoCartQuantityControls);

            function vintagoEnsureGalleryVisibility() {
                var gallery = $('.woocommerce-product-gallery');
                if (!gallery.length) return;

                gallery.find('.woocommerce-product-gallery__wrapper').css({ transform: 'none', width: '100%' });
                gallery.find('.woocommerce-product-gallery__image, .flex-viewport .woocommerce-product-gallery__image').each(function() {
                    var isActive = $(this).hasClass('flex-active-slide');
                    $(this).css({
                        display: 'block',
                        opacity: isActive ? 1 : 0,
                        visibility: isActive ? 'visible' : 'hidden',
                        position: 'absolute',
                        left: '0px',
                        top: '0',
                        width: '100%',
                        float: 'none',
                        marginRight: '0',
                        zIndex: isActive ? 1 : 0,
                        pointerEvents: isActive ? 'auto' : 'none',
                        transform: 'none'
                    });
                    var img = $(this).find('img');
                    if (img.length) {
                        img.css({
                            display: 'block',
                            opacity: isActive ? 1 : 0,
                            visibility: isActive ? 'visible' : 'hidden',
                            maxWidth: '100%',
                            maxHeight: '100%'
                        });
                    }
                });

                gallery.find('.flex-control-thumbs li').each(function() {
                    $(this).css({ display: 'block', opacity: 1, visibility: 'visible' });
                    $(this).find('img').css({ display: 'block', opacity: 1, visibility: 'visible' });
                });
            }

            vintagoEnsureGalleryVisibility();
            $(window).on('load', vintagoEnsureGalleryVisibility);
            $(document.body).on('woocommerce_gallery_init_zoom woocommerce_gallery_changed wc-product-gallery-after-init', vintagoEnsureGalleryVisibility);
            setTimeout(vintagoEnsureGalleryVisibility, 350);
        });
        </script>
        <?php
    }
}, 99);

/* ============================================
   3. LOGIN PAGE - ESTILOS OSCUROS
============================================ */
add_action('login_enqueue_scripts', function() {
    // CSS para wp-login.php
    echo '<style>
        body.login {
            background: #0a0a0f !important;
            font-family: "Inter", system-ui, sans-serif !important;
        }
        body.login #login {
            background: #16161f !important;
            border: 1px solid #2a2a3a !important;
            border-radius: 16px !important;
            padding: 32px !important;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5) !important;
        }
        body.login h1 a {
            background-image: none !important;
            color: #00e5c3 !important;
            font-size: 22px !important;
            font-weight: 800 !important;
            text-indent: 0 !important;
            height: auto !important;
            width: auto !important;
        }
        body.login h1 a::before { content: "Bodega Vintagø"; }
        body.login label {
            color: #8888a8 !important;
            font-size: 13px !important;
        }
        body.login input[type="text"],
        body.login input[type="password"],
        body.login input[type="email"] {
            background: #0a0a0f !important;
            border: 1px solid #2a2a3a !important;
            border-radius: 10px !important;
            color: #f0f0f5 !important;
            padding: 12px 14px !important;
            box-shadow: none !important;
        }
        body.login input[type="text"]:focus,
        body.login input[type="password"]:focus {
            border-color: #00e5c3 !important;
            box-shadow: 0 0 0 3px rgba(0,229,195,0.1) !important;
        }
        body.login .button-primary,
        body.login input[type="submit"] {
            background: #00e5c3 !important;
            border-color: #00e5c3 !important;
            color: #0a0a0f !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
            width: 100% !important;
            padding: 12px !important;
            font-size: 15px !important;
            box-shadow: none !important;
        }
        body.login .button-primary:hover {
            background: #00c9aa !important;
        }
        body.login #nav a,
        body.login #backtoblog a {
            color: #8888a8 !important;
        }
        body.login #nav a:hover,
        body.login #backtoblog a:hover {
            color: #00e5c3 !important;
        }
        body.login .privacy-policy-page-link { color: #8888a8 !important; }
    </style>';
});

/* ============================================
   4. REGISTRAR TEMPLATES DE PÁGINAS
============================================ */
// Esto permite que page-mayoristas.php y page-acceder.php
// aparezcan como opciones en el editor de páginas de WP
add_filter('theme_page_templates', function($templates) {
    $templates['page-mayoristas.php'] = 'Página Mayoristas';
    $templates['page-acceder.php']    = 'Página Acceder / Login';
    return $templates;
});

/* ============================================
   5. REDIRIGIR /MI-CUENTA A /ACCEDER SI NO LOGUEADO
============================================ */
add_action('template_redirect', function() {
    if (is_page('acceder') && is_user_logged_in()) {
        wp_redirect(wc_get_page_permalink('myaccount'));
        exit;
    }
    // Si no está logueado y entra a Mi cuenta o a cualquier endpoint suyo
    // (favoritos, pedidos, etc.), lo mandamos a la página de login bonita
    // en vez de mostrar el formulario genérico que WooCommerce mete solo.
    if (function_exists('is_account_page') && is_account_page() && !is_user_logged_in()) {
        $login_page = get_page_by_path('acceder');
        if ($login_page) {
            wp_redirect(get_permalink($login_page));
            exit;
        }
    }
});

/* ============================================
   6. QUITAR EL HEADER DE ASTRA EN PÁGINAS FULL
============================================ */
// Desactiva el header/footer de Astra en front-page si el template lo maneja solo
add_filter('astra_header_enabled', function($enabled) {
    if (is_front_page() && !is_home()) {
        // El front-page.php maneja su propio header
        // Cambia a false si quieres control 100% manual
        return true;
    }
    return $enabled;
});

/* ============================================
   7. SOPORTE DE CARACTERÍSTICAS
============================================ */
add_action('after_setup_theme', function() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
});

/* ============================================
   8. CONTEO RECURSIVO DE PRODUCTOS POR CATEGORÍA
============================================ */
if (!function_exists('vintago_recursive_product_count')) {
    function vintago_recursive_product_count(int $term_id): int {
        $cache_key = 'vintago_cat_count_' . $term_id;
        $cached = get_transient($cache_key);
        if ($cached !== false) {
            return (int) $cached;
        }

        $all_children = get_term_children($term_id, 'product_cat');
        if (is_wp_error($all_children)) {
            $all_children = [];
        }

        $count_query = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => false,
            'tax_query'      => [[
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => array_merge([$term_id], $all_children),
                'include_children' => false,
                'operator'         => 'IN',
            ]],
        ]);

        $count = (int) $count_query->found_posts;
        set_transient($cache_key, $count, 5 * MINUTE_IN_SECONDS);
        return $count;
    }
}

function vintago_invalidate_category_count_cache(int $post_id): void {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    $term_ids = wp_get_post_terms($post_id, 'product_cat', ['fields' => 'ids']);
    if (is_wp_error($term_ids) || empty($term_ids)) {
        return;
    }

    foreach ($term_ids as $term_id) {
        delete_transient('vintago_cat_count_' . $term_id);
        foreach (get_ancestors($term_id, 'product_cat', 'taxonomy') as $ancestor_id) {
            delete_transient('vintago_cat_count_' . $ancestor_id);
        }
    }
}
add_action('save_post_product', 'vintago_invalidate_category_count_cache', 20);
add_action('woocommerce_product_set_stock_status', 'vintago_invalidate_category_count_cache', 20);

// Uso: [cat_count slug="tecnologia-y-gadgets"]
remove_shortcode('cat_count');
add_shortcode('cat_count', function($atts) {
    $atts = shortcode_atts(['slug' => ''], $atts);
    $term = get_term_by('slug', $atts['slug'], 'product_cat');
    return $term ? esc_html(vintago_recursive_product_count((int) $term->term_id)) : '0';
});

/* ============================================
   9. WIDGET: LISTA DE CATEGORÍAS CON CONTEO REAL
============================================ */
// Helper para obtener categorías padre con sus hijos y conteos reales
function vintago_get_categories() {
    $parents = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ]);

    $result = [];
    foreach ($parents as $parent) {
        if ($parent->slug === 'uncategorized') continue;
        $children = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $parent->term_id,
            'orderby'    => 'count',
            'order'      => 'DESC',
        ]);
        $result[] = [
            'term'     => $parent,
            'children' => $children,
        ];
    }
    return $result;
}

/* ============================================
   FAVORITOS (WISHLIST) — sistema propio
   La función nativa "Wishlists" de WooCommerce 11
   es experimental: solo funciona dentro del bloque
   "Add to Cart with Options" y solo para usuarios logueados.
   Como este tema usa plantillas PHP clásicas (archive-product.php,
   front-page.php, single-product.php), esa función nativa no
   se muestra en ningún lado aunque esté activada en Ajustes.
   Este sistema propio sí funciona en todas las plantillas,
   para invitados (guardado en la sesión de WooCommerce) y para
   usuarios registrados (guardado permanente en user meta).
============================================ */
function vintago_get_wishlist_ids() {
    if (is_user_logged_in()) {
        $ids = get_user_meta(get_current_user_id(), '_vintago_wishlist', true);
        return is_array($ids) ? array_values(array_map('absint', $ids)) : [];
    }
    if (function_exists('WC') && WC()->session) {
        $ids = WC()->session->get('vintago_wishlist', []);
        return is_array($ids) ? array_values(array_map('absint', $ids)) : [];
    }
    return [];
}

function vintago_save_wishlist_ids($ids) {
    $ids = array_values(array_unique(array_map('absint', $ids)));
    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), '_vintago_wishlist', $ids);
    } elseif (function_exists('WC') && WC()->session) {
        WC()->session->set('vintago_wishlist', $ids);
    }
    return $ids;
}

function vintago_is_wishlisted($product_id) {
    return in_array((int) $product_id, vintago_get_wishlist_ids(), true);
}

function vintago_wishlist_count() {
    return count(vintago_get_wishlist_ids());
}

add_action('wp_ajax_vintago_toggle_wishlist', 'vintago_toggle_wishlist_ajax');
add_action('wp_ajax_nopriv_vintago_toggle_wishlist', 'vintago_toggle_wishlist_ajax');
function vintago_toggle_wishlist_ajax() {
    check_ajax_referer('vintago_wishlist_nonce', 'nonce');
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    if (!$product_id || !get_post($product_id)) {
        wp_send_json_error(['message' => 'Producto no válido'], 400);
    }
    $ids = vintago_get_wishlist_ids();
    $was_in_wishlist = in_array($product_id, $ids, true);
    $ids = $was_in_wishlist
        ? array_values(array_diff($ids, [$product_id]))
        : array_merge($ids, [$product_id]);
    $ids = vintago_save_wishlist_ids($ids);
    wp_send_json_success([
        'in_wishlist' => !$was_in_wishlist,
        'count'       => count($ids),
    ]);
}

/* Fusiona la lista de invitado con la del usuario al iniciar sesión */
add_action('wp_login', function ($user_login, $user) {
    if (!function_exists('WC') || !WC()->session) { return; }
    $guest_ids = WC()->session->get('vintago_wishlist', []);
    if (empty($guest_ids)) { return; }
    $existing = get_user_meta($user->ID, '_vintago_wishlist', true);
    $existing = is_array($existing) ? $existing : [];    update_user_meta($user->ID, '_vintago_wishlist', array_values(array_unique(array_merge($existing, $guest_ids))));
    WC()->session->set('vintago_wishlist', []);
}, 10, 2);

/* Pestaña "Mis favoritos" en Mi cuenta */
add_action('init', function () {
    add_rewrite_endpoint('favoritos', EP_ROOT | EP_PAGES);
});

add_filter('woocommerce_account_menu_items', function ($items) {
    /* Oculta el endpoint nativo "Wishlists" de WooCommerce: es experimental,
       solo funciona con el bloque "Add to Cart with Options" y no con las
       plantillas clásicas de este tema, así que solo confunde junto a
       "Mis favoritos" (que sí funciona en todo el sitio). */
    unset($items['wishlist'], $items['wishlists']);

    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'orders') {
            $new['favoritos'] = 'Mis favoritos';
        }
    }
    if (!isset($new['favoritos'])) {
        $new['favoritos'] = 'Mis favoritos';
    }
    return $new;
}, 20);

add_action('woocommerce_account_favoritos_endpoint', function () {
    $ids = vintago_get_wishlist_ids();
    if (empty($ids)) {
        echo '<div class="vintago-empty-state">';
        echo '<h2>Aún no tienes favoritos</h2>';
        echo '<p>Toca el corazón en cualquier producto de la tienda para guardarlo aquí.</p>';
        echo '<a class="vintago-btn" href="' . esc_url(wc_get_page_permalink('shop')) . '">Ver toda la tienda</a>';
        echo '</div>';
        return;
    }
    echo '<div class="product-grid vintago-wishlist-grid">';
    foreach ($ids as $id) {
        $product = wc_get_product($id);
        if (!$product || !$product->is_visible()) { continue; }
        $image_id = $product->get_image_id();
        $img_url  = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : wc_placeholder_img_src();
        ?>
        <div class="product-card" data-product-url="<?php echo esc_url(get_permalink($id)); ?>" tabindex="0" role="link">
            <div class="product-thumb">
                <img src="<?php echo esc_url($img_url); ?>" class="active" alt="<?php echo esc_attr($product->get_name()); ?>">
                <button type="button" class="product-card__wishlist is-active" data-product_id="<?php echo esc_attr($id); ?>" aria-label="Quitar de favoritos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                </button>
            </div>
            <h3><?php echo esc_html($product->get_name()); ?></h3>
            <div class="price-row">
                <?php if ($product->is_on_sale()) : ?>
                <span class="price"><?php echo wp_kses_post(wc_price($product->get_sale_price())); ?></span>
                <span class="price-old"><?php echo wp_kses_post(wc_price($product->get_regular_price())); ?></span>
                <?php else : ?>
                <span class="price"><?php echo wp_kses_post(wc_price($product->get_price())); ?></span>
                <?php endif; ?>
            </div>
            <a class="add-to-cart-btn add_to_cart_button ajax_add_to_cart"
               href="<?php echo esc_url($product->add_to_cart_url()); ?>"
               data-quantity="1"
               data-product_id="<?php echo esc_attr($id); ?>"
               data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
               rel="nofollow">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                Añadir al carrito
            </a>
        </div>
        <?php
    }
    echo '</div>';
});