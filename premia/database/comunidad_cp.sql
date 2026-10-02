-- Preferencias públicas, foto privada de perfil y visitas puntuables por mes.
CREATE TABLE IF NOT EXISTS cp_panel_comunidad (
  cliente_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  nombre_publico VARCHAR(40) NOT NULL DEFAULT '',
  visible TINYINT UNSIGNED NOT NULL DEFAULT 0,
  foto MEDIUMBLOB NULL,
  foto_version CHAR(32) NULL,
  CONSTRAINT cp_comunidad_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CHECK (visible IN (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_puntos_visitas (
  cliente_id BIGINT UNSIGNED NOT NULL,
  mes DATE NOT NULL,
  cantidad TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (cliente_id,mes),
  KEY cp_puntos_visitas_mes (mes,cliente_id),
  CONSTRAINT cp_puntos_visitas_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CHECK (cantidad<=10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
