# Dashboard de superadministrador y dueños

El mismo panel atiende dos perfiles. El rol existente `admin` es el
**superadministrador** y el rol `aliado` es el **dueño de negocio**. Se conservan
las cuentas y contraseñas actuales; no hay que cambiar sus roles en la base.

## Uso del panel

- **Negocios**: el superadministrador crea y edita negocios, su categoría,
  dirección, WhatsApp y habilitación. Un negocio nuevo aparece aunque aún no
  tenga promociones.
- **Dueños y administración → Agregar dueño**: selecciona un negocio habilitado
  y un nombre de usuario. El servidor crea una contraseña aleatoria, la guarda
  como hash y la muestra una sola vez. Entrégala al dueño de forma privada.
  Debe cambiarla al entrar por primera vez. Desde esta sección también puedes
  desactivar/reactivar accesos y restablecer contraseñas.
- **Promociones → Nueva promoción**: el superadministrador elige cualquier
  negocio. Cada dueño solo puede crear y editar las ofertas de su propio negocio.
  Puede haber varias ofertas por negocio. Completa beneficio, condiciones,
  descuento y cupo opcionales, y marca Activar para incluirla en la ruleta.
- Una oferta sin activar queda pausada. Pausar o editar no modifica el beneficio
  ni el vencimiento de los códigos ya entregados. El cupo no puede reducirse
  por debajo de los premios emitidos.
- Se conservan resumen, historial de códigos, validación, redención y auditoría.

Los permisos se aplican también en la API. Enviar el ID de otro negocio, otro
usuario o una promoción ajena no permite saltarse la asignación del dueño.
Crear más promociones no agrega sectores ni mejora las probabilidades de un
negocio en la ruleta: primero se equilibra entre negocios y luego se elige una
de sus ofertas disponibles.

## Actualizar la base que ya existe en Hostinger

1. Exportar una copia de seguridad de `u348170507_chapi_promos`.
2. En esa misma base, importar **solo** `database/005_dashboard_promociones.sql`.
   También se entrega en `entrega-hostinger/privado/005_dashboard_promociones.sql`.
   Quita la restricción de una oferta por negocio y conserva los datos y las
   relaciones. Es repetible. La base sigue teniendo 13 tablas.
3. Completar `db_password` en la configuración privada preparada en
   `entrega-hostinger/public_html/promos/config/local.php`, o conservar el archivo
   ya configurado del servidor. Mantener su `app_key`; no sustituirlo por la clave
   de XAMPP.
4. Si se utiliza el archivo ZIP, regenerarlo con `php promos/bin/zip-hostinger.php`
   después de completar la configuración. Subir el contenido de
   `entrega-hostinger/public_html/` dentro del `public_html` existente. No subir
   `privado/`, pruebas, SQL ni claves de accesos.
5. Abrir `https://chapitour.co/promos/panel/` con el administrador existente.
   Si `cp_usuarios` está vacía, la entrega anterior incluye
   `privado/004_cuentas_panel.sql` y sus credenciales privadas para la primera
   carga. No volver a importarlo si ya existen esas cuentas.
6. Crear un dueño, comprobar su acceso y configurar una promoción real antes de
   activarla. Cada dueño administra solo su negocio.

No importar de nuevo `hostinger_inicial.sql` sobre la base existente. Ese archivo
actualizado sirve exclusivamente para instalaciones vacías.

## Pruebas locales

La copia de `pruebas/promos/panel/` contiene el mismo dashboard. El botón
«Ver promoción otra vez · DEMO» se conserva solo en la portada de `pruebas/`.

Las comprobaciones automatizadas se ejecutan contra bases temporales:

```sh
php tests/promos_dashboard.php
php tests/promos_integration.php --keep-for-browser
php -d session.save_path=/private/tmp -S 127.0.0.1:8774 -t . tests/promos_router.php
php tests/promos_http.php
php tests/promos_hostinger_package.php
```

Se puede definir `CHAPI_TEST_ADMIN_DSN`, `CHAPI_TEST_ADMIN_USER` y
`CHAPI_TEST_ADMIN_PASSWORD` para usar una instancia MySQL local dedicada.
La vista en el puerto 8774 usa datos de demostración de la base temporal, no la
base de Hostinger. El usuario `admin.test` y los usuarios `aliado1.test` a
`aliado6.test` usan la clave de prueba `ChapiTest-only-4829!` únicamente allí.
Nunca se incluyen estas cuentas en el paquete de producción.
