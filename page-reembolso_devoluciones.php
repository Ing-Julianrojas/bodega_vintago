<?php
/**
 * Vintago Bodega — Devoluciones y Reembolsos
 * Template Name: Devoluciones y reembolsos
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$updated  = '2025-01-01';
$email    = 'soporte@vintago.com.co';
$whatsapp = 'https://wa.me/573127558773';
?>
<main class="vintago-legal vintago-legal--returns">
    <div class="vintago-legal__hero">
        <div class="vintago-legal__hero-inner">
            <div class="vintago-legal__badge">DEVOLUCIONES · GARANTÍAS · SOPORTE</div>
            <h1 class="vintago-legal__title">
                Compraste mal.<br><span class="vintago-accent">Te cubrimos.</span>
            </h1>
            <p class="vintago-legal__subtitle">
                Nuestro proceso para reportar novedades, cambios y reembolsos —
                claro, rápido y sin vueltas. Última actualización: <time datetime="<?php echo esc_attr($updated); ?>"><?php echo esc_html(date('d M Y', strtotime($updated))); ?></time>
            </p>
            <div class="vintago-legal__pills">
                <span>⚡ Respuesta ágil</span>
                <span>📸 Con evidencia</span>
                <span>🇨🇴 Ley colombiana</span>
            </div>
        </div>
        <div class="vintago-legal__hero-deco" aria-hidden="true">
            <svg class="vintago-deco-shield" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M60 4L8 26v44c0 30 22 58 52 68 30-10 52-38 52-68V26L60 4z" stroke="var(--accent-2)" stroke-width="2" fill="rgba(124,92,252,0.04)"/>
                <path d="M44 62l8 8 20-20" stroke="var(--accent-2)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M60 88v4" stroke="var(--accent-2)" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <article class="vintago-legal__article">

        <!-- Proceso rápido -->
        <div class="vintago-returns-steps">
            <h2 class="vintago-returns-steps__title">¿Cómo va el proceso?</h2>
            <div class="vintago-returns-steps__grid">
                <div class="vintago-returns-step">
                    <div class="vintago-returns-step__num">1</div>
                    <div>
                        <strong>Repórtalo</strong>
                        <p>Escríbenos con foto o video del problema dentro del plazo.</p>
                    </div>
                </div>
                <div class="vintago-returns-step__arrow" aria-hidden="true">→</div>
                <div class="vintago-returns-step">
                    <div class="vintago-returns-step__num">2</div>
                    <div>
                        <strong>Validamos</strong>
                        <p>Revisamos el caso y te confirmamos si aplica la solución.</p>
                    </div>
                </div>
                <div class="vintago-returns-step__arrow" aria-hidden="true">→</div>
                <div class="vintago-returns-step">
                    <div class="vintago-returns-step__num">3</div>
                    <div>
                        <strong>Resolvemos</strong>
                        <p>Cambio, reparación o reembolso según corresponda.</p>
                    </div>
                </div>
            </div>
        </div>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">01</div>
            <div class="vintago-legal__section-body">
                <h2>Cómo solicitar ayuda</h2>
                <p>
                    Contáctanos dentro de los <strong>5 días calendario</strong> siguientes a la entrega.
                    Puedes escribirnos por WhatsApp al <a href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener">+57 312 755 8773</a>
                    o al correo <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>.
                    Incluye:
                </p>
                <ul class="vintago-legal__list">
                    <li>Número de pedido</li>
                    <li>Descripción del problema</li>
                    <li>Fotografías o video cuando sea posible</li>
                </ul>
                <div class="vintago-legal__callout vintago-legal__callout--warning">
                    <span>⚠️</span>
                    <p>No envíes el producto sin autorización previa. Los paquetes enviados sin coordinar no serán recibidos.</p>
                </div>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">02</div>
            <div class="vintago-legal__section-body">
                <h2>Condiciones del producto</h2>
                <p>Para que aplique el cambio o devolución, el artículo debe conservar:</p>
                <div class="vintago-legal__check-list">
                    <div class="vintago-legal__check-item">
                        <span class="vintago-check">✓</span>
                        <span>Accesorios, cables y manuales originales</span>
                    </div>
                    <div class="vintago-legal__check-item">
                        <span class="vintago-check">✓</span>
                        <span>Empaque y comprobante de compra</span>
                    </div>
                    <div class="vintago-legal__check-item">
                        <span class="vintago-check">✓</span>
                        <span>Serial o sticker de garantía sin alteraciones</span>
                    </div>
                    <div class="vintago-legal__check-item">
                        <span class="vintago-check">✓</span>
                        <span>Sin señales de daño externo ni uso incorrecto</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">03</div>
            <div class="vintago-legal__section-body">
                <h2>Productos con falla o novedad de entrega</h2>
                <p>
                    Si recibes un producto incompleto, con daño visible o con una falla de funcionamiento,
                    repórtalo tan pronto lo detectes y adjunta evidencia fotográfica o en video.
                    Revisaremos el caso y, según corresponda, gestionaremos <strong>reparación, cambio o reembolso</strong>
                    conforme a la garantía aplicable y a la normativa colombiana de protección al consumidor.
                </p>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">04</div>
            <div class="vintago-legal__section-body">
                <h2>Derecho de retracto</h2>
                <p>
                    Cuando aplique según la ley colombiana, puedes ejercer el derecho de retracto dentro
                    del plazo legal establecido, siempre que el producto conserve sus condiciones originales.
                    Los costos y condiciones de transporte se manejarán conforme a la normativa y las
                    características de la compra.
                </p>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">05</div>
            <div class="vintago-legal__section-body">
                <h2>Reembolsos</h2>
                <p>
                    Una vez aprobada la solicitud, te informaremos el valor exacto y el medio del reembolso.
                    El tiempo de reflejo en tu cuenta depende del banco, la pasarela o el medio de pago
                    que usaste. Generalmente oscila entre <strong>3 y 15 días hábiles</strong>.
                </p>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">06</div>
            <div class="vintago-legal__section-body">
                <h2>Casos no cubiertos</h2>
                <div class="vintago-legal__callout vintago-legal__callout--red">
                    <span>🚫</span>
                    <p>No aplicamos cambio ni devolución por daños causados por golpes, humedad, mala instalación, modificaciones, uso contrario a las instrucciones o desgaste normal. Esto no limita los derechos irrenunciables del consumidor colombiano.</p>
                </div>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">07</div>
            <div class="vintago-legal__section-body">
                <h2>Contacto oficial</h2>
                <table class="vintago-legal__table">
                    <tr><td>Empresa</td><td><strong>[Nombre legal de la empresa]</strong></td></tr>
                    <tr><td>NIT</td><td><strong>[NIT]</strong></td></tr>
                    <tr><td>Correo</td><td><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td></tr>
                    <tr><td>WhatsApp</td><td><a href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener">+57 312 755 8773</a></td></tr>
                    <tr><td>Ciudad</td><td>Bogotá, Colombia</td></tr>
                </table>
            </div>
        </section>

        <div class="vintago-legal__contact-cta">
            <div>
                <strong>¿Tienes un problema con tu pedido?</strong>
                <p>Escríbenos ahora y lo resolvemos lo más rápido posible.</p>
            </div>
            <a href="<?php echo esc_url($whatsapp); ?>" class="vintago-btn" target="_blank" rel="noopener">Escribir por WhatsApp</a>
        </div>

    </article>
</main>
<?php get_footer(); ?>
