<?php
/**
 * Template Name: Carrito Vintago
 * Template Post Type: page
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main class="vintago-commerce-page vintago-cart-page">
    <div class="vintago-commerce-page__inner">
        <header class="vintago-commerce-page__hero">
            <span class="vintago-commerce-page__eyebrow">Tu selección</span>
            <h1>Carrito</h1>
            <p>Revisa tus productos antes de continuar con tu compra.</p>
        </header>
        <?php woocommerce_output_all_notices(); ?>
        <section class="vintago-commerce-card vintago-cart-content">
            <?php echo do_shortcode('[woocommerce_cart]'); ?>
        </section>
    </div>
</main>
<?php get_footer(); ?>
