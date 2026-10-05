<?php
/**
 * Vintago Bodega — Política de Privacidad
 * Template Name: Política de privacidad
 */
if (!defined('ABSPATH')) { exit; }
get_header();

$updated = '2025-01-01'; // Actualiza esta fecha cuando la publiques
$email   = 'privacidad@vintago.com.co';
?>
<main class="vintago-legal vintago-legal--privacy">
    <div class="vintago-legal__hero">
        <div class="vintago-legal__hero-inner">
            <div class="vintago-legal__badge">DATOS · PRIVACIDAD · CONFIANZA</div>
            <h1 class="vintago-legal__title">
                Tu info,<br><span class="vintago-accent">bajo candado.</span>
            </h1>
            <p class="vintago-legal__subtitle">
                Aquí va lo que hacemos con tus datos — sin letra chiquita, sin cuento.
                Última actualización: <time datetime="<?php echo esc_attr($updated); ?>"><?php echo esc_html(date('d M Y', strtotime($updated))); ?></time>
            </p>
            <div class="vintago-legal__pills">
                <span>🔒 No vendemos datos</span>
                <span>📦 Solo lo necesario</span>
                <span>🇨🇴 Normativa colombiana</span>
            </div>
        </div>
        <div class="vintago-legal__hero-deco" aria-hidden="true">
            <svg class="vintago-deco-shield" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M60 4L8 26v44c0 30 22 58 52 68 30-10 52-38 52-68V26L60 4z" stroke="var(--accent)" stroke-width="2" stroke-linejoin="round" fill="rgba(0,229,195,0.04)"/>
                <path d="M42 70l12 14 24-26" stroke="var(--accent)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <article class="vintago-legal__article">

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">01</div>
            <div class="vintago-legal__section-body">
                <h2>¿Quién responde por tus datos?</h2>
                <p>
                    El responsable del tratamiento es <strong>Bodega Vintago</strong>, operado por <strong>[Nombre legal de la empresa]</strong>,
                    NIT <strong>[NIT]</strong>, con domicilio en <strong>Bogotá, Colombia</strong>.
                    Para cualquier consulta sobre datos personales escríbenos a
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>.
                </p>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">02</div>
            <div class="vintago-legal__section-body">
                <h2>¿Qué info recopilamos?</h2>
                <p>Cuando creas una cuenta, haces un pedido, pides ayuda o te suscribes a comunicaciones, podemos tratar:</p>
                <ul class="vintago-legal__list">
                    <li>Nombre completo, documento de identidad</li>
                    <li>Correo electrónico y número de teléfono</li>
                    <li>Dirección de entrega y datos de facturación</li>
                    <li>Datos técnicos del dispositivo (IP, navegador, cookies) para seguridad y rendimiento</li>
                </ul>
                <div class="vintago-legal__callout">
                    <span>🛡</span>
                    <p>No almacenamos los datos completos de tu tarjeta. Los pagos los procesa directamente tu pasarela de pago autorizada.</p>
                </div>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">03</div>
            <div class="vintago-legal__section-body">
                <h2>¿Para qué usamos tus datos?</h2>
                <div class="vintago-legal__grid-uses">
                    <div class="vintago-legal__use-card">
                        <span>📦</span>
                        <strong>Procesar pedidos</strong>
                        <p>Preparar, coordinar y rastrear tu envío.</p>
                    </div>
                    <div class="vintago-legal__use-card">
                        <span>🔐</span>
                        <strong>Administrar tu cuenta</strong>
                        <p>Historial de compras, acceso seguro y soporte.</p>
                    </div>
                    <div class="vintago-legal__use-card">
                        <span>💬</span>
                        <strong>Atención al cliente</strong>
                        <p>Responder preguntas y resolver novedades.</p>
                    </div>
                    <div class="vintago-legal__use-card">
                        <span>🧾</span>
                        <strong>Cumplimiento legal</strong>
                        <p>Facturación, contabilidad y obligaciones fiscales.</p>
                    </div>
                    <div class="vintago-legal__use-card">
                        <span>📣</span>
                        <strong>Comunicaciones</strong>
                        <p>Solo si nos diste tu autorización expresa.</p>
                    </div>
                    <div class="vintago-legal__use-card">
                        <span>🔍</span>
                        <strong>Prevención de fraude</strong>
                        <p>Proteger tu cuenta y la plataforma.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">04</div>
            <div class="vintago-legal__section-body">
                <h2>¿Con quién compartimos información?</h2>
                <p>
                    <strong>No vendemos tus datos, punto.</strong> Solo compartimos la información mínima necesaria con:
                </p>
                <ul class="vintago-legal__list">
                    <li>Pasarelas de pago (para procesar tu transacción)</li>
                    <li>Transportadoras (para coordinar la entrega)</li>
                    <li>Proveedores tecnológicos que operan el sitio bajo confidencialidad</li>
                    <li>Autoridades competentes cuando exista una obligación legal válida</li>
                </ul>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">05</div>
            <div class="vintago-legal__section-body">
                <h2>Tus derechos</h2>
                <p>
                    Tienes derecho a consultar, actualizar, corregir, eliminar y conocer el uso de tus datos personales.
                    También puedes retirar autorizaciones para comunicaciones comerciales en cualquier momento.
                    Escríbenos a <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    y respondemos en los plazos que exige la normativa colombiana (Ley 1581 de 2012).
                </p>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">06</div>
            <div class="vintago-legal__section-body">
                <h2>Conservación y seguridad</h2>
                <p>
                    Guardamos la información el tiempo necesario para cumplir el propósito, las obligaciones contables y
                    fiscales, y atender posibles reclamaciones. Aplicamos medidas razonables de seguridad técnica y
                    organizacional, aunque ningún sistema conectado a internet está exento de riesgos.
                </p>
            </div>
        </section>

        <section class="vintago-legal__section">
            <div class="vintago-legal__section-num">07</div>
            <div class="vintago-legal__section-body">
                <h2>Actualizaciones de esta política</h2>
                <p>
                    Esta política puede cambiar para reflejar actualizaciones legales, técnicas o de operación.
                    Publicaremos la versión vigente en esta página con su fecha de actualización. Si el cambio es
                    significativo, te lo haremos saber por correo.
                </p>
            </div>
        </section>

        <div class="vintago-legal__contact-cta">
            <div>
                <strong>¿Preguntas sobre tu privacidad?</strong>
                <p>Escríbenos y te respondemos en máximo 10 días hábiles.</p>
            </div>
            <a href="https://wa.me/573127558773" class="vintago-btn" target="_blank" rel="noopener">Escribir por WhatsApp</a>
        </div>

    </article>
</main>
<?php get_footer(); ?>
