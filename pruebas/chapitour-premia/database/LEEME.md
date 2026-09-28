# SQL inicial · Chapitour te premia

Archivo: `chapitour_premia_pruebas.sql`.

Base independiente: **chapitour_premia_pruebas**. Instalación inicial para una base vacía, validada con **MariaDB 10.4.28 de XAMPP**. No se debe reimportar sobre datos existentes; no es una migración incremental.

## Incluye

- Las 10 tablas del modelo, claves primarias y foráneas, restricciones e índices.
- La vista `v_codigos_estado`, que calcula Activo, Redimido y Vencido al consultar.
- Guardas para impedir premios de borradores, duplicación de oportunidades/códigos, redenciones vencidas o por otro negocio, y modificaciones que reactiven un código.
- Los seis negocios y las seis ofertas indicadas por el usuario. Las cuatro ofertas de referencia se distinguen de las dos provisionales por `origen`. Todas comienzan en borrador, porque todavía faltan contactos y aprobaciones comerciales.
- La regla de visitas deshabilitada con las cuatro decisiones pendientes en `NULL`.
- Los tres retos medibles del mes en que se importa, calculado en horario de Bogotá. No hay progresos ficticios, códigos, visitas ni premios precargados.

## Administrador de la aplicación

- Nombre: Laura.
- Correo: **laurazoro@gmail.com**.
- Rol: `administrador`.
- Contraseña: hash bcrypt generado con PHP; la contraseña temporal se entrega en la conversación, no se incluye en texto plano en el SQL.
- `debe_cambiar_password = 1`: el backend deberá comprobar esta marca y exigir una nueva contraseña en el primer acceso.

Es una cuenta de la aplicación, no un usuario administrador del servidor MySQL. No se crea una cuenta de correo ni se envía un email.

## Importación

En phpMyAdmin de la instancia de pruebas, usar **Importar** y seleccionar el archivo SQL. La cuenta de conexión necesita permisos para crear la base, tablas, vista y triggers. El archivo crea y selecciona explícitamente `chapitour_premia_pruebas`.

También puede importarse con el cliente de XAMPP desde la raíz del proyecto:

```sh
/Applications/XAMPP/xamppfiles/bin/mysql -u TU_USUARIO -p < pruebas/chapitour-premia/database/chapitour_premia_pruebas.sql
```

No contiene `DROP`, no altera otras bases, no crea credenciales del servidor ni conecta automáticamente la aplicación.

## Conexión posterior del backend

La interfaz actual sigue usando sesiones de demostración. Para utilizar este administrador y datos compartidos entre dispositivos hay que conectar el backend a esta base, adaptar sus roles al esquema (`cliente`, `aliado`, `administrador`) y completar el cambio de contraseña.

- Autenticar con `password_verify`, regenerar la sesión al iniciar sesión y aplicar `debe_cambiar_password`.
- Ejecutar `SET time_zone = '+00:00'` en cada conexión y mostrar fechas en `America/Bogota`.
- Filtrar códigos por el cliente o el negocio de la sesión. El SQL no sustituye la autorización de las consultas.
- Tomar `confirmado_por` de la sesión autenticada, nunca de un campo confiado del navegador.
- Al emitir, bloquear la oportunidad y la promoción elegible dentro de una transacción. El trigger fija generación UTC, vencimiento a 72 horas e instantánea comercial. `oportunidad_id` único evita premios duplicados.
- Para insertar códigos puede suministrarse `JSON_OBJECT()` en `oferta_otorgada` y `UTC_TIMESTAMP(6)` en `vence_en`; el trigger sustituye ambos por los valores definitivos.
- El conteo de entradas, el intervalo mínimo y el otorgamiento del ciclo deben implementarse con bloqueo por cliente y regla. Las claves únicas evitan reintentos idénticos, pero no demuestran por sí solas que una visita sea válida o que se haya completado un ciclo.
- La restricción `grupo_habilitado` permite una sola regla activa por grupo de entrega. No crear otro grupo para eludir la decisión pendiente sobre la ruleta anterior.
- La caducidad de premios no necesita un cron: la vista consulta la hora actual. Crear los retos de cada nuevo mes sí requerirá un proceso del backend; el SQL inicial solo carga el mes de importación.
- Eliminar datos personales requerirá el flujo específico de eliminación/anonimización y la política de conservación todavía pendiente. No quitar las guardas de premios para resolverlo con borrados indiscriminados.

## Verificación realizada

El SQL se importó en una instancia temporal separada, sin puerto de red y fuera de los datos de XAMPP existentes. Se verificaron:

1. Creación de 10 tablas, vista, administrador, hash bcrypt, seis negocios y seis promociones en borrador.
2. Email único, relación rol/negocio y rechazo de reglas incompletas.
3. Bloqueo de visitas/premios con reglas desactivadas y de progreso sin criterio de verificación.
4. Exclusión de borradores, código único y una sola emisión por oportunidad.
5. Vigencia de 72 horas e instantánea de oferta sin cambios retroactivos.
6. Redención por el aliado correcto o administrador; rechazo de clientes, otros negocios y códigos vencidos.
7. Imposibilidad de redimir dos veces, eliminar la redención, extender vigencia o reutilizar la oportunidad eliminando el código.
8. Cambio automático a Vencido y prioridad de Redimido después de vencer.

Los datos sintéticos de QA se revirtieron. El SQL entregado no contiene teléfonos ni promociones de los fixtures. La prueba repetible está en `../tests/database.php` y solo admite un socket de instancia temporal bajo `/private/tmp/chapitour-sql-qa.*`.
