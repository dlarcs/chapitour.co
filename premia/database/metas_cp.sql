-- Metas mensuales independientes. Conserva el progreso y los premios anteriores.
CREATE TABLE IF NOT EXISTS cp_panel_meta_fotos (
  cliente_id BIGINT UNSIGNED NOT NULL,
  mes DATE NOT NULL,
  negocio_id INT UNSIGNED NOT NULL,
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cliente_id,mes,negocio_id),
  CONSTRAINT cp_meta_foto_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CONSTRAINT cp_meta_foto_negocio_fk FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_meta_preguntas (
  cliente_id BIGINT UNSIGNED NOT NULL,
  mes DATE NOT NULL,
  negocio_id INT UNSIGNED NOT NULL,
  version SMALLINT UNSIGNED NOT NULL,
  lugar_reportado_id INT UNSIGNED NOT NULL,
  respuestas TEXT NOT NULL,
  creado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cliente_id,mes,negocio_id,version),
  CONSTRAINT cp_meta_pregunta_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CONSTRAINT cp_meta_pregunta_negocio_fk FOREIGN KEY (negocio_id) REFERENCES cp_negocios(id),
  CONSTRAINT cp_meta_pregunta_lugar_fk FOREIGN KEY (lugar_reportado_id) REFERENCES cp_negocios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
