<?php
/**
 * Vintago Bodega — archive-product.php  (CORREGIDO v2)
 * ─────────────────────────────────────────────────────
 * FASE 1 FIX: $current_term->count solo cuenta productos directos.
 *             vintago_recursive_product_count() suma hijos recursivamente.
 * FASE 3 FIX: Buscador AJAX de subcategorías integrado encima del carrusel.
 */
if (!defined('ABSPATH')) { exit; }

get_header();

/* ═══════════════════════════════════════════════════════
   HELPER — Conteo recursivo de productos en subcategorías
   Reemplaza el uso directo de $current_term->count que
   solo cuenta los productos asignados DIRECTAMENTE a la
   categoría padre, ignorando las subcategorías hijas.
═══════════════════════════════════════════════════════ */
if ( ! function_exists('vintago_recursive_product_count') ) {
    function vintago_recursive_product_count( int $term_id ) : int {
        // Obtener todos los descendientes (hijos, nietos, etc.)
        $all_children = get_term_children( $term_id, 'product_cat' );
        if ( is_wp_error($all_children) ) {
            $all_children = [];
        }

        // Contar con una WP_Query real para no depender del term_count
        // que WordPress solo actualiza al guardar/modificar productos y
        // que NO suma subcategorías automáticamente.
        $term_ids   = array_merge( [ $term_id ], $all_children );
        $count_query = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',        // solo IDs — mínima memoria
            'no_found_rows'  => false,
            'tax_query'      => [[
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => $term_ids,
                'include_children' => false,  // ya los incluimos manualmente
                'operator'         => 'IN',
            ]],
        ]);
        return (int) $count_query->found_posts;
    }
}

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

/* ── Categorías raíz ─────────────────────────────────────────────── */
$root_categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);
if (is_wp_error($root_categories)) { $root_categories = []; }
$root_categories   = array_filter($root_categories, fn($t) => $t->slug !== 'uncategorized');
$browser_categories = $current_term ? $sub_categories : $root_categories;

/* ── Iconos de categoría ─────────────────────────────────────────── */
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
    $page_title   = 'Resultados para: ' . get_search_query();
    $page_desc    = 'Encontramos los siguientes productos relacionados con tu búsqueda.';
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
    if (!have_posts() || $wp_query->post_count === 0) {
        $paged = max(1, get_query_var('paged'));
        $ppp   = 24;
        $args  = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $ppp,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : get_option('woocommerce_default_catalog_orderby', 'menu_order');
        switch ($orderby) {
            case 'price':      $args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_price'; $args['order'] = 'ASC'; break;
            case 'price-desc': $args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_price'; $args['order'] = 'DESC'; break;
            case 'popularity': $args['orderby'] = 'meta_value_num'; $args['meta_key'] = 'total_sales'; break;
            case 'rating':     $args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_wc_average_rating'; break;
            case 'date':       $args['orderby'] = 'date'; break;
        }
        $wp_query = new WP_Query($args);
    }
}

$has_products = have_posts();
?>
<main class="vintago-shop">
<div class="vintago-shop__inner">

<style id="vintago-catalog-critical">
/* ── Base layout ─────────────────────────────────────────────── */
.vintago-shop{display:block!important;width:100%!important;max-width:none!important;margin:0!important;padding:42px 20px 96px!important;background:#0a0a0f!important;color:#f0f0f5!important;font-family:Inter,Arial,sans-serif!important}
.vintago-shop__inner{width:min(1240px,100%)!important;max-width:1240px!important;margin:0 auto!important}
.vintago-shop__header{display:flex!important;flex-direction:column!important;align-items:center!important;text-align:center!important;margin:0 0 34px!important;padding:36px 0 28px!important;border-bottom:1px solid #2a2a3a!important}
.vintago-shop__header .vintago-eyebrow{width:100%!important;text-align:center!important}
.vintago-shop__header h1{display:flex!important;flex-wrap:wrap!important;align-items:center!important;justify-content:center!important;gap:12px!important;width:100%!important;margin:0 0 12px!important;color:#f0f0f5!important;font-size:clamp(30px,5vw,52px)!important;font-weight:800!important;line-height:1.08!important;text-align:center!important}
.vintago-shop__header>p{width:100%!important;max-width:640px!important;margin:0 auto!important;color:#8888a8!important;font-size:15px!important;line-height:1.6!important;text-align:center!important}
.vintago-shop__header .vintago-breadcrumb{display:flex!important;justify-content:center!important;width:100%!important}
/* ── Carrusel categorías ─────────────────────────────────────── */
.vintago-category-carousel{display:block!important;margin:0 0 42px!important}
.vintago-category-carousel__heading{display:flex!important;align-items:center!important;justify-content:space-between!important;margin-bottom:14px!important}
.vintago-category-carousel__heading h2{margin:0!important;color:#f0f0f5!important;font-size:22px!important;font-weight:800!important}
.vintago-category-carousel__controls{display:flex!important;gap:8px!important}
.vintago-category-carousel__controls button{display:grid!important;width:44px!important;height:44px!important;place-items:center!important;border:1px solid rgba(0,229,195,.35)!important;border-radius:50%!important;background:linear-gradient(145deg,#20202c,#12121a)!important;color:#00e5c3!important;cursor:pointer!important;font-size:0!important;box-shadow:0 8px 20px rgba(0,0,0,.22)!important;transition:border-color .2s,color .2s,transform .2s,box-shadow .2s!important}
.vintago-category-carousel__controls button::before{font-size:27px!important;font-weight:400!important;line-height:1!important}
.vintago-category-carousel__prev::before{content:'‹'!important}
.vintago-category-carousel__next::before{content:'›'!important}
.vintago-category-carousel__controls button:hover{border-color:#00e5c3!important;background:#00e5c3!important;color:#0a0a0f!important;box-shadow:0 10px 26px rgba(0,229,195,.25)!important;transform:translateY(-2px)!important}
.vintago-category-carousel__track{display:flex!important;flex-wrap:nowrap!important;gap:16px!important;overflow-x:auto!important;padding:6px 4px 16px!important;scroll-behavior:smooth!important;scrollbar-width:none!important}
.vintago-category-carousel__track::-webkit-scrollbar{display:none!important}
.vintago-category-tile{position:relative!important;display:flex!important;flex:0 0 230px!important;flex-direction:column!important;min-height:250px!important;overflow:hidden!important;border:1px solid rgba(255,255,255,.13)!important;border-radius:18px!important;background:#16161f!important;text-decoration:none!important;transition:border-color .25s,box-shadow .25s,transform .25s!important}
.vintago-category-tile:hover{border-color:#00e5c3!important;box-shadow:0 0 0 1px rgba(0,229,195,.25),0 16px 34px rgba(0,229,195,.18)!important;transform:translateY(-5px)!important}
.vintago-category-tile__image{display:block!important;width:100%!important;height:250px!important;place-items:unset!important;background:#111118!important}
.vintago-category-tile__image img{display:block!important;width:100%!important;height:100%!important;object-fit:cover!important;object-position:center!important;transition:transform .45s ease,filter .3s ease!important}
.vintago-category-tile:hover .vintago-category-tile__image img{transform:scale(1.06)!important;filter:saturate(1.08)!important}
.vintago-category-tile strong{position:absolute!important;right:0!important;bottom:0!important;left:0!important;display:block!important;min-height:0!important;padding:42px 14px 16px!important;background:linear-gradient(transparent,rgba(8,8,14,.95) 58%)!important;color:#fff!important;font-size:13px!important;font-weight:800!important;line-height:1.25!important;text-align:left!important;text-shadow:0 2px 10px rgba(0,0,0,.55)!important}
/* ── Toolbar y grid de productos ─────────────────────────────── */
.vintago-products__toolbar{display:flex!important;align-items:center!important;justify-content:space-between!important;gap:14px!important;margin-bottom:20px!important;padding-bottom:14px!important;border-bottom:1px solid #2a2a3a!important}
.vintago-products__toolbar p{margin:0!important;color:#8888a8!important}
.vintago-product-grid{display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:22px!important}
/* ── Tarjeta de producto (mismo diseño que el home) ──────────── */
.vintago-product-grid .product-card{display:flex!important;min-width:0!important;flex-direction:column!important;background:linear-gradient(145deg,rgba(255,255,255,.065),rgba(255,255,255,.018))!important;border:1px solid rgba(255,255,255,.12)!important;border-radius:16px!important;overflow:hidden!important;cursor:pointer!important;opacity:1!important;transform:none!important;transition:transform .3s ease,border-color .3s ease,box-shadow .3s ease!important;box-shadow:0 14px 34px rgba(0,0,0,.2)!important}
.vintago-product-grid .product-card:hover{border-color:rgba(0,229,195,.7)!important;box-shadow:0 18px 42px rgba(0,229,195,.14)!important;transform:translateY(-6px)!important}
.vintago-product-grid .product-thumb{position:relative!important;width:100%!important;aspect-ratio:1!important;overflow:hidden!important;background:rgba(17,17,24,.72)!important}
.vintago-product-grid .product-thumb img{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;padding:14px!important;object-fit:contain!important;opacity:0!important;transition:opacity .35s ease,transform .5s ease!important}
.vintago-product-grid .product-thumb img.active{opacity:1!important}
.vintago-product-grid .product-card:hover .product-thumb img.active{transform:scale(1.07)!important}
.vintago-product-grid .product-dots{position:absolute!important;bottom:8px!important;left:50%!important;transform:translateX(-50%)!important;display:flex!important;gap:5px!important;z-index:2!important}
.vintago-product-grid .product-dots span{width:6px!important;height:6px!important;border-radius:50%!important;background:rgba(255,255,255,.35)!important;cursor:pointer!important;transition:background .2s ease,transform .2s ease!important}
.vintago-product-grid .product-dots span.active{background:#00e5c3!important;transform:scale(1.15)!important}
.vintago-product-grid .cat-label{margin:0!important;padding:12px 14px 0!important;color:#00e5c3!important;font-size:10px!important;font-weight:800!important;letter-spacing:.07em!important;text-transform:uppercase!important}
.vintago-product-grid .product-card h3{min-height:40px!important;margin:0!important;padding:5px 16px 8px!important;color:#f0f0f5!important;font-size:14px!important;font-weight:700!important;line-height:1.35!important;display:-webkit-box!important;-webkit-line-clamp:2!important;-webkit-box-orient:vertical!important;overflow:hidden!important}
.vintago-product-grid .price-row{display:flex!important;align-items:baseline!important;gap:8px!important;padding:0 16px 14px!important;margin-top:auto!important}
.vintago-product-grid .price{color:#00e5c3!important;font-size:17px!important;font-weight:800!important}
.vintago-product-grid .price-old{color:#8888a8!important;font-size:12px!important;text-decoration:line-through!important}
.vintago-product-grid .add-to-cart-btn{display:flex!important;align-items:center!important;justify-content:center!important;gap:7px!important;width:calc(100% - 32px)!important;margin:0 16px 16px!important;padding:11px!important;background:rgba(0,229,195,.08)!important;border:1px solid rgba(0,229,195,.3)!important;border-radius:999px!important;color:#00e5c3!important;font-size:12px!important;font-weight:700!important;text-align:center!important;text-decoration:none!important;cursor:pointer!important;transition:all .2s ease!important}
.vintago-product-grid .add-to-cart-btn:hover,.vintago-product-grid .add-to-cart-btn.added{background:#00e5c3!important;color:#0a0a0f!important;border-color:#00e5c3!important}
/* ── FASE 3 — Buscador de subcategorías ──────────────────────── */
.vintago-cat-search{position:relative!important;margin:0 0 22px!important}
.vintago-cat-search__input{display:block!important;width:100%!important;padding:14px 48px 14px 20px!important;border:1.5px solid rgba(0,229,195,.35)!important;border-radius:12px!important;background:rgba(0,229,195,.04)!important;color:#f0f0f5!important;font-family:Inter,Arial,sans-serif!important;font-size:14px!important;outline:none!important;transition:border-color .2s,box-shadow .2s!important;box-sizing:border-box!important}
.vintago-cat-search__input::placeholder{color:#555570!important}
.vintago-cat-search__input:focus{border-color:#00e5c3!important;box-shadow:0 0 0 3px rgba(0,229,195,.12)!important}
.vintago-cat-search__icon{position:absolute!important;top:50%!important;right:16px!important;transform:translateY(-50%)!important;color:#00e5c3!important;pointer-events:none!important}
.vintago-cat-search__empty{display:none!important;padding:20px 0!important;color:#8888a8!important;font-size:14px!important;text-align:center!important}
/* tile oculto por filtro */
.vintago-category-tile.is-hidden{display:none!important}
/* ── Responsivo ──────────────────────────────────────────────── */
@media(max-width:900px){.vintago-product-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}}
@media(max-width:560px){.vintago-shop{padding:26px 14px 70px!important}.vintago-category-tile{flex-basis:205px!important}.vintago-category-tile__image,.vintago-category-tile__image img{height:160px!important}.vintago-product-grid{gap:12px!important}.vintago-product-grid .cat-label{padding:10px 12px 0!important}.vintago-product-grid .product-card h3{padding:4px 12px 6px!important;font-size:13px!important}.vintago-product-grid .price-row{padding:0 12px 12px!important}.vintago-product-grid .add-to-cart-btn{width:calc(100% - 24px)!important;margin:0 12px 12px!important;font-size:11px!important}.vintago-products__toolbar{align-items:flex-start!important;flex-direction:column!important}}
</style>

    <!-- ══ HEADER DE PÁGINA ══════════════════════════════════════════ -->
    <header class="vintago-shop__header">
        <p class="vintago-eyebrow"><?php echo esc_html($page_eyebrow); ?></p>
        <h1>
            <?php echo esc_html($page_title); ?>
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

    <?php if (!empty($browser_categories)) : ?>
    <section class="vintago-category-carousel" aria-label="Categorías de la tienda">

        <?php
        /*
         * FASE 3 — Buscador de subcategorías
         * Aparece solo en páginas de categoría padre que tienen hijas,
         * o en la tienda global cuando hay más de 4 categorías raíz.
         */
        $show_cat_search = (
            ($current_term && count($browser_categories) > 3) ||
            (!$current_term && count($browser_categories) > 4)
        );
        ?>
        <?php if ($show_cat_search) : ?>
        <div class="vintago-cat-search" role="search" aria-label="Buscar categoría">
            <input
                class="vintago-cat-search__input"
                id="vintagoCatSearch"
                type="search"
                autocomplete="off"
                placeholder="Buscar en <?php echo esc_attr($current_term ? $current_term->name : 'el catálogo'); ?>…"
                aria-label="Filtrar subcategorías"
            >
            <span class="vintago-cat-search__icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </span>
            <p class="vintago-cat-search__empty" id="vintagoCatSearchEmpty" aria-live="polite">
                Sin resultados para tu búsqueda.
            </p>
        </div>
        <?php endif; ?>

        <div class="vintago-category-carousel__heading">
            <h2>Explora por categoría</h2>
            <div class="vintago-category-carousel__controls">
                <button type="button" class="vintago-category-carousel__prev" aria-label="Categoría anterior">&#8592;</button>
                <button type="button" class="vintago-category-carousel__next" aria-label="Siguiente categoría">&#8594;</button>
            </div>
        </div>

        <div class="vintago-category-carousel__track" id="vintagoCatTrack">
        <?php foreach ($browser_categories as $cat) :
            $cat_url = get_term_link($cat);
            if (is_wp_error($cat_url)) { continue; }
            $cat_image_id = (int) get_term_meta($cat->term_id, 'thumbnail_id', true);
        ?>
            <a class="vintago-category-tile"
               href="<?php echo esc_url($cat_url); ?>"
               data-cat-name="<?php echo esc_attr(strtolower($cat->name)); ?>">
                <span class="vintago-category-tile__image">
                    <?php if ($cat_image_id) : ?>
                        <?php echo wp_get_attachment_image($cat_image_id, 'medium_large', false, ['alt' => $cat->name, 'loading' => 'lazy']); // phpcs:ignore ?>
                    <?php else : ?>
                        <?php echo $cat_icons[$cat->slug] ?? $icon_all; // phpcs:ignore ?>
                    <?php endif; ?>
                </span>
                <strong><?php echo esc_html($cat->name); ?></strong>
            </a>
        <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══ PRODUCTOS ═════════════════════════════════════════════════ -->
    <?php if ($has_products) : ?>
    <section class="vintago-products" aria-label="Productos">

        <div class="vintago-products__toolbar">
            <p><?php woocommerce_result_count(); ?></p>
            <?php woocommerce_catalog_ordering(); ?>
        </div>
        <div class="vintago-product-grid">
        <?php while (have_posts()) : the_post();
            $product = wc_get_product(get_the_ID());
            if (!$product || !$product->is_visible()) { continue; }

            $image_ids = array_values(array_filter(array_merge(
                [$product->get_image_id()],
                $product->get_gallery_image_ids()
            )));
            if (empty($image_ids)) { $image_ids = [0]; }

            $cats      = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
            $cat_label = !empty($cats) ? $cats[0] : '';
        ?>
            <div class="product-card" data-product-url="<?php the_permalink(); ?>" data-id="<?php echo esc_attr($product->get_id()); ?>" tabindex="0" role="link">
                <div class="product-thumb">
                    <?php foreach ($image_ids as $idx => $img_id) :
                        $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'medium') : $placeholder;
                    ?>
                    <img src="<?php echo esc_url($img_url); ?>" class="<?php echo $idx === 0 ? 'active' : ''; ?>" alt="<?php echo esc_attr($product->get_name()); ?>" loading="<?php echo $idx === 0 ? 'eager' : 'lazy'; ?>">
                    <?php endforeach; ?>

                    <a class="product-card__quick-cart add_to_cart_button ajax_add_to_cart"
                      href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                      data-quantity="1"
                      data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                      data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                      rel="nofollow"
                      aria-label="Agregar <?php echo esc_attr($product->get_name()); ?> al carrito">
                       <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </a>

                    <?php $in_wishlist = vintago_is_wishlisted($product->get_id()); ?>
                    <button type="button"
                        class="product-card__wishlist <?php echo $in_wishlist ? 'is-active' : ''; ?>"
                        data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                        aria-label="<?php echo $in_wishlist ? 'Quitar de favoritos' : 'Añadir a favoritos'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                    </button>

                    <?php if (count($image_ids) > 1) : ?>
                    <div class="product-dots">
                       <?php foreach ($image_ids as $idx => $img_id) : ?>
                       <span class="<?php echo $idx === 0 ? 'active' : ''; ?>"></span>
                       <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <p class="cat-label"><?php echo esc_html($cat_label); ?></p>
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
                   data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                   data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                   rel="nofollow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    Añadir al carrito
                </a>
            </div>
        <?php endwhile; ?>
        </div>

        <!-- ══ PAGINACIÓN ══════════════════════════════════════════ -->
        <?php
        global $wp_query;
        $total_pages  = (int) $wp_query->max_num_pages;
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
            $range = 2;
            $start = max(1, $current_page - $range);
            $end   = min($total_pages, $current_page + $range);

            if ($start > 1) : ?>
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
    'use strict';

    /* ── FASE 3: Filtrado en vivo de tiles de categoría ─────────── */
    var input  = document.getElementById('vintagoCatSearch');
    var track  = document.getElementById('vintagoCatTrack');
    var empty  = document.getElementById('vintagoCatSearchEmpty');

    if (input && track) {
        var tiles = Array.from(track.querySelectorAll('.vintago-category-tile'));

        function filterTiles(query) {
            var q = query.trim().toLowerCase();
            var visible = 0;
            tiles.forEach(function(tile) {
                var name = (tile.dataset.catName || '').toLowerCase();
                var match = !q || name.indexOf(q) !== -1;
                tile.classList.toggle('is-hidden', !match);
                if (match) visible++;
            });
            if (empty) {
                empty.style.display = (visible === 0 && q.length > 0) ? 'block' : 'none';
            }
        }

        var debounceTimer;
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                filterTiles(input.value);
            }, 180);
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                input.value = '';
                filterTiles('');
                input.blur();
            }
        });
    }
})();
</script>
<?php get_footer(); ?>
