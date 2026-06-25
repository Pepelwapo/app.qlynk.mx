<?php $pageTitle = 'Términos y Condiciones – Qlynk'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="index,follow">
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;background:#f4f5f7;color:#1a1a2e;line-height:1.7}
.topbar{background:#1a1a2e;padding:16px 24px;display:flex;align-items:center;justify-content:space-between}
.topbar .logo{color:#fff;font-weight:800;font-size:20px;text-decoration:none;letter-spacing:-.5px}
.topbar .login-btn{background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:7px 18px;font-size:13px;text-decoration:none;font-weight:600;transition:.15s}
.topbar .login-btn:hover{background:rgba(255,255,255,.2)}
.hero{background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);color:#fff;padding:64px 24px 52px;text-align:center}
.hero h1{font-size:clamp(24px,5vw,38px);font-weight:800;margin-bottom:12px;letter-spacing:-.5px}
.hero p{color:rgba(255,255,255,.55);font-size:15px;max-width:560px;margin:0 auto}
.hero .meta{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.08);border-radius:20px;padding:5px 14px;font-size:12px;color:rgba(255,255,255,.5);margin-top:16px}
.container{max-width:820px;margin:0 auto;padding:48px 24px 80px}
.toc{background:#fff;border-radius:14px;padding:24px;margin-bottom:36px;box-shadow:0 1px 6px rgba(0,0,0,.06)}
.toc h3{font-size:13px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px}
.toc ol{padding-left:18px}
.toc li{font-size:13px;margin-bottom:4px}
.toc a{color:#1a1a2e;text-decoration:none}
.toc a:hover{text-decoration:underline}
.section{background:#fff;border-radius:14px;padding:32px;margin-bottom:20px;box-shadow:0 1px 6px rgba(0,0,0,.06)}
.section h2{font-size:17px;font-weight:700;color:#1a1a2e;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #f0f0f5;display:flex;align-items:center;gap:10px}
.section h2 .num{background:#1a1a2e;color:#fff;width:26px;height:26px;border-radius:6px;font-size:11px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
.section p{font-size:14px;color:#444;margin-bottom:12px;line-height:1.8}
.section p:last-child{margin-bottom:0}
.section ul{font-size:14px;color:#444;padding-left:20px;margin-bottom:12px;line-height:1.8}
.section ul li{margin-bottom:4px}
.alert-box{background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:16px 20px;font-size:13px;color:#6d4c00;margin-bottom:12px;display:flex;gap:10px}
.alert-box.red{background:#fff0f0;border-color:#ffcdd2;color:#7f1d1d}
.footer{background:#1a1a2e;color:rgba(255,255,255,.4);text-align:center;padding:24px;font-size:12px}
.footer a{color:rgba(255,255,255,.5);text-decoration:none}
</style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
  <a href="https://qlynk.mx" class="logo">Qlynk</a>
  <a href="/login" class="login-btn"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</a>
</div>

<!-- Hero -->
<div class="hero">
  <h1>Términos y Condiciones de Uso</h1>
  <p>Lee con atención antes de utilizar los servicios de Qlynk. El uso de nuestra plataforma implica la aceptación total de estos términos.</p>
  <div class="meta"><i class="bi bi-calendar3"></i> Última actualización: Junio 2025 · Versión 1.0</div>
</div>

<!-- Contenido -->
<div class="container">

  <!-- Índice -->
  <div class="toc">
    <h3>Contenido</h3>
    <ol>
      <li><a href="#aceptacion">Aceptación de los términos</a></li>
      <li><a href="#descripcion">Descripción del servicio</a></li>
      <li><a href="#elegibilidad">Elegibilidad y registro</a></li>
      <li><a href="#cuentas">Cuentas de usuario</a></li>
      <li><a href="#contenido">Contenido del usuario — Exención de responsabilidad</a></li>
      <li><a href="#usoAceptable">Uso aceptable</a></li>
      <li><a href="#planes">Planes, pagos y cancelaciones</a></li>
      <li><a href="#propiedad">Propiedad intelectual</a></li>
      <li><a href="#privacidad">Privacidad y datos personales</a></li>
      <li><a href="#disponibilidad">Disponibilidad del servicio</a></li>
      <li><a href="#limitacion">Limitación de responsabilidad</a></li>
      <li><a href="#indemnizacion">Indemnización</a></li>
      <li><a href="#terminacion">Terminación del servicio</a></li>
      <li><a href="#ley">Ley aplicable y jurisdicción</a></li>
      <li><a href="#cambios">Cambios a estos términos</a></li>
      <li><a href="#contacto">Contacto</a></li>
    </ol>
  </div>

  <!-- 1 -->
  <div class="section" id="aceptacion">
    <h2><span class="num">1</span>Aceptación de los Términos</h2>
    <p>Al acceder, registrarse o utilizar cualquiera de los servicios ofrecidos por <strong>Qlynk</strong> (en adelante "la Plataforma", "el Servicio" o "Qlynk"), incluyendo pero no limitado a la generación de códigos QR, creación de menús digitales, catálogos en línea, formularios web, páginas de destino y cualquier otra funcionalidad disponible en <strong>app.qlynk.mx</strong> y sus subdominios, usted (en adelante "el Usuario") declara haber leído, comprendido y aceptado en su totalidad los presentes Términos y Condiciones de Uso.</p>
    <p><strong>Si no está de acuerdo con alguno de estos términos, deberá abstenerse de utilizar el Servicio.</strong></p>
    <div class="alert-box">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <span>El uso continuo de la Plataforma después de la publicación de modificaciones a estos Términos constituye aceptación tácita de los mismos, sin necesidad de consentimiento expreso adicional.</span>
    </div>
  </div>

  <!-- 2 -->
  <div class="section" id="descripcion">
    <h2><span class="num">2</span>Descripción del Servicio</h2>
    <p>Qlynk es una plataforma SaaS (Software como Servicio) que proporciona a sus usuarios herramientas digitales para:</p>
    <ul>
      <li>Generación y gestión de códigos QR dinámicos y estáticos</li>
      <li>Creación de menús digitales para restaurantes y negocios de alimentos</li>
      <li>Construcción de catálogos de productos en línea</li>
      <li>Diseño de formularios de contacto y captura de datos</li>
      <li>Páginas de destino (landing pages)</li>
      <li>Estadísticas de escaneos y analíticas básicas</li>
      <li>Servicios de redirección de URLs mediante el subdominio <strong>go.qlynk.mx</strong></li>
    </ul>
    <p>Qlynk se reserva el derecho de modificar, ampliar, reducir o discontinuar cualquier funcionalidad del Servicio en cualquier momento, con o sin previo aviso, sin que ello genere derecho a compensación alguna a favor del Usuario.</p>
  </div>

  <!-- 3 -->
  <div class="section" id="elegibilidad">
    <h2><span class="num">3</span>Elegibilidad y Registro</h2>
    <p>Para utilizar el Servicio, el Usuario debe:</p>
    <ul>
      <li>Ser mayor de 18 años o contar con la autorización expresa de su representante legal</li>
      <li>Tener capacidad legal para celebrar contratos vinculantes</li>
      <li>No estar previamente suspendido o excluido de la Plataforma</li>
      <li>Proporcionar información verídica, completa y actualizada durante el registro</li>
    </ul>
    <p>Qlynk se reserva el derecho de rechazar o cancelar el registro de cualquier Usuario, a su entera discreción y sin obligación de expresar los motivos de dicha decisión.</p>
  </div>

  <!-- 4 -->
  <div class="section" id="cuentas">
    <h2><span class="num">4</span>Cuentas de Usuario</h2>
    <p>El Usuario es el único y exclusivo responsable de:</p>
    <ul>
      <li>Mantener la confidencialidad de sus credenciales de acceso (correo electrónico y contraseña)</li>
      <li>Todas las actividades realizadas bajo su cuenta, sean o no autorizadas por él</li>
      <li>Notificar de forma inmediata a Qlynk ante cualquier uso no autorizado de su cuenta</li>
      <li>La veracidad y exactitud de la información proporcionada al registrarse</li>
    </ul>
    <p>Qlynk no será responsable por ningún daño o pérdida derivados del incumplimiento del Usuario en la salvaguarda de sus credenciales de acceso, incluyendo accesos no autorizados por parte de terceros.</p>
  </div>

  <!-- 5 -->
  <div class="section" id="contenido">
    <h2><span class="num">5</span>Contenido del Usuario — Exención de Responsabilidad</h2>
    <div class="alert-box red">
      <i class="bi bi-shield-exclamation"></i>
      <span><strong>Sección crítica:</strong> Qlynk no revisa, modera ni supervisa el contenido publicado por los usuarios. El Usuario es el único responsable de todo lo que suba, publique o comparta a través de la Plataforma.</span>
    </div>
    <p>El Usuario reconoce y acepta que es el <strong>único y exclusivo responsable</strong> de todo el contenido que cargue, publique, transmita, almacene o difunda a través de la Plataforma, incluyendo sin limitación:</p>
    <ul>
      <li>Textos, descripciones, precios e información de productos o servicios</li>
      <li>Imágenes, fotografías, logotipos, videos y cualquier material gráfico</li>
      <li>Archivos PDF y documentos de cualquier tipo</li>
      <li>Datos de contacto, información personal de terceros y cualquier dato sensible</li>
      <li>URLs de destino configuradas en los códigos QR</li>
      <li>Información recabada a través de formularios</li>
      <li>Cualquier otro material o información introducida en el sistema</li>
    </ul>
    <p><strong>Qlynk se deslinda expresamente de toda responsabilidad</strong> respecto a la legalidad, exactitud, veracidad, integridad, calidad, adecuación o cualquier otra característica del contenido generado por los usuarios. En ningún caso Qlynk será responsable por:</p>
    <ul>
      <li>Contenido que infrinja derechos de propiedad intelectual de terceros</li>
      <li>Contenido difamatorio, ofensivo, engañoso, fraudulento o ilegal</li>
      <li>Datos personales de terceros publicados sin autorización</li>
      <li>Información falsa o inexacta que cause daños a consumidores o terceros</li>
      <li>Uso indebido de marcas registradas o nombres comerciales ajenos</li>
      <li>Cualquier daño directo, indirecto, incidental o consecuente derivado del contenido del Usuario</li>
      <li>Reclamaciones, demandas, sanciones o acciones legales iniciadas por terceros en relación con el contenido del Usuario</li>
    </ul>
    <p>El Usuario otorga a Qlynk una licencia mundial, no exclusiva, libre de regalías y sublicenciable para usar, reproducir, distribuir y mostrar el contenido exclusivamente con el fin de prestar el Servicio. Esta licencia no implica que Qlynk revise, controle o valide dicho contenido.</p>
    <p>Qlynk se reserva el derecho —pero no tiene la obligación— de eliminar o deshabilitar cualquier contenido que, a su exclusiva discreción, considere violatorio de estos Términos o de la ley aplicable, sin necesidad de aviso previo ni expresión de causa.</p>
  </div>

  <!-- 6 -->
  <div class="section" id="usoAceptable">
    <h2><span class="num">6</span>Uso Aceptable</h2>
    <p>Queda estrictamente prohibido al Usuario utilizar la Plataforma para:</p>
    <ul>
      <li>Actividades ilegales, fraudulentas o contrarias a la moral pública</li>
      <li>Phishing, distribución de malware, spyware o cualquier código malicioso</li>
      <li>Spam, envío masivo de comunicaciones no solicitadas</li>
      <li>Violación de derechos de propiedad intelectual de terceros</li>
      <li>Suplantación de identidad o representación falsa de personas, empresas u organizaciones</li>
      <li>Recopilación no autorizada de datos personales de terceros (scraping)</li>
      <li>Eludir, desactivar o interferir con las medidas de seguridad de la Plataforma</li>
      <li>Difundir contenido para adultos, violento o discriminatorio sin el contexto y consentimiento adecuados</li>
      <li>Cualquier actividad que pueda dañar, sobrecargar o deteriorar los servidores de Qlynk</li>
    </ul>
    <p>El incumplimiento de estas disposiciones faculta a Qlynk para suspender o eliminar la cuenta del Usuario de forma inmediata y sin responsabilidad alguna.</p>
  </div>

  <!-- 7 -->
  <div class="section" id="planes">
    <h2><span class="num">7</span>Planes, Pagos y Cancelaciones</h2>
    <p>Qlynk ofrece distintos planes de servicio, incluyendo un plan gratuito con funcionalidades limitadas y planes de pago con mayor capacidad. Los precios, límites y características de cada plan están disponibles en la sección de facturación dentro de la Plataforma y en el sitio web <strong>qlynk.mx</strong>.</p>
    <ul>
      <li>Los pagos se procesan a través de proveedores externos de pago (MercadoPago u otros). Qlynk no almacena datos de tarjetas de crédito o débito.</li>
      <li>Las suscripciones de pago son renovadas automáticamente según el ciclo elegido (mensual o anual) hasta que el Usuario las cancele.</li>
      <li>Qlynk no emite reembolsos por períodos ya pagados, salvo lo dispuesto en la legislación mexicana aplicable a la protección del consumidor.</li>
      <li>Los precios están expresados en Pesos Mexicanos (MXN) más IVA cuando aplique, y pueden modificarse con o sin previo aviso.</li>
      <li>En caso de impago, Qlynk podrá restringir o suspender el acceso al Servicio sin previo aviso.</li>
    </ul>
  </div>

  <!-- 8 -->
  <div class="section" id="propiedad">
    <h2><span class="num">8</span>Propiedad Intelectual</h2>
    <p>Todos los elementos de la Plataforma —incluyendo diseño, código fuente, logotipos, marcas, textos, gráficos y funcionalidades— son propiedad exclusiva de Qlynk o de sus licenciantes, y están protegidos por las leyes de propiedad intelectual aplicables en México y a nivel internacional.</p>
    <p>El Usuario no adquiere ningún derecho de propiedad sobre la Plataforma ni sobre ninguno de sus elementos. Queda prohibida la reproducción, distribución, modificación, ingeniería inversa o cualquier uso comercial de los elementos de la Plataforma sin autorización escrita previa de Qlynk.</p>
  </div>

  <!-- 9 -->
  <div class="section" id="privacidad">
    <h2><span class="num">9</span>Privacidad y Datos Personales</h2>
    <p>El tratamiento de datos personales se rige por el <strong>Aviso de Privacidad</strong> de Qlynk, conforme a lo establecido por la <em>Ley Federal de Protección de Datos Personales en Posesión de los Particulares</em> (LFPDPPP) de los Estados Unidos Mexicanos y su Reglamento.</p>
    <p>Al aceptar estos Términos, el Usuario consiente el tratamiento de sus datos personales conforme al Aviso de Privacidad vigente. Los datos podrán ser transferidos a proveedores de infraestructura y servicios tecnológicos que son necesarios para la operación del Servicio.</p>
    <p>Qlynk no vende ni comercializa datos personales de usuarios a terceros con fines de mercadotecnia o publicidad.</p>
  </div>

  <!-- 10 -->
  <div class="section" id="disponibilidad">
    <h2><span class="num">10</span>Disponibilidad del Servicio</h2>
    <p>Qlynk realizará esfuerzos razonables para mantener la Plataforma disponible de forma continua, pero no garantiza disponibilidad ininterrumpida ni libre de errores. El Servicio puede verse afectado por:</p>
    <ul>
      <li>Mantenimiento programado o de emergencia</li>
      <li>Fallas de terceros proveedores de infraestructura</li>
      <li>Eventos de fuerza mayor o caso fortuito</li>
      <li>Ataques cibernéticos o circunstancias fuera del control razonable de Qlynk</li>
    </ul>
    <p>Qlynk no será responsable por daños, pérdidas de datos o interrupciones del negocio derivados de la falta de disponibilidad del Servicio.</p>
  </div>

  <!-- 11 -->
  <div class="section" id="limitacion">
    <h2><span class="num">11</span>Limitación de Responsabilidad</h2>
    <div class="alert-box red">
      <i class="bi bi-exclamation-octagon-fill"></i>
      <span><strong>En ningún caso</strong> Qlynk, sus directores, empleados, socios, proveedores o agentes serán responsables por daños directos, indirectos, incidentales, especiales, ejemplares, punitivos o consecuentes de ningún tipo.</span>
    </div>
    <p>El Servicio se proporciona <strong>"tal como está"</strong> y <strong>"según disponibilidad"</strong>, sin garantías expresas ni implícitas de ningún tipo, incluyendo sin limitación garantías de comerciabilidad, adecuación para un propósito particular o no infracción.</p>
    <p>Qlynk no garantiza que:</p>
    <ul>
      <li>El Servicio satisfaga los requerimientos específicos del Usuario</li>
      <li>El Servicio sea ininterrumpido, oportuno, seguro o libre de errores</li>
      <li>Los resultados obtenidos sean exactos o confiables</li>
      <li>Los defectos en el Servicio serán corregidos en un plazo determinado</li>
    </ul>
    <p>La responsabilidad máxima acumulada de Qlynk frente al Usuario, por cualquier causa y bajo cualquier teoría legal, no excederá el monto total pagado por el Usuario a Qlynk durante los <strong>tres (3) meses anteriores</strong> al evento que dio origen a la reclamación.</p>
    <p>Bajo ninguna circunstancia el Usuario podrá iniciar acciones legales contra Qlynk, sus directivos o empleados por daños derivados directa o indirectamente del contenido que el propio Usuario haya publicado o de las actividades que haya realizado a través de la Plataforma.</p>
  </div>

  <!-- 12 -->
  <div class="section" id="indemnizacion">
    <h2><span class="num">12</span>Indemnización</h2>
    <p>El Usuario acepta <strong>defender, indemnizar y mantener indemne</strong> a Qlynk, sus directivos, empleados, socios, agentes, licenciantes y proveedores de servicios, de y contra cualquier reclamación, responsabilidad, daño, sentencia, premio, pérdida, costo, gasto u honorarios (incluyendo honorarios razonables de abogados) derivados de o relacionados con:</p>
    <ul>
      <li>El uso del Servicio por parte del Usuario</li>
      <li>El contenido publicado, transmitido o almacenado por el Usuario</li>
      <li>La violación de estos Términos por parte del Usuario</li>
      <li>La violación de derechos de terceros, incluyendo derechos de propiedad intelectual o privacidad</li>
      <li>Cualquier reclamación de que el contenido del Usuario causó daño a un tercero</li>
    </ul>
  </div>

  <!-- 13 -->
  <div class="section" id="terminacion">
    <h2><span class="num">13</span>Terminación del Servicio</h2>
    <p>Qlynk podrá, a su entera discreción y sin responsabilidad alguna, suspender o terminar el acceso del Usuario al Servicio, con o sin causa y con o sin previo aviso, incluyendo cuando:</p>
    <ul>
      <li>El Usuario incumpla estos Términos o cualquier política de Qlynk</li>
      <li>Qlynk considere que el uso representa un riesgo para la seguridad de la Plataforma o de terceros</li>
      <li>Lo requieran autoridades competentes</li>
      <li>Qlynk decida discontinuar el Servicio, total o parcialmente</li>
    </ul>
    <p>A la terminación, el derecho del Usuario a usar el Servicio cesará de inmediato. Qlynk no garantiza la conservación ni entrega de datos o contenidos del Usuario tras la terminación, salvo lo que exija la ley.</p>
  </div>

  <!-- 14 -->
  <div class="section" id="ley">
    <h2><span class="num">14</span>Ley Aplicable y Jurisdicción</h2>
    <p>Estos Términos se rigen e interpretan de conformidad con las leyes de los <strong>Estados Unidos Mexicanos</strong>, en particular:</p>
    <ul>
      <li>Código Civil Federal</li>
      <li>Ley Federal de Protección al Consumidor (PROFECO)</li>
      <li>Ley Federal de Protección de Datos Personales en Posesión de los Particulares</li>
      <li>Ley de la Propiedad Industrial</li>
      <li>Ley Federal del Derecho de Autor</li>
      <li>Legislación internacional aplicable en materia de comercio electrónico</li>
    </ul>
    <p>Para la resolución de cualquier controversia derivada de o relacionada con estos Términos, las partes se someten expresamente a la jurisdicción de los <strong>Tribunales competentes de la Ciudad de México, México</strong>, renunciando a cualquier otro fuero que pudiera corresponderles por razón de su domicilio presente o futuro.</p>
    <p>Sin perjuicio de lo anterior, Qlynk podrá solicitar medidas cautelares o ejecutar sentencias en cualquier jurisdicción donde el Usuario tenga activos o actividades.</p>
  </div>

  <!-- 15 -->
  <div class="section" id="cambios">
    <h2><span class="num">15</span>Cambios a estos Términos</h2>
    <div class="alert-box">
      <i class="bi bi-info-circle-fill"></i>
      <span>Qlynk se reserva el derecho de modificar estos Términos en cualquier momento y sin previo aviso. Los cambios entran en vigor en el momento de su publicación en la Plataforma.</span>
    </div>
    <p>La versión vigente de estos Términos siempre estará disponible en <strong>app.qlynk.mx/terminos</strong>. Se recomienda al Usuario revisarlos periódicamente. El uso continuado del Servicio tras la publicación de cambios constituye aceptación plena de los Términos modificados.</p>
    <p>Qlynk no tiene obligación de notificar individualmente a los usuarios sobre cambios en estos Términos, salvo que la ley aplicable expresamente lo requiera.</p>
  </div>

  <!-- 16 -->
  <div class="section" id="contacto">
    <h2><span class="num">16</span>Contacto</h2>
    <p>Para cualquier consulta relacionada con estos Términos y Condiciones, el Usuario puede dirigirse a:</p>
    <ul>
      <li><strong>Plataforma:</strong> <a href="https://qlynk.mx" target="_blank">qlynk.mx</a></li>
      <li><strong>Correo electrónico:</strong> <a href="mailto:legal@qlynk.mx">legal@qlynk.mx</a></li>
    </ul>
    <p style="margin-top:12px;font-size:13px;color:#888">Al utilizar Qlynk, confirmas que has leído, entendido y aceptado estos Términos y Condiciones en su totalidad.</p>
  </div>

</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date('Y'); ?> Qlynk. Todos los derechos reservados. &nbsp;|&nbsp;
  <a href="/terminos">Términos y Condiciones</a>
  </p>
</div>

</body>
</html>
