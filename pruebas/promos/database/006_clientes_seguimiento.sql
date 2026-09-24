-- Solo entorno de pruebas. Conserva las tablas y códigos existentes.
CREATE TABLE IF NOT EXISTS cp_clientes (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 activo TINYINT(1) NOT NULL DEFAULT 1,
 version_sesion INT UNSIGNED NOT NULL DEFAULT 1,
 creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_cliente_visitantes (
 cliente_id BIGINT UNSIGNED NOT NULL,
 visitante_id BIGINT UNSIGNED NOT NULL UNIQUE,
 vinculado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (cliente_id,visitante_id),
 FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
 FOREIGN KEY (visitante_id) REFERENCES cp_visitantes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_premio_detalles (
 premio_id BIGINT UNSIGNED PRIMARY KEY,
 negocio VARCHAR(120) NOT NULL,
 direccion VARCHAR(200) NOT NULL DEFAULT '',
 whatsapp VARCHAR(20) NOT NULL DEFAULT '',
 FOREIGN KEY (premio_id) REFERENCES cp_premios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO cp_premio_detalles(premio_id,negocio,direccion,whatsapp)
SELECT p.id,n.nombre,n.direccion,n.whatsapp FROM cp_premios p
JOIN cp_negocios n ON n.id=p.negocio_id
WHERE NOT EXISTS(SELECT 1 FROM cp_premio_detalles d WHERE d.premio_id=p.id);
