<?php
/**
 * Login form template override for Vintago.
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_customer_login_form');
?>

<div class="vintago-account-login-grid">
    <section class="vintago-commerce-card vintago-account-login-card vintago-account-login-card--primary">
        <span class="vintago-commerce-page__eyebrow"><?php esc_html_e('Acceso', 'vintago'); ?></span>
        <h2><?php esc_html_e('Inicia sesión', 'vintago'); ?></h2>
        <p><?php esc_html_e('Accede a tus pedidos, direcciones y detalles de compra en segundos.', 'vintago'); ?></p>

        <form class="woocommerce-form woocommerce-form-login login" method="post">
            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="username"><?php esc_html_e('Correo electrónico o usuario', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php esc_html_e('Contraseña', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <p class="form-row">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    <span><?php esc_html_e('Recuérdame', 'woocommerce'); ?></span>
                </label>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('Acceder', 'woocommerce'); ?>"><?php esc_html_e('Acceder', 'woocommerce'); ?></button>
            </p>

            <p class="woocommerce-LostPassword lost_password">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('¿Olvidaste tu contraseña?', 'woocommerce'); ?></a>
            </p>

            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <?php do_action('woocommerce_login_form_end'); ?>
        </form>
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

<?php do_action('woocommerce_after_customer_login_form'); ?>
