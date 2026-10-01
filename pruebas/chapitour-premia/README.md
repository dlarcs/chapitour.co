# Chapitour te premia — paneles conectados a MySQL

La versión de `pruebas/chapitour-premia/` utiliza las tablas `cp_` de la base existente. Los paneles de Cliente, Aliado y Administrador comparten datos persistentes mediante `api.php` y `lib/Panel.php`. Se conserva la carpeta de pruebas y no se modifica la página principal.

## Archivos de conexión

`config/database.php` abre PDO, usa `utf8mb4`, consultas preparadas y UTC. Lee el servidor, nombre de base, usuario y contraseña de `config/database.local.php`, que está excluido de Git. Cambiar allí la contraseña cuando se cambie en Hostinger. No ponerla en JavaScript.

`localhost` apunta al MySQL del servidor donde se ejecuta PHP. En XAMPP no conecta con Hostinger. Para una base local separada se pueden proporcionar las variables de servidor `CHAPITOUR_DB_HOST`, `CHAPITOUR_DB_NAME`, `CHAPITOUR_DB_USER`, `CHAPITOUR_DB_PASSWORD` y opcionalmente `CHAPITOUR_DB_SOCKET` sin editar las credenciales del hosting.

## Instalar en la ruta de pruebas de Hostinger

1. Mantener la base `u348170507_chapi_promos` y sus tablas `cp_`. No importar los SQL anteriores de diez tablas sin prefijo: corresponden a otra propuesta y no son el esquema de esta integración.
2. Subir a `public_html/pruebas/chapitour-premia/` los archivos `index.php`, `api.php` y las carpetas `assets/`, `config/` y `lib/`. Incluir `config/database.local.php` aunque esté excluido de Git, y los `.htaccess` de `config/` y `lib/`.
3. Subir también `database/paneles_cp.sql` y `database/.htaccess`. No hace falta subir los otros SQL, documentación ni `tests/`.
4. Abrir `https://chapitour.co/pruebas/chapitour-premia/` e iniciar sesión con una cuenta administradora existente. Si se ejecutó el SQL de preparación entregado en la conversación, el usuario es `laurazoro@gmail.com`. La contraseña de esa cuenta es distinta a la contraseña MySQL.
5. En el primer inicio de sesión de un administrador, la API crea cuatro tablas auxiliares con `CREATE TABLE IF NOT EXISTS`. Conserva las tablas, datos y contactos existentes. El usuario MySQL necesita permiso para crear tablas; alternativamente ejecutar `database/paneles_cp.sql` en phpMyAdmin antes de entrar. Un fallo parcial puede reintentarse: no hay borrados ni modificaciones de estructura del esquema original.
6. Si la cuenta tiene `cambiar_password=1`, elegir una contraseña nueva antes de acceder a sus datos o acciones. Las cuentas nuevas de aliados también exigen este cambio.
7. En Aliados, utilizar **Crear acceso** para asignar un correo y contraseña a un negocio existente sin duplicarlo. En Promociones, completar y confirmar las ofertas con cada negocio antes de aprobarlas.

No se han ejecutado consultas contra Hostinger desde el equipo local ni se ha subido el código al hosting. Esta entrega prepara y verifica los archivos para esa instalación. Se probaron con PHP 8.0.28 y MariaDB 10.4.28 sobre la estructura del export de MariaDB 11.8.9 suministrado.

## Datos y permisos

- Cliente: autenticación en `cp_clientes`, perfil persistente, retos del mes y premios propios vinculados mediante `cp_cliente_visitantes`. Registrar una cuenta no concede visitas, oportunidades ni premios.
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

## Decisiones pendientes: entrega de premios deshabilitada

La integración no concede premios nuevos ni cuenta visitas. La ruleta permanece visible como presentación y su botón de giro está deshabilitado. `prepare_spin` y `spin` también rechazan solicitudes desde el servidor. Falta definir:

- Si el premio corresponde a 8 o 10 visitas válidas.
- Cuánto tiempo debe separar dos visitas y cómo se deduplican.
- Si reemplaza o es independiente de la ruleta anterior de cinco visitas.
- Si el contador se reinicia al cambiar de mes.
- Condiciones y aprobación de cada promoción, especialmente las provisionales.
- Verificación de visitas a negocios, entregas al compartir y fotografías/etiquetas.

La API mantiene esta entrega deshabilitada aunque el sistema anterior tenga `cp_campanas.activa=1`. No activa ni desactiva por su cuenta otras rutas antiguas del sitio. Antes de operar la campaña hay que unificar esas reglas y retirar cualquier entrega paralela del sistema anterior. Aprobar una promoción no activa la campaña.

## Pruebas

La suite actual `tests/acceptance.cjs` verifica la versión conectada, no el prototipo de sesiones. Solo admite un servidor localhost dedicado. `tests/panel-fixtures.php` solo acepta un socket bajo `/private/tmp/chapitour-panel-qa.*`; importa la estructura y catálogo del export y añade fixtures sintéticos dentro de una base temporal independiente llamada `chapitour_panels_qa`.

Para repetir: inicializar una instancia temporal de MariaDB sin puerto de red, ejecutar `panel-fixtures.php setup SOCKET RUTA_EXPORT`, iniciar PHP con las variables `CHAPITOUR_DB_*` de esa instancia y `PHP_CLI_SERVER_WORKERS=4`, y ejecutar `node pruebas/chapitour-premia/tests/acceptance.cjs`. El destino predeterminado es `http://127.0.0.1:8792/pruebas/chapitour-premia/`. Se necesita Playwright y Chrome; `NODE_PATH` puede apuntar a las dependencias locales.

Comprobado: acceso real, cambio obligatorio de clave, CSRF, permisos por cuenta/negocio, CRUD, persistencia entre navegadores, exclusión de borradores, conservación de instantáneas, expiración, redención concurrente única, WhatsApp sin redención, eliminación/revocación, y vistas de escritorio y móvil. Se revisaron capturas de los paneles y del cambio de contraseña. No se enviaron mensajes a negocios.

`tests/database.php` corresponde al modelo SQL anterior sin prefijo y no valida esta integración.
