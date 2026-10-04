-- Un recordatorio de vencimiento por premio, sin copiar direcciones de correo.
CREATE TABLE IF NOT EXISTS cp_panel_recordatorios (
  premio_id BIGINT UNSIGNED NOT NULL PRIMARY KEY,
  estado ENUM('pending','sending','sent','retry','uncertain','skipped') NOT NULL DEFAULT 'pending',
  token CHAR(32) NOT NULL,
  intentos TINYINT UNSIGNED NOT NULL DEFAULT 0,
  proximo_intento_at DATETIME NULL,
  iniciado_at DATETIME NULL,
  enviado_at DATETIME NULL,
  error_codigo VARCHAR(40) NULL,
  actualizado_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY cp_recordatorio_pendiente (estado,proximo_intento_at),
  CONSTRAINT cp_recordatorio_premio_fk FOREIGN KEY (premio_id) REFERENCES cp_premios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
