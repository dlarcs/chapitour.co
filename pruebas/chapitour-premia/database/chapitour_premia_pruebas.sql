-- Chapitour te premia | Instalacion inicial en una base VACIA.
-- Base independiente: chapitour_premia_pruebas.
-- No ejecuta DROP, no crea usuarios del servidor MySQL y no cambia la web.
-- Cuenta administradora de la APLICACION: laurazoro@gmail.com.
-- Contrasena almacenada mediante bcrypt de PHP, nunca en texto plano.
-- El backend debe exigir cambio de contrasena cuando debe_cambiar_password = 1.
-- Motor objetivo validable: MariaDB 10.4 (XAMPP). Requiere InnoDB y CHECK.
-- No es una migracion incremental ni debe reimportarse sobre tablas con datos.

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET time_zone = '+00:00';
SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE DATABASE IF NOT EXISTS chapitour_premia_pruebas
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chapitour_premia_pruebas;

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
  vence_en DATETIME(6) NOT NULL COMMENT 'Asignado por trigger: generado_en + 72 horas',
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

-- Guardas de integridad. No sustituyen autenticacion ni permisos de la API.
DELIMITER $$

CREATE TRIGGER tr_promociones_insert BEFORE INSERT ON promociones
FOR EACH ROW
BEGIN
  IF NEW.aprobada_por IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM usuarios WHERE id = NEW.aprobada_por
      AND rol = 'administrador' AND desactivado_en IS NULL
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La aprobacion debe registrarla un administrador activo';
  END IF;
  IF NEW.publicacion = 'publicada' AND NOT EXISTS (
    SELECT 1 FROM negocios WHERE id = NEW.negocio_id AND archivado_en IS NULL
      AND whatsapp IS NOT NULL AND whatsapp_confirmado_en IS NOT NULL
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Falta un negocio disponible con WhatsApp confirmado';
  END IF;
END$$

CREATE TRIGGER tr_promociones_update BEFORE UPDATE ON promociones
FOR EACH ROW
BEGIN
  IF NEW.negocio_id <> OLD.negocio_id AND EXISTS (
    SELECT 1 FROM codigos_premio WHERE promocion_id = OLD.id
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Una promocion con codigos no puede cambiar de negocio';
  END IF;
  IF NEW.aprobada_por IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM usuarios WHERE id = NEW.aprobada_por
      AND rol = 'administrador' AND desactivado_en IS NULL
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La aprobacion debe registrarla un administrador activo';
  END IF;
  IF NEW.publicacion = 'publicada' AND NOT EXISTS (
    SELECT 1 FROM negocios WHERE id = NEW.negocio_id AND archivado_en IS NULL
      AND whatsapp IS NOT NULL AND whatsapp_confirmado_en IS NOT NULL
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Falta un negocio disponible con WhatsApp confirmado';
  END IF;
END$$

CREATE TRIGGER tr_reglas_inmutables BEFORE UPDATE ON reglas_incentivo
FOR EACH ROW
BEGIN
  IF (
    NOT (NEW.visitas_por_premio <=> OLD.visitas_por_premio)
    OR NOT (NEW.intervalo_minimo_segundos <=> OLD.intervalo_minimo_segundos)
    OR NOT (NEW.reinicia_contador_mensual <=> OLD.reinicia_contador_mensual)
    OR NOT (NEW.relacion_ruleta_anterior <=> OLD.relacion_ruleta_anterior)
    OR NEW.grupo_entrega <> OLD.grupo_entrega
  ) AND (
    EXISTS (SELECT 1 FROM visitas_validas WHERE regla_id = OLD.id)
    OR EXISTS (SELECT 1 FROM oportunidades_premio WHERE regla_id = OLD.id)
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Crea una nueva version de la regla que ya tiene historial';
  END IF;
END$$

CREATE TRIGGER tr_visitas_insert BEFORE INSERT ON visitas_validas
FOR EACH ROW
BEGIN
  IF NOT EXISTS (SELECT 1 FROM usuarios WHERE id = NEW.cliente_id
      AND rol = 'cliente' AND desactivado_en IS NULL) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La visita debe pertenecer a un cliente activo';
  END IF;
  IF NOT EXISTS (SELECT 1 FROM reglas_incentivo WHERE id = NEW.regla_id AND habilitada = 1) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La regla de visitas esta deshabilitada';
  END IF;
END$$

CREATE TRIGGER tr_oportunidades_insert BEFORE INSERT ON oportunidades_premio
FOR EACH ROW
BEGIN
  IF NOT EXISTS (SELECT 1 FROM usuarios WHERE id = NEW.cliente_id
      AND rol = 'cliente' AND desactivado_en IS NULL) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La oportunidad debe pertenecer a un cliente activo';
  END IF;
  IF NOT EXISTS (SELECT 1 FROM reglas_incentivo WHERE id = NEW.regla_id AND habilitada = 1) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La regla de premios esta deshabilitada';
  END IF;
END$$

CREATE TRIGGER tr_oportunidades_update BEFORE UPDATE ON oportunidades_premio
FOR EACH ROW
BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La titularidad y el ciclo de una oportunidad son inmutables';
END$$

CREATE TRIGGER tr_codigos_insert BEFORE INSERT ON codigos_premio
FOR EACH ROW
BEGIN
  DECLARE v_oferta LONGTEXT DEFAULT NULL;
  IF NOT EXISTS (
    SELECT 1 FROM oportunidades_premio o
    JOIN usuarios u ON u.id = o.cliente_id
    JOIN reglas_incentivo r ON r.id = o.regla_id
    WHERE o.id = NEW.oportunidad_id AND u.rol = 'cliente'
      AND u.desactivado_en IS NULL AND r.habilitada = 1
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La oportunidad o su cliente o regla no estan disponibles';
  END IF;
  SELECT JSON_OBJECT(
    'negocio', n.nombre,
    'beneficio', p.beneficio,
    'productos_servicios_incluidos', p.productos_servicios_incluidos,
    'horarios', p.horarios,
    'restricciones', p.restricciones
  ) INTO v_oferta
  FROM promociones p JOIN negocios n ON n.id = p.negocio_id
  WHERE p.id = NEW.promocion_id AND p.publicacion = 'publicada'
    AND p.aprobada_en IS NOT NULL AND p.aprobada_por IS NOT NULL
    AND n.archivado_en IS NULL AND n.whatsapp IS NOT NULL
    AND n.whatsapp_confirmado_en IS NOT NULL;
  IF v_oferta IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Solo se generan codigos de promociones publicadas y confirmadas';
  END IF;
  SET NEW.generado_en = UTC_TIMESTAMP(6);
  SET NEW.vence_en = DATE_ADD(NEW.generado_en, INTERVAL 72 HOUR);
  SET NEW.oferta_otorgada = v_oferta;
END$$

CREATE TRIGGER tr_codigos_update BEFORE UPDATE ON codigos_premio
FOR EACH ROW
BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Un codigo emitido no se modifica ni se reactiva';
END$$

CREATE TRIGGER tr_codigos_delete BEFORE DELETE ON codigos_premio
FOR EACH ROW
BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se elimina un codigo para reutilizar su oportunidad';
END$$

CREATE TRIGGER tr_redenciones_insert BEFORE INSERT ON redenciones
FOR EACH ROW
BEGIN
  DECLARE v_negocio BIGINT UNSIGNED DEFAULT NULL;
  DECLARE v_vence DATETIME(6) DEFAULT NULL;
  SELECT p.negocio_id, c.vence_en INTO v_negocio, v_vence
    FROM codigos_premio c JOIN promociones p ON p.id = c.promocion_id
    WHERE c.id = NEW.codigo_id;
  IF v_vence IS NULL OR v_vence <= UTC_TIMESTAMP(6) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El codigo no existe o esta vencido';
  END IF;
  IF NOT EXISTS (
    SELECT 1 FROM usuarios u
    WHERE u.id = NEW.confirmado_por AND u.desactivado_en IS NULL
      AND (u.rol = 'administrador' OR (u.rol = 'aliado' AND u.negocio_id = v_negocio))
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Solo el aliado del negocio o un administrador puede redimir';
  END IF;
  SET NEW.redimido_en = UTC_TIMESTAMP(6);
  -- La PK codigo_id impide redenciones duplicadas, incluso concurrentes.
END$$

CREATE TRIGGER tr_redenciones_update BEFORE UPDATE ON redenciones
FOR EACH ROW
BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Una redencion confirmada no se modifica';
END$$

CREATE TRIGGER tr_redenciones_delete BEFORE DELETE ON redenciones
FOR EACH ROW
BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Una redencion no se elimina para reactivar el codigo';
END$$

CREATE TRIGGER tr_progreso_insert BEFORE INSERT ON progreso_retos
FOR EACH ROW
BEGIN
  IF NOT EXISTS (SELECT 1 FROM usuarios WHERE id = NEW.cliente_id
    AND rol = 'cliente' AND desactivado_en IS NULL) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El progreso debe pertenecer a un cliente activo';
  END IF;
  IF NEW.cantidad_verificada IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM retos_mensuales WHERE id = NEW.reto_id
      AND criterio_verificacion IS NOT NULL AND CHAR_LENGTH(TRIM(criterio_verificacion)) > 0
      AND NEW.cantidad_verificada <= objetivo
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Define la verificacion y respeta el objetivo antes de registrar progreso';
  END IF;
END$$

CREATE TRIGGER tr_progreso_update BEFORE UPDATE ON progreso_retos
FOR EACH ROW
BEGIN
  IF NOT EXISTS (SELECT 1 FROM usuarios WHERE id = NEW.cliente_id
    AND rol = 'cliente' AND desactivado_en IS NULL) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El progreso debe pertenecer a un cliente activo';
  END IF;
  IF NEW.cantidad_verificada IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM retos_mensuales WHERE id = NEW.reto_id
      AND criterio_verificacion IS NOT NULL AND CHAR_LENGTH(TRIM(criterio_verificacion)) > 0
      AND NEW.cantidad_verificada <= objetivo
  ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Define la verificacion y respeta el objetivo antes de registrar progreso';
  END IF;
END$$

DELIMITER ;

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
