-- Chapitour te premia | Estructura y datos iniciales para hPanel / phpMyAdmin.
-- Importar UNA SOLA VEZ en una base VACIA creada previamente en Hostinger.
-- Seleccionar la base correcta en phpMyAdmin antes de importar.
-- No necesita reemplazar nombres: usa la base seleccionada.
-- Requiere un motor MySQL/MariaDB con InnoDB, JSON y CHECK efectivos.
-- IMPORTANTE: este archivo prepara datos, NO es una aplicacion lista para operar.
-- Las guardas adicionales del SQL de XAMPP deben implementarse en el backend:
-- permisos por negocio, exclusiones de borradores, reglas habilitadas,
-- fechas UTC y 72 horas, instantanea comercial, rechazo de vencidos,
-- inmutabilidad de codigos/redenciones y validacion de progreso.
-- No activar campana hasta conectar y probar ese backend.
-- Admin de la aplicacion: laurazoro@gmail.com; clave almacenada como bcrypt.
-- El backend debe exigir cambio de clave por debe_cambiar_password = 1.
-- Consulta HOSTINGER.md para instrucciones y diferencias con la version local.

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET time_zone = '+00:00';
SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE negocios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  whatsapp VARCHAR(15) CHARACTER SET ascii COLLATE ascii_bin NULL,
  whatsapp_confirmado_en DATETIME(6) NULL,
  creado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  actualizado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  archivado_en DATETIME(6) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_negocios_slug (slug),
  CONSTRAINT ck_negocios_whatsapp CHECK (
    (whatsapp IS NULL AND whatsapp_confirmado_en IS NULL)
    OR (whatsapp IS NOT NULL AND whatsapp REGEXP '^[1-9][0-9]{7,14}$')
  )
) ENGINE=InnoDB;

CREATE TABLE usuarios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  negocio_id BIGINT UNSIGNED NULL,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(191) NOT NULL,
  password_hash VARCHAR(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  rol ENUM('cliente','aliado','administrador') NOT NULL DEFAULT 'cliente',
  ciudad VARCHAR(100) NULL,
  debe_cambiar_password TINYINT(1) NOT NULL DEFAULT 0,
  creado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  actualizado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  desactivado_en DATETIME(6) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_email (email),
  KEY ix_usuarios_negocio_rol (negocio_id, rol),
  CONSTRAINT fk_usuarios_negocio FOREIGN KEY (negocio_id)
    REFERENCES negocios (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT ck_usuarios_rol_negocio CHECK (
    (rol = 'aliado' AND negocio_id IS NOT NULL)
    OR (rol IN ('cliente','administrador') AND negocio_id IS NULL)
  ),
  CONSTRAINT ck_usuarios_cambio_password CHECK (debe_cambiar_password IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE promociones (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  negocio_id BIGINT UNSIGNED NOT NULL,
  beneficio TEXT NOT NULL,
  productos_servicios_incluidos TEXT NULL,
  horarios TEXT NULL,
  restricciones TEXT NULL,
  publicacion ENUM('borrador','publicada','archivada') NOT NULL DEFAULT 'borrador',
  origen ENUM('referencia','provisional','administracion') NOT NULL DEFAULT 'administracion',
  aprobada_por BIGINT UNSIGNED NULL,
  aprobada_en DATETIME(6) NULL,
  creado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  actualizado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  KEY ix_promociones_negocio_publicacion (negocio_id, publicacion),
  KEY ix_promociones_aprobada_por (aprobada_por),
  CONSTRAINT fk_promociones_negocio FOREIGN KEY (negocio_id)
    REFERENCES negocios (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_promociones_aprobada_por FOREIGN KEY (aprobada_por)
    REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT ck_promociones_beneficio CHECK (CHAR_LENGTH(TRIM(beneficio)) > 0),
  CONSTRAINT ck_promociones_aprobacion CHECK (
    (aprobada_por IS NULL AND aprobada_en IS NULL)
    OR (aprobada_por IS NOT NULL AND aprobada_en IS NOT NULL)
  ),
  CONSTRAINT ck_promociones_publicada CHECK (
    publicacion <> 'publicada' OR (
      aprobada_por IS NOT NULL AND aprobada_en IS NOT NULL
      AND productos_servicios_incluidos IS NOT NULL AND CHAR_LENGTH(TRIM(productos_servicios_incluidos)) > 0
      AND horarios IS NOT NULL AND CHAR_LENGTH(TRIM(horarios)) > 0
      AND restricciones IS NOT NULL AND CHAR_LENGTH(TRIM(restricciones)) > 0
    )
  )
) ENGINE=InnoDB;

CREATE TABLE reglas_incentivo (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL,
  grupo_entrega VARCHAR(60) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'visitas_recurrentes',
  visitas_por_premio SMALLINT UNSIGNED NULL COMMENT 'Pendiente: 8 o 10; sin valor predeterminado',
  intervalo_minimo_segundos INT UNSIGNED NULL COMMENT 'Pendiente; recargar no cuenta como nueva visita',
  reinicia_contador_mensual TINYINT(1) NULL COMMENT 'NULL significa sin definir',
  relacion_ruleta_anterior ENUM('reemplaza','independiente') NULL,
  habilitada TINYINT(1) NOT NULL DEFAULT 0,
  grupo_habilitado VARCHAR(60) CHARACTER SET ascii COLLATE ascii_bin
    GENERATED ALWAYS AS (CASE WHEN habilitada = 1 THEN grupo_entrega ELSE NULL END) STORED,
  creado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY uq_reglas_grupo_habilitado (grupo_habilitado),
  CONSTRAINT ck_reglas_habilitada CHECK (habilitada IN (0,1)),
  CONSTRAINT ck_reglas_visitas CHECK (visitas_por_premio IS NULL OR visitas_por_premio > 0),
  CONSTRAINT ck_reglas_intervalo CHECK (intervalo_minimo_segundos IS NULL OR intervalo_minimo_segundos > 0),
  CONSTRAINT ck_reglas_reinicio CHECK (reinicia_contador_mensual IS NULL OR reinicia_contador_mensual IN (0,1)),
  CONSTRAINT ck_reglas_confirmadas CHECK (
    habilitada = 0 OR (
      visitas_por_premio IS NOT NULL AND intervalo_minimo_segundos IS NOT NULL
      AND reinicia_contador_mensual IS NOT NULL AND relacion_ruleta_anterior IS NOT NULL
    )
  )
) ENGINE=InnoDB;

CREATE TABLE visitas_validas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cliente_id BIGINT UNSIGNED NOT NULL,
  regla_id BIGINT UNSIGNED NOT NULL,
  clave_entrada VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  registrada_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY uq_visitas_cliente_entrada (cliente_id, clave_entrada),
  KEY ix_visitas_cliente_regla_fecha (cliente_id, regla_id, registrada_en),
  KEY ix_visitas_regla (regla_id),
  CONSTRAINT fk_visitas_cliente FOREIGN KEY (cliente_id)
    REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_visitas_regla FOREIGN KEY (regla_id)
    REFERENCES reglas_incentivo (id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE oportunidades_premio (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cliente_id BIGINT UNSIGNED NOT NULL,
  regla_id BIGINT UNSIGNED NOT NULL,
  numero_ciclo BIGINT UNSIGNED NOT NULL,
  clave_otorgamiento VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  creada_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (id),
  UNIQUE KEY uq_oportunidades_cliente_regla_ciclo (cliente_id, regla_id, numero_ciclo),
  UNIQUE KEY uq_oportunidades_clave (clave_otorgamiento),
  KEY ix_oportunidades_regla (regla_id),
  CONSTRAINT fk_oportunidades_cliente FOREIGN KEY (cliente_id)
    REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_oportunidades_regla FOREIGN KEY (regla_id)
    REFERENCES reglas_incentivo (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT ck_oportunidades_ciclo CHECK (numero_ciclo > 0)
) ENGINE=InnoDB;

CREATE TABLE codigos_premio (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  oportunidad_id BIGINT UNSIGNED NOT NULL,
  promocion_id BIGINT UNSIGNED NOT NULL,
  codigo VARCHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  oferta_otorgada JSON NOT NULL COMMENT 'Instantanea inmutable de beneficio, condiciones y negocio',
  generado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  vence_en DATETIME(6) NOT NULL COMMENT 'El backend debe asignar generado_en + 72 horas',
  PRIMARY KEY (id),
  UNIQUE KEY uq_codigos_codigo (codigo),
  UNIQUE KEY uq_codigos_oportunidad (oportunidad_id),
  KEY ix_codigos_promocion_vencimiento (promocion_id, vence_en),
  KEY ix_codigos_vencimiento (vence_en),
  CONSTRAINT fk_codigos_oportunidad FOREIGN KEY (oportunidad_id)
    REFERENCES oportunidades_premio (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_codigos_promocion FOREIGN KEY (promocion_id)
    REFERENCES promociones (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT ck_codigos_formato CHECK (codigo REGEXP '^[A-Z0-9-]{6,64}$'),
  CONSTRAINT ck_codigos_vigencia CHECK (vence_en = DATE_ADD(generado_en, INTERVAL 72 HOUR))
) ENGINE=InnoDB;

CREATE TABLE redenciones (
  codigo_id BIGINT UNSIGNED NOT NULL,
  confirmado_por BIGINT UNSIGNED NOT NULL,
  redimido_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (codigo_id),
  KEY ix_redenciones_usuario_fecha (confirmado_por, redimido_en),
  CONSTRAINT fk_redenciones_codigo FOREIGN KEY (codigo_id)
    REFERENCES codigos_premio (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_redenciones_usuario FOREIGN KEY (confirmado_por)
    REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE retos_mensuales (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  mes DATE NOT NULL COMMENT 'Primer dia del mes en Bogota',
  tipo ENUM('visitar','compartir','fotografia') NOT NULL,
  titulo VARCHAR(180) NOT NULL,
  objetivo SMALLINT UNSIGNED NOT NULL,
  criterio_verificacion TEXT NULL COMMENT 'Sin definir; no implica verificacion automatica',
  PRIMARY KEY (id),
  UNIQUE KEY uq_retos_mes_tipo (mes, tipo),
  CONSTRAINT ck_retos_mes CHECK (DAYOFMONTH(mes) = 1),
  CONSTRAINT ck_retos_objetivo CHECK (objetivo > 0)
) ENGINE=InnoDB;

CREATE TABLE progreso_retos (
  cliente_id BIGINT UNSIGNED NOT NULL,
  reto_id BIGINT UNSIGNED NOT NULL,
  cantidad_verificada SMALLINT UNSIGNED NULL COMMENT 'NULL: no hay avance confirmado',
  actualizado_en DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (cliente_id, reto_id),
  KEY ix_progreso_reto (reto_id),
  CONSTRAINT fk_progreso_cliente FOREIGN KEY (cliente_id)
    REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_progreso_reto FOREIGN KEY (reto_id)
    REFERENCES retos_mensuales (id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

-- El estado no se guarda: siempre se calcula con la hora actual y la redencion.
-- Una redencion tiene prioridad sobre el vencimiento posterior.
CREATE SQL SECURITY INVOKER VIEW v_codigos_estado AS
SELECT c.id, c.codigo, o.cliente_id, p.negocio_id, c.promocion_id,
  c.oportunidad_id, c.oferta_otorgada, c.generado_en, c.vence_en,
  d.confirmado_por, d.redimido_en,
  CASE
    WHEN d.codigo_id IS NOT NULL THEN 'Redimido'
    WHEN c.vence_en <= UTC_TIMESTAMP(6) THEN 'Vencido'
    ELSE 'Activo'
  END AS estado
FROM codigos_premio c
JOIN oportunidades_premio o ON o.id = c.oportunidad_id
JOIN promociones p ON p.id = c.promocion_id
LEFT JOIN redenciones d ON d.codigo_id = c.id;

-- Datos iniciales. No se crean clientes, visitas, oportunidades ni premios ficticios.
START TRANSACTION;

INSERT INTO usuarios (id, nombre, email, password_hash, rol, debe_cambiar_password)
VALUES (1, 'Laura', 'laurazoro@gmail.com', '$2y$12$KgZZoGYbZIiQGWmSYebuf.rnl83bMMAA8BLWpeLcVmxEQPS5Ch6ly', 'administrador', 1);

INSERT INTO negocios (id, slug, nombre) VALUES
  (1, 'capital-queer', 'Capital Queer'),
  (2, 'gran-chela', 'Gran&Chela'),
  (3, 'garage-gastrobar', 'Garage Gastrobar'),
  (4, 'pictogramas', 'Pictogramas'),
  (5, 'street-grill', 'Street Grill'),
  (6, 'jimar-factory', 'Jimar Factory');

-- Las cuatro ofertas de referencia NO equivalen a aprobaciones comerciales.
-- Todas quedan en borrador hasta confirmar condiciones y WhatsApp.
INSERT INTO promociones (negocio_id, beneficio, publicacion, origen) VALUES
  (1, 'Media de aguardiente Néctar y 6 cervezas — $88.000 COP.', 'borrador', 'referencia'),
  (2, 'Media más cubetazo y 7 cervezas — $80.000 COP.', 'borrador', 'referencia'),
  (3, 'Botella de Real más 4 cervezas — $148.000 COP.', 'borrador', 'referencia'),
  (4, '10 % de descuento al comprar una bebida de café y un postre.', 'borrador', 'provisional'),
  (5, '10 % de descuento.', 'borrador', 'referencia'),
  (6, '15 minutos adicionales de billar al pagar una hora.', 'borrador', 'provisional');

INSERT INTO reglas_incentivo (nombre, habilitada)
VALUES ('¡Volver tiene premio!', 0);

SET @mes_chapitour = CAST(DATE_FORMAT(
  CONVERT_TZ(UTC_TIMESTAMP(), '+00:00', '-05:00'), '%Y-%m-01'
) AS DATE);

INSERT INTO retos_mensuales (mes, tipo, titulo, objetivo) VALUES
  (@mes_chapitour, 'visitar', 'Visita 3 negocios', 3),
  (@mes_chapitour, 'compartir', 'Envía esta página a 20 personas', 20),
  (@mes_chapitour, 'fotografia', 'Tómate una foto en 2 lugares y etiquétanos', 2);

COMMIT;

-- En cada conexion futura del backend: SET time_zone = '+00:00'.
-- La API debe autenticar al actor; nunca aceptar confirmado_por desde el navegador.
-- Consultar codigos filtrando cliente_id o negocio_id segun la sesion autenticada.
-- El conteo, validacion temporal de entradas y otorgamiento por ciclo requieren
-- transacciones en el backend. Este SQL no inventa ni activa sus reglas.
-- Importar este archivo no conecta automaticamente la interfaz de demostracion.
