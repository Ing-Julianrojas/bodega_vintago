<?php
/**
 * Template Name: Finalizar compra Vintago
 * Template Post Type: page
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<style id="vintago-checkout-critical">
body.woocommerce-checkout,
body.page-template-page-finalizar-compra {
    background: #0a0a0f !important;
    color: #f0f0f5 !important;
}
body.woocommerce-checkout .site-content,
body.woocommerce-checkout #content,
body.woocommerce-checkout #primary {
    background: transparent !important;
}
</style>
<main class="vintago-commerce-page vintago-checkout-page">
    <div class="vintago-commerce-page__inner">
        <header class="vintago-commerce-page__hero">
            <span class="vintago-commerce-page__eyebrow">Compra segura</span>
            <h1>Finalizar compra</h1>
            <p>Completa tus datos y elige el método de pago que prefieras.</p>
        </header>
        <?php woocommerce_output_all_notices(); ?>
        <section class="vintago-commerce-card vintago-checkout-content">
            <?php echo do_shortcode('[woocommerce_checkout]'); ?>
        </section>
    </div>
</main>
<?php get_footer(); ?>
