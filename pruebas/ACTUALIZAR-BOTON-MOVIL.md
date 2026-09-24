# Botón de pruebas en pruebas.chapitour.co

Diagnóstico confirmado en el navegador: `TypeError: original.cloneNode is not a function`,
en `home/promocion/promocion.demo.js:17`. La entrada carga el controlador actual,
pero recibe una versión incompatible del módulo de demostración. La excepción
interrumpe la inicialización y deja el botón con el atributo `hidden`.

La corrección local actualiza los módulos juntos y cambia sus URLs de importación
para que el navegador solicite las versiones actuales. Además incluye un botón
ancho para celular, margen para el área inferior del dispositivo y acceso en el menú.

## Actualización en Hostinger

1. Abre el administrador de archivos del subdominio **pruebas.chapitour.co**.
2. Conserva una copia de los seis archivos que se reemplazarán.
3. Extrae `actualizacion-boton-movil.zip` en la raíz de ese subdominio, junto a
   su `index.php`. Las rutas internas empiezan en `home/`; no crees otra carpeta
   `pruebas` dentro del subdominio.
4. Si hay caché de página/CDN habilitada, purga la caché de este subdominio.
5. Recarga en el celular, cierra la promoción si se abre automáticamente y pulsa
   **Ver promoción otra vez · PRUEBAS**. También está en el menú principal.

El paquete contiene solo esos seis archivos del botón. No contiene credenciales,
configuración de conexión ni migraciones SQL. No modifica la base de datos.

La corrección fue verificada en la copia local a 390 px de ancho. El ZIP está
preparado para subir; no se ha publicado desde esta sesión en Hostinger.
