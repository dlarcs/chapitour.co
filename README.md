# Chapitour.co

La página principal (`index.php`) sirve Chapitour te premia desde el 2 de octubre de 2026. El código de producción está en `premia/`: API, paneles, ruleta, metas, comunidad y fotos de perfil. Las páginas de los negocios y sus imágenes mantienen sus rutas originales. La carpeta `pruebas/chapitour-premia/` conserva la versión anterior para revisión; no hay enlaces ni botones de pruebas en la portada nueva.

## Producción

- Inicio y acceso: `https://chapitour.co/`.
- Los roles de cliente, aliado y administrador se obtienen de la cuenta autenticada. Se conservan las cuentas y promociones de la base existente.
- Los premios se deciden exclusivamente mediante `premia/api.php` y MySQL. La bienvenida está disponible sin registro; el incentivo de regreso se calcula internamente sin publicar su frecuencia ni su contador.
- La ruleta aparece automáticamente cuando hay un giro disponible. La portada invita a crear cuenta con Google y no incluye un botón para abrir la ruleta.
- Cada giro genera un solo código, aunque se repita la solicitud. El código vence a las 72 horas. Abrir WhatsApp no redime el premio.
- Las vistas visibles se actualizan cada 30 segundos y al volver a la pestaña. Las lecturas automáticas no suman visitas ni emiten premios; se evita reemplazar formularios y se descartan respuestas que hayan quedado atrasadas frente a una acción.
- El ranking contiene exclusivamente clientes registrados y activos. No se completa con perfiles de ejemplo; muestra el nombre de la cuenta y conserva las preferencias de ocultarse.

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

## Ranking de participantes registrados · 3 de octubre de 2026

Se eliminó el catálogo de veinte perfiles de relleno, junto con sus avisos y estilos, tanto en producción como en la versión antigua de pruebas. La consulta y la paginación usan únicamente cuentas activas de `cp_clientes`. Las cuentas existentes sin preferencias reciben un alias estable al consultarlas; las nuevas crean su perfil público dentro de la transacción de registro. El alias inicial no publica el nombre de Google ni el correo. Puede cambiarse u ocultarse desde Mi perfil, y esas decisiones se conservan al volver a ingresar. Una foto no cambia la visibilidad del perfil.

No se borran cuentas ni premios y no hace falta SQL de migración: los perfiles de relleno estaban definidos en el código. La lista muestra el número real de participantes y un estado vacío si no hay ninguno.

Verificación local: 278 comprobaciones del ranking (cuentas, alias, preferencias, orden y paginación), 58 del registro/premios y Chrome con 0/5/19/20/21 participantes a 1440/390/320 px. Paquete de diez archivos: `chapitour-ranking-real-20261003.zip`; respaldo previo local: `/private/tmp/chapitour-ranking-respaldo-20261003.zip`.

Verificación en Hostinger: las API de producción y pruebas reportaron 23 participantes registrados y cero perfiles de demostración. La portada cargó la versión nueva; JS y CSS coincidieron byte a byte con los archivos probados. Chrome mostró el conteo y la lista de alias ordenada por puntaje. No se crearon cuentas, premios ni cambios de preferencias en producción durante la comprobación.


## Cuentas de aliados: correo reutilizable y negocio independiente

El botón de administración «Eliminar acceso» revoca la cuenta y sus sesiones, conservando el negocio publicado, su página, las promociones y los códigos. El registro de usuario permanece inactivo para conservar las referencias de auditoría y redención; el correo queda libre. Al crear un acceso también se liberan correos retenidos por cuentas de aliado inactivas de la versión anterior, dentro de la misma transacción. Las cuentas activas, de clientes y de administradores mantienen sus restricciones. No requiere migración SQL ni reactiva negocios ocultados previamente.

La confirmación identifica el negocio y el correo y envía el ID del acceso concreto: un diálogo antiguo no puede eliminar la cuenta que lo reemplazó. Después de eliminarlo, la tarjeta ofrece «Crear acceso» para asociar el correo y una nueva contraseña temporal al mismo negocio.

Pruebas aisladas: `tests/ally-delete.php` ejecuta 40 comprobaciones para cada copia (principal y pruebas), incluyendo reutilización, compatibilidad con eliminaciones anteriores, reversión en errores, permisos, sesiones e historial. `tests/ally-access.cjs` verifica en Chrome a 1440 y 390 px cancelar/confirmar, conservar el catálogo y promociones, revocar sesiones y recrear el acceso con el mismo correo. Se usa únicamente la base temporal `chapitour_panels_qa`.

Publicado en Hostinger mediante `chapitour-accesos-20261003.zip` (seis archivos de ejecución, sin SQL ni configuración privada). El respaldo previo está en `/private/tmp/chapitour-accesos-respaldo-20261003.zip`. Tras publicar, portada y ambas API respondieron HTTP 200 y los dos JavaScript coincidieron byte a byte con la versión probada. Chrome verificó la carga de la portada y su catálogo, sin modificar cuentas reales.


## Eliminación física de accesos de aliados (actualización posterior)

Por solicitud del propietario, «Eliminar acceso» ahora borra el registro de `cp_usuarios`, incluyendo correo y contraseña. Sustituye la conservación de una cuenta inactiva descrita anteriormente. Mantiene el negocio y las promociones, deja las redenciones con su fecha original y retira únicamente la referencia al usuario eliminado. La auditoría guarda su ID anterior, sin conservar una cuenta. Si un acceso de aliado figura como aprobador en datos antiguos, la operación exige confirmar esas promociones desde una cuenta administradora antes de borrarlo, para evitar retirar ofertas de la ruleta.

Las cuentas de cliente son independientes: al crear un aliado se explica específicamente si el correo aún pertenece a un cliente. La confirmación de eliminación informa que el borrado de la cuenta es definitivo. No cambia la eliminación del perfil de cliente desde su propio panel.

Validación: 46 comprobaciones PHP en cada copia, incluyendo registros de auditoría antiguos, ausencia física de la cuenta, revocación de sesiones, reutilización del correo y rechazo de códigos ya redimidos. El flujo de eliminación/recreación pasó en Chrome local a 1440 y 390 px. El SQL de mantenimiento para retirar cuentas concretas se validó con datos sintéticos y las diez relaciones de clientes, conservando negocios, promociones y premios.

Publicado mediante `chapitour-borrado-cuentas-20261003.zip` (seis archivos); respaldo previo en `/private/tmp/chapitour-borrado-cuentas-respaldo-20261003.zip`. La portada y ambas API respondieron HTTP 200; los dos JavaScript publicados coinciden byte a byte con los probados.

Tras la confirmación del propietario, se eliminaron en producción el cliente ID 8 y el acceso de aliado ID 8, restringiendo cada operación también a su correo. phpMyAdmin informó una fila eliminada en cada tabla; una consulta independiente posterior confirmó cero cuentas con los dos correos, ausencia de ambos registros y ausencia de sus vínculos Google y giros pendientes. No se modificaron negocios, promociones ni premios. Las dos referencias históricas de auditoría del aliado conservaron su ID anterior en JSON. Los contadores auxiliares basados en `ROW_COUNT()` no reflejaron las eliminaciones al ejecutarse desde phpMyAdmin; la verificación se basó en las respuestas de cada DELETE y en la consulta posterior, no en esos contadores.

## Canvas Tattoo · 3 de octubre de 2026

Se completó la ficha existente del negocio ID 11, sin duplicarla ni modificar su acceso o sus promociones. En `cp_negocios` quedaron la categoría `Tatuajes y piercings`, el WhatsApp `573146446837`, la página `experiencias/canvas-tattoo/index.php` y el logo `experiencias/canvas-tattoo/logo.svg`. La ruta local redirige únicamente a `https://www.canvastattoocolombia.com/`. El logo es una copia sin modificaciones del archivo `Diseñosintítulo.svg` utilizado por su web oficial; no depende de cargar una imagen de terceros en la portada.

Se publicaron los dos archivos mediante `chapitour-canvas-20261003.zip`. Verificación: sintaxis PHP correcta, redirección HTTP 302 a la web oficial, logo publicado idéntico al original, datos correctos en la API pública y tarjeta con el logo visible en Chrome. La información pública anterior se conservó fuera del sitio en `/private/tmp/chapitour-canvas-before.json`.

## Ficha de Street Grill restaurada · 3 de octubre de 2026

Se completó el registro activo existente ID 13, `Gastro Bar Street Grill`, con la página `gastronomia/streetgrill/index.php`, el logo `gastronomia/streetgrill/img/logo.jpeg` y la categoría `Gastronomía`. La actualización en producción se restringió a su ID, slug y nombre; no creó otro negocio ni modificó cuentas, teléfono, promociones o códigos. Ambos recursos existentes respondieron correctamente; la API pública y Chrome confirmaron el logo y el enlace en la misma ficha. Copia de los datos públicos anteriores: `/private/tmp/chapitour-streetgrill-before.json`. No fue necesario desplegar archivos de ejecución.

### Catálogo público y BogoPork — 2026-10-03

- Orden de portada: Canvas Tattoo primero, Gastro Bar Street Grill sexto y Capital Queer último; los demás conservan su orden relativo. Aplicado también a la copia de pruebas, sin alterar la selección de premios.
- Ale (negocio 12, sin cuentas ni promociones) quedó inactivo en producción; no se borró historial.
- BogoPork (negocio 14) apunta a `gastronomia/bogopork/index.php`, con logo local, categoría Gastronomía y dirección Carrera 8B #57-14, Chapinero, Bogotá. Se conservó su WhatsApp 573053861333.
- Fuente de datos: https://www.instagram.com/bogopork/ y https://linktr.ee/bogopork/, consultados el 2026-10-03. Menú enlazado desde el Linktree oficial, sin copiar precios ni crear promociones. Fotografías del carrusel https://www.instagram.com/bogopork/p/C-84QxIJtht/; crédito @renekimaru_art visible en la página.
- Verificación: PHP/JS sin errores de sintaxis; navegación, imágenes, enlaces y orden del catálogo comprobados a 1440, 390 y 320 px.

### Accesos independientes y WhatsApp — 2026-10-03

- La portada solo muestra registros con `published=true`, calculado desde una ruta válida configurada explícitamente en `cp_negocios.pagina`. Crear una cuenta no configura esa ruta; los metadatos por nombre no publican fichas. El panel de administración conserva todos los negocios y distingue «Ficha publicada» de «Sin ficha pública». Eliminar o recrear un acceso conserva la publicación existente.
- El mensaje de redención incluye negocio, promoción, código, dirección y URL absoluta de su página. Conserva la dirección del premio si existe y usa la dirección actual del negocio si el premio antiguo no la guardó. No acepta estos datos del navegador ni marca el premio como redimido. Los datos ausentes se indican como pendientes, sin inventarlos.
- Se completaron únicamente campos vacíos de producción: página y dirección de Pictogramas (ID 8), dirección de Canvas Tattoo (ID 11, Carrera 13 #60-19, según su web oficial) y Street Grill (ID 13, Carrera 9 #57-85, según su página existente). Las ocho fichas previas conservan su orden.
- Verificado en MySQL temporal: 21 comprobaciones por cada copia (principal/pruebas), incluyendo permisos, alta de cuenta con nombre conocido, publicación explícita, eliminación de acceso, contenido del mensaje, acentos/enlaces y rechazo de códigos vencidos o redimidos. Chrome local comprobó que un acceso nuevo permanece en el panel y no aparece en portada, en ambas copias.
- Publicado mediante `chapitour-accesos-whatsapp-20261003.zip` (seis archivos). Respaldo previo fuera del sitio: `/private/tmp/chapitour-accesos-whatsapp-before-20261003.zip`. API de producción con ocho fichas publicadas, ambos JavaScript idénticos a los probados y nueva versión de caché confirmados.

### Fichas públicas — 2026-10-03

- Administración → Fichas públicas permite mostrar u ocultar cada ficha del listado principal, con su estado y un enlace para revisar la página. Los negocios sin una ruta preparada no pueden publicarse desde este control.
- `cp_panel_fichas` guarda la preferencia por negocio, separada de `cp_negocios.activo`, la página y los accesos. La migración aditiva `premia/database/fichas_cp.sql` se instala al acceder como administrador; sin preferencias previas se conserva la visibilidad de las páginas existentes. La copia de pruebas incluye la misma migración.
- La acción `set_card_visibility` exige administrador y CSRF, valida el estado solicitado y guarda el cambio en una transacción con auditoría. Ocultar no afecta cuentas, promociones, premios ni enlaces de WhatsApp; crear una cuenta continúa sin publicar una ficha.
- Verificación aislada: `tests/catalog-whatsapp.php` pasó 43 comprobaciones por versión. `tests/card-visibility.cjs` comprobó mostrar/ocultar, recarga, recuperación de errores, fichas pendientes, orden y estado vacío en Chrome a 1440, 390 y 320 px. Los cambios de visibilidad de estas pruebas usan exclusivamente datos de QA.


## Ranking con nombres y nueva ubicación · 4 de octubre de 2026

El ranking aparece inmediatamente después de las fichas de aliados y antes de «Cómo funciona», tanto en la portada principal como en la copia de pruebas. La lista principal, la paginación y Mi perfil usan el nombre completo guardado en `cp_clientes.nombre`, que al registrarse con Google procede del nombre de esa cuenta. Se reemplaza así el uso público de alias automáticos o personalizados. Cambiar «Nombre y apellido» en Mi perfil actualiza también el ranking; la opción de ocultarse sigue vigente. El aviso de registro y los textos de comunidad explican este comportamiento.

No requiere migración SQL: el campo histórico de alias queda conservado pero ya no determina el nombre mostrado. Correos y fotografías siguen fuera del ranking.

Validación local: 281 comprobaciones de ranking y paginación, 58 de registro Google y premios, 34 de comunidad y puntos; Chrome comprobó el orden después de los aliados, las listas de 0/5/19/20/21 participantes, nombres largos y escape de HTML a 1440/390/320 px. Chrome también verificó el guardado del nombre desde Mi perfil, ocultarse del ranking y la persistencia de fotografías privadas. Sintaxis PHP/JavaScript y `git diff --check` correctos. Cambios locales pendientes de publicación.

El título de comunidad anuncia el tour de fin de mes para quien acumule más puntos. El texto invita a explorar aliados, responder preguntas y registrar fotos con etiquetas; el botón lleva a registrarse para participar o a Mis retos para sumar puntos. Este cambio de texto también está pendiente de publicación.

### Próxima meta y avance personal

La sección del ranking muestra una tarjeta con el puntaje, posición y puntos para alcanzar la siguiente posición. Sugiere una foto con etiquetas (+15) o una pregunta pendiente (+5) según el avance y los lugares disponibles, respetando los límites mensuales. El botón abre directamente el formulario correspondiente. Al guardar una actividad se actualiza la sugerencia con el estado devuelto por la API. También contempla primer lugar, nombre oculto, metas aún no preparadas y metas completadas; no ofrece los puntos de compartir pendientes de verificación. Los invitados ven una invitación al registro y no un progreso ficticio.

`tests/ranking-motivation.cjs` pasó en Chrome para las dos portadas con datos sintéticos: transición tras registrar una foto, límites, preguntas disponibles, primer lugar, privacidad y móvil de 390/320 px. Se revisaron las capturas y la sintaxis PHP/JavaScript. Los índices actualizan la versión de JS/CSS para evitar caché anterior al publicar. Pendiente de publicación.

## Recordatorio de promociones por correo · 4 de octubre de 2026

Se preparó el recordatorio para las últimas 24 horas de cada premio, desde `admin@chapitour.co` por SMTP de Hostinger al correo del cliente. El proceso CLI usa una tabla de seguimiento para evitar reenvíos, excluye premios redimidos/vencidos y cuentas inactivas, y funciona mediante cron cada cinco minutos. Incluye HTML/texto, código y vencimiento en Bogotá.

Pasaron 39 comprobaciones con base desechable y SMTP simulado, incluido el modo de prueba restringido al remitente. La dependencia PHPMailer y su versión están en Composer. Configuración y activación en [premia/EMAIL_REMINDERS.md](premia/EMAIL_REMINDERS.md). El 4 de octubre de 2026 se guardó la configuración privada y Hostinger aceptó un correo de prueba dirigido a `admin@chapitour.co`; la recepción en bandeja no se ha confirmado. Pendiente de publicación y cron en Hostinger; aún no está enviando recordatorios automáticos.
