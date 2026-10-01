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

- Cliente: autenticación en `cp_clientes`, perfil persistente, retos del mes y premios propios vinculados mediante `cp_cliente_visitantes`. Registrar una cuenta no concede premios. La interfaz registra la primera visita válida de la cuenta mediante una solicitud protegida por CSRF.
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

Eliminar una cuenta de aliado revoca sus sesiones y desactiva sus promociones; conserva negocio e historial. Eliminar una cuenta de cliente anonimiza nombre y correo, elimina ciudad y progreso y revoca el acceso; conserva las referencias de premios/redenciones. El diálogo informa de ese alcance antes de confirmar.

## Códigos y redenciones

Se consultan los premios existentes en `cp_premios` y sus instantáneas en `cp_premio_detalles`. El estado se calcula: Redimido si ya tiene fecha de redención, Vencido si pasó su vencimiento, y Activo en los demás casos.

La redención usa transacción, bloqueo y actualización condicional contra la hora UTC de MySQL. Rechaza códigos vencidos, ya usados y códigos de otros negocios, incluso en solicitudes simultáneas. El actor se obtiene de la sesión. No existe acción de reactivación o extensión de vigencia. Las acciones administrativas y redenciones se registran en `cp_auditoria`.

WhatsApp prepara un mensaje con negocio, beneficio y código; el cliente debe pulsar Enviar. Prepararlo o enviarlo no registra una redención. Editar o archivar una promoción conserva las condiciones de los premios ya emitidos.

## Ruleta: regla confirmada el 1 de octubre de 2026

Hay un único beneficio: un giro por cada **8 visitas válidas** de la misma cuenta de cliente. Se cuenta como máximo una visita cada **24 horas transcurridas**, usando la hora UTC de MySQL. Las recargas, pestañas y dispositivos comparten el mismo contador. Las cuentas de aliados y administradores no acumulan visitas ni generan premios.

Las visitas incompletas **vuelven a cero al cambiar de mes en Bogotá**. Ese cambio no concede una nueva visita antes de cumplir las 24 horas. Cada ciclo de ocho habilita un giro y comienza otro ciclo. Los giros ya ganados se conservan; las 72 horas de vigencia del código empiezan al girar. El contador interno no aparece en la tarjeta del cliente. La renovación de los otros retos no modifica premios emitidos.

Al abrir la versión de pruebas o iniciar sesión/registrarse, el navegador envía `visit` con CSRF. Consultar `state`, abrir la ruleta, editar perfiles o enviar datos de un supuesto contador no incrementa visitas. La integración sigue limitada a `pruebas/chapitour-premia/`; la web principal no se ha migrado.

`lib/Rewards.php` registra visitas y genera premios con transacciones y bloqueo por cuenta. `cp_panel_visitas` guarda el ciclo; `cp_panel_giros` tiene un índice único por cuenta/ciclo y enlaza el premio; `cp_panel_campana` referencia una campaña exclusiva para esta regla. La instalación es aditiva al iniciar sesión o consultar el panel con una sesión administradora válida. No activa las campañas del sistema antiguo ni consume sus oportunidades de bienvenida o de cinco visitas. No se importan contadores anónimos anteriores.

La elección de la promoción ocurre en el servidor. Solo se incluyen negocios activos con ofertas aprobadas, condiciones completas, WhatsApp confirmado y cupo disponible. Los borradores y ofertas agotadas no pueden generar premios. Si no quedan ofertas, el giro se conserva. Un doble clic, dos dispositivos o una respuesta perdida devuelven el mismo código para el mismo giro, sin descontar otro cupo. El premio conserva una instantánea del beneficio, condiciones, negocio y WhatsApp.

Despliegue verificado el 1 de octubre: actualización instalada en la ruta de pruebas de Hostinger, migración completada desde la sesión administradora, API HTTP 200 con regla 8 / 86400 segundos / reinicio mensual, y JavaScript remoto idéntico al probado. En esa revisión las siete promociones seguían en borrador, por lo que no había ofertas disponibles para entregar. No se alteraron sus condiciones ni se generaron premios en Hostinger para probar.

Para poner una oferta en la ruleta: **Administración → Promociones → Editar**, completar los datos aprobados por el negocio, elegir **Aprobada**, marcar la confirmación y guardar. Guardar como borrador no la habilita. La rueda muestra únicamente los negocios con ofertas disponibles; si no hay ninguna, muestra una vista previa sin entregar premios. No se aprueban automáticamente Pictogramas, Jimar Factory ni otras ofertas.

Siguen pendientes los mecanismos de verificación de visitas a negocios, entregas al compartir y fotografías/etiquetas. No se inventa progreso para esos retos.

## Pruebas

La suite actual `tests/acceptance.cjs` verifica la versión conectada, no el prototipo de sesiones. Solo admite un servidor localhost dedicado. `tests/panel-fixtures.php` solo acepta un socket bajo `/private/tmp/chapitour-panel-qa.*`; importa la estructura y catálogo del export y añade fixtures sintéticos dentro de una base temporal independiente llamada `chapitour_panels_qa`.

Para repetir: inicializar una instancia temporal de MariaDB sin puerto de red, ejecutar `panel-fixtures.php setup SOCKET RUTA_EXPORT`, iniciar PHP con las variables `CHAPITOUR_DB_*` de esa instancia y `PHP_CLI_SERVER_WORKERS=4`, y ejecutar `node pruebas/chapitour-premia/tests/acceptance.cjs`. El destino predeterminado es `http://127.0.0.1:8792/pruebas/chapitour-premia/`. Se necesita Playwright y Chrome; `NODE_PATH` puede apuntar a las dependencias locales.

Comprobado: acceso real, cambio obligatorio de clave, CSRF, permisos por cuenta/negocio, CRUD, persistencia entre navegadores, exclusión de borradores, conservación de instantáneas, expiración, redención concurrente única, WhatsApp sin redención, eliminación/revocación, y vistas de escritorio y móvil. Se revisaron capturas de los paneles y del cambio de contraseña. No se enviaron mensajes a negocios.

`tests/database.php` corresponde al modelo SQL anterior sin prefijo y no valida esta integración.

`php tests/configuration.php` comprueba configuración ausente o inválida, conexión mediante variables de entorno y clasificación de errores MySQL sin revelar secretos. No se conecta a ninguna base de datos real.

Después de `tests/acceptance.cjs`, ejecutar `node tests/rewards.cjs SOCKET_QA` con las mismas dependencias. Cubre las 24 horas exactas, ocho visitas, frontera mensual de Bogotá, concurrencia, cupos, borradores, reintentos tras perder una respuesta, el giro animado y el premio en escritorio/móvil. Solo acepta una base QA temporal y usa ofertas sintéticas sin valor comercial.
