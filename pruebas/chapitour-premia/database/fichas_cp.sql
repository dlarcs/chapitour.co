-- Control independiente de la visibilidad de las fichas en la página principal.
-- Sin una preferencia guardada, se conserva la publicación de las páginas existentes.
CREATE TABLE IF NOT EXISTS cp_panel_fichas (
  negocio_id INT UNSIGNED NOT NULL PRIMARY KEY,
  visible TINYINT(1) NOT NULL DEFAULT 0,
  actualizado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT cp_panel_ficha_negocio_fk FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
