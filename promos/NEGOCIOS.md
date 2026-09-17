# Negocios incluidos en la instalación

Datos revisados en las páginas locales del proyecto. Son datos existentes del sitio; no implican confirmación telefónica ni aprobación comercial de un descuento.

| ID | Negocio / usuario | Actividad | WhatsApp seleccionado | Dirección |
|---|---|---|---|---|
| 1 | Street Grill / `street-grill` | Gastronomía y ahumados | 573143580355 | Carrera 9 #57-85 |
| 2 | Capital Queer / `capital-queer` | Bar | 573007795016 | Carrera 9 #59-38 |
| 3 | Jimar Factory / `jimar-factory` | Juegos y billar | 573165180649 | Calle 58 #13-93 |
| 4 | Garage Disco Bar / `garage-disco-bar` | Gastrobar | 573156175056 | Calle 59 #9-39 |
| 5 | Pictogramas Café Bar / `pictogramas` | Café bar | 573502835648 | Calle 59 #13-20 |
| 6 | Gran&Chela Club / `gran-chela` | Bar y discoteca | 573224680419 | Calle 59 #10-24 |

Fuentes: `index.php` y `home/ubicacion/ubicacion.php` de cada negocio. Los logos se reutilizan desde los archivos existentes.

## Inconsistencias detectadas

- Varias plantillas compartidas de navegación contienen `573138846378`, distinto de los teléfonos principales. Ese número genérico no se copió a la base.
- El slider de Garage contiene `573007795016`, que corresponde al teléfono de Capital Queer en su página. Se eligió `573156175056` porque coincide en el inicio y la ubicación de Garage.
- El slider de Pictogramas usa el número genérico, mientras inicio, reservas y ubicación coinciden en `573502835648`.
- Los archivos de eventos de Gran&Chela mencionan también Calle 51 #10-24. Para la ficha permanente se tomó Calle 59 #10-24 de ubicación. Conviene revisar la dirección específica de cada evento por separado.

No se cambiaron esos contenidos de las páginas comerciales: la corrección de plantillas queda fuera del módulo de promociones. Todos los teléfonos y direcciones del módulo pueden editarse desde administración.

## Qué falta definir comercialmente

Para cada aliado: beneficio exacto, porcentaje si corresponde, productos incluidos, consumo mínimo, exclusiones, horarios/días y cupo total opcional. La vigencia del código es de 72 horas, configurable.

Las promociones están creadas pero inactivas. Al completar y aprobar esas condiciones, el administrador puede activarlas. Un aliado sin promoción activa no aparece como posible ganador y no participa en la ruleta dinámica.
