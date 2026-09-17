-- MySQL 8 / MariaDB 10.4+, InnoDB, fechas UTC. Importar en una base dedicada.
CREATE TABLE IF NOT EXISTS cp_campanas (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 activa TINYINT(1) NOT NULL DEFAULT 1,
 creada_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_negocios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 slug VARCHAR(80) NOT NULL UNIQUE,
 nombre VARCHAR(120) NOT NULL,
 categoria VARCHAR(80) NOT NULL,
 direccion VARCHAR(200) NOT NULL DEFAULT '',
 whatsapp VARCHAR(20) NOT NULL DEFAULT '',
 logo VARCHAR(250) NOT NULL DEFAULT '',
 pagina VARCHAR(250) NOT NULL DEFAULT '',
 activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_promociones (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 negocio_id INT UNSIGNED NOT NULL UNIQUE,
 titulo VARCHAR(160) NOT NULL,
 descripcion VARCHAR(500) NOT NULL DEFAULT '',
 condiciones VARCHAR(1000) NOT NULL DEFAULT '',
 porcentaje DECIMAL(5,2) NULL,
 cupo_total INT UNSIGNED NULL,
 entregados INT UNSIGNED NOT NULL DEFAULT 0,
 activa TINYINT(1) NOT NULL DEFAULT 0,
 actualizada_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_visitantes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 identidad_hash CHAR(64) NOT NULL UNIQUE,
 ip_hash CHAR(64) NOT NULL,
 referido_token CHAR(32) NOT NULL UNIQUE,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 ultimo_acceso_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_participaciones (
 visitante_id BIGINT UNSIGNED NOT NULL,
 campana_id INT UNSIGNED NOT NULL,
 visitas INT UNSIGNED NOT NULL DEFAULT 0,
 ultima_visita_at DATETIME NULL,
 ultima_programada_at DATETIME NULL,
 ultima_programada_visita INT UNSIGNED NOT NULL DEFAULT 0,
 PRIMARY KEY (campana_id, visitante_id),
 FOREIGN KEY (visitante_id) REFERENCES cp_visitantes(id),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_oportunidades (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 visitante_id BIGINT UNSIGNED NOT NULL,
 campana_id INT UNSIGNED NOT NULL,
 origen VARCHAR(24) NOT NULL,
 origen_clave VARCHAR(100) NOT NULL,
 creada_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 mostrada_at DATETIME NULL,
 consumida_at DATETIME NULL,
 UNIQUE KEY oportunidad_origen (campana_id, visitante_id, origen_clave),
 KEY disponibles (visitante_id, campana_id, consumida_at),
 FOREIGN KEY (visitante_id) REFERENCES cp_visitantes(id),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_usuarios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 negocio_id INT UNSIGNED NULL,
 usuario VARCHAR(100) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 rol ENUM('admin','aliado') NOT NULL DEFAULT 'aliado',
 activo TINYINT(1) NOT NULL DEFAULT 1,
 cambiar_password TINYINT(1) NOT NULL DEFAULT 1,
 version_sesion INT UNSIGNED NOT NULL DEFAULT 1,
 FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_premios (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 oportunidad_id BIGINT UNSIGNED NOT NULL UNIQUE,
 visitante_id BIGINT UNSIGNED NOT NULL,
 campana_id INT UNSIGNED NOT NULL,
 negocio_id INT UNSIGNED NOT NULL,
 promocion_id INT UNSIGNED NOT NULL,
 codigo VARCHAR(40) NOT NULL UNIQUE,
 solicitud_id CHAR(36) NOT NULL,
 titulo VARCHAR(160) NOT NULL,
 descripcion VARCHAR(500) NOT NULL,
 condiciones VARCHAR(1000) NOT NULL,
 porcentaje DECIMAL(5,2) NULL,
 creado_at DATETIME NOT NULL,
 vence_at DATETIME NOT NULL,
 redimido_at DATETIME NULL,
 redimido_por INT UNSIGNED NULL,
 UNIQUE KEY solicitud_unica (visitante_id, solicitud_id),
 KEY negocio_fecha (negocio_id, creado_at),
 KEY visitante_fecha (visitante_id, creado_at),
 KEY vigencia (vence_at, redimido_at),
 FOREIGN KEY (oportunidad_id) REFERENCES cp_oportunidades(id),
 FOREIGN KEY (visitante_id) REFERENCES cp_visitantes(id),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id),
 FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id),
 FOREIGN KEY (promocion_id) REFERENCES cp_promociones(id),
 FOREIGN KEY (redimido_por) REFERENCES cp_usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_referidos (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 campana_id INT UNSIGNED NOT NULL,
 propietario_id BIGINT UNSIGNED NOT NULL,
 visitante_id BIGINT UNSIGNED NOT NULL,
 ip_hash CHAR(64) NOT NULL,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY visitante_una_vez (campana_id, visitante_id),
 KEY propietario_ip (campana_id, propietario_id, ip_hash),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id),
 FOREIGN KEY (propietario_id) REFERENCES cp_visitantes(id),
 FOREIGN KEY (visitante_id) REFERENCES cp_visitantes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_eventos (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 campana_id INT UNSIGNED NOT NULL,
 visitante_id BIGINT UNSIGNED NULL,
 premio_id BIGINT UNSIGNED NULL,
 tipo VARCHAR(50) NOT NULL,
 datos TEXT NULL,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY evento_fecha (tipo, creado_at),
 KEY premio_evento (premio_id, tipo),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id),
 FOREIGN KEY (visitante_id) REFERENCES cp_visitantes(id),
 FOREIGN KEY (premio_id) REFERENCES cp_premios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_auditoria (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT UNSIGNED NULL,
 accion VARCHAR(50) NOT NULL,
 entidad_id BIGINT UNSIGNED NULL,
 datos TEXT NULL,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY auditoria_fecha (creado_at),
 FOREIGN KEY (usuario_id) REFERENCES cp_usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_limites (
 clave CHAR(64) PRIMARY KEY,
 cantidad INT UNSIGNED NOT NULL DEFAULT 1,
 vence_at DATETIME NOT NULL,
 KEY vencimiento (vence_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_bienvenidas_ip (
 campana_id INT UNSIGNED NOT NULL,
 ip_hash CHAR(64) NOT NULL,
 dia DATE NOT NULL,
 cantidad INT UNSIGNED NOT NULL DEFAULT 0,
 PRIMARY KEY (campana_id, ip_hash, dia),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
