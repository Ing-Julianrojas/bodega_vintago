<?php
/**
 * Ficha de producto Vintago con galeria, compra y bloques informativos.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
while (have_posts()) : the_post();
    global $product;
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
            <section class="vintago-product-gallery">
                <?php woocommerce_show_product_images(); ?>
            </section>
            <section class="vintago-product-summary">
                <p class="vintago-product-kicker">Producto Vintago</p>
                <h1><?php the_title(); ?></h1>
                <div class="vintago-product-rating"><?php woocommerce_template_single_rating(); ?></div>
                <div class="vintago-product-price"><?php woocommerce_template_single_price(); ?></div>
                <div class="vintago-product-short-description"><?php woocommerce_template_single_excerpt(); ?></div>
                <div class="vintago-product-purchase"><?php woocommerce_template_single_add_to_cart(); ?></div>
                <div class="vintago-trust-points">
                    <span><b>Garantía</b> Compra respaldada</span>
                    <span><b>Envío rápido</b> Bogotá y Colombia</span>
                    <span><b>Soporte</b> Atención por WhatsApp</span>
                </div>
                <div class="vintago-product-accordions">
                    <details><summary>Descripción y especificaciones</summary><div><?php the_content(); ?></div></details>
                    <details><summary>Tiempo de entrega</summary><div><p>Despachamos desde Bogotá. El tiempo estimado depende de la ciudad, la transportadora y la confirmación del pago. Te enviaremos la información de seguimiento cuando el pedido sea despachado.</p></div></details>
                    <details><summary>Políticas de devolución</summary><div><p>Consulta las condiciones completas en nuestra <a href="<?php echo esc_url($returns_url); ?>">Política de devoluciones y reembolsos</a>.</p></div></details>
                    <details><summary>Política de privacidad</summary><div><p>Conoce cómo tratamos tus datos en nuestra <a href="<?php echo esc_url($privacy_url); ?>">Política de privacidad</a>.</p></div></details>
                </div>
            </section>
        </div>
        <section class="vintago-related-products"><h2>También te puede interesar</h2><?php woocommerce_output_related_products(); ?></section>
    </div>
</main>
<?php endwhile; get_footer(); ?>
