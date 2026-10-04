# Recordatorios de vencimiento

Remitente: **admin@chapitour.co**, mediante SMTP de Hostinger. Destinatario: correo de la cuenta vinculada al premio, incluido Gmail. El proceso funciona sin que el cliente abra la página.

El cron consulta cada cinco minutos los premios sin redimir que entran en sus últimas 24 horas: normalmente avisa entre 24 horas y 23 horas 55 minutos antes del vencimiento. Recupera ejecuciones atrasadas mientras el premio siga vigente. Excluye premios usados o vencidos, cuentas inactivas, invitados sin vincular y correos inválidos. Incluye negocio, beneficio, código, condiciones, vencimiento en Bogotá y acceso a Mis promociones.

## Activar en Hostinger

1. Publicar las bibliotecas nuevas `ExpiryReminders.php`, `ReminderDeliveryError.php`, `ReminderMessage.php`, `ReminderMailer.php`; `bin/expiry-reminders.php` y `bin/.htaccess`; `database/recordatorios_cp.sql`; `config/mail.php` y `config/mail.example.php`; `composer.json`, `composer.lock` y `vendor/` actualizado. Conservar archivos privados y `.htaccess` existentes. PHPMailer necesita también el autoloader actualizado, no solo su carpeta.
2. Crear `premia/config/mail.local.php` desde el ejemplo, con permiso `600`. Configurar `smtp.hostinger.com`, puerto `465`, cifrado `ssl`, usuario/remitente `admin@chapitour.co` y la contraseña **del buzón**. Guardarla directamente en el archivo privado, nunca en Git, ZIP, URL, comandos o conversaciones. Cambiar `enabled` a `true` cuando se vaya a activar.
3. Ejecutar una vez, reemplazando `RUTA_PRIVADA_DEL_SITIO` por la ruta absoluta real de `public_html`:

   ```sh
   php RUTA_PRIVADA_DEL_SITIO/premia/bin/expiry-reminders.php --install
   php RUTA_PRIVADA_DEL_SITIO/premia/bin/expiry-reminders.php --check
   php RUTA_PRIVADA_DEL_SITIO/premia/bin/expiry-reminders.php --dry-run
   ```

   `--install` añade una tabla sin alterar premios o cuentas. `--check` valida configuración y esquema, **no confirma autenticación SMTP ni recepción**. `--dry-run`, también modo predeterminado, solo devuelve conteos; no escribe ni envía.

4. Ejecutar una sola vez `php RUTA_PRIVADA_DEL_SITIO/premia/bin/expiry-reminders.php --test`. Envía un mensaje claramente identificado como prueba únicamente al remitente configurado, sin consultar premios ni activar envíos generales. Admite `enabled=false`. Un resultado positivo confirma aceptación SMTP; comprobar también la bandeja de entrada o spam del buzón.

5. En tareas cron de Hostinger, seleccionar **cada cinco minutos**, todos los días, y este comando con la ruta real:

   ```sh
   php RUTA_PRIVADA_DEL_SITIO/premia/bin/expiry-reminders.php --send
   ```

   Usar el ejecutable PHP indicado por hPanel si requiere una ruta absoluta. Configurar una sola tarea en producción; la copia de pruebas no necesita otro cron. Guardar los registros fuera de `public_html`.
6. Si el proveedor rechaza mensajes, revisar la autenticación del dominio y los límites de envío en el panel de correo.

Referencia: [configuración oficial de Hostinger](https://www.hostinger.com/es/support/1575756-como-obtener-los-detalles-de-configuracion-de-la-cuenta-de-email-para-el-correo-de-hostinger/) y [PHPMailer](https://github.com/PHPMailer/PHPMailer).

Se admiten variables privadas `CHAPITOUR_MAIL_ENABLED`, `CHAPITOUR_MAIL_HOST`, `CHAPITOUR_MAIL_PORT`, `CHAPITOUR_MAIL_ENCRYPTION`, `CHAPITOUR_MAIL_USERNAME`, `CHAPITOUR_MAIL_PASSWORD`, `CHAPITOUR_MAIL_FROM_EMAIL` y `CHAPITOUR_MAIL_FROM_NAME`. No colocar la contraseña en el comando del cron.

## Reintentos y seguimiento

`cp_panel_recordatorios` guarda una fila por premio, un identificador estable y su estado; no copia nombres o correos. `sent` significa **aceptado por SMTP**, no lectura ni llegada confirmada a la bandeja de entrada. No se mandan copias a otros destinatarios.

Un bloqueo de MySQL impide ejecuciones simultáneas. Se comprueban nuevamente la cuenta y el premio inmediatamente antes de enviar y se bloquean esas filas durante la entrega SMTP. La reserva `sending` se persiste antes de entregar para reconocer interrupciones.

Los fallos anteriores a la transferencia se reintentan después de quince minutos, hasta tres intentos. Si se interrumpe una vez iniciado `DATA`, o desaparece el proceso durante un envío, se marca `uncertain` y se requiere revisión del proveedor. No se reenvía automáticamente: SMTP no permite saber si un mensaje con confirmación perdida ya fue aceptado. El `Message-ID` estable ayuda a investigar, pero no garantiza deduplicación en destino.

El comando informa `sent`, `retry`, `uncertain`, `skipped`, `busy` y `needs_review`. Salida `0`: ejecución normal; `1`: configuración/esquema/error; `2`: reintentos o revisión pendiente. Consultar `error_codigo` sin volcar credenciales ni mensajes. Los reintentos agotados pueden habilitarse individualmente después de corregir SMTP; nunca reiniciar estados `sent` o `uncertain` sin comprobar la entrega.

## Validación local

`tests/expiry-reminders.php SOCKET_QA`, dentro de `pruebas/chapitour-premia`, solo acepta un socket bajo `/private/tmp/chapitour-panel-qa.*`. Crea y elimina una base desechable, copia solo estructuras QA e inyecta SMTP simulado. Comprueba umbral, destinatario, exclusiones, repetición, reintentos, concurrencia, interrupciones, cambios antes del envío, escape HTML, Bogotá y MIME.

El 4 de octubre de 2026 se verificó la configuración privada y Hostinger aceptó un correo de prueba dirigido a `admin@chapitour.co`, ejecutado desde el entorno local. La recepción en bandeja todavía no está confirmada. Pendiente: publicar, instalar la tabla y activar el cron en Hostinger.
