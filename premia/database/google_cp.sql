-- Vinculación única de cada identidad de Google a una cuenta de cliente.
CREATE TABLE IF NOT EXISTS cp_panel_google (
  cliente_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  subject_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT cp_panel_google_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
