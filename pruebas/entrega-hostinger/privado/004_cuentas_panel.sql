-- Importar UNA VEZ en u348170507_chapi_promos, después del esquema inicial.
-- Solo crea las siete cuentas del panel, con hashes y cambio inicial obligatorio.
-- No modifica usuarios existentes. Si un nombre ya existe, el INSERT completo falla.
-- No contiene contraseñas en texto plano. No subir este archivo a public_html.
SET NAMES utf8mb4;
START TRANSACTION;
INSERT INTO cp_usuarios (negocio_id,usuario,password_hash,rol,activo,cambiar_password,version_sesion) VALUES
(NULL,'admin','$2y$10$zKb2NuCd/dG80Kb4nyAs0e1SEymroNkMRPVFPWpLkOf0EMok3hAQO','admin',1,1,1),
(1,'street-grill','$2y$10$1qerq/LWlgTrcJZsLR7FVu0Sgtygh0xPD1e//vnMUMQtD8VKmVZJS','aliado',1,1,1),
(2,'capital-queer','$2y$10$/dptukvINDtnUNuoTVsXeOj8jVkmjl6kNvXg6aglmLDZPudwgaa5W','aliado',1,1,1),
(3,'jimar-factory','$2y$10$T/EIgUz.roS754pv2hlOFONCf/FVlu7lfbcput6/MWw.5I1RbDkai','aliado',1,1,1),
(4,'garage-disco-bar','$2y$10$5DR58FkdXt0utttaRVI1AuFLWHWq9KczODMR1q2SnQfxWmFnj0mLm','aliado',1,1,1),
(5,'pictogramas','$2y$10$oZrVxasncjiabYPDZLAneuyjLZwGklP4BahjAtvTphw4gP6/Of8aO','aliado',1,1,1),
(6,'gran-chela','$2y$10$Y7rnis3WmeEWgFONr3zxd.R05PgjgcM/t41Ct9OoAWSqss1l1QJ2q','aliado',1,1,1);
COMMIT;
