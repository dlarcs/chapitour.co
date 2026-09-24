-- Ejecutar sobre la base existente de Chapitour antes de subir el nuevo panel.
-- Conserva negocios, usuarios, promociones, premios e historial. Se puede repetir.
-- Primero se mantiene un índice para la clave foránea; luego se quita la unicidad.
SET @chapi_index_sql = IF(
  EXISTS(SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE()
         AND TABLE_NAME='cp_promociones' AND INDEX_NAME='cp_promociones_negocio'),
  'SELECT 1', 'ALTER TABLE cp_promociones ADD INDEX cp_promociones_negocio (negocio_id)'
);
PREPARE chapi_migration FROM @chapi_index_sql;
EXECUTE chapi_migration;
DEALLOCATE PREPARE chapi_migration;

SET @chapi_unique_index = (
  SELECT INDEX_NAME FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='cp_promociones' AND NON_UNIQUE=0
    AND INDEX_NAME<>'PRIMARY'
  GROUP BY INDEX_NAME HAVING COUNT(*)=1 AND MAX(COLUMN_NAME)='negocio_id' LIMIT 1
);
SET @chapi_index_sql = IF(@chapi_unique_index IS NULL, 'SELECT 1',
  CONCAT('ALTER TABLE cp_promociones DROP INDEX `', REPLACE(@chapi_unique_index,'`','``'), '`'));
PREPARE chapi_migration FROM @chapi_index_sql;
EXECUTE chapi_migration;
DEALLOCATE PREPARE chapi_migration;
