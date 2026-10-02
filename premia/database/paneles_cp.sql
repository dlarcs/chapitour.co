-- Extension aditiva del esquema cp_ existente. No borra ni sustituye tablas.
-- La API la instala una sola vez al iniciar sesion como administrador.
-- Tambien se puede ejecutar en phpMyAdmin sobre la base cp_ seleccionada.
CREATE TABLE IF NOT EXISTS cp_panel_clientes (
  cliente_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  ciudad VARCHAR(100) NOT NULL DEFAULT '',
  CONSTRAINT cp_panel_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_promociones (
  promocion_id INT UNSIGNED NOT NULL PRIMARY KEY,
  publicacion ENUM('draft','approved','archived') NOT NULL DEFAULT 'draft',
  beneficio VARCHAR(500) NOT NULL,
  incluidos VARCHAR(350) NOT NULL DEFAULT '',
  horarios VARCHAR(250) NOT NULL DEFAULT '',
  restricciones VARCHAR(350) NOT NULL DEFAULT '',
  whatsapp_confirmado VARCHAR(20) NOT NULL DEFAULT '',
  aprobada_por INT UNSIGNED NULL,
  aprobada_at DATETIME NULL,
  CONSTRAINT cp_panel_promocion_fk FOREIGN KEY (promocion_id) REFERENCES cp_promociones(id),
  CONSTRAINT cp_panel_aprobador_fk FOREIGN KEY (aprobada_por) REFERENCES cp_usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_retos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  mes DATE NOT NULL,
  tipo ENUM('visitar','compartir','fotografia') NOT NULL,
  titulo VARCHAR(180) NOT NULL,
  objetivo SMALLINT UNSIGNED NOT NULL,
  criterio_verificacion TEXT NULL,
  UNIQUE KEY cp_panel_reto_mes (mes,tipo),
  CHECK (DAYOFMONTH(mes)=1 AND objetivo>0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cp_panel_progreso (
  cliente_id BIGINT UNSIGNED NOT NULL,
  reto_id BIGINT UNSIGNED NOT NULL,
  cantidad_verificada SMALLINT UNSIGNED NULL,
  actualizado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cliente_id,reto_id),
  CONSTRAINT cp_panel_progreso_cliente_fk FOREIGN KEY (cliente_id) REFERENCES cp_clientes(id),
  CONSTRAINT cp_panel_progreso_reto_fk FOREIGN KEY (reto_id) REFERENCES cp_panel_retos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
