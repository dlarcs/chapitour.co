# Chapitour.co

La página principal (`index.php`) sirve Chapitour te premia desde el 2 de octubre de 2026. El código de producción está en `premia/`: API, paneles, ruleta, metas, comunidad y fotos de perfil. Las páginas de los negocios y sus imágenes mantienen sus rutas originales. La carpeta `pruebas/chapitour-premia/` conserva la versión anterior para revisión; no hay enlaces ni botones de pruebas en la portada nueva.

## Producción

- Inicio y acceso: `https://chapitour.co/`.
- Los roles de cliente, aliado y administrador se obtienen de la cuenta autenticada. Se conservan las cuentas y promociones de la base existente.
- Los premios se deciden exclusivamente mediante `premia/api.php` y MySQL. Un giro de bienvenida por cuenta nueva y otro por cada ocho visitas posteriores, como máximo una visita cada cuatro horas. El contador incompleto se reinicia mensualmente según la regla existente; los giros pendientes se conservan.
- Se quitó el giro de demostración del administrador. Un cliente con giro disponible ve la ruleta al entrar; las personas sin sesión tienen acceso al registro con Google y al inicio de sesión.
- Cada giro genera un solo código, aunque se repita la solicitud. El código vence a las 72 horas. Abrir WhatsApp no redime el premio.
- Las vistas visibles se actualizan cada 30 segundos y al volver a la pestaña. Las lecturas automáticas no suman visitas ni emiten premios; se evita reemplazar formularios y se descartan respuestas que hayan quedado atrasadas frente a una acción.
- Los perfiles ficticios del ranking conservan sus etiquetas y se sustituyen por participantes reales visibles. No son cuentas ni receptores de premios.

## Configuración y despliegue

`premia/config/database.local.php` y `google.local.php` son privados y están excluidos de Git. En Hostinger se copiaron desde la configuración que ya funcionaba en pruebas, dentro de la misma cuenta de alojamiento. No se cambiaron contraseñas ni se recreó la base. También se admiten las variables `CHAPITOUR_DB_*` y `CHAPITOUR_GOOGLE_CLIENT_ID`.

Subir `index.php`, `.htaccess` y `premia/` preservando los archivos privados del servidor. Incluir `premia/vendor/` (dependencias de Composer; no se versiona), todos los `.htaccess` de sus subcarpetas y las cinco migraciones aditivas de `premia/database/`. No publicar ZIP, credenciales ni respaldos dentro de `public_html`. PHP necesita PDO MySQL, mbstring, OpenSSL, cURL y GD para las fotografías.

La sesión de producción usa la ruta `/premia/`; una sesión de la antigua ruta de pruebas puede requerir entrar de nuevo, con la misma cuenta. Google mantiene el origen `https://chapitour.co`.

## Verificación del 2 de octubre de 2026

`pruebas/chapitour-premia/tests/production-fixtures.php SOCKET_QA` y `production.cjs SOCKET_QA` usan exclusivamente una base MariaDB temporal. Pasaron en Chrome: portada y rutas, ausencia de controles de pruebas, acceso invitado, perfiles y foto, tamaños 1440/390/320 px, actualización automática, giro animado contra MySQL, bloqueo del doble clic, reintento tras una respuesta perdida y un único código válido 72 horas. La sintaxis PHP/JavaScript y `git diff --check` también pasaron.

Se publicó en Hostinger mediante paquetes sin credenciales. La portada, JavaScript, CSS y API responden HTTP 200; JavaScript y CSS coinciden byte a byte con la versión probada. La API informó Google activo, campaña configurada, cinco promociones elegibles, ocho visitas y un intervalo de 14.400 segundos. Configuración, dependencias y `.git` devuelven 403; las fotografías privadas devuelven 404 sin sesión. No se consumieron premios reales durante la comprobación.

Se guardó una copia del `index.php` anterior en la raíz privada de la cuenta de alojamiento, fuera de `public_html`. También existe una copia local en `/private/tmp/chapitour-index-before-production-20261002.php`.

La revisión de producción encontró que Pictogramas había sido recreado con un identificador nuevo y sin página o imagen. `premia/lib/Catalog.php` asocia los nombres exactos de los seis negocios conocidos con sus páginas existentes cuando falta esa información, preservando los datos configurados y sin aprobar ofertas. Las metas reconocen esa misma asociación. `tests/catalog.php SOCKET_QA` pasó 25 comprobaciones; todos los cambios de prueba se revirtieron.

Verificación final en Chrome sobre `https://chapitour.co/`: portada nueva sin banda de pruebas, actualización automática del enlace de Pictogramas, ruleta con cinco aliados elegibles y controles de registro/ingreso para invitados. El botón oficial de Google cargó desde la portada principal; no se completó un registro ni se emitieron premios reales para verificarlo.
