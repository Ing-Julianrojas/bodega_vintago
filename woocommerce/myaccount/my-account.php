<?php
/**
 * My Account page template override for the Vintago child theme.
 * Includes a premium two-column dashboard and keeps WooCommerce hooks intact.
 */
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
?>
<main class="vintago-commerce-page vintago-account-page">
    <div class="vintago-commerce-page__inner">
        <header class="vintago-commerce-page__hero">
            <span class="vintago-commerce-page__eyebrow"><?php esc_html_e('Tu espacio Vintago', 'vintago'); ?></span>
            <h1><?php esc_html_e('Mi cuenta', 'vintago'); ?></h1>
            <p><?php esc_html_e('Consulta tus pedidos, administra tus datos y mantén todo bajo control.', 'vintago'); ?></p>
        </header>

        <?php woocommerce_output_all_notices(); ?>

        <?php if (is_user_logged_in()) : ?>
            <div class="vintago-account-layout">
                <aside class="vintago-account-sidebar">
                    <div class="vintago-account-profile">
                        <span class="vintago-account-profile__icon" aria-hidden="true"><?php echo esc_html(strtoupper(substr($current_user->display_name ?: $current_user->user_login, 0, 1))); ?></span>
                        <strong><?php echo esc_html($current_user->display_name ?: $current_user->user_login); ?></strong>
                        <span><?php echo esc_html($current_user->user_email); ?></span>
                    </div>

                    <?php wc_get_template('myaccount/navigation.php'); ?>

                    <div class="vintago-account-quicklinks">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php esc_html_e('Ver carrito', 'vintago'); ?></a>
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Volver a la tienda', 'vintago'); ?></a>
                    </div>
                </aside>

                <section class="vintago-account-content">
                    <div class="vintago-account-content__intro">
                        <span class="vintago-commerce-page__eyebrow"><?php esc_html_e('Panel personal', 'vintago'); ?></span>
                        <h2><?php esc_html_e('Administra tu cuenta', 'vintago'); ?></h2>
                        <p><?php esc_html_e('Desde aquí puedes consultar pedidos, actualizar tus direcciones y cambiar tus datos de acceso.', 'vintago'); ?></p>
                    </div>

                    <?php do_action('woocommerce_account_content'); ?>
                </section>
            </div>
        <?php else : ?>
            <div class="vintago-account-login-grid">
                <section class="vintago-commerce-card vintago-account-login-card vintago-account-login-card--primary">
                    <span class="vintago-commerce-page__eyebrow"><?php esc_html_e('Acceso', 'vintago'); ?></span>
                    <h2><?php esc_html_e('Inicia sesión', 'vintago'); ?></h2>
                    <p><?php esc_html_e('Accede a tus pedidos, direcciones y detalles de compra en segundos.', 'vintago'); ?></p>
                    <?php woocommerce_login_form(); ?>
                </section>

                <aside class="vintago-commerce-card vintago-account-login-card vintago-account-login-card--secondary">
                    <span class="vintago-commerce-page__eyebrow"><?php esc_html_e('Beneficios', 'vintago'); ?></span>
                    <h2><?php esc_html_e('Tu cuenta Vintago', 'vintago'); ?></h2>
                    <ul class="vintago-account-benefits">
                        <li><?php esc_html_e('Consulta el historial de tus compras.', 'vintago'); ?></li>
                        <li><?php esc_html_e('Revisa tus direcciones y datos de entrega.', 'vintago'); ?></li>
                        <li><?php esc_html_e('Gestiona pedidos y devoluciones con mayor claridad.', 'vintago'); ?></li>
                        <li><?php esc_html_e('Recibe soporte más rápido por WhatsApp.', 'vintago'); ?></li>
                    </ul>

                    <div class="vintago-account-benefit-boxes">
                        <div>
                            <strong><?php esc_html_e('Envíos', 'vintago'); ?></strong>
                            <span><?php esc_html_e('Entrega rápida a Colombia', 'vintago'); ?></span>
                        </div>
                        <div>
                            <strong><?php esc_html_e('Soporte', 'vintago'); ?></strong>
                            <span><?php esc_html_e('Atención directa por WhatsApp', 'vintago'); ?></span>
                        </div>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</main>
