# Crear la base de pruebas en Hostinger

> **Integración actual:** los paneles ya usan la base existente con tablas `cp_`. Ver [README de los paneles](../README.md). Este documento describe la propuesta SQL anterior sin prefijo; no se debe importar sobre la base `u348170507_chapi_promos`. Para los paneles actuales solo se añaden las cuatro tablas de `database/paneles_cp.sql`, automáticamente en el primer acceso de un administrador.


Estas instrucciones corresponden al hosting PHP con hPanel, no a un VPS. Mantener el trabajo en pruebas: importar la base no conecta automáticamente la interfaz ni habilita la campaña.

## 1. Abrir la administración de bases

En hPanel, entrar a **Sitios web → Administrar / Panel de control** del sitio Chapitour. Buscar **Bases de datos → Administración**. Comprobar que el dominio seleccionado sea el correcto.

## 2. Crear una base y su usuario

En el formulario de creación, usar por ejemplo el sufijo `chapi_pruebas` para la base y `chapi_app` para el usuario. Generar una contraseña nueva y guardarla en un gestor de contraseñas. Pulsar **Crear**.

Hostinger antepone un prefijo propio a los nombres. Guardar los nombres completos que muestre el panel; no usar los ejemplos literalmente ni quitar el prefijo.

Guardar cuatro datos para la conexión posterior:

| Dato | Valor |
| --- | --- |
| Servidor | `localhost`, cuando PHP se ejecute en ese mismo hosting |
| Base | El nombre completo mostrado por hPanel |
| Usuario MySQL | El nombre completo mostrado por hPanel |
| Contraseña MySQL | La contraseña elegida al crear la base |

Este usuario MySQL es la conexión de PHP al servidor de datos. La administradora `laurazoro@gmail.com` es una cuenta distinta, dentro de Chapitour. No usar el correo como usuario MySQL ni la clave de la aplicación como contraseña de la base.

## 3. Importar estructura y datos

Abrir **phpMyAdmin** desde la fila de la base nueva. Seleccionarla y verificar que esté vacía. Abrir **Importar**, seleccionar `chapitour_premia_hostinger.sql`, mantener las opciones predeterminadas y pulsar **Importar / Continuar / Go**, según el idioma de la pantalla.

No es necesario editar el nombre de la base dentro del archivo: usa la base seleccionada en phpMyAdmin. Importar solamente esta variante; no importar además el archivo de XAMPP. El SQL se selecciona desde el computador en phpMyAdmin; no necesita subirse a `public_html`.

Si falla, guardar el mensaje de error. La creación de tablas puede quedar aplicada parcialmente aunque una instrucción posterior falle: no repetir la importación ni borrar tablas de otra base sin revisar qué se creó.

## 4. Comprobar la carga

Deben aparecer **10 tablas y una vista** llamada `v_codigos_estado`. En **Examinar**, comprobar:

| Tabla | Datos iniciales |
| --- | --- |
| `usuarios` | Laura, `laurazoro@gmail.com`, rol `administrador`, cambio de contraseña pendiente |
| `negocios` | Los seis aliados del proyecto; ningún teléfono inventado |
| `promociones` | Seis ofertas en borrador; Pictogramas y Jimar Factory con origen provisional |
| `reglas_incentivo` | Una regla deshabilitada; decisiones pendientes sin completar |
| `retos_mensuales` | Tres retos para el mes de importación en Bogotá |

Las otras cinco tablas comienzan vacías. No hay clientes, visitas, progresos ni premios ficticios. Registrar los seis negocios no crea las cuentas de acceso de sus aliados.

No hace falta insertar esos datos manualmente: ya vienen en el archivo. La contraseña temporal de Laura se entregó en la conversación; el SQL contiene únicamente su hash bcrypt. No escribir una contraseña en texto plano en `password_hash`.

## 5. Completar los datos reales

Antes de publicar ofertas, confirmar con cada negocio: beneficio, productos o servicios incluidos, horarios, restricciones y número de WhatsApp. Confirmar especialmente las ofertas provisionales de Pictogramas y Jimar Factory. El sistema todavía no conoce los correos de acceso de los aliados.

Definir también si el premio corresponde a 8 o 10 entradas, cuánto tiempo separa dos visitas válidas, su relación con la ruleta anterior y si el contador se reinicia mensualmente. No activar la regla mientras estas decisiones sigan pendientes.

## 6. Conectar la aplicación: trabajo de desarrollo pendiente

La interfaz actual utiliza sesiones de demostración. No basta con importar el SQL o escribir las credenciales para convertirla en una aplicación conectada.

El backend debe recibir las cuatro credenciales mediante configuración privada del servidor, crear la conexión PDO con `utf8mb4`, trabajar en UTC y reemplazar el almacenamiento de demostración por consultas SQL. La clave MySQL nunca debe incluirse en JavaScript ni publicarse en el repositorio.

También debe autenticar con `password_verify`, usar los roles del esquema, exigir el cambio de contraseña inicial y filtrar los datos por la identidad autenticada. La cuenta de Laura solo funcionará en el login real después de implementar esa conexión y autenticación.

## 7. Diferencia entre los dos archivos SQL

La guía de importación de Hostinger pide excluir creación/borrado de bases, declaraciones `DEFINER`, procedimientos y triggers. Por eso la variante hPanel conserva las tablas, restricciones, índices, vista y datos, pero no instala los triggers del archivo local.

Las claves únicas y foráneas siguen presentes: por ejemplo, una oportunidad solo admite un código y un código solo admite una fila de redención. La vista calcula Activo, Redimido o Vencido. Eso por sí solo no garantiza las reglas comerciales ni los permisos.

Antes de habilitar uso real, el backend debe aplicar y probar:

- Emisión solo para clientes activos, oportunidades válidas, reglas habilitadas y promociones aprobadas con negocio y WhatsApp confirmados.
- Captura de las condiciones otorgadas y generación UTC con vencimiento exactamente a las 72 horas. Esta variante exige que PHP suministre `oferta_otorgada` y `vence_en` correctos; no se sustituyen automáticamente.
- Redención únicamente antes del vencimiento, por un administrador activo o el aliado activo del propio negocio. Tomar la identidad de la sesión, usar una transacción y comprobar el estado al confirmar, incluso ante dos solicitudes simultáneas.
- Prohibición de modificar o borrar códigos y redenciones para reactivarlos, cambiar titularidad/ciclo o alterar el negocio de una promoción con códigos emitidos.
- Versionado de reglas con historial y conteo de visitas sin duplicar recargas.
- Validación de progresos solo para clientes y criterios de verificación definidos, sin superar los objetivos.

Abrir WhatsApp o enviar un mensaje no debe insertar una redención.

## 8. Pruebas y migración posterior

Conectar primero la ruta de pruebas y comprobar registro, login, cambio de contraseña de Laura, permisos por negocio, exclusión de borradores, vigencia y redención única. Migrar a la parte principal después de completar estas pruebas y la revisión solicitada por el usuario.

La variante hPanel fue revisada para conservar las 10 tablas, la vista y los datos del SQL original, excluyendo las instrucciones indicadas. No se ha importado ni probado en la cuenta de Hostinger del usuario. Las pruebas locales documentadas en `LEEME.md` sobre triggers pertenecen al archivo original de XAMPP.

## Guías oficiales consultadas

- [Crear la base y su usuario](https://www.hostinger.com/support/1583542-how-to-create-a-new-mysql-database-in-hostinger/).
- [Requisitos del archivo SQL](https://www.hostinger.com/support/1864324-how-to-upload-and-set-up-your-database-at-hostinger/).
- [Importar en phpMyAdmin](https://www.hostinger.com/support/1884149-how-to-import-a-database-with-phpmyadmin-in-hostinger/).
- [Consultar los datos de conexión](https://www.hostinger.com/support/1583552-how-to-find-your-mysql-database-details-in-hostinger/).
