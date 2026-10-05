<?php
/**
 * front-page.php — Bodega Vintago v3.0
 * Carrusel hero + Spline 3D + carrito barra + footer limpio
 */
$account_page    = get_page_by_path('acceder');
$myaccount_url   = $account_page ? get_permalink($account_page) : wc_get_page_permalink('myaccount');
$mayoristas_page = get_page_by_path('mayoristas');
$mayoristas_url  = $mayoristas_page ? get_permalink($mayoristas_page) : home_url('/mayoristas/');
$shop_url        = get_permalink(wc_get_page_id('shop'));
$cart_url        = wc_get_cart_url();
$cart_count      = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_subtotal   = WC()->cart ? WC()->cart->get_cart_subtotal() : '$ 0';

function bvg_cat_url($name){
  $terms=get_terms(array('taxonomy'=>'product_cat','name'=>$name,'hide_empty'=>false,'fields'=>'slugs','number'=>1));
  if(!is_wp_error($terms)&&!empty($terms)) return get_term_link($terms[0],'product_cat');
  return wc_get_page_permalink('shop');
}
function bvg_cat_count($name){
  $terms=get_terms(array('taxonomy'=>'product_cat','name'=>$name,'hide_empty'=>false,'fields'=>'ids'));
  if(is_wp_error($terms)||empty($terms)) return 0;
  $t=0; foreach($terms as $tid){$tr=get_term($tid);if($tr&&!is_wp_error($tr))$t+=(int)$tr->count;} return $t;
}
function bvg_subcats($parent_name){
  $parent=get_term_by('name',$parent_name,'product_cat');
  if(!$parent||is_wp_error($parent)) return array();
  $terms=get_terms(array('taxonomy'=>'product_cat','parent'=>$parent->term_id,'hide_empty'=>true,'number'=>0));
  if(is_wp_error($terms)||empty($terms)) return array();
  $u=array();$s=array();
  foreach($terms as $t){if(!isset($s[$t->slug])){$s[$t->slug]=true;$u[]=$t;}} return $u;
}
$politica_devoluciones=get_page_by_path('reembolso_devoluciones');
if(!$politica_devoluciones)$politica_devoluciones=get_page_by_title('Política de devoluciones y reembolsos');
$politica_privacidad=get_page_by_path('privacy-policy');
if(!$politica_privacidad)$politica_privacidad=get_page_by_title('Privacy Policy');
$terminos_id=(int)get_option('woocommerce_terms_page_id');
$terminos=$terminos_id?get_post($terminos_id):get_page_by_path('terminos-y-condiciones');
$url_devoluciones=$politica_devoluciones?get_permalink($politica_devoluciones):'#';
$url_privacidad=$politica_privacidad?get_permalink($politica_privacidad):'#';
$url_terminos=$terminos?get_permalink($terminos):'#';

/* ===== CARRUSEL SLIDES — EDITA AQUI =====
 * Imágenes desktop/mobile en assets/; se elige la versión según el dispositivo.
 * Sube a: /wp-content/themes/TU-CHILD/assets/
 * Mobile se ve: ~100vw × ~56vw (min 300px alto)
 */
$hero_asset_url=get_stylesheet_directory_uri().'/assets/';
$hero_slides=array(
  array('img'=>$hero_asset_url.'slide-1.png','mobile'=>$hero_asset_url.'slide-1-mobile.png','badge'=>'🔥 Precio de bodega','title'=>'940+ productos de <span class="accent">tecnología</span>, directo de bodega a tu negocio.','desc'=>'Audio, gadgets, electrodomésticos, belleza y entretenimiento al detal y mayor, con despacho desde Bogotá en 24h.','btn1_txt'=>'Explorar categorías','btn1_url'=>$shop_url,'btn2_txt'=>'Precio mayorista','btn2_url'=>$mayoristas_url),
  array('img'=>$hero_asset_url.'slide-2.png','mobile'=>$hero_asset_url.'slide-2-mobile.png','badge'=>'🎧 Audio premium','title'=>'Audífonos y <span class="accent">gadgets top</span> con garantía real','desc'=>'Lo más vendido de la bodega en tecnología: audífonos, smartwatches, cámaras y accesorios.','btn1_txt'=>'Ver tecnología','btn1_url'=>bvg_cat_url('Tecnología y Gadgets'),'btn2_txt'=>'Ver precios mayor','btn2_url'=>$mayoristas_url),
  array('img'=>$hero_asset_url.'slide-3.png','mobile'=>$hero_asset_url.'slide-3-mobile.png','badge'=>'🏠 Hogar inteligente','title'=>'Todo para tu hogar al <span class="accent">precio de bodega</span>','desc'=>'Electrodomésticos, organización, cocina y más. Stock disponible y despacho a todo Colombia.','btn1_txt'=>'Ver hogar y cocina','btn1_url'=>bvg_cat_url('Hogar y Cocina'),'btn2_txt'=>'Ver precios mayor','btn2_url'=>$mayoristas_url),
  array('img'=>$hero_asset_url.'slide-4.png','mobile'=>$hero_asset_url.'slide-4-mobile.png','badge'=>'✨ Nuevas referencias','title'=>'Descubre lo nuevo de la <span class="accent">bodega</span>','desc'=>'Referencias seleccionadas para tu hogar y tu negocio, con despacho a todo Colombia.','btn1_txt'=>'Ver novedades','btn1_url'=>$shop_url,'btn2_txt'=>'Comprar al por mayor','btn2_url'=>$mayoristas_url),
);

$bvg_parents=array(
  'Tecnología y Gadgets'=>array('icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>','subs'=>array('Audio','Relojes Inteligentes','Accesorios para Celular','Accesorios para Computador','Cables y Convertidores Audio/Video','Cámaras y Seguridad','Hogar Inteligente','Tablets y Celulares')),
  'Entretenimiento y Multimedia'=>array('icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.544-.604-6.584-.685-7.258-.007-.05-.011-.1-.017-.151A4 4 0 0 0 17.32 5z"/></svg>','subs'=>array('Gaming','Proyectores','Generadores de Contenido')),
  'Hogar y Cocina'=>array('icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>','subs'=>array('Cocina','Electrodomésticos','Organización y Almacenamiento','Iluminación','Decoración','Ventiladores','Mesas de Noche','Ferretería / Herramientas')),
  'Belleza y Salud'=>array('icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>','subs'=>array('Máquinas de Corte de Cabello','Planchas Alisadoras','Secadores','Rizadoras','Salud y Fitness','Cuidado Facial y Maquillaje')),
  'Movilidad y Vehículos'=>array('icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C1.4 11.3 1 12.2 1 13v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>','subs'=>array('Autos','Motos')),
  'Estilo de Vida y Otros'=>array('icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>','subs'=>array('Juguetería e Infantil','Mascotas','Remates')),
);
?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo esc_url(trailingslashit(get_stylesheet_directory_uri()).'style.css'); ?>?v=<?php echo esc_attr(filemtime(get_stylesheet_directory().'/style.css')); ?>" id="bvg-style">
<style>
:root{--bg:#0a0a0f;--bg2:#111118;--bgc:#16161f;--bgh:#1e1e2a;--acc:#00e5c3;--acc2:#7c5cfc;--tx:#f0f0f5;--txm:#8888a8;--brd:#2a2a3a;--r:10px;--rl:16px;--tr:.2s ease;--fh:'Outfit',system-ui,sans-serif;--fb:'Inter',system-ui,sans-serif;}
*{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--bg)!important;color:var(--tx)!important;font-family:var(--fb)!important;}
a{text-decoration:none;color:inherit;}
img{display:block;max-width:100%;}
.wrap{max-width:1240px;margin:0 auto;padding:0 24px;}

/* UTILITY BAR */
.utility-bar{background:#080810;border-bottom:1px solid var(--brd);padding:7px 0;font-size:12px;color:var(--txm);}
.utility-bar .wrap{display:flex;justify-content:space-between;align-items:center;}
.utility-bar .left,.utility-bar .right{display:flex;align-items:center;gap:16px;}
.utility-bar a{color:var(--txm);display:flex;align-items:center;gap:5px;transition:color var(--tr);}
.utility-bar a:hover{color:var(--acc);}

/* HEADER */
.site-header{background:rgba(10,10,15,.97)!important;border-bottom:1px solid var(--brd)!important;position:sticky!important;top:0!important;z-index:2000!important;backdrop-filter:blur(12px);}
body{overflow-x:clip;}
.utility-bar{position:sticky;top:0;z-index:2001;}
.header-inner{display:flex;align-items:center;gap:20px;padding:14px 0;}
.brand{font-family:var(--fh);font-size:20px;font-weight:800;color:var(--acc);letter-spacing:-.02em;white-space:nowrap;}
.brand .dot{color:var(--acc);}
.search-bar{flex:1;max-width:520px;display:flex;background:var(--bgc);border:1px solid var(--brd);border-radius:var(--r);overflow:hidden;transition:border-color var(--tr);}
.search-bar:focus-within{border-color:var(--acc);}
.search-bar input{flex:1;background:transparent;border:none;outline:none;padding:10px 14px;color:var(--tx);font-size:14px;font-family:var(--fb);}
.search-bar input::placeholder{color:var(--txm);}
.search-bar button{background:var(--acc);border:none;padding:0 16px;cursor:pointer;display:flex;align-items:center;color:#0a0a0f;transition:background var(--tr);}
.search-bar button:hover{background:#00c9aa;}
.header-actions{display:flex;align-items:center;gap:8px;margin-left:auto;}
.header-actions a,.header-actions button{display:flex;align-items:center;gap:7px;padding:9px 14px;border-radius:var(--r);font-size:13px;font-weight:600;color:var(--txm);background:transparent;border:1px solid transparent;cursor:pointer;transition:all var(--tr);font-family:var(--fb);}
.header-actions a:hover,.header-actions button:hover{background:var(--bgc);border-color:var(--brd);color:var(--tx);}
.cart-badge{background:var(--acc);color:#0a0a0f;font-size:11px;font-weight:800;min-width:18px;height:18px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;}
.main-nav{border-top:1px solid var(--brd);background:var(--bg2);}
.main-nav .wrap ul{display:flex;align-items:center;gap:0;list-style:none;padding:0;flex-wrap:nowrap;overflow:hidden;}
.main-nav .wrap ul::-webkit-scrollbar{display:none;}
.main-nav .wrap ul li a{display:flex;align-items:center;gap:6px;padding:9px 8px;font-size:12px;font-weight:500;color:var(--txm);border-radius:var(--r);transition:all var(--tr);white-space:nowrap;}
.main-nav .wrap ul li a:hover,.main-nav .wrap ul li a.active{color:var(--acc);background:rgba(0,229,195,.06);}
.main-nav .wrap ul li a.nav-accent{color:var(--acc);font-weight:700;}
.main-nav .wrap ul li a.nav-tienda{border:1px solid rgba(0,229,195,.3);background:rgba(0,229,195,.05);font-weight:700;}
.main-nav .wrap ul li a.nav-mayoristas{border:1px solid rgba(0,229,195,.3);background:rgba(0,229,195,.05);}
.vintago-menu-toggle{display:none;}

/* HERO CAROUSEL
 * Imagen ideal: 1600×700px | Mobile: 100vw × clamp(300px,56vw,700px)
 */
.hero-carousel{position:relative;width:100%;height:clamp(360px,52.5vw,700px);overflow:hidden;background:#080810;touch-action:pan-y;}
.hero-slide{position:absolute;inset:0;opacity:0;transition:opacity .7s ease;display:flex;align-items:center;}
.hero-slide.active{opacity:1;z-index:1;}
.hero-slide-bg{position:absolute;inset:0;display:block;}
.hero-slide-bg img{width:100%;height:100%;object-fit:contain;object-position:center;background:#080810;filter:brightness(1.04) saturate(1.06);}
.hero-slide-content{position:relative;z-index:2;display:flex;width:100%;max-width:none;align-items:flex-end;justify-content:flex-end;padding:0 48px 42px;}
.hero-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(0,229,195,.12);border:1px solid rgba(0,229,195,.3);color:var(--acc);font-size:12px;font-weight:700;padding:5px 13px;border-radius:100px;margin-bottom:18px;letter-spacing:.04em;}
.hero-slide-content h1{font-family:var(--fh);font-size:clamp(22px,3.2vw,52px);font-weight:800;line-height:1.1;letter-spacing:-.03em;color:var(--tx);margin-bottom:14px;}
.hero-slide-content h1 .accent{color:var(--acc);}
.hero-slide-content p{font-size:clamp(13px,1.3vw,17px);color:rgba(240,240,245,.75);line-height:1.6;margin-bottom:28px;max-width:440px;}
.hero-ctas{display:flex;gap:12px;flex-wrap:wrap;}
.btn-primary{display:inline-flex;align-items:center;gap:8px;background:var(--acc);color:#0a0a0f!important;font-weight:700;font-size:14px;padding:13px 24px;border-radius:var(--r);border:none;cursor:pointer;transition:all var(--tr);font-family:var(--fb);}
.btn-primary:hover{background:#00c9aa;transform:translateY(-1px);box-shadow:0 6px 24px rgba(0,229,195,.35);}
.btn-outline{display:inline-flex;align-items:center;gap:8px;background:transparent;color:var(--tx)!important;font-weight:600;font-size:14px;padding:12px 24px;border-radius:var(--r);border:1px solid rgba(240,240,245,.25);cursor:pointer;transition:all var(--tr);font-family:var(--fb);}
.btn-outline:hover{border-color:var(--acc);color:var(--acc)!important;}

/* TRUST STRIP */
.hero-strip{overflow:hidden;background:var(--bg2);border-bottom:1px solid var(--brd);padding:10px 0;}
.hero-strip-track{display:flex;gap:0;animation:stripScroll 22s linear infinite;width:max-content;}
.hero-strip-track .item{display:flex;align-items:center;gap:8px;font-size:12px;font-weight:500;color:var(--txm);padding:0 28px;white-space:nowrap;border-right:1px solid var(--brd);}
.hero-strip-track .item svg{color:var(--acc);}
@keyframes stripScroll{0%{transform:translateX(0);}100%{transform:translateX(-50%);}}

/* SECTIONS */
.section{padding:64px 0;}
.section-header{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:36px;flex-wrap:wrap;gap:12px;}
.section-eyebrow{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:var(--acc);text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px;}
.section-title{font-family:var(--fh);font-size:clamp(20px,3vw,30px);font-weight:800;color:var(--tx);letter-spacing:-.02em;}
.front-page-title{margin:32px auto 20px;max-width:1100px;padding:0 24px;font-family:var(--fh);font-size:clamp(2.2rem,4vw,4.2rem);font-weight:800;letter-spacing:-.04em;line-height:1.05;color:var(--tx);}
.section-link{display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--acc);transition:gap var(--tr);}
.section-link:hover{gap:10px;}

/* CATEGORÍAS */
.cat-carousel{display:block;position:relative;padding:0 4px;}
.cat-carousel::before,.cat-carousel::after{content:'';position:absolute;top:0;bottom:14px;width:56px;z-index:2;pointer-events:none;}
.cat-carousel::before{left:0;background:linear-gradient(90deg,var(--bg) 10%,transparent);}
.cat-carousel::after{right:0;background:linear-gradient(270deg,var(--bg) 10%,transparent);}
.cat-grid{display:flex;gap:14px;min-width:0;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;overscroll-behavior-inline:contain;scroll-snap-type:x proximity;padding:8px 2px 14px;}
.cat-grid::-webkit-scrollbar{display:none;}
.cat-card{flex:0 0 clamp(210px,24vw,280px);scroll-snap-align:start;animation:categoryFloat 5.8s ease-in-out infinite;}
.cat-card:nth-child(2n){animation-delay:-1.4s;}
.cat-card:nth-child(3n){animation-delay:-2.8s;}
@keyframes categoryFloat{0%,100%{transform:translateY(0);}50%{transform:translateY(-5px);}}
.cat-carousel-arrow{display:flex;position:absolute;top:calc(50% - 7px);z-index:3;width:42px;height:42px;align-items:center;justify-content:center;background:rgba(16,16,25,.92);border:1px solid var(--brd);border-radius:50%;color:var(--tx);cursor:pointer;transform:translateY(-50%);transition:all var(--tr);backdrop-filter:blur(6px);box-shadow:0 6px 18px rgba(0,0,0,.35);}
.cat-carousel-prev{left:2px;}
.cat-carousel-next{right:2px;}
.cat-carousel-arrow:hover{border-color:var(--acc);color:var(--acc);box-shadow:0 0 0 4px rgba(0,229,195,.12);}
.cat-card{background:linear-gradient(160deg,rgba(255,255,255,.05),rgba(255,255,255,.015)),var(--bgc);border:1px solid var(--brd);border-radius:var(--rl);padding:0;position:relative;transition:all var(--tr);overflow:hidden;display:flex;flex-direction:column;}
.cat-card-image{position:relative;aspect-ratio:1/1;background:#fff;}
.cat-card-image::after{display:none;}
.cat-card-image img{width:100%;height:100%;object-fit:contain;opacity:1;transition:transform .5s ease;}
.cat-card:hover .cat-card-image img{transform:scale(1.06);}
.cat-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,229,195,.04),transparent);opacity:0;transition:opacity var(--tr);}
.cat-card:hover{border-color:var(--acc);transform:translateY(-3px);box-shadow:0 10px 40px rgba(0,229,195,.1);}
.cat-card:hover::before{opacity:1;}
.cat-card-icon{width:44px;height:44px;background:rgba(0,229,195,.08);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;color:var(--acc);transition:background var(--tr);}
.cat-card:hover .cat-card-icon{background:rgba(0,229,195,.15);}
.cat-card h3{position:relative;z-index:1;font-family:var(--fh);font-size:16px;font-weight:700;color:var(--tx);margin:0;padding:16px 14px 18px;text-align:center;text-transform:uppercase;line-height:1.25;}
.cat-count,.subcats,.cat-card .arrow{display:none;}
.subcat-tag{font-size:11px;color:var(--txm);background:var(--bgh);border-radius:4px;padding:3px 8px;}
.arrow{position:absolute;top:20px;right:20px;color:var(--txm);transition:all var(--tr);}
.cat-card:hover .arrow{color:var(--acc);transform:translateX(3px);}

/* SPLINE SECTION */
.spline-section{position:relative;width:100%;overflow:hidden;background:#060610;}
spline-viewer{width:100%;height:clamp(340px,42vw,560px);display:block;}
.spline-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(6,6,16,.93) 0%,rgba(6,6,16,.55) 48%,rgba(6,6,16,.1) 100%);pointer-events:none;z-index:2;}
.spline-content{position:absolute;top:50%;left:0;transform:translateY(-50%);z-index:3;padding:0 64px;max-width:560px;}
.spline-eyebrow{display:inline-flex;align-items:center;gap:6px;background:rgba(0,229,195,.1);border:1px solid rgba(0,229,195,.25);color:var(--acc);font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;margin-bottom:16px;letter-spacing:.06em;text-transform:uppercase;}
.spline-content h2{font-family:var(--fh);font-size:clamp(24px,3.2vw,44px);font-weight:800;line-height:1.1;letter-spacing:-.03em;color:var(--tx);margin-bottom:14px;}
.spline-content h2 em{font-style:normal;color:var(--acc);}
.spline-content p{font-size:15px;color:rgba(240,240,245,.65);line-height:1.7;margin-bottom:20px;}
.spline-features{display:flex;flex-direction:column;gap:8px;margin-bottom:24px;}
.spline-feature{display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(240,240,245,.75);}
.spline-feature::before{content:'✓';color:var(--acc);font-weight:800;font-size:15px;flex-shrink:0;}
.spline-btn{display:inline-flex;align-items:center;gap:8px;background:var(--acc);color:#0a0a0f!important;font-weight:700;font-size:14px;padding:13px 28px;border-radius:var(--r);transition:all var(--tr);}
.spline-btn:hover{background:#00c9aa;transform:translateY(-1px);box-shadow:0 8px 32px rgba(0,229,195,.35);}

/* PRODUCTS */
.product-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px;}
.cart-bar,.cart-float-btn,.vintago-side-cart__trigger{display:none!important;}
.product-card{background:linear-gradient(145deg,rgba(255,255,255,.065),rgba(255,255,255,.018));border:1px solid rgba(255,255,255,.12);border-radius:16px;overflow:hidden;transition:transform .3s ease,border-color .3s ease,box-shadow .3s ease;opacity:0;transform:translateY(16px);box-shadow:0 14px 34px rgba(0,0,0,.2);}
.product-card.in-view{opacity:1;transform:translateY(0);}
.product-card:hover{border-color:rgba(0,229,195,.7);box-shadow:0 18px 42px rgba(0,229,195,.14);transform:translateY(-6px);}
.product-card[data-product-url]{cursor:pointer;}
.product-thumb{position:relative;aspect-ratio:1;background:rgba(17,17,24,.72);overflow:hidden;}
  .product-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;padding:14px;transition:opacity .35s ease,transform .5s ease;opacity:0;}
.product-thumb img.active{opacity:1;}
.product-card:hover .product-thumb img.active{transform:scale(1.07);}
.product-dots{position:absolute;bottom:8px;left:50%;transform:translateX(-50%);display:flex;gap:4px;z-index:2;}
.product-dots span{width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,.35);transition:background var(--tr);}
.product-dots span.active{background:var(--acc);}
.cat-label{font-size:10px;font-weight:700;color:var(--acc);text-transform:uppercase;letter-spacing:.07em;padding:12px 14px 0;}
.product-card h3{font-family:'Inter',system-ui,sans-serif;font-size:clamp(.81rem,1vw,1.03rem);font-weight:600;color:rgba(255,255,255,.92);padding:5px 16px 8px;line-height:1.35;letter-spacing:-.03em;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.price-row{padding:0 16px 14px;display:flex;align-items:baseline;gap:8px;}
.price{font-size:17px;font-weight:800;color:var(--acc);}
.price-old{font-size:12px;color:var(--txm);text-decoration:line-through;}
.add-to-cart-btn{display:flex;align-items:center;justify-content:center;gap:7px;width:calc(100% - 32px);margin:0 16px 16px;background:rgba(0,229,195,.08);border:1px solid rgba(0,229,195,.3);color:var(--acc);font-size:12px;font-weight:700;padding:11px;border-radius:999px;cursor:pointer;transition:all var(--tr);font-family:var(--fb);}
.add-to-cart-btn:hover,.add-to-cart-btn.added{background:var(--acc);color:#0a0a0f;border-color:var(--acc);}

/* TRUST BAR */
.trust-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--brd);border-top:1px solid var(--brd);border-bottom:1px solid var(--brd);margin:0 0 28px;}
.trust-item{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px;padding:28px 20px;background:var(--bg2);transition:background var(--tr);}
.trust-item:hover{background:var(--bgc);}
.trust-item svg{color:var(--acc);}
.trust-item strong{font-size:14px;font-weight:700;color:var(--tx);}
.trust-item span{font-size:12px;color:var(--txm);}

/* WHOLESALE */
.wholesale-cta{background:linear-gradient(135deg,#111118 0%,#16161f 100%);border:1px solid var(--brd);border-radius:20px;padding:56px 48px;display:grid;grid-template-columns:1fr auto;gap:48px;align-items:center;margin:0 0 28px;}
.wholesale-cta h2{font-family:var(--fh);font-size:clamp(22px,3vw,34px);font-weight:800;color:var(--tx);margin-bottom:14px;letter-spacing:-.02em;}
.wholesale-cta p{font-size:15px;color:var(--txm);line-height:1.7;margin-bottom:20px;}
.benefits{display:grid;grid-template-columns:1fr 1fr;gap:10px 20px;}
.benefit{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--txm);}
.benefit svg{color:var(--acc);flex-shrink:0;}

.coupon-section{margin:0 0 32px;}

/* CARRITO BARRA */
.cart-bar{position:fixed;top:76px;bottom:0;left:auto;right:0;width:min(420px,100%);z-index:300;background:rgba(16,16,25,.98);border-left:1px solid var(--brd);backdrop-filter:blur(14px);transform:translateX(100%);transition:transform .35s cubic-bezier(.4,0,.2,1);}
.cart-bar.open{transform:translateX(0);}
.cart-bar-inner{height:100%;max-width:none;margin:0;padding:24px;display:flex;flex-direction:column;align-items:stretch;gap:16px;}
.cart-bar-items{flex:1;display:flex;flex-direction:column;align-items:stretch;gap:10px;overflow-y:auto;scrollbar-width:none;}
.cart-bar-items::-webkit-scrollbar{display:none;}
.cart-bar-item{display:flex;align-items:center;gap:10px;background:var(--bgc);border:1px solid var(--brd);border-radius:10px;padding:8px 12px;flex-shrink:0;min-width:0;}
.cart-bar-item-img{width:40px;height:40px;border-radius:6px;object-fit:cover;background:var(--bg2);}
.cart-bar-item-info{flex:1;min-width:0;}
.cart-bar-item-info .name{font-size:12px;font-weight:600;color:var(--tx);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.cart-bar-item-info .meta{font-size:11px;color:var(--txm);}
.cart-bar-item-remove{background:none;border:none;color:var(--txm);cursor:pointer;padding:4px;border-radius:4px;display:flex;align-items:center;transition:color var(--tr);}
.cart-bar-item-remove:hover{color:#e74c3c;}
.cart-bar-summary{display:flex;flex-direction:row;align-items:baseline;justify-content:space-between;gap:2px;flex-shrink:0;}
.cart-bar-summary .subtotal-label{font-size:11px;color:var(--txm);}
.cart-bar-summary .subtotal-amt{font-size:18px;font-weight:800;color:var(--acc);}
.cart-bar-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;flex-shrink:0;}
.btn-outline-sm{display:flex;align-items:center;gap:6px;padding:10px 16px;border-radius:var(--r);border:1px solid var(--brd);color:var(--txm);font-size:13px;font-weight:600;background:transparent;cursor:pointer;transition:all var(--tr);font-family:var(--fb);white-space:nowrap;}
.btn-outline-sm:hover{border-color:var(--acc);color:var(--acc);}
.cart-bar-close{background:none;border:none;color:var(--txm);cursor:pointer;padding:8px;border-radius:var(--r);display:flex;align-items:center;transition:color var(--tr);flex-shrink:0;}
.cart-bar-close:hover{color:var(--tx);}
.cart-float-btn{position:fixed;bottom:20px;right:20px;z-index:290;background:var(--acc);color:#0a0a0f;width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:none;box-shadow:0 4px 20px rgba(0,229,195,.4);transition:all var(--tr);}
.cart-float-btn:hover{transform:scale(1.08);}
.cart-float-btn .cart-badge{position:absolute;top:-4px;right:-4px;}
.cart-float-btn.hidden{opacity:0;pointer-events:none;}

/* COUPON */
.coupon-section{background:var(--bgc);border:1px solid var(--brd);border-radius:20px;padding:48px;text-align:center;margin-bottom:64px;}
.coupon-section h2{font-family:var(--fh);font-size:26px;font-weight:800;color:var(--tx);margin-bottom:10px;}
.coupon-section p{font-size:15px;color:var(--txm);margin-bottom:28px;}
.coupon-form{display:flex;gap:10px;max-width:420px;margin:0 auto 20px;}
.coupon-form input{flex:1;background:var(--bg);border:1px solid var(--brd);border-radius:var(--r);color:var(--tx);padding:12px 16px;font-size:14px;font-family:var(--fb);}
.coupon-form input:focus{outline:none;border-color:var(--acc);}
.coupon-result{background:rgba(0,229,195,.06);border:1px solid rgba(0,229,195,.2);border-radius:var(--rl);padding:24px;max-width:380px;margin:0 auto;display:none;}
.coupon-result.show{display:block;animation:fadeIn .4s ease;}
.coupon-result .label{font-size:11px;font-weight:700;color:var(--txm);text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px;}
.coupon-result .code{font-size:24px;font-weight:800;color:var(--acc);letter-spacing:.08em;margin-bottom:6px;}
.coupon-result .desc{font-size:13px;color:var(--txm);}
@keyframes fadeIn{from{opacity:0;transform:scale(.95);}to{opacity:1;transform:scale(1);}}

/* FOOTER */
.site-footer{background:var(--bg2);border-top:1px solid var(--brd);}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;padding:56px 0 40px;}
.footer-brand p{font-size:13px;color:var(--txm);line-height:1.7;margin-top:12px;max-width:260px;}
.footer-col h4{font-size:12px;font-weight:700;color:var(--tx);text-transform:uppercase;letter-spacing:.08em;margin-bottom:16px;}
.footer-col ul{list-style:none;display:flex;flex-direction:column;gap:10px;}
.footer-col ul li a{font-size:13px;color:var(--txm);transition:color var(--tr);}
.footer-col ul li a:hover{color:var(--acc);}
.footer-col input[type="email"]{width:100%;background:var(--bgc);border:1px solid var(--brd);border-radius:var(--r);color:var(--tx);padding:10px 14px;font-size:13px;margin-bottom:8px;font-family:var(--fb);}
.footer-col input[type="email"]:focus{outline:none;border-color:var(--acc);}
.footer-bottom{border-top:1px solid var(--brd);padding:20px 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;font-size:12px;color:var(--txm);}
.footer-bottom a{color:var(--txm);transition:color var(--tr);}
.footer-bottom a:hover{color:var(--acc);}

/* POPUP */
.popup-overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;opacity:0;visibility:hidden;transition:all .3s ease;}
.popup-overlay.active{opacity:1;visibility:visible;}
.popup-box{background:var(--bgc);border:1px solid var(--brd);border-radius:20px;padding:40px;max-width:420px;width:100%;position:relative;transform:scale(.9);transition:transform .3s ease;}
.popup-overlay.active .popup-box{transform:scale(1);}
.popup-close{position:absolute;top:14px;right:14px;background:var(--bgh);border:none;color:var(--txm);width:30px;height:30px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;}
.popup-close:hover{color:var(--tx);}
.popup-badge{display:inline-block;background:rgba(0,229,195,.1);color:var(--acc);border:1px solid rgba(0,229,195,.25);font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;margin-bottom:14px;letter-spacing:.05em;text-transform:uppercase;}
.popup-box h3{font-family:var(--fh);font-size:22px;font-weight:800;color:var(--tx);margin-bottom:8px;}
.popup-box>p{font-size:14px;color:var(--txm);margin-bottom:22px;line-height:1.6;}
.popup-form{display:flex;flex-direction:column;gap:10px;}
.popup-form input{background:var(--bg);border:1px solid var(--brd);border-radius:var(--r);color:var(--tx);padding:12px 14px;font-size:14px;font-family:var(--fb);}
.popup-form input:focus{outline:none;border-color:var(--acc);box-shadow:0 0 0 3px rgba(0,229,195,.1);}
.popup-form input::placeholder{color:#555566;}
.popup-submit{background:var(--acc);color:#0a0a0f;font-weight:700;font-size:15px;padding:14px;border-radius:var(--r);border:none;cursor:pointer;transition:all .2s;font-family:var(--fb);}
.popup-submit:hover{background:#00c9aa;}
.popup-skip{text-align:center;margin-top:10px;}
.popup-skip a{font-size:12px;color:#555566;cursor:pointer;}

/* RESPONSIVE */
@media(max-width:1024px){.product-grid{grid-template-columns:repeat(3,1fr);}.cat-card{flex-basis:280px;}.footer-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:768px){.hero-slide-content{padding:0 24px;}.search-bar{display:none;}.product-grid{grid-template-columns:repeat(2,1fr);gap:12px;}.trust-bar{grid-template-columns:repeat(2,1fr);}.wholesale-cta{grid-template-columns:1fr;padding:32px 24px;}.spline-content{padding:0 24px;max-width:100%;}.footer-grid{grid-template-columns:1fr;}.cart-bar{top:64px;width:100%;}.cart-bar-items{display:flex;}}
@media(max-width:767px){.vintago-menu-toggle{display:inline-flex;min-height:42px;align-items:center;justify-content:center;border:1px solid var(--brd);border-radius:var(--r);padding:8px 12px;background:var(--bgc);color:var(--tx);cursor:pointer;font-size:12px;font-weight:700}.site-header .main-nav{display:none}.site-header .main-nav.is-open{display:block}.site-header .main-nav .wrap ul{flex-direction:column;align-items:stretch;padding:8px 0;flex-wrap:nowrap;overflow:visible}.site-header .main-nav .wrap ul li a{display:block;padding:12px 14px}.site-header .header-inner{flex-wrap:wrap}.site-header .header-actions{margin-left:auto}.site-header .search-bar{order:3;width:100%;max-width:none}}
@media(max-width:480px){.cat-card{flex-basis:240px;}.product-grid{grid-template-columns:repeat(2,1fr);gap:10px;}.header-actions a span,.header-actions button span{display:none;}}
@media(max-width:767px){.hero-carousel{height:min(163vw,700px);min-height:0}.hero-slide-bg img{object-fit:contain;object-position:center;background:#080810}.hero-slide-content{align-self:flex-end;width:100%;max-width:100%;padding:0 24px 54px}.hero-ctas .btn-primary,.hero-ctas .btn-outline{min-height:44px;padding:10px 16px}}
@media(max-width:767px){.hero-slide-content{justify-content:center;padding:0 22px 28px}.hero-ctas{justify-content:center}.hero-ctas .btn-primary,.hero-ctas .btn-outline{font-size:12px;padding:9px 13px}}
@media(prefers-reduced-motion:reduce){.hero-slide,.hero-slide-bg img,.product-card,.hero-strip-track{animation:none!important;transition:none!important}.hero-strip-track{transform:none!important}}
</style>
<?php if (false): ?>
<div class="utility-bar"><div class="wrap"><div class="left"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg><span>Envíos a toda Colombia</span></div><div class="right"><a href="https://wa.me/573127558773" target="_blank" rel="noopener"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>+57 312 755 8773</a><a href="<?php echo esc_url($myaccount_url); ?>">Mi cuenta</a></div></div></div>

<header class="site-header">
  <div class="wrap header-inner">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">VINTAG<span class="dot">.</span> BODEGA</a>
    <form class="search-bar" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input type="text" name="s" placeholder="Buscar audífonos, smartwatch, cocinas...">
      <input type="hidden" name="post_type" value="product">
      <button type="submit" aria-label="Buscar"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></button>
    </form>
    <div class="header-actions">
      <a href="<?php echo esc_url($myaccount_url); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Cuenta</span></a>
      <button type="button" id="cartToggle" data-vintago-cart-open aria-label="Ver carrito"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg><span>Carrito</span><span class="cart-badge" id="cartCount"><?php echo esc_html($cart_count); ?></span></button>
    </div>
  </div>
  <button class="vintago-menu-toggle" type="button" aria-expanded="false" aria-controls="front-main-nav" aria-label="Abrir menú">&#9776;</button>
  <nav class="main-nav" id="front-main-nav"><div class="wrap"><ul>
    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="active"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Inicio</a></li><li><a href="<?php echo esc_url($shop_url); ?>" class="nav-tienda">Tienda</a></li>
    <?php foreach($bvg_parents as $pname=>$pdata): ?>
    <li><a href="<?php echo esc_url(bvg_cat_url($pname)); ?>"><?php echo esc_html($pname); ?></a></li>
    <?php endforeach; ?>
    <li><a href="<?php echo esc_url($mayoristas_url); ?>" class="nav-accent">Mayoristas</a></li>
  </ul></div></nav>
</header>
<script>(function(){var button=document.querySelector('.vintago-menu-toggle'),nav=document.getElementById('front-main-nav');if(button&&nav){button.addEventListener('click',function(){var open=button.getAttribute('aria-expanded')==='true';button.setAttribute('aria-expanded',String(!open));nav.classList.toggle('is-open',!open);});}})();</script>
<?php endif; ?>

<!-- HERO CAROUSEL -->
<div class="hero-carousel" id="heroCarousel">
  <?php foreach($hero_slides as $si=>$slide): ?>
  <div class="hero-slide <?php echo $si===0?'active':''; ?>">
    <picture class="hero-slide-bg">
      <source media="(max-width: 767px)" srcset="<?php echo esc_url($slide['mobile']); ?>">
      <img src="<?php echo esc_url($slide['img']); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($slide['title'])); ?>" width="1730" height="909" <?php echo $si===0?'fetchpriority="high" loading="eager"':'loading="lazy"'; ?>>
    </picture>
  </div>
  <?php endforeach; ?>
</div>

<div class="hero-strip"><div class="hero-strip-track">
  <?php $strip=array('Envíos a toda Colombia','Garantía real','Despacho en 24h desde Bogotá','Atención por WhatsApp','Pagos 100% seguros');
  for($r=0;$r<2;$r++) foreach($strip as $it): ?>
  <span class="item"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg><?php echo esc_html($it); ?></span>
  <?php endforeach; ?>
</div></div>

<main>
  <h1 class="front-page-title">Bodega Vintago: tecnología, hogar y mayoristas en Bogotá</h1>
  <section class="section" id="categorias"><div class="wrap">
  <div class="section-header">
    <div><p class="section-eyebrow"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Categorías</p><h2 class="section-title">Explora nuestro catálogo</h2></div>
    <a href="<?php echo esc_url($shop_url); ?>" class="section-link">Ver todo <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
  </div>
  <div class="cat-carousel">
    <button class="cat-carousel-arrow cat-carousel-prev" type="button" aria-label="Categoría anterior">&#8592;</button>
    <div class="cat-grid">
    <?php foreach($bvg_parents as $pname=>$pdata):
      $parent_term=get_term_by('name',$pname,'product_cat');
      $parent_image=$parent_term?wp_get_attachment_image_url((int)get_term_meta($parent_term->term_id,'thumbnail_id',true),'medium_large'):'';
      if(!$parent_image && function_exists('wc_placeholder_img_src')) $parent_image=wc_placeholder_img_src();
    ?>
    <a href="<?php echo esc_url(bvg_cat_url($pname)); ?>" class="cat-card">
      <div class="cat-card-image"><img src="<?php echo esc_url($parent_image); ?>" alt="<?php echo esc_attr($pname); ?>" loading="lazy"></div>
      <h3><?php echo esc_html($pname); ?></h3>
    </a>
    <?php endforeach; ?>
    </div>
    <button class="cat-carousel-arrow cat-carousel-next" type="button" aria-label="Siguiente categoría">&#8594;</button>
  </div>
</div></section>

<!-- BANNER SPLINE 3D -->
<section class="spline-section">
  <spline-viewer url="https://prod.spline.design/d853H7hxjsSd8oW0/scene.splinecode" loading="lazy"></spline-viewer>
  <div class="spline-overlay"></div>
  <div class="spline-content">
    <span class="spline-eyebrow"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>Producto estrella</span>
    <h2>El sonido que <em>cambia</em> tu experiencia</h2>
    <p>Audio inalámbrico premium con cancelación de ruido activa y hasta 30h de batería. El gadget más vendido de la bodega.</p>
    <div class="spline-features">
      <span class="spline-feature">Cancelación de ruido activa (ANC)</span>
      <span class="spline-feature">Hasta 30h de reproducción</span>
      <span class="spline-feature">Conexión multipunto — 2 dispositivos</span>
      <span class="spline-feature">Garantía real + despacho en 24h</span>
    </div>
    <a href="<?php echo esc_url(bvg_cat_url('Tecnología y Gadgets')); ?>" class="spline-btn">Ver todos los gadgets <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
  </div>
</section>

<!-- PRODUCTOS -->
<section class="section trend-section"><div class="wrap">
  <div class="section-header">
    <div><p class="section-eyebrow"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>Los más vendidos</p><h2 class="section-title">Tendencia en la bodega</h2></div>
    <a href="<?php echo esc_url($shop_url); ?>" class="section-link">Ver todos <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
  </div>
  <div class="product-grid">
    <?php
    $trend_ids=array_filter(array_map('absint',explode(',',(string)get_theme_mod('vintago_trending_product_ids',''))));
    $products=array();
    if(!empty($trend_ids)){
      $products=wc_get_products(array('status'=>'publish','include'=>$trend_ids,'limit'=>8,'orderby'=>'include','order'=>'DESC'));
    }
    if(count($products)<8){
      $featured=wc_get_products(array('status'=>'publish','featured'=>true,'limit'=>8,'orderby'=>'date','order'=>'DESC','exclude'=>array_map(function($item){return $item->get_id();},$products)));
      $products=array_merge($products,$featured);
    }
    if(count($products)<8){
      $popular=wc_get_products(array('status'=>'publish','limit'=>8,'orderby'=>'popularity','order'=>'DESC','exclude'=>array_map(function($item){return $item->get_id();},$products)));
      $products=array_merge($products,$popular);
    }
    if(count($products)<8){
      $recent=wc_get_products(array('status'=>'publish','limit'=>8,'orderby'=>'date','order'=>'DESC','exclude'=>array_map(function($item){return $item->get_id();},$products),'return'=>'objects'));
      $products=array_merge($products,$recent);
    }
    $products=array_slice($products,0,8);
    foreach($products as $trend_index=>$product):
      $image_ids=array_filter(array_merge(array($product->get_image_id()),$product->get_gallery_image_ids()));
      $cats=wp_get_post_terms($product->get_id(),'product_cat',array('fields'=>'names'));
      $cat_label=!empty($cats)?$cats[0]:'';
    ?>
    <div class="product-card" data-product-url="<?php echo esc_url(get_permalink($product->get_id())); ?>" tabindex="0" role="link" style="--trend-index:<?php echo esc_attr($trend_index); ?>;">
      <div class="product-thumb">
        <?php foreach($image_ids as $i=>$img_id): ?>
        <img src="<?php echo esc_url(wp_get_attachment_image_url($img_id,'medium')); ?>" class="<?php echo $i===0?'active':''; ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
        <?php endforeach; ?>
        <?php $in_wishlist = vintago_is_wishlisted($product->get_id()); ?>
        <button type="button"
            class="product-card__wishlist <?php echo $in_wishlist ? 'is-active' : ''; ?>"
            data-product_id="<?php echo esc_attr($product->get_id()); ?>"
            aria-label="<?php echo $in_wishlist ? 'Quitar de favoritos' : 'Añadir a favoritos'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
        </button>
        <?php if(count($image_ids)>1): ?><div class="product-dots"><?php foreach($image_ids as $i=>$img_id): ?><span class="<?php echo $i===0?'active':''; ?>"></span><?php endforeach; ?></div><?php endif; ?>
      </div>
      <p class="cat-label"><?php echo esc_html($cat_label); ?></p>
      <h3><?php echo esc_html($product->get_name()); ?></h3>
      <div class="price-row">
        <?php if($product->is_on_sale()): ?>
        <span class="price"><?php echo wp_kses_post(wc_price($product->get_sale_price())); ?></span>
        <span class="price-old"><?php echo wp_kses_post(wc_price($product->get_regular_price())); ?></span>
        <?php else: ?>
        <span class="price"><?php echo wp_kses_post(wc_price($product->get_price())); ?></span>
        <?php endif; ?>
      </div>
      <a class="add-to-cart-btn" href="<?php echo esc_url(get_permalink($product->get_id())); ?>" rel="nofollow">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12h18"/><path d="M12 1v18"/></svg>
        Ver producto
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div></section>

<div class="trust-bar">
  <div class="trust-item"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg><strong>Envíos a todo el país</strong><span>Cobertura nacional, 24h en Bogotá</span></div>
  <div class="trust-item"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><strong>Garantía real</strong><span>Seguridad y confianza en cada compra</span></div>
  <div class="trust-item"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg><strong>Atención por WhatsApp</strong><span>Te asesoramos siempre</span></div>
  <div class="trust-item"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><strong>Bodega en Bogotá</strong><span>Retiro y despacho directo</span></div>
</div>

<div class="wrap">
<section class="wholesale-cta">
  <div>
    <h2>Convierte tecnología en tu propio negocio.</h2>
    <p>Compra al por mayor, con precios de bodega, catálogo de más de 900 referencias y atención directa por WhatsApp.</p>
    <div class="benefits">
      <div class="benefit"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Compra al detal o al por mayor</div>
      <div class="benefit"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Productos de alta rotación</div>
      <div class="benefit"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Catálogo con tendencias reales</div>
      <div class="benefit"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Envíos a todo el país</div>
    </div>
  </div>
  <a href="<?php echo esc_url($mayoristas_url); ?>" class="btn-primary">Quiero ser mayorista <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
</section>

<section class="coupon-section">
  <h2>Gana tu cupón de bodega</h2>
  <p>Déjanos tu correo y te asignamos un cupón al instante: envío gratis o 10% de descuento.</p>
  <form class="coupon-form" id="couponForm"><input type="email" required placeholder="tucorreo@ejemplo.com"><button class="btn-primary" type="submit">Obtener mi cupón</button></form>
  <div class="coupon-result" id="couponResult"><p class="label">Tu cupón</p><p class="code" id="couponCode">-</p><p class="desc" id="couponDesc">-</p></div>
</section>
</div>
</main>

<?php if (false): ?>
<footer class="site-footer"><div class="wrap">
  <div class="footer-grid">
    <div class="footer-brand"><a href="<?php echo esc_url(home_url('/')); ?>" class="brand" style="font-size:18px;">VINTAG<span class="dot">.</span> BODEGA</a><p>Tecnología para comprar, usar y emprender. Bodega mayorista en Bogotá, Colombia.</p></div>
    <div class="footer-col"><h4>Tienda</h4><ul><?php foreach($bvg_parents as $pname=>$pdata): ?><li><a href="<?php echo esc_url(bvg_cat_url($pname)); ?>"><?php echo esc_html($pname); ?></a></li><?php endforeach; ?></ul></div>
    <div class="footer-col"><h4>Legal & Soporte</h4><ul>
      <li><a href="<?php echo esc_url($mayoristas_url); ?>">Mayoristas</a></li>
      <li><a href="<?php echo esc_url($url_devoluciones); ?>">Política de Devoluciones</a></li>
      <li><a href="<?php echo esc_url($url_privacidad); ?>">Política de Privacidad</a></li>
      <li><a href="https://wa.me/573127558773" target="_blank" rel="noopener">WhatsApp Soporte</a></li>
    </ul>
    <p style="font-size:11px;color:var(--txm);margin-top:20px;line-height:1.6;">Diseñado y desarrollado por<br><a href="https://www.linkedin.com/in/julianrojasgonzalez/" target="_blank" rel="noopener" style="color:var(--acc);">Julian Rojas</a></p>
    </div>
    <div class="footer-col"><h4>Recibe nuestras ofertas</h4><input type="email" placeholder="Correo electrónico"><button class="btn-primary" style="width:100%;justify-content:center;">Suscribirme</button></div>
  </div>
  <div class="footer-bottom"><span>&copy; <?php echo esc_html(date('Y')); ?> Vintago Bodega. Todos los derechos reservados.</span><span>Bogotá, Colombia</span></div>
</div></footer>
<?php endif; ?>

<!-- POPUP -->
<div class="popup-overlay" id="vbPopup">
  <div class="popup-box">
    <button class="popup-close" id="vbPopupClose">×</button>
    <span class="popup-badge">🎁 Oferta exclusiva</span>
    <h3>Obtén tu cupón de bodega</h3>
    <p>Déjanos tu correo y te enviamos un cupón al instante: envío gratis o 10% de descuento en tu primer pedido.</p>
    <div class="popup-form">
      <input type="email" id="vbEmail" placeholder="tu@correo.com">
      <input type="tel" id="vbWa" placeholder="WhatsApp (opcional)">
      <button class="popup-submit" id="vbPopupBtn">Obtener mi cupón →</button>
    </div>
    <div class="popup-skip"><a id="vbPopupSkip">No gracias, continúo sin descuento</a></div>
  </div>
</div>

<script>
(function(){
/* Carrusel */
try{
  var slides=document.querySelectorAll('.hero-slide'),cur=0,timer;
  function goTo(n){if(!slides.length)return;slides[cur].classList.remove('active');cur=(n+slides.length)%slides.length;slides[cur].classList.add('active');}
  function startTimer(){clearInterval(timer);timer=setInterval(function(){goTo(cur+1);},5500);}
  var hero=document.getElementById('heroCarousel'),touchStartX=0,touchStartY=0;
  if(hero){hero.addEventListener('touchstart',function(e){touchStartX=e.changedTouches[0].clientX;touchStartY=e.changedTouches[0].clientY;},{passive:true});hero.addEventListener('touchend',function(e){var dx=e.changedTouches[0].clientX-touchStartX,dy=e.changedTouches[0].clientY-touchStartY;if(Math.abs(dx)>50&&Math.abs(dx)>Math.abs(dy)){goTo(cur+(dx<0?1:-1));startTimer();}},{passive:true});}
  if(slides.length>1)startTimer();
}catch(err){console.error('Vintago hero carousel error:',err);}

/* Scroll reveal */
var io=new IntersectionObserver(function(entries){entries.forEach(function(e,i){if(e.isIntersecting){setTimeout(function(){e.target.classList.add('in-view');},i*60);io.unobserve(e.target);}});},{threshold:.12});
document.querySelectorAll('.product-card').forEach(function(c){io.observe(c);});

/* Imagen hover carrusel */
document.querySelectorAll('.product-card').forEach(function(card){
  var imgs=card.querySelectorAll('.product-thumb img'),dots2=card.querySelectorAll('.product-dots span');
  if(imgs.length<2)return;
  var idx=0,iv=null;
  card.addEventListener('mouseenter',function(){iv=setInterval(function(){imgs[idx].classList.remove('active');if(dots2[idx])dots2[idx].classList.remove('active');idx=(idx+1)%imgs.length;imgs[idx].classList.add('active');if(dots2[idx])dots2[idx].classList.add('active');},650);});
  card.addEventListener('mouseleave',function(){clearInterval(iv);imgs.forEach(function(im,i){im.classList.toggle('active',i===0);});dots2.forEach(function(d,i){d.classList.toggle('active',i===0);});idx=0;});
});

/* Toda la tarjeta abre el producto; el botón conserva su acción propia. */
document.querySelectorAll('.product-card[data-product-url]').forEach(function(card){
  function openProduct(){window.location.href=card.getAttribute('data-product-url');}
  card.addEventListener('click',function(event){if(!event.target.closest('.add-to-cart-btn'))openProduct();});
  card.addEventListener('keydown',function(event){if(event.key==='Enter'||event.key===' '){event.preventDefault();openProduct();}});
});

/* Carrito barra oculto: WooCommerce y el drawer lateral son la fuente única del carrito. */
var cartBar=document.getElementById('cartBar'),cartBarClose=document.getElementById('cartBarClose'),cartToggle=document.getElementById('cartToggle'),cartFloat=document.getElementById('cartFloatBtn');
if(cartBar){cartBar.style.display='none';}
if(cartFloat){cartFloat.style.display='none';}
if(cartToggle){cartToggle.addEventListener('click',function(){
  if(typeof jQuery !== 'undefined'){
    jQuery('[data-vintago-cart-open]').trigger('click');
  }
});}

/* Popup */
var PK='vintago_popup_v3',popupDismissed=parseInt(localStorage.getItem(PK)||'0',10),forcePopup=new URLSearchParams(window.location.search).get('mostrar_popup')==='1';
var popupExpired=!popupDismissed||(Date.now()-popupDismissed>604800000);
if(forcePopup||popupExpired){
  var po=document.getElementById('vbPopup'),pc=document.getElementById('vbPopupClose'),ps=document.getElementById('vbPopupSkip'),pb=document.getElementById('vbPopupBtn');
  function closePop(){po.classList.remove('active');localStorage.setItem(PK,String(Date.now()));}
  setTimeout(function(){po.classList.add('active');},3500);
  pc.addEventListener('click',closePop);ps.addEventListener('click',closePop);
  po.addEventListener('click',function(e){if(e.target===po)closePop();});
  pb.addEventListener('click',function(){var em=document.getElementById('vbEmail').value.trim(),wa=document.getElementById('vbWa').value.trim();if(!em&&!wa){document.getElementById('vbEmail').style.borderColor='#e74c3c';return;}pb.textContent='✓ ¡Listo!';pb.style.background='#0F6E56';setTimeout(closePop,1800);});
}

/* Cupón */
var cf=document.getElementById('couponForm');
if(cf)cf.addEventListener('submit',function(e){e.preventDefault();var r=Math.random()<.5;document.getElementById('couponCode').textContent=r?'BODEGA-ENVIOGRATIS':'BODEGA10';document.getElementById('couponDesc').textContent=r?'Envío gratis en tu próxima compra.':'10% de descuento en tu próxima compra.';var el=document.getElementById('couponResult');el.classList.remove('show');void el.offsetWidth;el.classList.add('show');});
})();
</script>
<?php get_footer(); ?>