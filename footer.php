<?php
if (!defined('ABSPATH')) { exit; }
$returns = get_page_by_path('reembolso_devoluciones');
if (!$returns) { $returns = get_page_by_title('Política de devoluciones y reembolsos'); }
$privacy = get_page_by_path('privacy-policy');
if (!$privacy) { $privacy = get_page_by_title('Privacy Policy'); }
$returns_url = $returns ? get_permalink($returns) : home_url('/reembolso_devoluciones/');
$privacy_url = $privacy ? get_permalink($privacy) : home_url('/privacy-policy/');
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/tienda/');
$wholesale_page = get_page_by_path('mayoristas');
$wholesale_url = $wholesale_page ? get_permalink($wholesale_page) : home_url('/mayoristas/');
?>
<style id="vintago-footer-safety">
    .vintago-global-footer{display:block!important;width:100%!important;clear:both!important;background:#111118!important;border-top:1px solid #2a2a3a!important;color:#8888a8!important;}
    .vintago-global-footer *{box-sizing:border-box;}
    .vintago-footer__grid{display:grid!important;width:min(1240px,100%)!important;grid-template-columns:1.6fr 1fr 1.5fr 1fr!important;gap:34px!important;margin:0 auto!important;padding:54px 22px 42px!important;}
    .vintago-footer__grid>div{min-width:0!important;}
    .vintago-footer__grid p{margin:14px 0 0!important;color:#8888a8!important;font-size:13px!important;line-height:1.7!important;}
    .vintago-footer__grid h2{margin:0 0 15px!important;color:#f0f0f5!important;font-size:12px!important;letter-spacing:.08em!important;text-transform:uppercase!important;}
    .vintago-footer__grid a{display:block!important;margin:0 0 10px!important;color:#8888a8!important;font-size:13px!important;line-height:1.45!important;}
    .vintago-footer__grid a:hover{color:#00e5c3!important;}
    .vintago-footer__grid .vintago-global-header__brand{display:inline-block!important;margin:0!important;color:#f0f0f5!important;font-size:20px!important;font-weight:800!important;}
    .vintago-footer__grid .vintago-global-header__brand span{color:#00e5c3!important;}
    .vintago-footer__bottom{display:flex!important;width:min(1240px,100%)!important;justify-content:space-between!important;gap:16px!important;margin:0 auto!important;border-top:1px solid #2a2a3a!important;padding:18px 22px!important;color:#8888a8!important;font-size:12px!important;}
    .vintago-footer__bottom span{color:#8888a8!important;}
    @media(max-width:640px){.vintago-footer__grid{grid-template-columns:1fr 1fr!important;gap:28px 18px!important;padding:38px 18px 30px!important;}.vintago-footer__bottom{flex-direction:column!important;padding:16px 18px!important;}}
</style>
<footer class="vintago-global-footer">
    <div class="vintago-footer__grid">
        <div><a class="vintago-global-header__brand" href="<?php echo esc_url(home_url('/')); ?>">VINTAGO<span>.</span> BODEGA</a><p>Tecnología, hogar y oportunidades para comprar mejor y revender con confianza.</p></div>
        <div><h2>Explora</h2><a href="<?php echo esc_url($shop_url); ?>">Tienda</a><a href="<?php echo esc_url($wholesale_url); ?>">Mayoristas</a><a href="<?php echo esc_url(home_url('/acceder/')); ?>">Mi cuenta</a></div>
        <div><h2>Ayuda y legal</h2><a href="<?php echo esc_url($returns_url); ?>">Devoluciones y reembolsos</a><a href="<?php echo esc_url($privacy_url); ?>">Política de privacidad</a></div>
        <div><h2>Atención</h2><p>Bogotá, Colombia</p><a href="https://wa.me/573127558773" target="_blank" rel="noopener">WhatsApp: +57 312 755 8773</a></div>
    </div>
    <div class="vintago-footer__bottom"><span>&copy; <?php echo esc_html(date('Y')); ?> Vintago Bodega</span><span>Diseñado y desarrollado por <a href="https://www.linkedin.com/in/julianrojasgonzalez/" target="_blank" rel="noopener">Julian Rojas</a></span></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
