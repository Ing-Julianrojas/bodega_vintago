<?php
/**
 * Template Name: Cuenta Vintagø
 * page-acceder.php — Bodega Vintagø
 *
 * Portal de cuenta con login/registro conectado
 * de verdad a WooCommerce (wc_login_form / wc_registration_form),
 * para que el usuario y contraseña se procesen con la lógica y
 * seguridad nativas de WooCommerce (nonces, hooks, mensajes de error).
 *
 * Después de subir este archivo:
 * 1. Ve a Páginas → Añadir nueva, título "Cuenta", slug "acceder".
 * 2. En Atributos de página, elige la plantilla "Acceso Vintagø".
 * 3. Publica la página.
 * 4. (Opcional) Enlaza aquí tu botón "Iniciar sesión" del header,
 *    en vez de a la página nativa "Mi cuenta" de WooCommerce.
 *
 * Nota: esta página NO reemplaza la página "Mi cuenta" de WooCommerce
 * (esa sigue mostrando pedidos, direcciones, etc. cuando ya hay sesión).
 * Esta es la puerta de entrada; una vez el usuario inicia
 * sesión aquí, lo mandamos a la página real de "Mi cuenta".
 */

// Si ya hay sesión iniciada, no tiene sentido mostrar el formulario:
// mandamos directo al dashboard real de WooCommerce.
if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() && function_exists( 'wc_get_page_permalink' ) ) {
	wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
	exit;
}

$myaccount_url        = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/mi-cuenta/' );
$registration_enabled = class_exists( 'WooCommerce' ) && 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$wa_number             = '573127558773'; // mismo número que ya usas en el flotante de WhatsApp
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<?php wp_head(); ?>
<style>
  :root{
   --bg-page:#edf1f4;
   --panel-dark:#0b1117;
   --panel-dark-2:#0f1a22;
   --panel-glass:rgba(255,255,255,0.78);
   --panel-soft:#f5f7f8;
   --border:rgba(17,24,39,.08);
   --text:#101a21;
   --text-soft:#5c6770;
   --muted:#7d8a92;
   --accent:#6feee0;
   --accent-strong:#48d7c5;
   --accent-ink:#062f2d;
   --shadow:0 26px 65px rgba(15,23,42,.12);
 }
 *{box-sizing:border-box;}
 body{margin:0;min-height:100vh;background:var(--bg-page);color:var(--text);font-family:'Inter',sans-serif;display:block;}
 body.page-template-page-acceder .site-content,
 body.page-template-page-acceder #content,
 body.page-template-page-acceder #primary,
 body.page-template-page-acceder .entry-content,
 body.page-template-page-acceder .ast-container,
 body.page-template-page-acceder .site-main {
   width: 100% !important;
   max-width: none !important;
   margin: 0 !important;
   padding: 0 !important;
   overflow: visible !important;
 }
 .vintago-access-page{display:flex;align-items:center;justify-content:center;min-height:100vh;width:100%;max-width:none;margin:0;padding:28px;}
 .vintago-access-frame{width:min(1240px,100%);max-width:1240px;margin:0 auto;}
 .vintago-access-topbar{display:flex;align-items:center;justify-content:space-between;gap:22px;margin:0 auto 18px;padding:0 4px;}
 .vintago-access-topbar .brand{color:#101a21;}
 .vintago-product-search{display:flex;align-items:center;width:min(430px,100%);height:46px;border:1px solid rgba(17,24,39,.12);border-radius:14px;background:rgba(255,255,255,.82);box-shadow:0 10px 24px rgba(15,23,42,.06);overflow:hidden;flex-shrink:0;}
 .vintago-product-search input{min-width:0;flex:1;height:100%;padding:0 14px;border:0;background:transparent;color:var(--text);font:inherit;font-size:13px;outline:0;}
 .vintago-product-search input::placeholder{color:var(--muted);}
 .vintago-product-search button{display:inline-flex;align-items:center;justify-content:center;width:46px;height:100%;border:0;background:var(--accent-strong);color:var(--accent-ink);cursor:pointer;transition:background .2s ease;}
 .vintago-product-search button:hover{background:var(--accent);}
 .vintago-product-search svg{width:17px;height:17px;}
 .vintago-access-cart{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:46px;padding:0 14px;border:1px solid rgba(17,24,39,.12);border-radius:14px;background:#101a21;color:#f3f7f8;font:700 13px 'Inter',sans-serif;cursor:pointer;white-space:nowrap;transition:background .2s ease,transform .2s ease;}
 .vintago-access-cart:hover{background:#1b3038;transform:translateY(-1px);}
 .vintago-access-cart svg{width:17px;height:17px;}
 .vintago-access-cart b{display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;padding:0 5px;border-radius:10px;background:var(--accent);color:var(--accent-ink);font-size:11px;}
 .vintago-auth-shell{display:flex;align-items:stretch;width:100%;min-width:0;min-height:760px;border-radius:32px;overflow:hidden;box-shadow:var(--shadow);}
 a{color:inherit;}
 h1,.brand,.headline{font-family:'Space Grotesk',sans-serif;}
 :focus-visible{outline:2px solid var(--accent-strong);outline-offset:2px;}

 .side{flex:1.15 1 0;min-width:0;background:linear-gradient(135deg,#0d171e,#0a1117 55%,#070d13 100%);display:flex;flex-direction:column;justify-content:space-between;padding:34px 30px 26px;position:relative;overflow:hidden;}
 .side:before{content:"";position:absolute;width:560px;height:560px;border-radius:50%;top:-180px;right:-160px;background:radial-gradient(circle, rgba(111,238,224,.13), transparent 60%);}
 .side:after{content:"";position:absolute;width:440px;height:440px;border-radius:50%;bottom:-160px;left:-120px;background:radial-gradient(circle, rgba(130,110,255,.12), transparent 62%);}
 .brand,.pitch,.visual-wrap,.foot{position:relative;z-index:1;}
 .brand{display:inline-block;font-size:20px;font-weight:700;letter-spacing:-.05em;color:#eef8f7;text-decoration:none;}
 .brand .dot{color:var(--accent);}
 .pitch{margin-top:18px;max-width:330px;}
 .side .pitch p.eyebrow{margin:0 0 18px;display:inline-flex;align-items:center;padding:9px 12px;border-radius:999px;border:1px solid rgba(111,238,224,.18);background:rgba(111,238,224,.06);color:var(--accent);font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;}
 .side .pitch h1{margin:0;font-size:clamp(2.9rem,3.2vw,4.8rem);line-height:.92;letter-spacing:-.06em;color:#f3f7f8;}
 .side .pitch h1 span{display:block;}
 .side .pitch .lead{margin:18px 0 0;color:rgba(236,243,246,.78);font-size:15px;line-height:1.6;}

 .visual-wrap{position:relative;min-height:420px;margin-top:30px;border-radius:28px;overflow:hidden;border:1px solid rgba(255,255,255,.05);background:linear-gradient(140deg,#111d27 0%,#0a1117 38%,#080d12 100%);}
 .visual-wrap .scene{position:absolute;inset:0;background:radial-gradient(circle at 18% 20%, rgba(111,238,224,.22), transparent 12%),radial-gradient(circle at 72% 26%, rgba(117,107,255,.16), transparent 20%),linear-gradient(180deg, rgba(255,255,255,.07), transparent 50%);}
 .visual-wrap .badge{position:absolute;left:22px;top:22px;z-index:2;display:inline-flex;align-items:center;padding:9px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.06);background:rgba(255,255,255,.04);color:#edf9f8;font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;}
 .visual-wrap .mini-card{position:absolute;left:22px;bottom:22px;z-index:2;width:176px;padding:14px 16px;border-radius:18px;background:rgba(111,238,224,.1);border:1px solid rgba(111,238,224,.18);color:#edf9f8;box-shadow:0 18px 38px rgba(0,0,0,.22);}
 .visual-wrap .mini-card strong{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(237,249,248,.8);}
 .visual-wrap .mini-card span{display:block;margin-top:8px;font-size:28px;font-weight:800;letter-spacing:-.07em;line-height:1;}
 .visual-wrap .panel-card{position:absolute;right:22px;bottom:22px;z-index:2;width:72%;padding:18px 18px 14px;border-radius:22px;background:rgba(14,20,25,.88);border:1px solid rgba(111,238,224,.14);box-shadow:0 24px 48px rgba(0,0,0,.2);}
 .visual-wrap .panel-card .line{height:10px;border-radius:999px;background:rgba(255,255,255,.08);margin-bottom:10px;}
 .visual-wrap .panel-card .line.short{width:58%;}
 .visual-wrap .panel-card .line.medium{width:76%;}
 .visual-wrap .panel-card .line.long{width:100%;}
 .side .foot{font-size:12px;color:rgba(236,243,246,.72);}

 .panel{flex:0.95 1 0;min-width:0;display:flex;align-items:center;justify-content:center;padding:30px 22px;background:#edf1f4;}
 .card{width:min(100%,430px);min-width:0;background:rgba(255,255,255,.8);border:1px solid var(--border);border-radius:26px;padding:24px 22px 18px;box-shadow:0 18px 36px rgba(15,23,42,.06);}
 .back{display:inline-flex;align-items:center;gap:8px;font-size:12px;color:var(--text-soft);text-decoration:none;margin-bottom:18px;font-weight:700;}
 .back:hover{color:var(--accent-strong);}

 .tabs{display:flex;gap:8px;padding:6px;border-radius:999px;border:1px solid var(--border);background:rgba(15,23,42,.04);margin-bottom:18px;}
 .tabs button{flex:1;border:0;border-radius:999px;background:transparent;color:var(--text-soft);font-weight:700;font-size:13px;padding:11px 12px;cursor:pointer;transition:all .2s ease;}
 .tabs button.active{background:linear-gradient(135deg,#0d1419,#1a2e33);color:#f5fbfa;box-shadow:0 10px 22px rgba(15,23,42,.12);}
 .panel-form{display:none;}
 .panel-form.active{display:block;animation:fade .22s ease;}
 @keyframes fade{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:translateY(0);}}

 .card h2{margin:0 0 8px;font-size:clamp(1.9rem,2.5vw,2.6rem);line-height:1.08;letter-spacing:-.06em;color:#111a22;}
 .card .sub{margin:0 0 20px;color:var(--text-soft);font-size:14px;line-height:1.5;}

 .woocommerce-form{margin:0;}
 .woocommerce-form .form-row-first,
 .woocommerce-form .form-row-last{
   float:none;
   width:100%;
   margin-right:0;
 }
 .woocommerce-form .form-row-wide{clear:both;width:100%;}
 .woocommerce-form .form-row-first + .form-row-last{margin-top:0;}
 .woocommerce-form .form-row,
 .woocommerce-form p.woocommerce-form-row{margin:0 0 18px;padding:0;}
 .woocommerce-form label{display:block;margin-bottom:8px;font-size:12px;font-weight:700;color:#1d2b33;}
 .woocommerce-form .required{color:#0d7d70;text-decoration:none;}
 .woocommerce-form input.input-text,
 .woocommerce-form input[type="text"],
 .woocommerce-form input[type="email"],
 .woocommerce-form input[type="password"]{
   width:100%;height:52px;padding:14px;border-radius:14px;border:1px solid var(--border);background:rgba(255,255,255,.72);color:#101922;font-size:15px;transition:all .2s ease;
 }
 .woocommerce-form input:focus{outline:none;border-color:rgba(43,185,167,.8);background:rgba(255,255,255,.96);box-shadow:0 0 0 4px rgba(111,238,224,.18);}
 .woocommerce-form-login__rememberme{display:flex;align-items:center;gap:8px;margin:0 0 18px;font-size:12.8px;color:var(--text-soft);}
 .woocommerce-form-login__rememberme input{width:14px;height:14px;accent-color:var(--accent-strong);}
 .woocommerce-LostPassword{margin:-4px 0 18px;text-align:right;font-size:12.8px;}
 .woocommerce-LostPassword a{color:#0d7d70;text-decoration:none;font-weight:700;}
 .woocommerce-form button[type="submit"]{display:flex;align-items:center;justify-content:center;width:100%;height:52px;border:0;border-radius:14px;cursor:pointer;background:linear-gradient(135deg,var(--accent),#9ef0f0);color:var(--accent-ink);font-size:15px;font-weight:800;box-shadow:0 15px 28px rgba(43,185,167,.24);transition:transform .15s ease, box-shadow .15s ease;}
 .woocommerce-form button[type="submit"]:hover{transform:translateY(-1px);box-shadow:0 18px 32px rgba(43,185,167,.28);}
 .woocommerce-privacy-policy-text{font-size:12px;color:var(--text-soft);margin:0 0 16px;}

 .secondary-link{margin-top:18px;text-align:center;color:var(--text-soft);font-size:14px;}
 .secondary-link a{color:#0d7d70;text-decoration:none;font-weight:700;}

 .woocommerce-error,.woocommerce-message,.woocommerce-info{list-style:none;padding:12px 14px;border-radius:12px;margin:0 0 18px;background:rgba(255,255,255,.6);border:1px solid var(--border);color:#11222d;font-size:13px;}
 .woocommerce-error{border-left:3px solid #d9534f;}
 .woocommerce-message{border-left:3px solid var(--accent-strong);}

 /* Override the child-theme global palette only inside this access page. */
 .vintago-access-page .side,
 .vintago-access-page .side p,
 .vintago-access-page .side .lead,
 .vintago-access-page .side .foot{color:#ecf3f6 !important;}
 .vintago-access-page .side .brand{color:#f4fffe !important;opacity:1 !important;}
 .vintago-access-page .side .brand .dot,
 .vintago-access-page .side .pitch .eyebrow{color:#6feee0 !important;}
 .vintago-access-page .side .pitch h1{color:#f7fbfc !important;text-shadow:0 2px 18px rgba(0,0,0,.28);}
 .vintago-access-page .side .pitch .lead{color:rgba(236,243,246,.84) !important;}
 .vintago-access-page .panel,
 .vintago-access-page .panel .card{color:#101a21 !important;}
 .vintago-access-page .panel .card h2{color:#101a21 !important;}
 .vintago-access-page .panel .card .sub,
 .vintago-access-page .panel .card .secondary-link{color:#52616a !important;}
 .vintago-access-page .panel .card label,
 .vintago-access-page .panel .card .woocommerce-form label{color:#26363e !important;}
 .vintago-access-page .panel .card .woocommerce-form input.input-text,
 .vintago-access-page .panel .card .woocommerce-form input[type="text"],
 .vintago-access-page .panel .card .woocommerce-form input[type="email"],
 .vintago-access-page .panel .card .woocommerce-form input[type="password"]{background:#fff !important;color:#101a21 !important;border-color:rgba(17,24,39,.16) !important;}
 .vintago-access-page .panel .card .woocommerce-form input[type="checkbox"]{accent-color:#48d7c5;}
 .vintago-access-page .panel .card a{color:#087d70 !important;}
 .vintago-access-page .panel .card button{color:inherit;}

 @media (max-width: 980px){
  .vintago-access-page{padding:16px;}
  .vintago-access-topbar{align-items:stretch;flex-direction:column;gap:12px;}
   .vintago-product-search{width:100%;}
  .vintago-access-cart{width:100%;}
   .vintago-auth-shell{display:block;width:100%;min-height:auto;border-radius:24px;}
   .side,.panel{width:100%;}
   .side{padding:24px 22px 18px;}
   .panel{padding:18px 16px 24px;}
   .visual-wrap{min-height:260px;}
 }

 @media (min-width: 640px){
   #registerForm .woocommerce-form > .form-row-first,
   #registerForm .woocommerce-form > .form-row-last{
     display:inline-block;
     vertical-align:top;
     width:calc(50% - 8px);
   }
   #registerForm .woocommerce-form > .form-row-first{margin-right:16px;}
 }

 @media (max-width: 520px){
   .side .pitch h1{font-size:2.7rem;}
   .card{padding:16px 14px 14px;}
   .tabs button{font-size:12px;}
   .woocommerce-form input.input-text,
   .woocommerce-form input[type="text"],
   .woocommerce-form input[type="email"],
   .woocommerce-form input[type="password"]{height:48px;}
   .woocommerce-form button[type="submit"]{height:48px;}
 }
</style>
</head>
<body>

<div class="vintago-access-page">
  <div class="vintago-access-frame">
    <div class="vintago-access-topbar">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">VINTAG<span class="dot">Ø</span> BODEGA</a>
      <form class="vintago-product-search" role="search" method="get" action="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="vintago-product-search-input"><?php esc_html_e( 'Buscar productos', 'vintago' ); ?></label>
        <input id="vintago-product-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Buscar productos..." autocomplete="off" />
        <input type="hidden" name="post_type" value="product" />
        <button type="submit" aria-label="Buscar productos">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
        </button>
      </form>
      <button type="button" class="vintago-access-cart" data-vintago-cart-open aria-controls="vintagoSideCart" aria-label="Abrir carrito">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="20" r="1"></circle><circle cx="19" cy="20" r="1"></circle><path d="M2 3h3l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H6"></path></svg>
        <span>Carrito</span><b id="vintagoCartCount">0</b>
      </button>
    </div>

  <div class="vintago-auth-shell">
    <div class="side">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">VINTAG<span class="dot">Ø</span> BODEGA</a>

      <div class="pitch">
        <p class="eyebrow">Bodega mayorista</p>
        <h1>Accede <span>a precios de bodega</span></h1>
        <p class="lead">Consulta tu historial, guarda tus pedidos y compra más rápido con condiciones especiales para tu negocio.</p>
      </div>

      <div class="visual-wrap" aria-hidden="true">
        <div class="scene"></div>
        <div class="badge">Mayorista</div>
        <div class="mini-card">
          <strong>Pedidos</strong>
          <span>+24%</span>
        </div>
        <div class="panel-card">
          <div class="line long"></div>
          <div class="line medium"></div>
          <div class="line short"></div>
        </div>
      </div>

      <div class="foot">© <?php echo esc_html( date( 'Y' ) ); ?> Vintagø Bodega · Bogotá, Colombia</div>
    </div>

    <div class="panel">
      <div class="card">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back">← Volver a la tienda</a>

        <?php if ( $registration_enabled ) : ?>
        <div class="tabs" role="tablist" aria-label="Opciones de acceso">
          <button type="button" id="loginTab" class="active" role="tab" aria-selected="true" aria-controls="loginForm" data-tab="login">Iniciar sesión</button>
          <button type="button" id="registerTab" role="tab" aria-selected="false" aria-controls="registerForm" data-tab="register">Crear cuenta</button>
        </div>
        <?php endif; ?>

        <?php if ( function_exists( 'wc_print_notices' ) ) { wc_print_notices(); } ?>

        <?php if ( function_exists( 'woocommerce_login_form' ) ) : ?>
        <div class="panel-form active" id="loginForm" role="tabpanel" aria-labelledby="loginTab">
          <h2>Bienvenido de vuelta</h2>
          <p class="sub">Ingresa tus datos para continuar comprando.</p>

          <?php
          woocommerce_login_form(
            array(
              'redirect' => $myaccount_url,
            )
          );
          ?>

          <div class="secondary-link">¿No tienes cuenta? <a href="#" class="toggle-register">Crear una</a></div>
        </div>
        <?php endif; ?>

        <?php if ( $registration_enabled ) : ?>
        <div class="panel-form" id="registerForm" role="tabpanel" aria-labelledby="registerTab" hidden>
          <h2>Crea tu cuenta</h2>
          <p class="sub">Regístrate para acceder a precios de bodega y compra al por mayor.</p>
          <form method="post" class="woocommerce-form woocommerce-form-register register">
            <?php do_action( 'woocommerce_register_form_start' ); ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
              <label for="reg_email"><?php esc_html_e( 'Correo electrónico', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
              <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ! empty( $_POST['email'] ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
            </p>
            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
              <label for="reg_password"><?php esc_html_e( 'Contraseña', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
              <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required />
            </p>
            <?php endif; ?>
            <?php do_action( 'woocommerce_register_form' ); ?>
            <p class="woocommerce-form-row form-row">
              <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
              <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Registrarse', 'woocommerce' ); ?>">
                <?php esc_html_e( 'Crear mi cuenta', 'woocommerce' ); ?>
              </button>
            </p>
            <?php do_action( 'woocommerce_register_form_end' ); ?>
          </form>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</div>

<?php if ( $registration_enabled ) : ?>
<script>
function switchAccessTab(tabName){
  document.querySelectorAll('.tabs button').forEach(function(btn){
    btn.classList.toggle('active', btn.dataset.tab === tabName);
    btn.setAttribute('aria-selected', btn.dataset.tab === tabName ? 'true' : 'false');
  });
  document.querySelectorAll('.panel-form').forEach(function(form){
    var isActive = form.id === tabName + 'Form';
    form.classList.toggle('active', isActive);
    form.hidden = !isActive;
  });
}

document.querySelectorAll('.tabs button').forEach(function(btn){
  btn.addEventListener('click', function(){
    switchAccessTab(btn.dataset.tab);
  });
});

const toggleRegister = document.querySelector('.toggle-register');
if (toggleRegister) {
  toggleRegister.addEventListener('click', function(e){
    e.preventDefault();
    switchAccessTab('register');
  });
}
</script>
<?php endif; ?>

<?php get_footer(); ?>
