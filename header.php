<?php
/**
 * header.php — Bodega Vintago (HEADER UNIFICADO)
 * Este archivo es EL ÚNICO header. Se usa en todas las páginas del tema hijo.
 *
 * Características:
 * - Utility bar idéntica a front-page.php (con SVG de camión y WhatsApp)
 * - Header con barra de búsqueda, SVG de cuenta y carrito
 * - Navegación con botón TIENDA + todas las categorías en el mismo orden
 * - Carrito lateral (side-cart) que funciona en TODAS las páginas
 * - NO muestra carrito flotante en página de acceder (evita el bug)
 */
if (!defined('ABSPATH')) { exit; }

/* ── URLs ────────────────────────────────────────────────────────── */
$shop_url      = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/tienda/');
$cart_url      = function_exists('wc_get_cart_url')      ? wc_get_cart_url()             : home_url('/carrito/');
$checkout_url  = function_exists('wc_get_checkout_url')  ? wc_get_checkout_url()         : home_url('/checkout/');
$account_url   = home_url('/acceder/');
$wholesale_url = home_url('/mayoristas/');
$cart_count    = (class_exists('WooCommerce') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;

/* ── Detectar si estamos en página de acceder (no mostrar carrito flotante) */
$is_login_page = is_page('acceder');
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url(home_url('/favicon.ico')); ?>">
    <link rel="shortcut icon" href="<?php echo esc_url(home_url('/favicon.ico')); ?>">
    <?php wp_head(); ?>

    <!-- CSS variables globales + header crítico (igual a front-page.php) -->
    <style id="vintago-header-css">
    .astra-cart-drawer,.astra-cart-drawer-overlay,.astra-cart-drawer-container,.astra-cart-drawer-content{display:none!important;}
    :root{
        --bg:#0a0a0f;--bg2:#111118;--bgc:#16161f;--bgh:#1e1e2a;
        --acc:#00e5c3;--acc2:#7c5cfc;
        --tx:#f0f0f5;--txm:#8888a8;--brd:#2a2a3a;
        --r:10px;--rl:16px;--tr:.2s ease;
        --fh:'Outfit',system-ui,sans-serif;
        --fb:'Inter',system-ui,sans-serif;
    }
    *{box-sizing:border-box;margin:0;padding:0;}
    body{background:var(--bg)!important;color:var(--tx)!important;font-family:var(--fb)!important;}
    a{text-decoration:none;color:inherit;}
    img{display:block;max-width:100%;}
    .wrap{max-width:1240px;margin:0 auto;padding:0 24px;}

    /* ── UTILITY BAR ───────────────────────────────────────── */
    .utility-bar{
        background:#080810;
        border-bottom:1px solid var(--brd);
        padding:7px 0;
        font-size:12px;
        color:var(--txm);
    }
    .utility-bar .wrap{display:flex;justify-content:space-between;align-items:center;}
    .utility-bar .left,.utility-bar .right{display:flex;align-items:center;gap:16px;}
    .utility-bar .left{gap:6px;}
    .utility-bar svg{flex-shrink:0;}
    .utility-bar a{
        color:var(--txm);
        display:flex;align-items:center;gap:5px;
        transition:color var(--tr);
    }
    .utility-bar a:hover{color:var(--acc);}

    /* ── SITE HEADER ──────────────────────────────────────── */
    .site-header{
        background:rgba(10,10,15,.97)!important;
        border-bottom:1px solid var(--brd)!important;
        position:sticky!important;
        top:0!important;
        z-index:4000!important;
        backdrop-filter:blur(12px);
        will-change:transform;
    }
    body{overflow-x:hidden;}
    .header-inner{
        display:flex;
        align-items:center;
        gap:20px;
        padding:14px 0;
    }
    .brand{
        font-family:var(--fh);
        font-size:20px;
        font-weight:800;
        color:var(--acc)!important;
        letter-spacing:-.02em;
        white-space:nowrap;
        flex-shrink:0;
    }
    .brand .dot{color:var(--acc);}

    /* ── BARRA DE BÚSQUEDA ────────────────────────────────── */
    .search-bar{
        flex:1;
        max-width:520px;
        display:flex;
        background:var(--bgc);
        border:1px solid var(--brd);
        border-radius:var(--r);
        overflow:hidden;
        transition:border-color var(--tr);
    }
    .search-bar:focus-within{border-color:var(--acc);}
    .search-bar input[type="text"],
    .search-bar input[type="search"]{
        flex:1;
        background:transparent;
        border:none;
        outline:none;
        padding:10px 14px;
        color:var(--tx);
        font-size:14px;
        font-family:var(--fb);
    }
    .search-bar input::placeholder{color:var(--txm);}
    .search-bar button{
        background:var(--acc);
        border:none;
        padding:0 16px;
        cursor:pointer;
        display:flex;
        align-items:center;
        color:#0a0a0f;
        transition:background var(--tr);
        flex-shrink:0;
    }
    .search-bar button:hover{background:#00c9aa;}

    /* ── HEADER ACTIONS ───────────────────────────────────── */
    .header-actions{
        display:flex;
        align-items:center;
        gap:8px;
        margin-left:auto;
        flex-shrink:0;
    }
    .header-actions a,
    .header-actions button{
        display:flex;
        align-items:center;
        gap:7px;
        padding:9px 14px;
        border-radius:var(--r);
        font-size:13px;
        font-weight:600;
        color:var(--txm);
        background:transparent;
        border:1px solid transparent;
        cursor:pointer;
        transition:all var(--tr);
        font-family:var(--fb);
        white-space:nowrap;
    }
    .header-actions a:hover,
    .header-actions button:hover{
        background:var(--bgc);
        border-color:var(--brd);
        color:var(--tx);
    }
    .cart-badge{
        background:var(--acc);
        color:#0a0a0f;
        font-size:11px;
        font-weight:800;
        min-width:18px;
        height:18px;
        border-radius:9px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:0 4px;
    }

    /* ── NAV PRINCIPAL ────────────────────────────────────── */
    .main-nav{
        border-top:1px solid var(--brd);
        background:var(--bg2);
    }
    .main-nav .wrap ul{
        display:flex;
        align-items:center;
        gap:2px;
        list-style:none;
        padding:0;
        flex-wrap:nowrap;
        overflow:hidden;
    }
    .main-nav .wrap ul::-webkit-scrollbar{display:none;}
    .main-nav .wrap ul li a{
        display:flex;
        align-items:center;
        gap:6px;
        padding:9px 8px;
        font-size:12px;
        font-weight:500;
        color:var(--txm);
        border-radius:var(--r);
        transition:all var(--tr);
        white-space:nowrap;
    }
    .main-nav .wrap ul li a:hover,
    .main-nav .wrap ul li a.active{
        color:var(--acc);
        background:rgba(0,229,195,.06);
    }
    .main-nav .wrap ul li a.nav-accent{
        color:var(--acc);
        font-weight:700;
    }
    .main-nav .wrap ul li a.nav-mayoristas{border:1px solid rgba(0,229,195,.3);background:rgba(0,229,195,.05);}
    /* Botón Tienda destacado */
    .main-nav .wrap ul li a.nav-tienda{
        border:1px solid rgba(0,229,195,.3);
        color:var(--acc);
        font-weight:700;
        background:rgba(0,229,195,.05);
        margin-right:4px;
    }
    .main-nav .wrap ul li a.nav-tienda:hover{
        background:rgba(0,229,195,.14);
        border-color:var(--acc);
    }

    /* ── MENÚ TOGGLE (MOBILE) ─────────────────────────────── */
    .vintago-menu-toggle{
        display:none;
        align-items:center;
        justify-content:center;
        border:1px solid var(--brd);
        border-radius:var(--r);
        padding:8px 12px;
        background:var(--bgc);
        color:var(--tx);
        cursor:pointer;
        font-size:18px;
        font-weight:700;
        margin:8px 24px;
        min-height:42px;
    }

    /* ── CARRITO LATERAL ──────────────────────────────────── */
    .vintago-side-cart{
        position:fixed;
        z-index:9000;
        inset:0;
        pointer-events:none;
    }
    .vintago-side-cart__shade{
        position:absolute;
        inset:0;
        background:rgba(0,0,0,.58);
        opacity:0;
        transition:opacity .3s;
    }
    .vintago-side-cart__panel{
        position:absolute;
        top:0;right:0;
        display:flex;
        width:min(420px,100%);
        height:100%;
        flex-direction:column;
        background:#111118;
        transform:translateX(110%);
        transition:transform .35s cubic-bezier(.4,0,.2,1);
        border-left:1px solid var(--brd);
    }
    .vintago-side-cart.is-open{pointer-events:auto;}
    .vintago-side-cart.is-open .vintago-side-cart__shade{opacity:1;}
    .vintago-side-cart.is-open .vintago-side-cart__panel{transform:translateX(0);}

    .vintago-side-cart__head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:22px 24px 18px;
        border-bottom:1px solid var(--brd);
        flex-shrink:0;
    }
    .vintago-side-cart__head h2{
        font-size:17px;
        font-weight:800;
        color:var(--tx);
    }
    .vintago-side-cart__close{
        display:flex;
        align-items:center;
        justify-content:center;
        width:32px;height:32px;
        border:1px solid var(--brd);
        border-radius:var(--r);
        background:var(--bgc);
        color:var(--txm);
        font-size:18px;
        cursor:pointer;
        transition:all var(--tr);
    }
    .vintago-side-cart__close:hover{border-color:var(--acc);color:var(--acc);}

    .vintago-side-cart__content{
        flex:1;
        overflow-y:auto;
        padding:16px 24px;
        scrollbar-width:thin;
        scrollbar-color:var(--brd) transparent;
    }
    /* Mini-carrito WooCommerce nativo */
    .vintago-mini-cart{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:14px;}
    .vintago-mini-cart__item{
        position:relative;display:grid;grid-template-columns:72px 1fr 24px;grid-template-rows:auto auto;gap:3px 12px;
        border-bottom:1px solid var(--brd);
        padding-bottom:14px;
    }
    .vintago-mini-cart__image{grid-row:1 / span 2;display:block;}
    .vintago-mini-cart__image img{
        width:72px;height:72px;object-fit:contain;
        border:1px solid var(--brd);border-radius:var(--r);
        background:var(--bgc);flex-shrink:0;
    }
    .vintago-mini-cart__name{
        font-size:13px;font-weight:600;color:var(--tx);
        display:block;
        line-height:1.4;
    }
    .vintago-mini-cart__price{font-size:13px;color:var(--txm);}
    .vintago-mini-cart__remove{
        grid-column:3;grid-row:1;
        display:inline-flex;align-items:center;justify-content:center;
        width:24px;height:24px;
        min-width:24px;border:1px solid var(--brd);border-radius:50%;
        font-size:13px;color:var(--txm);
        margin-top:6px;transition:all var(--tr);
        float:none; margin-left:auto; align-self:flex-start;
    }
    .vintago-mini-cart__remove:hover{border-color:#ff5a6e;color:#ff5a6e;}

    .vintago-side-cart__empty{
        display:flex;flex-direction:column;align-items:center;
        gap:14px;padding:48px 0;text-align:center;
    }
    .vintago-side-cart__empty svg{color:var(--txm);opacity:.4;}
    .vintago-side-cart__empty p{color:var(--txm);font-size:14px;margin:0;}

    .vintago-side-cart__footer{
        padding:16px 24px 24px;
        border-top:1px solid var(--brd);
        flex-shrink:0;
    }
    .vintago-side-cart__total{
        display:flex;justify-content:space-between;
        margin-bottom:14px;
    }
    .vintago-side-cart__total span{font-size:13px;color:var(--txm);}
    .vintago-side-cart__total strong{font-size:17px;font-weight:800;color:var(--tx);}
    .vintago-side-cart__actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
    .vintago-side-cart__actions a{
        display:flex;align-items:center;justify-content:center;
        padding:12px;border-radius:var(--r);
        font-size:13px;font-weight:700;
        transition:all var(--tr);
    }
    .vintago-side-cart__actions a:first-child{
        border:1px solid var(--brd);color:var(--tx);
        background:var(--bgc);
    }
    .vintago-side-cart__actions a:first-child:hover{border-color:var(--acc);}
    .vintago-side-cart__actions a:last-child{
        background:var(--acc);color:#0a0a0f;
    }
    .vintago-side-cart__actions a:last-child:hover{background:#00c9aa;}

    /* ── RESPONSIVE ───────────────────────────────────────── */
    @media(max-width:768px){
        .search-bar{display:none;}
        .header-actions a span,
        .header-actions button span:not(.cart-badge){display:none;}
    }
    @media(max-width:767px){
        .vintago-menu-toggle{display:inline-flex;}
        .main-nav{display:none;}
        .main-nav.is-open{display:block;}
        .main-nav .wrap ul{flex-direction:column;align-items:stretch;padding:8px 0;flex-wrap:nowrap;overflow:visible;}
        .main-nav .wrap ul li a{padding:13px 16px;}
        .main-nav .wrap ul li a.nav-tienda{margin:6px 16px;border-radius:var(--r);}
        .header-inner{flex-wrap:wrap;min-height:64px;}
        .search-bar{
            display:flex;order:3;width:100%;max-width:none;
            margin-bottom:10px;
        }
        .header-actions{margin-left:auto;}
        .brand{font-size:17px;}
    }
    </style>
    <style id="vintago-layout-safety">
        .vintago-shop{display:block!important;width:100%!important;background:var(--bg)!important;}
        .vintago-shop__inner{display:block!important;width:min(1240px,100%)!important;margin:0 auto!important;}
        .vintago-category-grid{display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:18px!important;}
        .vintago-category-card{display:flex!important;min-width:0!important;flex-direction:column!important;overflow:hidden!important;}
        .vintago-category-card__image{display:block!important;width:100%!important;aspect-ratio:1/1!important;}
        .vintago-category-card__image img{display:block!important;width:100%!important;height:100%!important;object-fit:contain!important;}
        .vintago-product-grid{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:18px!important;}
        .vintago-product-card{display:flex!important;min-width:0!important;flex-direction:column!important;}
        .vintago-product-card__media{display:block!important;width:100%!important;aspect-ratio:1/1!important;}
        .vintago-product-card__media img{display:block!important;position:absolute!important;inset:0!important;width:100%!important;height:100%!important;object-fit:contain!important;padding:14px!important;opacity:0!important;visibility:hidden!important;}
        .vintago-product-card__media img.is-active{opacity:1!important;visibility:visible!important;}
        .vintago-product-card__content{display:flex!important;min-width:0!important;flex:1!important;flex-direction:column!important;}
        .vintago-product-card__button{margin-top:auto!important;display:block!important;width:100%!important;}
        @media(max-width:900px){.vintago-category-grid,.vintago-product-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;}}
        @media(max-width:520px){.vintago-category-grid,.vintago-product-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:10px!important;}}
    </style>
    <style id="vintago-product-safety">
        .vintago-product-page{display:block!important;width:100%!important;padding:42px 20px 90px!important;background:var(--bg)!important;}
        .vintago-product-page__inner{display:block!important;width:min(1180px,100%)!important;margin:0 auto!important;}
        .vintago-product-layout{display:grid!important;grid-template-columns:minmax(0,1.08fr) minmax(360px,.92fr)!important;gap:42px!important;align-items:start!important;}
        .vintago-product-gallery,.vintago-product-summary{min-width:0!important;border:1px solid var(--brd)!important;border-radius:var(--rl)!important;background:var(--bgc)!important;}
        .vintago-product-gallery{padding:14px!important;}
        .vintago-product-gallery .woocommerce-product-gallery{width:100%!important;max-width:none!important;float:none!important;margin:0!important;}
        .vintago-product-gallery .flex-viewport,.vintago-product-gallery .woocommerce-product-gallery__wrapper{position:relative!important;width:100%!important;aspect-ratio:1/1!important;background:var(--bgc)!important;border-radius:var(--r)!important;overflow:hidden!important;}
        .vintago-product-gallery .woocommerce-product-gallery__wrapper{width:100%!important;transform:none!important;display:block!important;}
        .vintago-product-gallery .woocommerce-product-gallery__image{position:absolute!important;inset:0!important;left:0!important;top:0!important;width:100%!important;height:100%!important;float:none!important;margin-right:0!important;opacity:0!important;visibility:hidden!important;pointer-events:none!important;z-index:0!important;transform:none!important;}
        .vintago-product-gallery .woocommerce-product-gallery__image.flex-active-slide{left:0!important;opacity:1!important;visibility:visible!important;pointer-events:auto!important;z-index:1!important;}
        .vintago-product-gallery .woocommerce-product-gallery__image a{display:flex!important;align-items:center!important;justify-content:center!important;width:100%!important;height:100%!important;}
        .vintago-product-gallery .woocommerce-product-gallery__image img{display:block!important;width:auto!important;height:auto!important;max-width:100%!important;max-height:100%!important;object-fit:contain!important;}
        .vintago-product-gallery .woocommerce-product-gallery__image .zoomImg{max-width:none!important;max-height:none!important;}
        .vintago-product-gallery .flex-control-thumbs{display:flex!important;flex-wrap:wrap!important;gap:10px!important;margin:14px 0 0!important;padding:0!important;list-style:none!important;}
        .vintago-product-gallery .flex-control-thumbs li{display:block!important;width:72px!important;height:72px!important;margin:0!important;padding:0!important;list-style:none!important;border:1px solid var(--brd)!important;border-radius:var(--r)!important;background:var(--bgc)!important;overflow:hidden!important;}
        .vintago-product-gallery .flex-control-thumbs li img{display:block!important;width:100%!important;height:100%!important;object-fit:contain!important;padding:5px!important;opacity:.85!important;cursor:pointer!important;}
        .vintago-product-gallery .flex-control-thumbs li img.flex-active{opacity:1!important;border:2px solid var(--acc)!important;}
        .vintago-product-summary{padding:30px!important;}
        .vintago-product-summary h1{margin:0 0 14px!important;color:var(--tx)!important;font-size:clamp(26px,4vw,42px)!important;line-height:1.12!important;}
        .vintago-product-price .price{color:var(--acc)!important;font-size:24px!important;font-weight:800!important;}
        .vintago-product-short-description{color:var(--txm)!important;line-height:1.7!important;}
        .vintago-product-short-description p{color:var(--txm)!important;}
        .vintago-product-purchase{margin:22px 0!important;padding:18px 0!important;border-top:1px solid var(--brd)!important;border-bottom:1px solid var(--brd)!important;}
        .vintago-trust-points{display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:8px!important;margin:24px 0!important;}
        .vintago-trust-points span{display:block!important;border:1px solid var(--brd)!important;border-radius:var(--r)!important;padding:12px 10px!important;color:var(--txm)!important;font-size:11px!important;line-height:1.4!important;}
        .vintago-trust-points b{display:block!important;margin-bottom:4px!important;color:var(--tx)!important;font-size:12px!important;}
        .vintago-product-accordions{border-top:1px solid var(--brd)!important;}
        .vintago-product-accordions details{border-bottom:1px solid var(--brd)!important;}
        .vintago-product-accordions summary{display:block!important;padding:17px 4px!important;color:var(--tx)!important;cursor:pointer!important;font-weight:700!important;list-style:none!important;}
        .vintago-product-accordions summary::-webkit-details-marker{display:none!important;}
        .vintago-product-accordions summary:after{float:right!important;color:var(--acc)!important;content:'+'!important;font-size:20px!important;}
        .vintago-product-accordions details[open] summary:after{content:'-'!important;}
        .vintago-product-accordions details>div{padding:0 4px 18px!important;color:var(--txm)!important;font-size:14px!important;line-height:1.75!important;}
        .vintago-related-products{clear:both!important;margin-top:64px!important;border-top:1px solid var(--brd)!important;padding-top:30px!important;}
        @media(max-width:800px){.vintago-product-layout{grid-template-columns:1fr!important;gap:24px!important;}.vintago-product-summary{padding:22px!important;}}
        @media(max-width:520px){.vintago-product-page{padding:28px 14px 70px!important;}.vintago-trust-points{grid-template-columns:1fr!important;}.vintago-product-gallery{padding:10px!important;}}
    </style>
</head>
<body <?php body_class('vintago-site'); ?>>
<?php wp_body_open(); ?>

<!-- ════════════════════════════════════════════════════════════════
     UTILITY BAR — idéntica a front-page.php
═════════════════════════════════════════════════════════════════ -->
<div class="utility-bar">
    <div class="wrap">
        <div class="left">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span>Envíos a toda Colombia</span>
        </div>
        <div class="right">
            <a href="https://wa.me/573127558773" target="_blank" rel="noopener">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                +57 312 755 8773
            </a>
            <a href="<?php echo esc_url($account_url); ?>">Mi cuenta</a>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════
     SITE HEADER
═════════════════════════════════════════════════════════════════ -->
<header class="site-header">
    <div class="wrap header-inner">
        <!-- Logo / Brand -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">VINTAG<span class="dot">.</span> BODEGA</a>

        <!-- Búsqueda -->
        <form class="search-bar" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Buscar audífonos, smartwatch, cocinas...">
            <input type="hidden" name="post_type" value="product">
            <button type="submit" aria-label="Buscar">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>

        <!-- Cuenta + Carrito -->
        <div class="header-actions">
            <a href="<?php echo esc_url($account_url); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Cuenta</span>
            </a>
            <?php if (!$is_login_page && function_exists('wc_get_account_endpoint_url')) :
                $vintago_wl_count = function_exists('vintago_wishlist_count') ? vintago_wishlist_count() : 0;
            ?>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('favoritos')); ?>" aria-label="Mis favoritos">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                <span>Favoritos</span>
                <span class="cart-badge" id="vintagoWishlistCount" <?php echo $vintago_wl_count === 0 ? 'style="display:none;"' : ''; ?>><?php echo esc_html($vintago_wl_count); ?></span>
            </a>
            <?php endif; ?>
            <?php if (!$is_login_page) : ?>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" id="vintagoCartBtn" data-vintago-cart-open aria-label="Ver carrito">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span>Carrito</span>
                <span class="cart-badge" id="vintagoCartCount"><?php echo esc_html($cart_count); ?></span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Toggle móvil -->
    <button class="vintago-menu-toggle" type="button" aria-expanded="false" aria-controls="vintago-main-nav" aria-label="Abrir menú">&#9776;</button>

    <!-- Navegación principal -->
    <nav class="main-nav" id="vintago-main-nav" aria-label="Navegación principal">
        <div class="wrap">
            <ul>
                <!-- Inicio -->
                <li>
                    <a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Inicio
                    </a>
                </li>
                <!-- Tienda (DESTACADO) -->
                <li>
                    <a href="<?php echo esc_url($shop_url); ?>" class="nav-tienda <?php echo is_shop() ? 'active' : ''; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 3 1.664 9.914A2 2 0 0 0 6.633 14.5H17.5a2 2 0 0 0 1.956-1.586L20.5 6H6"/><circle cx="9" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                        Tienda
                    </a>
                </li>
                <!-- Categorías -->
                <li><a href="<?php echo esc_url(home_url('/categoria-producto/tecnologia-y-gadgets/')); ?>" <?php echo is_product_category('tecnologia-y-gadgets') ? 'class="active"' : ''; ?>>Tecnología y Gadgets</a></li>
                <li><a href="<?php echo esc_url(home_url('/categoria-producto/entretenimiento-y-multimedia/')); ?>" <?php echo is_product_category('entretenimiento-y-multimedia') ? 'class="active"' : ''; ?>>Entretenimiento y Multimedia</a></li>
                <li><a href="<?php echo esc_url(home_url('/categoria-producto/hogar-y-cocina/')); ?>" <?php echo is_product_category('hogar-y-cocina') ? 'class="active"' : ''; ?>>Hogar y Cocina</a></li>
                <li><a href="<?php echo esc_url(home_url('/categoria-producto/belleza-y-salud/')); ?>" <?php echo is_product_category('belleza-y-salud') ? 'class="active"' : ''; ?>>Belleza y Salud</a></li>
                <li><a href="<?php echo esc_url(home_url('/categoria-producto/movilidad-y-vehiculos/')); ?>" <?php echo is_product_category('movilidad-y-vehiculos') ? 'class="active"' : ''; ?>>Movilidad y Vehículos</a></li>
                <li><a href="<?php echo esc_url(home_url('/categoria-producto/estilo-de-vida-y-otros/')); ?>" <?php echo is_product_category('estilo-de-vida-y-otros') ? 'class="active"' : ''; ?>>Estilo de Vida y Otros</a></li>
                <!-- Mayoristas -->
                <li><a href="<?php echo esc_url($wholesale_url); ?>" class="nav-accent nav-mayoristas <?php echo is_page('mayoristas') ? 'active' : ''; ?>">Mayoristas</a></li>
            </ul>
        </div>
    </nav>
</header>

<!-- ── Toggle móvil JS ─────────────────────────────────────────── -->
<script>
(function(){
    var btn = document.querySelector('.vintago-menu-toggle');
    var nav = document.getElementById('vintago-main-nav');
    if (!btn || !nav) return;
    btn.addEventListener('click', function(){
        var open = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!open));
        nav.classList.toggle('is-open', !open);
    });
    nav.addEventListener('click', function(e){
        if (e.target.closest('a')) {
            btn.setAttribute('aria-expanded', 'false');
            nav.classList.remove('is-open');
        }
    });
})();
</script>

<?php if (!$is_login_page && class_exists('WooCommerce') && WC()->cart) : ?>
<!-- ════════════════════════════════════════════════════════════════
     CARRITO LATERAL — se renderiza en el footer via hook,
     pero el trigger ya está en el header.
     NO aparece en /acceder/ para evitar el bug del carrito flotante.
═════════════════════════════════════════════════════════════════ -->
<?php endif; ?>