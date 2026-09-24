# Pruebas de Chapitour

Con Apache de XAMPP encendido, abre `http://localhost/chapitour.co/pruebas/`.

El botón rosado **Ver promoción otra vez · DEMO**, abajo a la izquierda, abre
la demostración de la ruleta aunque ya la hayas visto o cerrado. Puedes simular
giros, cerrar la ventana y volver a abrirla tantas veces como quieras, también
después de recargar la página. No necesitas borrar las cookies.

Esta demostración solo está en `pruebas/`: muestra los seis aliados sin depender
de ofertas activas ni de oportunidades disponibles. No genera códigos canjeables,
no consume giros reales y no registra referidos.

El botón **Chapinero te premia** abre el módulo conectado al servidor para
consultar las oportunidades y promociones reales del navegador.

Las reglas actuales del módulo son:

- Un giro de bienvenida; recargar la página no concede otro.
- Apertura automática una vez por oportunidad pendiente, si hay ofertas activas.
  Cerrar la ventana sin girar conserva la oportunidad.
- Códigos válidos durante 72 horas desde su emisión; el negocio los valida y
  redime una sola vez desde su panel.
- Otra oportunidad por cada cinco visitas referidas válidas. Cada amigo debe
  abrir el enlace, esperar al menos diez segundos y confirmar la visita;
  compartir el mensaje de WhatsApp por sí solo no suma. Se comprueban duplicados
  por navegador y red.
- Otra oportunidad cuando el aliado confirma la redención de un código.
- El progreso se conserva mediante una cookie del navegador.

Las reglas se configuran en `promos/config/reglas.php` dentro de esta carpeta.
La guía técnica completa está en `promos/README.md`.

## Dashboard de negocios y dueños

Disponible en `promos/panel/` dentro de esta carpeta. El superadministrador puede
crear negocios y dueños; los dueños crean y editan las promociones de su negocio.
La guía de configuración y actualización de Hostinger está en
[promos/DASHBOARD-HOSTINGER.md](promos/DASHBOARD-HOSTINGER.md).
