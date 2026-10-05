<?php
/**
 * Template Name: Mi cuenta Vintago
 * Template Post Type: page
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$current_user = wp_get_current_user();
?>
<style id="vintago-account-critical">
body.page-template-page-mi-cuenta,
body.woocommerce-account,
main.vintago-account-page {
    background: #0a0a0f !important;
    color: #f0f0f5 !important;
}
main.vintago-account-page .vintago-commerce-page__hero {
    display: block !important;
    width: min(1180px, 100%) !important;
    margin: 0 auto 30px !important;
    text-align: center !important;
}
main.vintago-account-page .vintago-commerce-page__hero p {
    margin-right: auto !important;
    margin-left: auto !important;
}
main.vintago-account-page .vintago-account-layout {
    display: grid !important;
    grid-template-columns: 280px minmax(0, 1fr) !important;
    gap: 28px !important;
}
main.vintago-account-page .vintago-account-login-grid {
    display: grid !important;
    grid-template-columns: 1.1fr .9fr !important;
    gap: 24px !important;
}
</style>
<main class="vintago-commerce-page vintago-account-page">
    <div class="vintago-commerce-page__inner">
        <header class="vintago-commerce-page__hero">
            <span class="vintago-commerce-page__eyebrow">Tu espacio Vintago</span>
            <h1>Mi cuenta</h1>
            <p>Consulta tus pedidos, administra tus datos y mantén todo bajo control.</p>
        </header>

        <?php woocommerce_output_all_notices(); ?>

        <?php if (is_user_logged_in()) : ?>
            <div class="vintago-account-layout">
                <aside class="vintago-account-sidebar">
                    <div class="vintago-account-profile">
                        <span class="vintago-account-profile__icon" aria-hidden="true">V</span>
                        <strong><?php echo esc_html($current_user->display_name ?: $current_user->user_login); ?></strong>
                        <span><?php echo esc_html($current_user->user_email); ?></span>
                    </div>
                    <?php wc_get_template('myaccount/navigation.php'); ?>
                    <div class="vintago-account-quicklinks">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>">Ver carrito</a>
                        <a href="<?php echo esc_url(home_url('/')); ?>">Volver a la tienda</a>
                    </div>
                </aside>
                <section class="vintago-account-content">
                    <div class="vintago-account-content__intro">
                        <span class="vintago-commerce-page__eyebrow">Panel personal</span>
                        <h2>Administra tu cuenta</h2>
                        <p>Desde aquí puedes consultar pedidos, actualizar tus direcciones y cambiar tus datos de acceso.</p>
                    </div>
                    <?php do_action('woocommerce_account_content'); ?>
                </section>
            </div>
        <?php else : ?>
            <div class="vintago-account-login-grid">
                <section class="vintago-commerce-card vintago-account-login-card vintago-account-login-card--primary">
                    <span class="vintago-commerce-page__eyebrow">Acceso</span>
                    <h2>Inicia sesión</h2>
                    <p>Accede a tus pedidos, direcciones y detalles de compra en segundos.</p>
                    <?php woocommerce_login_form(); ?>
                </section>
                <aside class="vintago-commerce-card vintago-account-login-card vintago-account-login-card--secondary">
                    <span class="vintago-commerce-page__eyebrow">Beneficios</span>
                    <h2>Tu cuenta Vintago</h2>
                    <ul class="vintago-account-benefits">
                        <li>Consulta el historial de tus compras.</li>
                        <li>Revisa tus direcciones y datos de entrega.</li>
                        <li>Gestiona pedidos y devoluciones con mayor claridad.</li>
                        <li>Recibe soporte más rápido por WhatsApp.</li>
                    </ul>
                    <div class="vintago-account-benefit-boxes">
                        <div>
                            <strong>Envíos</strong>
                            <span>Entrega rápida a Colombia</span>
                        </div>
                        <div>
                            <strong>Soporte</strong>
                            <span>Atención directa por WhatsApp</span>
                        </div>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
