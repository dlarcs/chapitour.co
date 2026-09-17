# Chapinero te premia — PHP, PDO y MySQL

Implementación integrada en la página existente. No usa Node.js, npm ni un servidor adicional en producción. Compatible con PHP 8.0+ y MySQL 8 / MariaDB 10.4+ con InnoDB, PDO MySQL, mbstring, sesiones y HTTPS en producción.

## Estado de esta instalación local

- Base: `chapitour_promos`. Usuario de conexión: `chapitour_app`, limitado a SELECT/INSERT/UPDATE/DELETE en esa base.
- Panel: `http://localhost/ChapiTour/promos/panel/`.
- Accesos independientes: `admin`, `street-grill`, `capital-queer`, `jimar-factory`, `garage-disco-bar`, `pictogramas`, `gran-chela`.
- Contraseñas aleatorias: `promos/storage/accesos-iniciales.php`. Archivo privado, ignorado por Git y bloqueado por HTTP. Ábrelo con el editor. El panel exige cambiar la contraseña inicial.
- **Las seis promociones están desactivadas hasta completar el beneficio y las condiciones reales.** No hay descuentos de demostración en esta base. Desde administración, entra a “Negocios y promociones”, edita cada oferta y actívala.
- Cuando no hay ofertas disponibles, la ruleta no aparece automáticamente ni consume oportunidades. El botón flotante explica el estado.

## Recorrido del código

`evento del navegador → promocion.control.js → makeAjaxRequest → promos/api/index.php → ChapiSecurity → ChapiController → ChapiPromocionModel/ChapiPanelModel → PDO → MySQL → JSON → makeAjaxRequest → función del control → promocion.ui.js / ruleta.js`.

- `home/promocion/promocion.js`: entrada del módulo.
- `home/promocion/promocion.control.js`: eventos, solicitudes, reintentos de giro, invitaciones, sincronización.
- `home/promocion/promocion.ui.js`: pantallas, historial, condiciones, contador de vigencia.
- `home/promocion/ruleta.js`: dibujo y animación; nunca decide el ganador.
- `promos/assets/makeAjaxRequest.js`: JSON, CSRF, errores, tiempo de espera.
- `promos/api/index.php`: punto de entrada y respuestas sin información interna.
- `promos/src/ChapiSecurity.php`: cookies, IP, CSRF, origen, tamaños, límites y validaciones.
- `promos/src/ChapiController.php`: autoriza y dirige cada acción hacia el modelo.
- `promos/src/ChapiPromocionModel.php`: visitas, elegibilidad, premios, referidos y eventos.
- `promos/src/ChapiPanelModel.php`: acceso, consultas por negocio, redención, métricas, edición y contraseñas.
- `promos/config/local.php`: conexión y clave privada. No se sube al repositorio.
- `promos/config/reglas.php`: reglas que puedes cambiar directamente.

## Cambiar la frecuencia, días y oportunidades

Edita únicamente `promos/config/reglas.php`. Los cambios se leen en la siguiente solicitud.

```php
'frecuencia' => 'primera_vez',
'cada_visitas' => 5,
'cada_dias' => 7,
'min_segundos_entre_visitas' => 1800,
'vigencia_horas' => 72,
'amigos_requeridos' => 5,
'premiar_redencion' => true,
'identificacion' => 'anonimo',
```

- **Solo la primera vez:** `frecuencia = primera_vez`. Los próximos giros se obtienen por referidos o redenciones.
- **Cada 5 visitas adicionales:** `frecuencia = cada_visitas`, `cada_visitas = 5`. Ejemplo: visitas 1, 6, 11, 16.
- **Cada 10 visitas adicionales:** cambia solo `cada_visitas` a `10`.
- **Cada 7 días:** `frecuencia = cada_dias`, `cada_dias = 7`. Se concede al regresar después del plazo, sin cron.
- **Otro plazo:** cambia `cada_dias` o `vigencia_horas` según corresponda. La vigencia nueva se aplica a los premios futuros; los códigos existentes conservan su vencimiento.
- Una visita cuenta cuando han pasado 1800 segundos desde la anterior. Recargar repetidamente no incrementa el contador. Puedes ajustar ese número.
- El modal se abre automáticamente una vez por oportunidad pendiente, cuando hay ofertas disponibles. Cerrarlo no consume el giro. El botón flotante permite regresar.
- El cambio de una regla no borra los premios ni revoca giros ya concedidos.

## Identidad anónima e IP

El modo recomendado y predeterminado es `anonimo`: una cookie aleatoria HttpOnly, SameSite=Lax, duración de 365 días; la base almacena su HMAC. Conserva el historial sin pedir datos personales. La IP se obtiene de `REMOTE_ADDR`, se normaliza y se guarda como HMAC con una clave privada. No se guarda en texto plano ni se confía en `X-Forwarded-For` enviado por el cliente.

`identificacion = ip` permite agrupar por la IP de la solicitud, pero todas las personas de una misma red compartirían progreso y códigos. Las IP dinámicas pueden cambiar la identidad. Por eso no es el modo por defecto. Cambiar entre los dos modos crea un espacio de identidad diferente: no migra automáticamente las cookies anteriores.

`max_bienvenidas_por_ip_dia = 3` limita los giros iniciales por red y día aunque se creen nuevas cookies. Una identidad a la que se deniega esa bienvenida no recibe después otra bienvenida automáticamente. Si tus visitantes comparten mucho una red, ajusta el límite.

Sin registro, borrar cookies o cambiar de navegador pierde el acceso al historial. Este mecanismo reduce duplicados, pero no garantiza identificar personas únicas frente a cambios de dispositivos o redes.

## Cinco visitas referidas válidas por WhatsApp

La regla confirmada es contar visitas referidas, **no mensajes enviados**. El botón de WhatsApp prepara el mensaje con un enlace propio del visitante. El usuario decide enviarlo.

El amigo debe abrir el enlace, permanecer al menos 10 segundos y pulsar “Confirmar mi visita”. El servidor comprueba el plazo, la sesión, CSRF, la referencia y los duplicados. Una identidad cuenta una sola vez por campaña, aunque abra otro enlace o recargue. La autoinvitación se rechaza.

Con `referidos_ip_distinta = true`, la red del propietario no suma y una misma IP solo suma una vez para ese propietario. Amigos en una misma Wi-Fi pueden no contar; se puede desactivar esa comprobación manteniendo la identidad anónima. No se consultan contactos ni se automatiza el envío de mensajes.

Al alcanzar 5, 10, 15… visitas válidas se concede una oportunidad por cada meta. El progreso se obtiene de la base. No existe un botón de simulación. Se refresca al volver a la pestaña, manualmente y cada 30 segundos mientras el modal esté abierto.

Referencia: [clic para chatear de WhatsApp](https://faq.whatsapp.com/5913398998672934/?locale=es_LA).

## Tick del establecimiento

1. El aliado inicia sesión con su usuario.
2. Consulta el código del cliente.
3. El servidor comprueba el negocio, vencimiento y uso previo.
4. El empleado marca “El cliente está presente y utilizó esta promoción”.
5. Pulsa “Confirmar visita y redimir código”.
6. La redención, auditoría y nueva oportunidad se guardan dentro de la misma transacción.

El código no se puede reutilizar. Un doble clic o dos peticiones simultáneas solo producen una redención y una nueva oportunidad. `premiar_redencion = false` desactiva únicamente el nuevo giro. El cliente conserva sus otros códigos activos y puede elegirlos en “Mis promociones”.

## Paneles y permisos

El mismo panel muestra exclusivamente los datos del negocio asociado a la cuenta. El servidor aplica ese filtro a métricas, consulta y redención; no depende de un ID enviado por el navegador.

Administración puede ver todos los aliados de la campaña, modificar WhatsApp, dirección, beneficio, condiciones, porcentaje opcional, cupos y activación. También ve cuentas, registros y auditoría. Puede restablecer claves temporales; la clave se muestra una sola vez y las sesiones anteriores se invalidan. Los aliados pueden validar, redimir, revisar su rendimiento y cambiar su contraseña.

Las métricas filtran por **fecha de emisión del premio** usando días de Bogotá: emitidos, activos, vencidos, redimidos, aperturas de WhatsApp y tasa de redención. Las aperturas son eventos de navegación y pueden repetirse; no significan mensaje enviado ni visita al negocio. La redención confirmada por el aliado sí proviene del servidor.

## Instalar desde cero con terminal

Desde la raíz del proyecto, usando PHP de XAMPP:

```sh
/Applications/XAMPP/xamppfiles/bin/php promos/bin/install.php --base-url=http://localhost/ChapiTour
```

El instalador crea una base dedicada, un usuario PDO con permisos limitados y siete cuentas con claves aleatorias. No sobrescribe una instalación existente. Requiere una cuenta MySQL con permisos de crear base y usuario. Para credenciales administrativas distintas usa las variables de entorno `CHAPI_INSTALL_DSN`, `CHAPI_INSTALL_USER`, `CHAPI_INSTALL_PASSWORD` (sin escribir secretos en Git).

## Instalar manualmente / phpMyAdmin

1. Importa `database/000_database.sql`, o crea una base dedicada con UTF-8 si el alojamiento impone otro nombre.
2. Selecciona esa base e importa `database/001_schema.sql` y después `database/002_negocios.sql`.
3. Crea un usuario MySQL limitado a SELECT, INSERT, UPDATE y DELETE en esa base.
4. Copia `config/local.example.php` a `config/local.php` y configura DSN, usuario, contraseña, URL pública y `secure_cookies = true` en HTTPS.
5. Genera una clave privada con `php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'` y colócala en `app_key`. Conserva esa clave entre despliegues: cambiarla invalida la identidad anónima y las referencias de IP.
6. Crea las cuentas desde terminal:

```sh
php promos/bin/accounts.php --usuario=admin --admin
php promos/bin/accounts.php --usuario=street-grill --negocio=1
php promos/bin/accounts.php --usuario=capital-queer --negocio=2
php promos/bin/accounts.php --usuario=jimar-factory --negocio=3
php promos/bin/accounts.php --usuario=garage-disco-bar --negocio=4
php promos/bin/accounts.php --usuario=pictogramas --negocio=5
php promos/bin/accounts.php --usuario=gran-chela --negocio=6
```

Cada ejecución escribe una credencial temporal en `storage/acceso-USUARIO.php`. No hay una contraseña predeterminada compartida. Para recuperar una cuenta: `php promos/bin/accounts.php --usuario=admin --reset`.

Las tablas se crean con claves únicas, relaciones e índices. Los premios incluyen una copia de la oferta y fechas UTC; no es necesario cambiar filas periódicamente para declarar un código vencido, porque se verifica el vencimiento en cada consulta/redención.

## Seguridad y despliegue

- El único punto público de operaciones es `promos/api/index.php`.
- Las carpetas `config`, `src`, `database`, `storage` y `bin` rechazan acceso HTTP mediante `.htaccess`, además de guardas PHP.
- En un servidor Nginx debes configurar bloqueos equivalentes; `.htaccess` solo aplica a Apache. No uses el servidor PHP integrado sin un router que bloquee esos directorios.
- Configura la URL pública real antes de compartir enlaces. `localhost` solo funciona en tu propio equipo.
- Activa HTTPS y `secure_cookies = true`. Si hay un proxy, configura sus IP de confianza a nivel servidor; la aplicación no acepta cabeceras de IP arbitrarias.
- Contraseñas mediante `password_hash`/`password_verify`, cambio inicial obligatorio, sesiones regeneradas y vencimiento por inactividad.
- SQL mediante PDO preparado, validaciones, CSRF, mismo origen, límite de tamaño, límites de intentos y permisos en servidor.
- La solicitud de giro lleva una clave de idempotencia. Reintentar esa solicitud devuelve el mismo código.
- Las operaciones críticas usan un bloqueo de campaña y transacciones. Para esta escala prioriza consistencia; con volúmenes altos se puede subdividir el bloqueo manteniendo las mismas garantías.
- No subir `config/local.php`, contraseñas, datos de prueba ni `tests/` al alojamiento público. La cuenta web no necesita permisos de crear tablas ni usuarios.

Referencias: [PDO preparado](https://www.php.net/manual/en/pdo.prepared-statements.php), [password_hash](https://www.php.net/manual/en/function.password-hash.php).

## Mantenimiento y pruebas

```sh
php promos/bin/maintenance.php
php tests/promos_integration.php
```

El mantenimiento elimina solo contadores de límites ya vencidos e IP diarias con más de 7 días. Conserva premios, referidos y auditoría. No se configuró ninguna tarea programada.

Las pruebas crean una base aleatoria `chapitour_test_*`, verifican premios, idempotencia, permisos, redenciones, referidos, frecuencias y equidad; la eliminan al terminar. Requieren permisos administrativos locales sobre bases de prueba. No escriben en `chapitour_promos`.

Para pruebas HTTP y navegador: ejecuta `php tests/promos_integration.php --keep-for-browser`, luego `php -S 127.0.0.1:8774 -t . tests/promos_router.php`. Ejecuta `php tests/promos_http.php` y `php tests/promos_concurrency.php`. La configuración temporal está en `/private/tmp/chapi-test-environment.json`. El router y las cuentas de prueba no se usan en producción.

Al terminar, detén ese servidor y ejecuta `php tests/promos_cleanup.php`. La limpieza solo admite el nombre aleatorio de la base temporal creada por estas pruebas.
