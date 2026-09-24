-- ChapiTour: estructura inicial para Hostinger / phpMyAdmin.
-- Seleccionar primero una BASE NUEVA Y VACIA creada desde hPanel.
-- Este archivo no crea bases ni usuarios MySQL y no incluye credenciales.
-- Las cuentas del panel se crean con promos/bin/accounts.php después de configurar PDO.
-- Las promociones quedan pendientes de los beneficios y condiciones reales.
SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- MySQL 8 / MariaDB 10.4+, InnoDB, fechas UTC. Importar en una base dedicada.
CREATE TABLE cp_campanas (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 activa TINYINT(1) NOT NULL DEFAULT 1,
 creada_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cp_negocios (
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

CREATE TABLE cp_promociones (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 negocio_id INT UNSIGNED NOT NULL,
 titulo VARCHAR(160) NOT NULL,
 descripcion VARCHAR(500) NOT NULL DEFAULT '',
 condiciones VARCHAR(1000) NOT NULL DEFAULT '',
 porcentaje DECIMAL(5,2) NULL,
 cupo_total INT UNSIGNED NULL,
 entregados INT UNSIGNED NOT NULL DEFAULT 0,
 activa TINYINT(1) NOT NULL DEFAULT 0,
 actualizada_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY cp_promociones_negocio (negocio_id),
 FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cp_visitantes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 identidad_hash CHAR(64) NOT NULL UNIQUE,
 ip_hash CHAR(64) NOT NULL,
 referido_token CHAR(32) NOT NULL UNIQUE,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 ultimo_acceso_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cp_participaciones (
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

CREATE TABLE cp_oportunidades (
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

CREATE TABLE cp_usuarios (
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

CREATE TABLE cp_premios (
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

CREATE TABLE cp_referidos (
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

CREATE TABLE cp_eventos (
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

CREATE TABLE cp_auditoria (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT UNSIGNED NULL,
 accion VARCHAR(50) NOT NULL,
 entidad_id BIGINT UNSIGNED NULL,
 datos TEXT NULL,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY auditoria_fecha (creado_at),
 FOREIGN KEY (usuario_id) REFERENCES cp_usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cp_limites (
 clave CHAR(64) PRIMARY KEY,
 cantidad INT UNSIGNED NOT NULL DEFAULT 1,
 vence_at DATETIME NOT NULL,
 KEY vencimiento (vence_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cp_bienvenidas_ip (
 campana_id INT UNSIGNED NOT NULL,
 ip_hash CHAR(64) NOT NULL,
 dia DATE NOT NULL,
 cantidad INT UNSIGNED NOT NULL DEFAULT 0,
 PRIMARY KEY (campana_id, ip_hash, dia),
 FOREIGN KEY (campana_id) REFERENCES cp_campanas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

START TRANSACTION;
INSERT IGNORE INTO cp_campanas (id,nombre) VALUES (1,'Chapinero te premia');
-- Los datos provienen de las páginas principales / ubicación del repositorio.
INSERT IGNORE INTO cp_negocios (id,slug,nombre,categoria,direccion,whatsapp,logo,pagina) VALUES
(1,'street-grill','Street Grill','Gastronomía','Carrera 9 #57-85','573143580355','gastronomia/streetgrill/img/logo.jpeg','gastronomia/streetgrill/index.php'),
(2,'capital-queer','Capital Queer','Bar','Carrera 9 #59-38','573007795016','bar/CapitalQueer/img/logoCapitalQueer.jpg','bar/CapitalQueer/index.php'),
(3,'jimar-factory','Jimar Factory','Juegos y billar','Calle 58 #13-93','573165180649','juegos/JimarFactory/img/logo.jpeg','juegos/JimarFactory/index.php'),
(4,'garage-disco-bar','Garage Disco Bar','Gastrobar','Calle 59 #9-39','573156175056','gastrobar/GarageDiscoBar/img/general11.jpg','gastrobar/GarageDiscoBar/index.php'),
(5,'pictogramas','Pictogramas Café Bar','Café bar','Calle 59 #13-20','573502835648','bar/Pictograma/img/logo.jpeg','bar/Pictograma/index.php'),
(6,'gran-chela','Gran&Chela Club','Bar y discoteca','Calle 59 #10-24','573224680419','bar/Gran&Chela_Club/img/logo.jpg','bar/Gran&Chela_Club/index.php');
-- No se activan descuentos sin conocer la oferta y condiciones aprobadas por cada aliado.
INSERT IGNORE INTO cp_promociones (negocio_id,titulo,condiciones,activa)
SELECT id,'Beneficio por configurar','Completar y aprobar las condiciones antes de activar.',0 FROM cp_negocios;

COMMIT;
