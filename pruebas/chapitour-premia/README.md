# Chapitour te premia — paneles conectados a MySQL

La versión de `pruebas/chapitour-premia/` utiliza las tablas `cp_` de la base existente. Los paneles de Cliente, Aliado y Administrador comparten datos persistentes mediante `api.php` y `lib/Panel.php`. Se conserva la carpeta de pruebas y no se modifica la página principal.

## Archivos de conexión

`config/database.php` abre PDO, usa `utf8mb4`, consultas preparadas y UTC. Lee el servidor, nombre de base, usuario y contraseña de `config/database.local.php`, que está excluido de Git. Cambiar allí la contraseña cuando se cambie en Hostinger. No ponerla en JavaScript.

`localhost` apunta al MySQL del servidor donde se ejecuta PHP. En XAMPP no conecta con Hostinger. Para una base local separada se pueden proporcionar las variables de servidor `CHAPITOUR_DB_HOST`, `CHAPITOUR_DB_NAME`, `CHAPITOUR_DB_USER`, `CHAPITOUR_DB_PASSWORD` y opcionalmente `CHAPITOUR_DB_SOCKET` sin editar las credenciales del hosting.

**Si se despliega mediante Git:** `database.local.php` no llega con el push porque es privado. Crearlo una vez en el Administrador de archivos de Hostinger, en `public_html/pruebas/chapitour-premia/config/database.local.php`, usando la estructura de `database.example.php` y las credenciales MySQL. También se admite configurar todos los datos mediante variables de entorno del servidor sin ese archivo. Si falta la configuración, la API responde `503 / DATABASE_CONFIGURATION` y registra el motivo concreto en el log de PHP, sin mostrar contraseñas al navegador.

Deben existir **ambos archivos**: `database.php` es el conector que forma parte del repositorio; `database.local.php` contiene los datos privados y no lo reemplaza. La contraseña configurada debe coincidir con la contraseña del usuario MySQL en Hostinger; escribirla en el archivo no cambia la del servidor. `DATABASE_LOGIN_FAILED` identifica el rechazo de credenciales (MySQL 1045); `DATABASE_ACCESS_DENIED`, permisos insuficientes; y `DATABASE_SCHEMA_MISSING` o `DATABASE_SCHEMA_MISMATCH`, tablas o columnas incompatibles. Los diagnósticos no muestran las consultas ni los valores de conexión.

## Instalar en la ruta de pruebas de Hostinger

1. Mantener la base `u348170507_chapi_promos` y sus tablas `cp_`. No importar los SQL anteriores de diez tablas sin prefijo: corresponden a otra propuesta y no son el esquema de esta integración.
2. Subir a `public_html/pruebas/chapitour-premia/` los archivos `index.php`, `api.php` y las carpetas `assets/`, `config/` y `lib/`. Incluir `config/database.local.php` aunque esté excluido de Git, y los `.htaccess` de `config/` y `lib/`.
3. Subir también `database/paneles_cp.sql`, `database/ruleta_cp.sql` y `database/.htaccess`. Incluir `lib/Rewards.php` junto a `lib/Panel.php`. No hace falta subir los otros SQL, documentación ni `tests/`.
4. Abrir `https://chapitour.co/pruebas/chapitour-premia/` e iniciar sesión con una cuenta administradora existente. Si se ejecutó el SQL de preparación entregado en la conversación, el usuario es `laurazoro@gmail.com`. La contraseña de esa cuenta es distinta a la contraseña MySQL.
5. En el primer inicio de sesión de un administrador, la API crea cuatro tablas auxiliares con `CREATE TABLE IF NOT EXISTS`. Conserva las tablas, datos y contactos existentes. El usuario MySQL necesita permiso para crear tablas; alternativamente ejecutar `database/paneles_cp.sql` en phpMyAdmin antes de entrar. Un fallo parcial puede reintentarse: no hay borrados ni modificaciones de estructura del esquema original.
6. Si la cuenta tiene `cambiar_password=1`, elegir una contraseña nueva antes de acceder a sus datos o acciones. Las cuentas nuevas de aliados también exigen este cambio.
7. En Aliados, utilizar **Crear acceso** para asignar un correo y contraseña a un negocio existente sin duplicarlo. En Promociones, completar y confirmar las ofertas con cada negocio antes de aprobarlas.

Las pruebas de integración se ejecutaron con PHP 8.0.28 y MariaDB 10.4.28 sobre la estructura del export de MariaDB 11.8.9 suministrado. Durante la revisión del despliegue del 30 de septiembre se restauró `config/database.php`, ausente en Hostinger, y se actualizó `api.php` con diagnósticos seguros. Tras corregir la contraseña MySQL en Hostinger, se verificaron una respuesta HTTP 200, la carga de la página en Chrome y el inicio de sesión de la administradora. Ese acceso instaló las tablas auxiliares (`setup_required=false`) y mostró el cambio obligatorio de contraseña temporal. No se borró ni recreó la base; la entrega de premios permanece deshabilitada.

## Datos y permisos

- Cliente: autenticación en `cp_clientes`, perfil persistente, retos del mes y premios propios vinculados mediante `cp_cliente_visitantes`. Registrar una cuenta concede un único giro de bienvenida; el código solo se emite al girar con una oferta disponible. La interfaz registra las visitas posteriores mediante una solicitud protegida por CSRF.
- Aliado: autenticación en `cp_usuarios`, rol `aliado`, negocio tomado de la sesión validada. La API filtra tanto consultas como acciones por ese negocio. No puede editar promociones ni cuentas.
- Administrador: rol `admin` en `cp_usuarios`, gestión de cuentas de aliados, promociones y consulta/redención de códigos.
- Se quitó el selector de cuentas demo y la acción `demo_login`. La sesión `CHAPITOUR_PREMIA_DB` solo guarda la identidad autenticada, su versión y CSRF; el catálogo, los perfiles, promociones, avances y premios se leen de MySQL.
- Contraseñas con bcrypt y `password_verify`, regeneración de sesión, comprobación de `activo` y `version_sesion` en cada solicitud, límites de intentos y respuestas sin credenciales, hashes o errores SQL internos.
- Los correos de las dos tablas de acceso se comprueban juntos. Una cuenta pública no puede registrarse como administrador ni apropiarse del correo de un aliado.

## Extensión del esquema original

- `cp_panel_clientes`: ciudad del perfil, ligada al cliente existente.
- `cp_panel_promociones`: beneficio, publicación, condiciones desglosadas y aprobación por un administrador. Las ofertas antiguas sin esa información se muestran pendientes de confirmar. La publicación no es un estado del código.
- `cp_panel_retos`: los tres objetivos medibles por mes de Bogotá, creados de forma idempotente al consultar los retos.
- `cp_panel_progreso`: progreso verificado de cada cuenta y reto. La UI no inventa avances ni escribe al compartir o consultar fotos. Solo muestra valores respaldados por un criterio de verificación definido y dentro del objetivo.

El esquema original permite una promoción por negocio mediante un índice único. Se conserva esa regla técnica: si ya existe, se utiliza Editar. Eliminar una promoción la archiva y permite crear su sustituta para ese negocio sin borrar premios históricos.

Administración → Aliados muestra «Eliminar aliado» en cada tarjeta, tenga o no una cuenta de acceso. Tras confirmar, el negocio se desactiva y deja de aparecer en el listado y en el catálogo; se revocan sus sesiones y sus promociones pasan a borrador, fuera de la ruleta. Se conservan el registro del negocio y el historial de códigos y redenciones. Solo un administrador puede realizar esta acción. Eliminar una cuenta de cliente anonimiza nombre y correo, elimina ciudad y progreso y revoca el acceso; conserva las referencias de premios/redenciones. El diálogo informa de ese alcance antes de confirmar.

## Códigos y redenciones

Se consultan los premios existentes en `cp_premios` y sus instantáneas en `cp_premio_detalles`. El estado se calcula: Redimido si ya tiene fecha de redención, Vencido si pasó su vencimiento, y Activo en los demás casos.

La redención usa transacción, bloqueo y actualización condicional contra la hora UTC de MySQL. Rechaza códigos vencidos, ya usados y códigos de otros negocios, incluso en solicitudes simultáneas. El actor se obtiene de la sesión. No existe acción de reactivación o extensión de vigencia. Las acciones administrativas y redenciones se registran en `cp_auditoria`.

WhatsApp prepara un mensaje con negocio, beneficio y código; el cliente debe pulsar Enviar. Prepararlo o enviarlo no registra una redención. Editar o archivar una promoción conserva las condiciones de los premios ya emitidos.

Los premios nuevos usan `CHAPI-MES-` seguido de 3 a 6 números, sin ceros iniciales (`100` a `999999`); por ejemplo, `CHAPI-OCT-4123`. El mes de emisión se calcula con la hora de Bogotá a partir del mismo instante UTC de MySQL que se guarda en el premio. Abreviaturas: `ENE`, `FEB`, `MAR`, `ABR`, `MAY`, `JUN`, `JUL`, `AGO`, `SEP`, `OCT`, `NOV`, `DIC`. Las 72 horas se cuentan desde ese instante, independientemente del cambio de mes.

Se elige un número aleatorio y el índice único de MySQL impide reutilizar cualquier código completo, incluso redimido, vencido o emitido en un año anterior. Tras varias colisiones se busca un hueco real para el mismo prefijo mensual; solo si todos sus números están ocupados se amplía a 7 cifras, después a 8, y así sucesivamente. Si no puede completarse la asignación, la transacción conserva el giro y los cupos. Los códigos emitidos anteriormente mantienen su valor y vigencia; un reintento devuelve su código original aunque haya cambiado el mes.

`php tests/reward-codes.php SOCKET_QA` pasó 55 comprobaciones de formato, los doce meses, medianoche de Bogotá al cambiar de mes/año, colisiones reales de MySQL, huecos al principio/interior/final, agotamiento de un rango pequeño, ampliación a 7 y 8 cifras, idempotencia, conservación de códigos anteriores, 72 horas y rollback. El agotamiento de los rangos grandes se simula únicamente en el PDO de la prueba CLI; el buscador de huecos sí se ejecuta contra MariaDB. No se generan premios reales para verificar este cambio.

Desplegado el 1 de octubre de 2026: se reemplazó únicamente `lib/Rewards.php` en Hostinger y se verificó en el administrador de archivos el formato `CHAPI-MES-NÚMERO`, las doce abreviaturas y el cálculo con hora de Bogotá. La API de pruebas siguió respondiendo HTTP 200, con Google activo y la regla de ocho visitas cada cuatro horas. No se modificaron códigos ya emitidos ni el esquema de la base de datos.

## Ruleta: regla confirmada el 1 de octubre de 2026

Cada cuenta de cliente nueva recibe **un giro de bienvenida al registrarse**. Después recibe un giro por cada **8 visitas válidas posteriores** de la misma cuenta. La visita del registro inicia el plazo de 4 horas y no se suma a esas ocho visitas de regreso. Se cuenta como máximo una visita cada **4 horas transcurridas**, usando la hora UTC de MySQL. Las recargas, pestañas y dispositivos comparten el mismo contador. Las cuentas de aliados y administradores no acumulan visitas ni generan premios.

Las visitas incompletas **vuelven a cero al cambiar de mes en Bogotá**. Ese cambio no concede una nueva visita antes de cumplir las 4 horas. Cada ciclo de ocho habilita un giro y comienza otro ciclo. Los giros ya ganados se conservan; las 72 horas de vigencia del código empiezan al girar. El contador interno no aparece en la tarjeta del cliente. La renovación de los otros retos no modifica premios emitidos.

Al abrir la versión de pruebas o iniciar sesión/registrarse, el navegador envía `visit` con CSRF. Consultar `state`, abrir la ruleta, editar perfiles o enviar datos de un supuesto contador no incrementa visitas. La integración sigue limitada a `pruebas/chapitour-premia/`; la web principal no se ha migrado.

`lib/Rewards.php` registra visitas y genera premios con transacciones y bloqueo por cuenta. `cp_panel_visitas` guarda el ciclo; `cp_panel_giros` tiene un índice único por cuenta/ciclo y enlaza el premio; `cp_panel_campana` referencia una campaña exclusiva para esta regla. La instalación es aditiva al iniciar sesión o consultar el panel con una sesión administradora válida. No activa las campañas del sistema antiguo ni consume sus oportunidades de bienvenida o de cinco visitas. No se importan contadores anónimos anteriores.

La elección de la promoción ocurre en el servidor. Solo se incluyen negocios activos con ofertas aprobadas, condiciones completas, WhatsApp confirmado y cupo disponible. Los borradores y ofertas agotadas no pueden generar premios. Si no quedan ofertas, el giro se conserva. Un doble clic, dos dispositivos o una respuesta perdida devuelven el mismo código para el mismo giro, sin descontar otro cupo. El premio conserva una instantánea del beneficio, condiciones, negocio y WhatsApp.

Verificación de la versión anterior del 1 de octubre: se instaló la regla de ocho visitas con intervalo de 24 horas, posteriormente reemplazada por la regla de cuatro horas y bienvenida descrita arriba. En aquella revisión las siete promociones seguían en borrador. No se alteraron sus condiciones ni se generaron premios en Hostinger para probar.

Para poner una oferta en la ruleta: **Administración → Promociones → Editar**, completar los datos aprobados por el negocio, marcar la casilla de confirmación y pulsar **Confirmar promoción**. Ese botón guarda la publicación como **Confirmada**; **Guardar borrador** es una acción separada. Los campos pendientes se indican junto a cada dato y no se realizan escrituras parciales. Guardar como borrador no la habilita. La rueda muestra únicamente los negocios con ofertas disponibles; si no hay ninguna, muestra una vista previa sin entregar premios. No se aprueban automáticamente Pictogramas, Jimar Factory ni otras ofertas.

La verificación de las 20 entregas al compartir sigue pendiente. Las nuevas metas de fotografías y preguntas se describen abajo; sus registros no verifican visitas presenciales.

## Pruebas

La suite actual `tests/acceptance.cjs` verifica la versión conectada, no el prototipo de sesiones. Solo admite un servidor localhost dedicado. `tests/panel-fixtures.php` solo acepta un socket bajo `/private/tmp/chapitour-panel-qa.*`; importa la estructura y catálogo del export y añade fixtures sintéticos dentro de una base temporal independiente llamada `chapitour_panels_qa`.

Para repetir: inicializar una instancia temporal de MariaDB sin puerto de red, ejecutar `panel-fixtures.php setup SOCKET RUTA_EXPORT`, iniciar PHP con las variables `CHAPITOUR_DB_*` de esa instancia y `PHP_CLI_SERVER_WORKERS=4`, y ejecutar `node pruebas/chapitour-premia/tests/acceptance.cjs`. El destino predeterminado es `http://127.0.0.1:8792/pruebas/chapitour-premia/`. Se necesita Playwright y Chrome; `NODE_PATH` puede apuntar a las dependencias locales.

Comprobado: acceso real, cambio obligatorio de clave, CSRF, permisos por cuenta/negocio, CRUD, persistencia entre navegadores, exclusión de borradores, conservación de instantáneas, expiración, redención concurrente única, WhatsApp sin redención, eliminación/revocación, y vistas de escritorio y móvil. Se revisaron capturas de los paneles y del cambio de contraseña. No se enviaron mensajes a negocios.

`tests/database.php` corresponde al modelo SQL anterior sin prefijo y no valida esta integración.

`php tests/configuration.php` comprueba configuración ausente o inválida, conexión mediante variables de entorno y clasificación de errores MySQL sin revelar secretos. No se conecta a ninguna base de datos real.

Después de `tests/acceptance.cjs`, ejecutar `node tests/rewards.cjs SOCKET_QA` con las mismas dependencias. Cubre las 4 horas exactas, ocho visitas, frontera mensual de Bogotá, concurrencia, cupos, borradores, reintentos tras perder una respuesta, el giro animado y el premio en escritorio/móvil. Solo acepta una base QA temporal y usa ofertas sintéticas sin valor comercial.

Corrección de confirmación publicada el 1 de octubre: se reemplazó el selector independiente de publicación por dos botones explícitos, **Confirmar promoción** y **Guardar borrador**. Se verificó el cambio persistente a Confirmada desde móvil y tras recargar en escritorio, y la salida del filtro de borradores para que la oferta confirmada siga visible. La prueba aislada `tests/promotion-publication.php SOCKET_QA` pasó 20 comprobaciones de permisos, validación, estados MySQL, elegibilidad e historial. Se publicaron y revisaron los controles en Hostinger, sin confirmar ofertas reales para probar.

## Giro de prueba del administrador

El panel incluye **Probar ruleta**. En la sesión administradora, el botón **Girar** ejecuta una animación local y muestra **Giro de prueba completado**, identificado como **Modo de prueba · Sin premio real**. No llama a la acción `spin`, no genera códigos ni consume cupos, visitas o giros. Se muestran los negocios con ofertas disponibles; si no hay ofertas, se usa el catálogo como vista previa, sin presentar borradores como premios. No cambia la elegibilidad de clientes o aliados.

En un giro real, la rueda comienza a moverse mientras el servidor determina el premio. Después se detiene en el negocio devuelto por la API. Ante un fallo de red se detiene la animación y el botón permite reintentar el mismo giro; la API conserva su idempotencia. Se respeta la preferencia de movimiento reducido.

`tests/wheel-interaction.cjs SOCKET_QA` prueba la animación de administrador sin escrituras, repetición, móvil, movimiento reducido, restricciones de clientes sin giro, respuesta demorada, recuperación tras perder la respuesta y conservación de un único código de 72 horas. Solo usa localhost y el socket de una base QA temporal.

El giro de bienvenida se crea en la misma transacción que el registro, como ciclo 0 de `cp_panel_giros`; el índice único de cuenta/ciclo evita duplicarlo. Reintentar un giro, iniciar sesión o recargar no concede otra bienvenida. Si no hay ofertas disponibles, se conserva el giro. Los ciclos 1 en adelante corresponden a cada ocho visitas posteriores. Esta regla se aplica a cuentas nuevas desde esta actualización, sin conceder retroactivamente giros a cuentas existentes ni alterar sus visitas o premios.

## Acceso de clientes con Google

El registro público se realiza mediante Google Identity Services. La API rechaza el registro manual por correo/contraseña. Administradores, aliados y clientes que ya tenían contraseña conservan su acceso. Una cuenta existente solo puede vincular Google desde Mi perfil después de entrar con su contraseña y elegir el mismo correo; no se vinculan cuentas por mera coincidencia del correo.

Configurar un cliente OAuth de tipo **Aplicación web** en Google Cloud, con origen JavaScript autorizado **https://chapitour.co**. Copiar `config/google.example.php` como `config/google.local.php` y colocar su `client_id`; también se acepta `CHAPITOUR_GOOGLE_CLIENT_ID`. El flujo usa una ventana emergente y callback JavaScript, por lo que no necesita secreto de cliente ni URL de redirección. El ID de cliente es público; nunca pegar una API key o un secreto en su lugar. Mientras falte este dato, la interfaz informa que Google está pendiente de configuración y no simula un inicio de sesión.

Desplegar `lib/GoogleAuth.php`, `database/google_cp.sql`, `vendor/` (generado por `composer install --no-dev --optimize-autoloader`) y `var/.htaccess`, además de Panel.php, Rewards.php y los archivos de interfaz. El directorio var guarda únicamente la caché de claves públicas de Google y debe ser escribible por PHP. Abrir una sesión administradora instala de forma aditiva `cp_panel_google`; no cambia contraseñas ni elimina datos.

La validación utiliza firebase/php-jwt: firma RSA con las claves oficiales de Google, audiencia del cliente OAuth, emisor, expiración, correo verificado y nonce de sesión. El POST también exige el CSRF de la aplicación. La identidad se vincula mediante hash del identificador estable de Google (sub), con unicidad por cliente. No se almacenan tokens Google, contraseñas Google ni accesos a Gmail. Los correos vinculados a Google no pueden cambiarse desde el formulario de perfil.

`tests/google-welcome.php SOCKET_QA` comprueba firma y claims, repetición, colisiones con cuentas administradoras, vinculación autenticada, baja de cuentas, bienvenida única, cuatro horas exactas, ocho regresos y conservación de giros al cambiar de mes. Las firmas de prueba solo se inyectan en una instancia PHP CLI; no existe un endpoint ni una opción del navegador que omita la validación de Google.

Actualización desplegada y verificada el 1 de octubre de 2026 en `pruebas/chapitour-premia/`: API HTTP 200, `welcome_on_registration=true`, ocho visitas, `new_visit_after=14400`, reinicio mensual y dos promociones elegibles. El JavaScript remoto coincide byte a byte con el local. En Chrome, el panel administrador completó **Probar ruleta → Girar** y mostró el resultado de demostración sin generar premios. No se cambiaron ofertas, contactos ni credenciales MySQL.

Pasaron las 28 comprobaciones de `google-welcome.php`, `wheel-interaction.cjs`, `configuration.php`, la validación de sintaxis PHP/JavaScript y la revisión de espacios del diff. En ese primer despliegue faltaba configurar el ID de cliente OAuth (`google_auth.enabled=false`). Las pruebas de identidad usaron tokens firmados sintéticos en la base QA, no un inicio de sesión real con Google.

Activación posterior del 1 de octubre de 2026: se guardó el ID OAuth web proporcionado por la usuaria en `config/google.local.php`, tanto localmente como en Hostinger. Ese archivo está excluido de Git y su acceso HTTP devuelve 403. La API respondió HTTP 200 con `google_auth.enabled=true` y el ID esperado. Desde Chrome en una sesión de visitante, **Crear mi cuenta → Continuar con Google** cargó la pantalla oficial de Google para iniciar sesión en `chapitour.co`. Queda pendiente que la usuaria complete un registro real con una cuenta de cliente; no se introdujeron credenciales ni se crearon cuentas o premios reales durante esta verificación. El correo del administrador conserva su acceso por contraseña y no se utiliza para crear un cliente con Google.


## Metas independientes: actualización local del 1 de octubre de 2026

El panel muestra, en este orden: compartir con 20 amigos, fotografías y etiquetas de Instagram, y preguntas sobre los negocios. La última instrucción cambió la meta fotográfica de 6/6 a **3/3**. El incentivo por visitas conserva su funcionamiento y aparece fuera de estas tres tarjetas.

Instagram suma un punto por negocio distinto registrado por el cliente, con un máximo de tres por mes en Bogotá. El formulario solo pide seleccionar el negocio: no solicita fotos, enlaces, códigos ni confirmación de terceros. Se recuerda etiquetar a @chapitour.co y al negocio. Las cuentas enlazadas en las páginas se muestran como ayuda; Pictogramas mantiene su cuenta pendiente, sin inventarla. La pista del retrato forma parte de esta meta. El servidor deduplica por cuenta, mes y negocio, limita a tres incluso con solicitudes simultáneas y rechaza formularios de otro mes.

Las cinco preguntas se presentan sin el nombre del negocio: público del lugar, pasillo y bebidas, nombre del cóctel, tres banderas y especialidad gastronómica. El cliente selecciona dónde lo descubrió y escribe su respuesta. Se guarda el texto como **respuesta registrada**, sin afirmar que es correcta ni verificar presencia; no se inventa una carta de cócteles ni se aplica la antigua validación por palabras clave. Las tres respuestas de países deben ser distintas. La interfaz muestra el avance de preguntas separado del 3/3. No se conceden giros ni premios al completar fotografías o preguntas.

Compartir conserva su acción anterior: abre la opción del dispositivo o copia la página; el clic no incrementa un contador ni entrega un giro. Se muestra el objetivo de compartir con 20 amigos para ganar otro giro, pero la comprobación de las entregas y su concesión siguen pendientes de definición. No se convirtió el reto en un programa de registros referidos ni se modificaron sus datos existentes.

Archivos de la actualización: `lib/Challenges.php`, `database/metas_cp.sql`, `lib/Panel.php`, `assets/app.js`, `assets/app.css` e `index.php`. Las dos tablas nuevas se instalan de forma aditiva al abrir una sesión administradora. No se borra progreso del esquema anterior. Los nuevos registros se eliminan cuando el cliente elimina su cuenta. Esta actualización está implementada **localmente en pruebas**, todavía sin publicar en Hostinger.

Verificación: `tests/monthly-goals.php SOCKET_QA` pasó 31 comprobaciones de roles, aislamiento, deduplicación, límite 3/3, persistencia, respuestas abiertas, cambio de mes y conservación de premios/giros. `tests/monthly-goals.cjs SOCKET_QA` pasó en Chrome de escritorio y en tamaños móviles de 390 y 320 px, incluyendo concurrencia desde dos sesiones, registro sin archivos, preguntas anónimas y ausencia de desbordes. La sintaxis PHP/JavaScript y `git diff --check` pasaron. Solo se usó MariaDB temporal con cuentas ficticias.


## Ranking público y fotografía de perfil (implementación local)

La portada de pruebas incluye «Los que más viven Chapinero», con los primeros diez participantes del mes y una lista paginada de veinte por página. Las personas reales que aparecen son cuentas activas que activaron su participación desde Mi perfil y eligieron un nombre público o alias. La respuesta pública contiene nombre y puntaje, la marca `demo: true` solo para ejemplos y metadatos de paginación: no expone ID de cuenta, correo, respuestas, fotografías ni desglose privado. Una cuenta nueva comienza sin participación pública; puede sumar puntos y decidir cuándo aparecer. Los empates comparten posición en el panel privado.

Mientras haya menos de veinte participantes reales visibles, se completan veinte espacios con perfiles ficticios. La portada y la lista completa muestran «Datos de demostración»; cada ejemplo lleva «Perfil ficticio». Las personas reales aparecen primero según su puntaje, incluso si tienen cero puntos. Con cinco personas reales se muestran quince ejemplos; al llegar a veinte, no queda ninguno. Las cuentas privadas o inactivas no sustituyen ejemplos; si baja la participación pública, se vuelven a completar los espacios. Los ejemplos solo se generan al consultar la lista: no crean cuentas, correos, visitas, puntos, giros ni premios, y no afectan las posiciones privadas. No requiere una migración ni una tarea programada.

Puntaje mensual en Bogotá: +1 por visita válida, máximo 10; +5 por pregunta registrada, máximo 25; +15 por negocio registrado en la meta de Instagram, máximo 45. Los 20 puntos restantes se reservan a un reto de compartir con objetivo 20, progreso verificado 20 y criterio de verificación definido. No existe comprobación automática de las entregas, por lo que abrir Compartir no suma puntos ni concede giros. Fotos y preguntas se puntúan como participación declarada, no como presencia o respuestas verificadas. No se entregan premios por liderar o completar 100 puntos.

Los puntos de visitas se actualizan dentro de la misma transacción y bloqueo por cuenta que la regla existente de cuatro horas. No se cuentan recargas ni se reconstruyen visitas históricas que no estaban registradas. La nueva tabla conserva hasta diez puntos por cuenta y mes; el cambio mensual se refleja sin tareas programadas ni alterar los giros pendientes. Las fotos y respuestas ya registradas durante el mes sí cuentan. Mi perfil muestra puntaje, posición pública, desglose y puntos para alcanzar la siguiente posición.

Mi perfil permite seleccionar, previsualizar, guardar, cambiar y quitar una fotografía JPG/PNG/WebP de hasta 5 MB. El navegador corrige la orientación al decodificar y recorta el centro a 512 × 512; el servidor vuelve a validar y codificar la imagen como JPEG, descartando contenido adicional y metadatos. El original no se conserva. La imagen se guarda como BLOB en MySQL, sin archivos ejecutables subidos al directorio web. `avatar.php` sirve únicamente la imagen de la cuenta cliente autenticada, con `no-store`; no admite seleccionar otra cuenta mediante parámetros. La foto no aparece en el ranking público. Eliminar la cuenta elimina su foto, preferencias públicas y puntos de visitas.

Instalación aditiva por administrador: `database/comunidad_cp.sql` crea `cp_panel_comunidad` y `cp_panel_puntos_visitas`. Desplegar junto a `lib/Community.php`, `lib/Http.php`, `avatar.php` y los cambios de Panel, Rewards, API e interfaz. Requiere GD para procesar imágenes; si falta, devuelve un mensaje claro. Se conserva la misma configuración de sesiones y CSRF en la API y el endpoint de imagen. No se modificaron credenciales, cuentas reales ni la página principal de producción.

Verificación local: `tests/community.php SOCKET_QA` pasó 25 comprobaciones del ranking, puntuación, cuatro horas, límites, privacidad, empates, paginación, renovación mensual y eliminación. `tests/community.cjs SOCKET_QA` comprueba Chrome, móvil 390/320 px, subida/cambio/eliminación y persistencia de foto, separación entre cuentas, rechazo de SVG y tamaño excesivo, CSRF y escape de alias. También pasaron las 31 comprobaciones de metas y las 28 de Google/bienvenida/visitas. Para los ejemplos, `tests/community-demo.php SOCKET_QA` pasó 337 comprobaciones con 0/1/5/10/19/20/21 cuentas públicas; `tests/community-demo.cjs SOCKET_QA` pasó en Chrome a 1440/390/320 px, incluyendo etiquetas, sustitución y paginación. Esta última prueba visual utiliza respuestas generadas por la prueba PHP, sin modificar los datos compartidos. Todo se ejecutó en MariaDB temporal con cuentas ficticias. Esta actualización todavía no se ha publicado en Hostinger.
