-- Extension aditiva: visitas por cuenta y un giro por cada ciclo de ocho.
CREATE TABLE IF NOT EXISTS cp_panel_campana (
  id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  campana_id INT UNSIGNED NOT NULL,
  reinicio_mensual TINYINT UNSIGNED NULL,
  CONSTRAINT cp_panel_campana_fk FOREIGN KEY (campana_id) REFERENCES cp_campanas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_visitas (
  cliente_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  ultima_visita_at DATETIME NULL,
  mes CHAR(7) NOT NULL,
  visitas_ciclo TINYINT UNSIGNED NOT NULL DEFAULT 0,
  ciclos BIGINT UNSIGNED NOT NULL DEFAULT 0,
  CONSTRAINT cp_panel_visita_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CHECK (visitas_ciclo < 8)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_giros (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  ciclo BIGINT UNSIGNED NOT NULL,
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  premio_id BIGINT UNSIGNED NULL,
  UNIQUE KEY cp_panel_giro_ciclo (cliente_id,ciclo),
  UNIQUE KEY cp_panel_giro_premio (premio_id),
  CONSTRAINT cp_panel_giro_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CONSTRAINT cp_panel_giro_premio_fk FOREIGN KEY (premio_id) REFERENCES cp_premios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
