# Chapitour te premia — entorno de pruebas

Prototipo funcional aislado, en español, con inicio público, registro e inicio de sesión, paneles independientes de cliente, aliado y administrador, ruleta y detalle del premio.

## Abrir

Desde la raíz de `chapitour.co`:

```sh
/Applications/XAMPP/xamppfiles/bin/php -S 127.0.0.1:8791 -t .
```

Abrir <http://127.0.0.1:8791/pruebas/chapitour-premia/>. También puede servirse con Apache de XAMPP en la ruta equivalente.

No se ha modificado la página principal ni conectado una base de datos de producción. Las imágenes se leen del proyecto existente mediante rutas relativas.

## Recorridos para revisión

1. **Inicio público:** crear una cuenta de prueba, iniciar sesión o explorar aliados. Una sesión iniciada sustituye el registro por acceso a sus retos o panel.
2. **Cambiar de vista:** selector explícito de cuentas de demostración para Cliente, Aliado (Street Grill) y Administrador. Cambiar de vista conserva los cambios de esta sesión.
3. **Cliente:** cuatro retos con la copia indicada, promociones y perfil. La cuenta demo contiene ejemplos rotulados. Una cuenta recién registrada comienza sin promociones y sin progresos ficticios. Compartir no aumenta el progreso. No se verifica automáticamente ninguna fotografía.
4. **Ruleta:** desde el inicio, “Conoce la ruleta”. Utiliza la cuenta demo y genera exclusivamente un código `DEMO-CHAPI-…`, sin validez comercial. La selección se realiza en el servidor; la animación termina en el negocio seleccionado. Repetir una misma solicitud de giro no duplica el premio.
5. **Aliado:** solo ve promociones y códigos de Street Grill. Buscador y filtros, estados Activo/Redimido/Vencido, y confirmación antes de redimir. No hay gestión de promociones ni acceso a otros negocios.
6. **Administrador:** cuentas de aliados, CRUD de promociones separado de códigos y confirmación de redención. El aviso de promociones pendientes permanece visible. Pictogramas y Jimar Factory comienzan como “Borrador · Por confirmar”. Confirmarlas requiere completar los datos comerciales y confirmar su revisión con el negocio.
7. **Eliminaciones:** identifican el elemento y requieren confirmación. Eliminar una promoción conserva el beneficio y la vigencia de códigos ya emitidos. Eliminar un aliado deshabilita su cuenta y deja sus promociones en borrador; los códigos se conservan para consulta.

## Datos y límites del prototipo

- La API PHP aplica permisos por rol, filtro por propietario, validación de campos y protección CSRF. Las contraseñas de prueba se guardan con `password_hash` y nunca se incluyen en las respuestas.
- Los datos viven en la sesión PHP de cada navegador. Se guardan fuera de la carpeta pública, en `sys_get_temp_dir()/chapitour-premia-sandbox`, con cookie independiente `CHAPITOUR_PREMIA_TEST`. No son compartidos entre navegadores, no son cuentas de producción y pueden desaparecer al cerrar o caducar la sesión. Para reiniciar la demo se puede borrar solo esa cookie o abrir otra sesión privada.
- El cambio de rol es una herramienta deliberada de demostración, no un acceso válido para producción. No publicar este prototipo como sistema de cuentas real.
- Todos los códigos son de demostración, únicos por aleatoriedad criptográfica, válidos durante 72 horas desde su generación y utilizables una vez. El servidor comprueba la caducidad en cada acción; no hay selector para reactivar códigos.
- No se inventaron números de teléfono. “Redimir por WhatsApp” permanece deshabilitado hasta registrar el contacto confirmado en la edición administrativa de la promoción. El enlace prepara el negocio, la promoción, el código y la intención de redimir. El mensaje incluye un aviso de prueba, requiere que el usuario pulse Enviar y no cambia el estado del código.
- Las cuatro ofertas de referencia conservan exactamente el beneficio indicado. Su etiqueta “Oferta de referencia” no implica aprobación de los aliados. Solo se usan para la demostración. Los dos borradores se excluyen del sorteo.
- La renovación mensual del encabezado de retos no modifica los códigos ni reinicia un contador de visitas.

## Decisiones que bloquean la activación real

La campaña está desactivada. No se contabilizan visitas ni se entregan premios por recargar la página. La respuesta `campaign` conserva `null` para las decisiones no confirmadas:

- ¿Premio cada **8 o 10 visitas válidas**? Ambos números aparecen en el documento.
- ¿Cuánto tiempo debe pasar para contar una visita nueva de la misma cuenta?
- ¿Sustituye a la ruleta anterior de cada 5 visitas o es independiente?
- ¿El contador se reinicia al cambiar de mes?
- Aprobación de beneficio, incluidos, horarios, restricciones y WhatsApp de Pictogramas y Jimar Factory; contactos y condiciones confirmados para los demás aliados.
- Definición del criterio de visita a negocios y del mecanismo de verificación para compartir, fotos y etiquetas.

No se escogió ninguna de estas reglas por cuenta propia. No hay puntos, niveles ni recompensa extra por completar todos los retos.

## Pruebas automatizadas

Con el servidor local en ejecución y Playwright disponible:

```sh
node pruebas/chapitour-premia/tests/acceptance.cjs
```

Variables opcionales: `CHAPITOUR_TEST_URL`, `CHROME_PATH`, `CHAPITOUR_QA_DIR`; `NODE_PATH` si Playwright no está instalado en el proyecto. El navegador predeterminado es Google Chrome en macOS. Las capturas se guardan fuera del proyecto, en `/private/tmp/chapitour-premia-qa`.

La suite usa sesiones separadas de las del usuario. Comprueba permisos, CSRF, caducidad, redención única, exclusión de borradores, idempotencia del giro, CRUD, registro/login/perfil/eliminación, estados vacíos y recorridos visuales a 1440 y 390 px. La función nativa Compartir se simula para evitar enviar contenido fuera de las pruebas.

## Migración posterior

Requiere aprobación de la experiencia y decisiones pendientes. Después se reemplazará el almacenamiento por sesión y el selector demo por autenticación y persistencia compartidas, se conectará el contador individual con deduplicación de visitas y entrega atómica, y se integrará el bloque público con la página principal. Esta entrega no realiza esa migración.
