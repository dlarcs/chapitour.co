# Promociones de Chapitour: entorno de pruebas

Vista local aislada: <http://127.0.0.1:8785/pruebas/>. Usa una base temporal
con promociones ficticias, sin canje en establecimientos reales.

- **Ver promoción otra vez · PRUEBAS** conserva el acceso a la ruleta. Si no hay
  oportunidades pendientes, concede una de prueba. Usa el flujo completo de la
  base de pruebas, con códigos y seguimiento; ya no genera resultados simulados
  solo en el navegador.
- Un giro inicial y otro cada **5 visitas adicionales** al inicio: visitas
  1, 6, 11… En esta copia recargar cuenta como visita. Cerrar la ventana conserva
  la oportunidad y no vuelve a abrirla automáticamente hasta una nueva oportunidad.
- El negocio seleccionado tiene una promoción activa. Su comprobante guarda
  negocio, oferta, dirección, condiciones, WhatsApp y código `CHAPI-SG-000000001`.
  El número procede del ID único de la base de datos; no se reutiliza en la aplicación.
- Cada código vence exactamente **72 horas** después del giro. Tiene estados
  **activo**, **redimido** o **vencido**, con sus fechas. Solo un dueño del negocio
  correspondiente puede confirmar la redención; el superadministrador consulta
  y administra. Abrir WhatsApp no equivale a redimir.
- El mensaje que aparece en el comprobante es exactamente el preparado en
  **Reclama por WhatsApp**. El cliente decide si lo envía.
- Cada **8 invitados confirmados** conceden un giro adicional. Cada persona abre
  su enlace, espera 10 segundos y confirma. Abrir WhatsApp o compartir por sí solo
  no suma: la web no puede comprobar cuántos mensajes se enviaron. Se controlan
  duplicados por navegador, cuenta y red. Otra oportunidad se concede al redimir.

## Dashboards

- Clientes: <http://127.0.0.1:8785/pruebas/promos/cliente/>, todos los códigos con
  filtros por estado y paginación. Funciona sin registro mediante una cookie
  privada; al registrarse o iniciar sesión vincula el historial de ese navegador.
  La IP se guarda como hash para controlar abuso, nunca para unir personas que
  comparten red. Cerrar sesión oculta el historial de la cuenta.
- Superadministrador y negocios: <http://127.0.0.1:8785/pruebas/promos/panel/>.
  El superadministrador agrega negocios y dueños, y ambos gestionan promociones
  según sus permisos. Los códigos y su seguimiento coinciden con la vista del cliente.
- Explorador, Gold y Platino muestran metas de demostración de 0, 5 y 15
  redenciones. Los nombres y beneficios definitivos están por definir.

Editar una oferta no elimina ni reinicia los códigos emitidos, sus condiciones
o el progreso del cliente. Las nuevas condiciones se usan en los siguientes giros.

## Base de datos y validación

Los cambios de esta etapa están solo en `pruebas/`. No se han publicado en Hostinger.
Para una base existente, después de las migraciones del dashboard, ejecutar
`promos/database/006_clientes_seguimiento.sql`; agrega tres tablas sin borrar las
anteriores. Las instalaciones nuevas usan `001_schema.sql` o `hostinger_inicial.sql`,
que ya incluyen esas tablas. No ejecutar el instalador de datos ficticios en producción.
La guía previa del panel está en [DASHBOARD-HOSTINGER.md](promos/DASHBOARD-HOSTINGER.md).

Reglas: `promos/config/reglas.php`. Pruebas nuevas:
`tests/promos_customers.php` y `tests/promos_customers_http.php`. La primera crea
su propia base temporal; `--keep-for-browser` la conserva para el router local
`tests/customer_router.php`. La segunda verifica sesiones y permisos por HTTP
contra ese router en el puerto 8785.

Accesos ficticios de esa base temporal: `admin.test` (superadministrador),
`aliado1.test` a `aliado6.test` (negocios) y `cliente.prueba@example.test`
(cliente con historial). Contraseña exclusivamente local: `ChapiTest-only-4829!`.
No son credenciales de Hostinger.
