<?php
/*
 * Template Name: Página Mayoristas
 * Description: Página para mayoristas y revendedores de Bodega Vintago
 */
get_header();
$wholesale_pdf_url = get_theme_mod('vintago_wholesale_pdf_url', '');
?>
<style>
* { box-sizing: border-box; }
.vb-page { background: #0a0a0f; color: #f0f0f5; font-family: 'Inter', system-ui, sans-serif; min-height: 100vh; padding: 0 0 80px; }
.vb-section { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
.vb-eyebrow { font-size: 11px; font-weight: 700; color: #00e5c3; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }

/* HERO */
.vm-hero { padding: 80px 24px 72px; text-align: center; max-width: 720px; margin: 0 auto; }
.vm-hero h1 { font-size: clamp(28px,6vw,52px); font-weight: 800; letter-spacing: -0.03em; line-height: 1.1; color: #f0f0f5 !important; margin: 0 0 20px; }
.vm-hero h1 span { color: #00e5c3; }
.vm-hero p { font-size: 17px; color: #8888a8; line-height: 1.7; margin-bottom: 36px; }
.vm-hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.vb-btn { display: inline-block; padding: 14px 28px; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; text-decoration: none !important; transition: all 0.2s ease; border: none; font-family: inherit; }
.vb-btn-primary { background: #00e5c3; color: #0a0a0f !important; }
.vb-btn-primary:hover { background: #00c9aa; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,229,195,0.3); }
.vb-btn-wa { background: rgba(37,211,102,0.1); border: 1px solid rgba(37,211,102,0.3) !important; color: #25D366 !important; }
.vb-btn-wa:hover { background: rgba(37,211,102,0.2); }

/* BENEFICIOS */
.vm-benefits { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin: 56px 0; }
.vm-benefit-card { background: #16161f; border: 1px solid #2a2a3a; border-radius: 14px; padding: 28px 24px; transition: border-color 0.2s; }
.vm-benefit-card:hover { border-color: #00e5c3; }
.vm-benefit-icon { font-size: 28px; margin-bottom: 14px; }
.vm-benefit-title { font-size: 16px; font-weight: 700; color: #f0f0f5; margin: 0 0 8px; }
.vm-benefit-desc { font-size: 14px; color: #8888a8; line-height: 1.6; margin: 0; }

/* CÓMO FUNCIONA */
.vm-steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin: 40px 0; }
.vm-step { background: #16161f; border: 1px solid #2a2a3a; border-radius: 14px; padding: 28px 20px; text-align: center; position: relative; }
.vm-step-num { font-size: 11px; font-weight: 700; color: #00e5c3; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; }
.vm-step-icon { font-size: 32px; margin-bottom: 12px; display: block; }
.vm-step-title { font-size: 15px; font-weight: 700; color: #f0f0f5; margin: 0 0 8px; }
.vm-step-desc { font-size: 13px; color: #8888a8; line-height: 1.5; margin: 0; }

/* FORMULARIO CONTACTO */
.vm-contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start; margin: 40px 0; }
.vm-form-card { background: #16161f; border: 1px solid #2a2a3a; border-radius: 16px; padding: 36px; }
.vm-form-card h3 { font-size: 20px; font-weight: 800; color: #f0f0f5; margin: 0 0 6px; }
.vm-form-card p { font-size: 14px; color: #8888a8; margin: 0 0 28px; }
.vm-form-field { margin-bottom: 16px; }
.vm-form-field label { display: block; font-size: 12px; font-weight: 600; color: #8888a8; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
.vm-form-field input,
.vm-form-field select,
.vm-form-field textarea {
  width: 100%; background: #0a0a0f; border: 1px solid #2a2a3a; border-radius: 10px; color: #f0f0f5;
  padding: 12px 14px; font-size: 14px; font-family: inherit; transition: border-color 0.2s;
}
.vm-form-field input:focus,
.vm-form-field select:focus,
.vm-form-field textarea:focus { outline: none; border-color: #00e5c3; box-shadow: 0 0 0 3px rgba(0,229,195,0.1); }
.vm-form-field input::placeholder,
.vm-form-field textarea::placeholder { color: #555566; }
.vm-form-field select option { background: #16161f; }
.vm-form-field textarea { resize: vertical; min-height: 100px; }
.vm-form-submit { width: 100%; background: #00e5c3; color: #0a0a0f; font-weight: 700; font-size: 15px; padding: 14px; border-radius: 10px; border: none; cursor: pointer; transition: all 0.2s; font-family: inherit; margin-top: 8px; }
.vm-form-submit:hover { background: #00c9aa; transform: translateY(-1px); }

.vm-info-col { display: flex; flex-direction: column; gap: 16px; }
.vm-info-card { background: #16161f; border: 1px solid #2a2a3a; border-radius: 14px; padding: 24px; }
.vm-info-card h4 { font-size: 14px; font-weight: 700; color: #f0f0f5; margin: 0 0 12px; }
.vm-info-card p { font-size: 13px; color: #8888a8; line-height: 1.6; margin: 0; }
.vm-info-card a { color: #00e5c3 !important; }
.vm-pdf-card { border-color: rgba(0,229,195,.35); background: linear-gradient(135deg, #16161f, #10221f); }
.vm-pdf-card .vb-btn { margin-top: 16px; padding: 11px 16px; font-size: 13px; }
.vm-pdf-note { color: #8888a8; font-size: 12px; line-height: 1.5; margin: 0; }
.vm-stats-mini { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.vm-stat { text-align: center; }
.vm-stat-num { font-size: 26px; font-weight: 800; color: #00e5c3; display: block; }
.vm-stat-label { font-size: 11px; color: #8888a8; }

/* SECCIÓN TÍTULO */
.vm-section-header { margin-bottom: 32px; }
.vm-section-header h2 { font-size: clamp(22px,4vw,32px); font-weight: 800; color: #f0f0f5 !important; margin: 0; }

@media (max-width: 768px) {
  .vm-contact-grid { grid-template-columns: 1fr; }
  .vm-hero { padding: 56px 20px 48px; }
}
@media (max-width: 480px) {
  .vm-steps { grid-template-columns: 1fr 1fr; }
  .vm-benefits { grid-template-columns: 1fr; }
}
</style>

<div class="vb-page">

  <div class="vm-hero">
    <p class="vb-eyebrow">Para revendedores y emprendedores</p>
    <h1>Precios de bodega para <span>hacer crecer tu negocio</span></h1>
    <p>Accede a más de 940 productos al por mayor, con atención directa, despacho desde Bogotá y catálogo con las tendencias que más se venden.</p>
    <div class="vm-hero-btns">
      <a href="#contacto" class="vb-btn vb-btn-primary">Quiero ser mayorista</a>
      <a href="https://wa.me/573127558773?text=Hola%2C+quiero+información+sobre+precios+mayoristas" target="_blank" rel="noopener" class="vb-btn vb-btn-wa">💬 Escribir por WhatsApp</a>
    </div>
  </div>

  <div class="vb-section">

    <!-- BENEFICIOS -->
    <div class="vm-section-header">
      <p class="vb-eyebrow">Por qué trabajar con nosotros</p>
      <h2>Todo lo que necesitas para revender</h2>
    </div>
    <div class="vm-benefits">
      <div class="vm-benefit-card">
        <div class="vm-benefit-icon">🏷️</div>
        <p class="vm-benefit-title">Precios de bodega real</p>
        <p class="vm-benefit-desc">Sin intermediarios. Compramos directo y te trasladamos el ahorro para que tu margen sea mayor.</p>
      </div>
      <div class="vm-benefit-card">
        <div class="vm-benefit-icon">📦</div>
        <p class="vm-benefit-title">+940 referencias disponibles</p>
        <p class="vm-benefit-desc">Tecnología, hogar, belleza, gaming y más. Catálogo actualizado con lo que realmente se vende.</p>
      </div>
      <div class="vm-benefit-card">
        <div class="vm-benefit-icon">🚚</div>
        <p class="vm-benefit-title">Despacho en 24h desde Bogotá</p>
        <p class="vm-benefit-desc">Envíos a toda Colombia. Puedes recoger directamente en bodega o recibir donde estés.</p>
      </div>
      <div class="vm-benefit-card">
        <div class="vm-benefit-icon">💬</div>
        <p class="vm-benefit-title">Atención directa por WhatsApp</p>
        <p class="vm-benefit-desc">Sin formularios complicados. Hablas con nosotros, cerramos el pedido y listo.</p>
      </div>
      <div class="vm-benefit-card">
        <div class="vm-benefit-icon">🛡️</div>
        <p class="vm-benefit-title">Garantía en todos los productos</p>
        <p class="vm-benefit-desc">Respalda tu negocio con garantía real. Tus clientes quedan contentos y tú también.</p>
      </div>
      <div class="vm-benefit-card">
        <div class="vm-benefit-icon">📈</div>
        <p class="vm-benefit-title">Soporte para revendedores</p>
        <p class="vm-benefit-desc">Te ayudamos con información de productos, fotos y descripciones para que vendas más fácil.</p>
      </div>
    </div>

    <!-- CÓMO FUNCIONA -->
    <div class="vm-section-header" style="margin-top:64px">
      <p class="vb-eyebrow">Proceso</p>
      <h2>¿Cómo funciona?</h2>
    </div>
    <div class="vm-steps">
      <div class="vm-step">
        <p class="vm-step-num">Paso 01</p>
        <span class="vm-step-icon">📋</span>
        <p class="vm-step-title">Regístrate o escríbenos</p>
        <p class="vm-step-desc">Llena el formulario o contáctanos por WhatsApp con tus datos de negocio.</p>
      </div>
      <div class="vm-step">
        <p class="vm-step-num">Paso 02</p>
        <span class="vm-step-icon">🛍️</span>
        <p class="vm-step-title">Elige tus productos</p>
        <p class="vm-step-desc">Navega el catálogo completo y selecciona lo que quieres revender.</p>
      </div>
      <div class="vm-step">
        <p class="vm-step-num">Paso 03</p>
        <span class="vm-step-icon">💳</span>
        <p class="vm-step-title">Paga y confirma</p>
        <p class="vm-step-desc">Pago seguro online o acuerda con nosotros directamente.</p>
      </div>
      <div class="vm-step">
        <p class="vm-step-num">Paso 04</p>
        <span class="vm-step-icon">🚀</span>
        <p class="vm-step-title">Recibe y vende</p>
        <p class="vm-step-desc">Despacho en 24h desde Bogotá. A revender y crecer.</p>
      </div>
    </div>

    <!-- FORMULARIO + INFO -->
    <div class="vm-contact-grid" id="contacto" style="margin-top:64px">
      <div class="vm-form-card">
        <h3>Regístrate como mayorista</h3>
        <p>Cuéntanos sobre tu negocio y te contactamos con la lista de precios especial.</p>
        <?php if (function_exists('wpcf7')) : ?>
          <?php echo do_shortcode('[contact-form-7 id="mayoristas" title="Mayoristas"]'); ?>
        <?php else : ?>
        <div>
          <div class="vm-form-field">
            <label>Nombre completo *</label>
            <input type="text" placeholder="Tu nombre">
          </div>
          <div class="vm-form-field">
            <label>WhatsApp *</label>
            <input type="tel" placeholder="3127558773">
          </div>
          <div class="vm-form-field">
            <label>Correo electrónico</label>
            <input type="email" placeholder="tu@correo.com">
          </div>
          <div class="vm-form-field">
            <label>¿Qué tipo de negocio tienes?</label>
            <select>
              <option value="">Selecciona...</option>
              <option>Tienda física</option>
              <option>Tienda online</option>
              <option>Emprendedor / Freelance</option>
              <option>Distribuidora</option>
              <option>Otro</option>
            </select>
          </div>
          <div class="vm-form-field">
            <label>¿Qué categorías te interesan?</label>
            <textarea placeholder="Ej: Tecnología, Audio, Hogar..."></textarea>
          </div>
          <button class="vm-form-submit" onclick="this.textContent='✓ Enviado — te contactamos pronto'; this.style.background='#0F6E56';">
            Enviar solicitud →
          </button>
        </div>
        <?php endif; ?>
      </div>

      <div class="vm-info-col">
        <div class="vm-info-card vm-pdf-card">
          <h4>Normas para ser mayorista</h4>
          <p class="vm-pdf-note">Consulta requisitos, condiciones de compra y recomendaciones antes de registrarte.</p>
          <?php if ($wholesale_pdf_url) : ?>
            <a class="vb-btn vb-btn-primary" href="<?php echo esc_url($wholesale_pdf_url); ?>" target="_blank" rel="noopener">Descargar PDF</a>
          <?php else : ?>
            <p class="vm-pdf-note" style="margin-top:16px;">El documento estará disponible próximamente.</p>
          <?php endif; ?>
        </div>
        <div class="vm-info-card">
          <h4>📊 Números que importan</h4>
          <div class="vm-stats-mini">
            <div class="vm-stat"><span class="vm-stat-num">940+</span><p class="vm-stat-label">Productos</p></div>
            <div class="vm-stat"><span class="vm-stat-num">6</span><p class="vm-stat-label">Categorías</p></div>
            <div class="vm-stat"><span class="vm-stat-num">24h</span><p class="vm-stat-label">Despacho</p></div>
            <div class="vm-stat"><span class="vm-stat-num">100%</span><p class="vm-stat-label">Garantía</p></div>
          </div>
        </div>
        <div class="vm-info-card">
          <h4>💬 Contacto directo</h4>
          <p>Prefieres hablar directamente:<br>
          <a href="https://wa.me/573127558773?text=Hola%2C+quiero+precios+mayoristas" target="_blank">+57 312 755 8773</a> por WhatsApp.<br><br>
          Horario: Lunes a Viernes 8am–6pm, Sábados 9am–1pm.</p>
        </div>
        <div class="vm-info-card">
          <h4>📍 Ubicación bodega</h4>
          <p>Bogotá, Colombia.<br>Retiro en bodega disponible con cita previa. Escríbenos para la dirección exacta.</p>
        </div>
      </div>
    </div>

  </div>
</div>

<?php get_footer(); ?>