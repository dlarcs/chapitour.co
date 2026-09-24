# Entrega de promociones para Hostinger

Archivos preparados localmente. No se han subido ni se han creado las cuentas del panel en Hostinger todavía.

## Actualización del dashboard

Antes de subir este nuevo panel a una base existente, importar
`privado/005_dashboard_promociones.sql` en `u348170507_chapi_promos`.
Permite varias promociones por negocio y conserva el historial.
El rol `admin` actual es el superadministrador: crea negocios y dueños desde
el panel. Los dueños pueden gestionar sus propias promociones.
Consultar `DASHBOARD-HOSTINGER.md` para el procedimiento completo.

## Contenido

- `public_html/`: actualización de la portada y del módulo de promociones. Copiar su contenido dentro del `public_html` existente de **chapitour.co**.
- `privado/004_cuentas_panel.sql`: siete cuentas listas para importar en **u348170507_chapi_promos**; contiene hashes de las contraseñas.
- `privado/ACCESOS-PANELES.md`: usuarios y claves temporales para entregar individualmente.
- `privado/hostinger_inicial.sql`: copia del esquema inicial, **ya importado**. No volver a ejecutarlo en esta base.
- `chapitour-promociones.zip`: contiene solamente los archivos de `public_html/`, sin la carpeta privada ni las claves de los paneles.

El ZIP es una actualización del sitio PHP existente: incluye `index.php`, `home/promocion/` y `promos/`. Las otras secciones, imágenes, CSS y JS del sitio deben conservarse en el hosting. No es una copia completa del sitio.

## Único dato de conexión pendiente

Editar `public_html/promos/config/local.php` y reemplazar `PENDIENTE_CONTRASENA_MYSQL` por la contraseña que se definió al crear el usuario MySQL en Hostinger. No es la contraseña de `admin` ni de un establecimiento.

Ya están configurados:

- Base: `u348170507_chapi_promos`.
- Usuario MySQL: `u348170507_chapi_app`.
- Servidor: `localhost`, para el PHP ejecutándose en Hostinger.
- URL: `https://chapitour.co`.
- Cookies seguras y clave privada nueva para esta instalación. Conservar esa clave entre actualizaciones.

Referencia del servidor: [documentación oficial de Hostinger](https://www.hostinger.com/support/1583226-which-database-management-system-is-used-at-hostinger/).

## Cuando se suba a Hostinger

1. Completar la contraseña MySQL en `public_html/promos/config/local.php`.
2. Si se utilizará el ZIP, regenerarlo después de editar ese archivo: `php promos/bin/zip-hostinger.php`, desde la raíz del proyecto local.
3. Conservar una copia de la portada y los archivos que ya existan en el hosting antes de reemplazarlos.
4. En phpMyAdmin, seleccionar **u348170507_chapi_promos** e importar **una sola vez** `privado/004_cuentas_panel.sql`. Este INSERT crea siete cuentas juntas; no restablece contraseñas existentes.
5. Subir únicamente el contenido de `public_html/`, o extraer el ZIP directamente dentro del `public_html` del sitio. No subir la carpeta `entrega-hostinger` completa ni `privado/`.
6. Entrar en `https://chapitour.co/promos/panel/` con la cuenta `admin` y cambiar la clave temporal.
7. Configurar los beneficios y condiciones reales y activar las promociones.

Las seis cuentas de los aliados también exigen cambio de contraseña al entrar por primera vez. La configuración local de XAMPP permanece separada de estos archivos.

No ejecutar de nuevo `prepare-hostinger.php` sobre esta entrega: conserva las cuentas y sus claves. El comando se detiene si la carpeta ya existe.
