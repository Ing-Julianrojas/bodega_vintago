<?php
/**
 * Vintago Bodega — archive-product.php
 * Maneja /tienda/ (shop index) y todas las categorías de producto.
 */
if (!defined('ABSPATH')) { exit; }

get_header();

/* ── Contexto ────────────────────────────────────────────────────── */
$current_term   = is_product_category() ? get_queried_object() : null;
$is_shop_index  = is_shop();
$is_search      = is_search();
$parent_id      = $current_term ? (int) $current_term->term_id : 0;
$placeholder    = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : '';

/* ── Mapa de descripciones por categoría ─────────────────────────── */
$cat_descriptions = [
    'tecnologia-y-gadgets'       => 'Los mejores gadgets, accesorios y dispositivos tech al mejor precio de bodega.',
    'entretenimiento-y-multimedia'=> 'Gaming, streaming, proyectores y todo lo que necesitas para crear contenido.',
    'hogar-y-cocina'             => 'Electrodomésticos y accesorios que transforman tu espacio sin romper el presupuesto.',
    'belleza-y-salud'            => 'Cuidado facial, maquillaje, fitness y bienestar al alcance de tu mano.',
    'movilidad-y-vehiculos'      => 'Accesorios para tu vehículo, patinetas eléctricas y todo lo que te mueve.',
    'estilo-de-vida-y-otros'     => 'Mascotas, hobbies, organización y todo lo que hace grande tu estilo de vida.',
];

/* ── Subcategorías ───────────────────────────────────────────────── */
$sub_categories = (!$is_shop_index && !$is_search) ? get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => $parent_id,
    'orderby'    => 'name',
    'order'      => 'ASC',
]) : [];
if (is_wp_error($sub_categories)) { $sub_categories = []; }

/* ── Categorías raíz (respaldo para cualquier página de catálogo) ── */
$root_categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);
if (is_wp_error($root_categories)) { $root_categories = []; }
$root_categories = array_filter($root_categories, fn($t) => $t->slug !== 'uncategorized');
$browser_categories = $is_shop_index ? $root_categories : [];

/* ── Iconos de categoría (mismo set que la portada) para las píldoras ──── */
$cat_icons = [
    'tecnologia-y-gadgets'        => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
    'entretenimiento-y-multimedia'=> '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.544-.604-6.584-.685-7.258-.007-.05-.011-.1-.017-.151A4 4 0 0 0 17.32 5z"/></svg>',
    'hogar-y-cocina'              => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    'belleza-y-salud'             => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
    'movilidad-y-vehiculos'       => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C1.4 11.3 1 12.2 1 13v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>',
    'estilo-de-vida-y-otros'      => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>',
];
$icon_all = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>';

/* ── Título y descripción de la página ───────────────────────────── */
if ($is_search) {
    $page_title = 'Resultados para: ' . get_search_query();
    $page_desc  = 'Encontramos los siguientes productos relacionados con tu búsqueda.';
    $page_eyebrow = 'Búsqueda';
} elseif ($current_term) {
    $page_title   = $current_term->name;
    $slug         = $current_term->slug;
    $page_desc    = !empty($current_term->description)
                    ? strip_tags($current_term->description)
                    : ($cat_descriptions[$slug] ?? 'Encuentra productos seleccionados para tu hogar, tu negocio y tus clientes.');
    $page_eyebrow = 'Catálogo Vintago';
} else {
    $page_title   = 'El catálogo Vintago';
    $page_desc    = 'Encuentra productos seleccionados para tu hogar, tu negocio y tus clientes.';
    $page_eyebrow = 'Catálogo Vintago';
}

/* ── Query de productos: FORZAR para shop index ──────────────────── */
if ($is_shop_index && !$is_search) {
    global $wp_query;
    // Asegurar que el query traiga productos publicados
    if (!have_posts() || $wp_query->post_count === 0) {
        $paged = max(1, get_query_var('paged'));
        $ppp   = (int) get_option('posts_per_page', 24);
        $args  = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $ppp,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];
        // Respetar el ordering de WooCommerce si existe
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : get_option('woocommerce_default_catalog_orderby', 'menu_order');
        switch ($orderby) {
            case 'price':        $args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_price'; $args['order'] = 'ASC'; break;
            case 'price-desc':   $args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_price'; $args['order'] = 'DESC'; break;
            case 'popularity':   $args['orderby'] = 'meta_value_num'; $args['meta_key'] = 'total_sales'; break;
            case 'rating':       $args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_wc_average_rating'; break;
            case 'date':         $args['orderby'] = 'date'; break;
        }
        $wp_query = new WP_Query($args);
    }
}

$has_products = have_posts();

/* ── Badge de conteo para el hero header (calculado tras el query final) ── */
$header_count = null;
if ($current_term) {
    $header_count = (int) $current_term->count;
} elseif ($is_shop_index) {
    $header_count = (int) $wp_query->found_posts;
}
?>
<main class="vintago-shop">
<div class="vintago-shop__inner">

    <!-- ══ HEADER DE PÁGINA ══════════════════════════════════════════ -->
    <header class="vintago-shop__header">
        <p class="vintago-eyebrow"><?php echo esc_html($page_eyebrow); ?></p>
        <h1>
            <?php echo esc_html($page_title); ?>
            <?php if ($header_count !== null) : ?>
                <span class="vintago-count-badge"><?php echo esc_html($header_count); ?> producto<?php echo $header_count === 1 ? '' : 's'; ?></span>
            <?php endif; ?>
        </h1>
        <p><?php echo esc_html($page_desc); ?></p>
        <?php if ($current_term) : ?>
            <nav class="vintago-breadcrumb" aria-label="Ruta">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Tienda</a>
                <span>›</span>
                <span><?php echo esc_html($current_term->name); ?></span>
            </nav>
        <?php endif; ?>
    </header>

    <!-- ══ PRODUCTOS ═════════════════════════════════════════════════ -->
    <?php if ($has_products) : ?>
    <section class="vintago-products" aria-label="Productos">

        <div class="vintago-products__toolbar">
            <p><?php woocommerce_result_count(); ?></p>
            <?php woocommerce_catalog_ordering(); ?>
        </div>
        <?php if (!empty($root_categories)) : ?>
        <nav class="vintago-cat-pills" aria-label="Explorar categorías">
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="vintago-cat-pill is-active">
                <?php echo $icon_all; // phpcs:ignore ?>
                Todos
            </a>
            <?php foreach ($root_categories as $cat) :
                $cat_url = get_term_link($cat);
                if (is_wp_error($cat_url)) { continue; }
            ?>
                <a href="<?php echo esc_url($cat_url); ?>" class="vintago-cat-pill">
                    <?php echo $cat_icons[$cat->slug] ?? ''; // phpcs:ignore ?>
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <div class="vintago-product-grid">
        <?php while (have_posts()) : the_post();
            global $product;
            if (!$product || !$product->is_visible()) { continue; }

            $image_ids = array_values(array_filter(array_merge(
                [$product->get_image_id()],
                $product->get_gallery_image_ids()
            )));
            if (empty($image_ids)) { $image_ids = [0]; }
        ?>
            <article class="vintago-product-card" data-id="<?php echo esc_attr($product->get_id()); ?>">
                <a class="vintago-product-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                    <?php foreach ($image_ids as $idx => $img_id) :
                        $img = $img_id
                            ? wp_get_attachment_image($img_id, 'medium_large', false, [
                                'class'   => $idx === 0 ? 'is-active' : '',
                                'alt'     => $product->get_name(),
                                'loading' => $idx === 0 ? 'eager' : 'lazy',
                              ])
                            : sprintf('<img class="is-active" src="%s" alt="%s" loading="lazy">', esc_url($placeholder), esc_attr($product->get_name()));
                        echo $img; // phpcs:ignore
                    endforeach; ?>

                    <?php if (count($image_ids) > 1) : ?>
                    <span class="vintago-product-card__gallery" aria-hidden="true">
                        <?php foreach ($image_ids as $idx => $img_id) : ?>
                        <i class="<?php echo $idx === 0 ? 'is-active' : ''; ?>"></i>
                        <?php endforeach; ?>
                    </span>
                    <button class="vintago-product-card__next" type="button" aria-label="Siguiente imagen">&#8250;</button>
                    <?php endif; ?>

                    <?php if ($product->is_on_sale()) : ?>
                    <span class="vintago-badge-sale">Oferta</span>
                    <?php endif; ?>
                </a>

                <div class="vintago-product-card__content">
                    <p class="vintago-product-card__category"><?php echo esc_html(wp_strip_all_tags(wc_get_product_category_list($product->get_id()))); ?></p>
                    <h2><a href="<?php the_permalink(); ?>"><?php echo esc_html($product->get_name()); ?></a></h2>
                    <div class="vintago-product-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                    <a class="vintago-product-card__button add_to_cart_button ajax_add_to_cart"
                       href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                       data-quantity="1"
                       data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                       data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                       rel="nofollow">
                        Agregar al carrito
                    </a>
                </div>
            </article>
        <?php endwhile; ?>
        </div>

        <!-- ══ PAGINACIÓN ══════════════════════════════════════════ -->
        <?php
        global $wp_query;
        $total_pages = (int) $wp_query->max_num_pages;
        $current_page = max(1, get_query_var('paged'));
        if ($total_pages > 1) :
        ?>
        <nav class="vintago-pagination" aria-label="Páginas de productos">
            <?php if ($current_page > 1) : ?>
            <a class="vintago-page-btn vintago-page-prev" href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>" aria-label="Página anterior">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <?php endif; ?>

            <div class="vintago-page-numbers">
            <?php
            $range = 2; // páginas a mostrar a cada lado del actual
            $start  = max(1, $current_page - $range);
            $end    = min($total_pages, $current_page + $range);

            if ($start > 1) :
            ?>
                <a class="vintago-page-btn" href="<?php echo esc_url(get_pagenum_link(1)); ?>">1</a>
                <?php if ($start > 2) : ?><span class="vintago-page-ellipsis">…</span><?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++) :
                if ($i === $current_page) : ?>
                    <span class="vintago-page-btn is-active" aria-current="page"><?php echo esc_html($i); ?></span>
                <?php else : ?>
                    <a class="vintago-page-btn" href="<?php echo esc_url(get_pagenum_link($i)); ?>"><?php echo esc_html($i); ?></a>
                <?php endif;
            endfor; ?>

            <?php if ($end < $total_pages) :
                if ($end < $total_pages - 1) : ?><span class="vintago-page-ellipsis">…</span><?php endif; ?>
                <a class="vintago-page-btn" href="<?php echo esc_url(get_pagenum_link($total_pages)); ?>"><?php echo esc_html($total_pages); ?></a>
            <?php endif; ?>
            </div>

            <?php if ($current_page < $total_pages) : ?>
            <a class="vintago-page-btn vintago-page-next" href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>" aria-label="Página siguiente">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>

    </section>
    <?php else : ?>
    <div class="vintago-empty-state">
        <div class="vintago-empty-state__icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <h2>Sin productos por aquí</h2>
        <p>No encontramos productos en esta sección. Prueba otra categoría o vuelve a la tienda.</p>
        <a class="vintago-btn" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Ver toda la tienda</a>
    </div>
    <?php endif; ?>

</div><!-- /.vintago-shop__inner -->
</main>

<script>
(function(){
    /* ── Paginación: animación de entrada ───────────────────────── */
    (function(){
        var btns = document.querySelectorAll('.vintago-page-btn:not(.is-active)');
        btns.forEach(function(b, i){
            b.style.transitionDelay = (i * 18) + 'ms';
        });
    })();
})();
</script>
<?php get_footer(); ?>
