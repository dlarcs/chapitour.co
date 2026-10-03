# Chapitour.co

La página principal (`index.php`) sirve Chapitour te premia desde el 2 de octubre de 2026. El código de producción está en `premia/`: API, paneles, ruleta, metas, comunidad y fotos de perfil. Las páginas de los negocios y sus imágenes mantienen sus rutas originales. La carpeta `pruebas/chapitour-premia/` conserva la versión anterior para revisión; no hay enlaces ni botones de pruebas en la portada nueva.

## Producción

- Inicio y acceso: `https://chapitour.co/`.
- Los roles de cliente, aliado y administrador se obtienen de la cuenta autenticada. Se conservan las cuentas y promociones de la base existente.
- Los premios se deciden exclusivamente mediante `premia/api.php` y MySQL. La bienvenida está disponible sin registro; el incentivo de regreso se calcula internamente sin publicar su frecuencia ni su contador.
- La ruleta aparece automáticamente cuando hay un giro disponible. La portada invita a crear cuenta con Google y no incluye un botón para abrir la ruleta.
- Cada giro genera un solo código, aunque se repita la solicitud. El código vence a las 72 horas. Abrir WhatsApp no redime el premio.
- Las vistas visibles se actualizan cada 30 segundos y al volver a la pestaña. Las lecturas automáticas no suman visitas ni emiten premios; se evita reemplazar formularios y se descartan respuestas que hayan quedado atrasadas frente a una acción.
- Los perfiles ficticios del ranking conservan sus etiquetas y se sustituyen por participantes reales visibles. No son cuentas ni receptores de premios.

## Configuración y despliegue

`premia/config/database.local.php` y `google.local.php` son privados y están excluidos de Git. En Hostinger se copiaron desde la configuración que ya funcionaba en pruebas, dentro de la misma cuenta de alojamiento. No se cambiaron contraseñas ni se recreó la base. También se admiten las variables `CHAPITOUR_DB_*` y `CHAPITOUR_GOOGLE_CLIENT_ID`.

Subir `index.php`, `.htaccess` y `premia/` preservando los archivos privados del servidor. Incluir `premia/vendor/` (dependencias de Composer; no se versiona), todos los `.htaccess` de sus subcarpetas y las cinco migraciones aditivas de `premia/database/`. No publicar ZIP, credenciales ni respaldos dentro de `public_html`. PHP necesita PDO MySQL, mbstring, OpenSSL, cURL y GD para las fotografías.

La sesión de producción usa la ruta `/premia/`; una sesión de la antigua ruta de pruebas puede requerir entrar de nuevo, con la misma cuenta. Google mantiene el origen `https://chapitour.co`.

## Verificación del 2 de octubre de 2026

`pruebas/chapitour-premia/tests/production-fixtures.php SOCKET_QA` y `production.cjs SOCKET_QA` usan exclusivamente una base MariaDB temporal. Pasaron en Chrome: portada y rutas, ausencia de controles de pruebas, acceso invitado, perfiles y foto, tamaños 1440/390/320 px, actualización automática, giro animado contra MySQL, bloqueo del doble clic, reintento tras una respuesta perdida y un único código válido 72 horas. La sintaxis PHP/JavaScript y `git diff --check` también pasaron.

Se publicó en Hostinger mediante paquetes sin credenciales. La portada, JavaScript, CSS y API responden HTTP 200; JavaScript y CSS coinciden byte a byte con la versión probada. La API informó Google activo, campaña configurada, cinco promociones elegibles y la configuración de la campaña de esa versión. Configuración, dependencias y `.git` devuelven 403; las fotografías privadas devuelven 404 sin sesión. No se consumieron premios reales durante la comprobación.

Se guardó una copia del `index.php` anterior en la raíz privada de la cuenta de alojamiento, fuera de `public_html`. También existe una copia local en `/private/tmp/chapitour-index-before-production-20261002.php`.

La revisión de producción encontró que Pictogramas había sido recreado con un identificador nuevo y sin página o imagen. `premia/lib/Catalog.php` asocia los nombres exactos de los seis negocios conocidos con sus páginas existentes cuando falta esa información, preservando los datos configurados y sin aprobar ofertas. Las metas reconocen esa misma asociación. `tests/catalog.php SOCKET_QA` pasó 25 comprobaciones; todos los cambios de prueba se revirtieron.

Verificación final en Chrome sobre `https://chapitour.co/`: portada nueva sin banda de pruebas, actualización automática del enlace de Pictogramas, ruleta con cinco aliados elegibles y controles de registro/ingreso para invitados. El botón oficial de Google cargó desde la portada principal; no se completó un registro ni se emitieron premios reales para verificarlo.

## Correcciones de aliados del 3 de octubre de 2026

Se corrigieron las rutas de recursos, iconos y metadatos de las páginas de aliados, se restauró la navegación interna de Gran&Chela y se eliminó la referencia al `app.js` inexistente de Street Grill. Pictogramas usa sus propios archivos de reservas. Los enlaces a menús inexistentes llevan a las actividades publicadas de Capital Queer, Gran&Chela y Pictogramas; Garage abre su carta PDF existente.

Los contactos de navegación y carruseles usan los números publicados en la sección de ubicación de cada aliado. Street Grill abre una búsqueda de Google Maps con su nombre y dirección publicada, en lugar de la ficha de Garage. La dirección y el teléfono de los datos estructurados de Garage coinciden con su sección de contacto. También se corrigieron 16 scripts de animación: variables locales y observación de cada elemento por separado.

Verificación local: 26 páginas renderizadas, 1.117 referencias, cero rutas ausentes, cero recursos de otro aliado y cero avisos PHP. La prueba en Chrome verificó navegación, contactos y carga de recursos en las 26 páginas, sin errores de JavaScript; incluyó una reserva de Pictogramas y una consulta de Street Grill con WhatsApp interceptado, sin enviar mensajes, y navegación móvil de Gran&Chela. Pasaron la sintaxis de los 29 archivos PHP y 16 JavaScript, y `git diff --check`.

Se publicaron 45 archivos mediante `chapitour-aliados-rutas-20261003.zip`, conservado fuera de `public_html`. Las 26 páginas públicas y los 16 scripts modificados devolvieron HTTP 200 y coincidieron con la versión probada (ignorando únicamente las marcas de versión de los recursos en el HTML). El paquete anterior de los 44 archivos existentes quedó en `/private/tmp/chapitour-aliados-antes-20261003.zip`; el archivo de navegación de Gran&Chela es nuevo.

## Bienvenida y regla privada · 3 de octubre de 2026

La primera visita permite girar sin registro. Una cookie aleatoria HttpOnly, Secure en HTTPS y SameSite Strict conserva la bienvenida en ese navegador; el servidor guarda únicamente su hash. No se crean visitantes ni premios al consultar la página. El premio se vincula al registrarse o ingresar como cliente, y ocupa el giro de bienvenida de una cuenta nueva. Después de vincularlo, hace falta la sesión de esa cuenta para consultarlo. Sin cuenta, borrar las cookies o cambiar de navegador impide reconocer a la misma persona.

El código conserva su vigencia y su uso único. Las transacciones, restricciones únicas y reintentos acotados ante bloqueos evitan duplicados por solicitudes repetidas o simultáneas; la selección usa únicamente ofertas confirmadas con cupo. El botón de WhatsApp prepara el mensaje sin confirmar la redención. Se mantienen las tablas, cuentas, ofertas y reglas internas existentes: esta actualización no requiere migración SQL.

La frecuencia del incentivo se retiró de los textos de portada, perfil, administración y API, incluyendo las pantallas de la antigua carpeta de pruebas. La portada muestra la invitación a crear cuenta; el premio del invitado incluye acceso para guardarlo mediante Google.

Pruebas aisladas: `tests/guest-welcome.php` (53 comprobaciones), `tests/guest-edges.php` (solicitudes paralelas, último cupo, ausencia de ofertas y vencimiento) y `tests/production.cjs` (Chrome, ruleta automática, animación, reintento de una respuesta perdida, invitación tras el premio, conservación al recargar, WhatsApp, perfil/foto, actualización y tamaños 1440/390/320). Los archivos de pruebas están en `pruebas/chapitour-premia/tests/` y solo aceptan la base temporal de QA.

Paquete de despliegue: `chapitour-bienvenida-20261003.zip`, nueve archivos, sin configuración privada ni SQL. Respaldo local de las versiones anteriores: `/private/tmp/chapitour-bienvenida-respaldo-20261003.zip`.

Verificación tras publicar: portada, API y recursos HTTP 200; JS/CSS de producción y JS antiguo idénticos a la versión probada; ambas API sin campos de frecuencia y la biblioteca protegida con HTTP 403. Chrome mostró automáticamente la ruleta de bienvenida con botón habilitado, cuatro aliados con ofertas elegibles y portada con registro Google sin botón de ruleta. No se giró ni se emitieron códigos de producción en esta comprobación.
