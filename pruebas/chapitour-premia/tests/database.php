<?php
declare(strict_types=1);

// Only accepts the isolated temporary MariaDB socket created for this QA run.
$socket = $argv[1] ?? '';
if (!preg_match('#^/private/tmp/chapitour-sql-qa\.[A-Za-z0-9]+/mysql\.sock$#', $socket)) {
    throw new RuntimeException('Indica el socket de la instancia temporal de QA. No se usan bases reales.');
}
$pdo = new PDO('mysql:unix_socket='.$socket.';charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
]);
function check(bool $condition, string $label): void {
    if (!$condition) { throw new RuntimeException('FAIL: '.$label); }
}
function rejected(PDO $pdo, string $sql, string $label): void {
    try { $pdo->exec($sql); } catch (PDOException $e) {
        check(in_array($e->getCode(), ['23000','45000','HY000'], true), $label.' unexpected: '.$e->getMessage());
        return;
    }
    throw new RuntimeException('FAIL: no se rechazo '.$label);
}
function scalar(PDO $pdo, string $sql) { return $pdo->query($sql)->fetchColumn(); }
check((int)scalar($pdo, "SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name='chapitour_premia_pruebas'") === 0, 'La base temporal debe estar vacia');

$delimiter = ';'; $buffer = '';
foreach (file(__DIR__.'/../database/chapitour_premia_pruebas.sql') as $line) {
    if (preg_match('/^\s*--/', $line)) { continue; }
    if (preg_match('/^DELIMITER\s+(\S+)/', trim($line), $m)) {
        check(trim($buffer) === '', 'DELIMITER fuera de sentencia');
        $delimiter = $m[1]; continue;
    }
    $buffer .= $line;
    if (str_ends_with(rtrim($buffer), $delimiter)) {
        $sql = substr(rtrim($buffer), 0, -strlen($delimiter));
        if (trim($sql) !== '') { $pdo->exec($sql); }
        $buffer = '';
    }
}
check(trim($buffer) === '', 'SQL completo');
check((int)scalar($pdo, "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_type='BASE TABLE'") === 10, 'Diez tablas');
check((int)scalar($pdo, 'SELECT COUNT(*) FROM usuarios') === 1, 'Solo administrador inicial');
$admin = $pdo->query('SELECT * FROM usuarios')->fetch(PDO::FETCH_ASSOC);
check($admin['email'] === 'laurazoro@gmail.com' && $admin['rol'] === 'administrador', 'Administrador correcto');
check((int)$admin['debe_cambiar_password'] === 1, 'Cambio de contrasena pendiente');
check(password_get_info($admin['password_hash'])['algoName'] === 'bcrypt', 'Hash bcrypt');
check((int)scalar($pdo, 'SELECT COUNT(*) FROM negocios') === 6, 'Seis negocios');
check((int)scalar($pdo, "SELECT COUNT(*) FROM promociones WHERE publicacion='borrador'") === 6, 'Sin aprobaciones inventadas');
check((int)scalar($pdo, "SELECT COUNT(*) FROM promociones WHERE origen='provisional'") === 2, 'Dos ofertas provisionales');
check((int)scalar($pdo, 'SELECT COUNT(*) FROM codigos_premio') === 0, 'Sin premios ficticios');
check((int)scalar($pdo, 'SELECT COUNT(*) FROM retos_mensuales') === 3, 'Tres retos medibles');
check(scalar($pdo, 'SELECT visitas_por_premio FROM reglas_incentivo') === null, 'Umbral pendiente');
check((int)scalar($pdo, 'SELECT habilitada FROM reglas_incentivo') === 0, 'Regla desactivada');
echo "PASS importacion, 10 tablas, administrador, bcrypt, catalogo y reglas pendientes\n";

$pdo->beginTransaction();
try {
    $hash = $pdo->quote(password_hash(bin2hex(random_bytes(12)), PASSWORD_BCRYPT));
    $pdo->exec("INSERT INTO usuarios (id,nombre,email,password_hash,rol) VALUES (100,'Cliente QA','cliente@example.invalid',$hash,'cliente')");
    rejected($pdo, "INSERT INTO usuarios (nombre,email,password_hash,rol) VALUES ('Otro','LAURAZORO@gmail.com',$hash,'cliente')", 'email duplicado');
    rejected($pdo, "INSERT INTO usuarios (nombre,email,password_hash,rol) VALUES ('Aliado','sin-negocio@example.invalid',$hash,'aliado')", 'aliado sin negocio');
    rejected($pdo, 'UPDATE reglas_incentivo SET habilitada=1 WHERE id=1', 'regla incompleta');
    rejected($pdo, "INSERT INTO visitas_validas (cliente_id,regla_id,clave_entrada) VALUES (100,1,'visita-bloqueada')", 'visita con regla desactivada');
    rejected($pdo, "INSERT INTO oportunidades_premio (cliente_id,regla_id,numero_ciclo,clave_otorgamiento) VALUES (100,1,1,'ciclo-bloqueado')", 'premio con regla desactivada');
    rejected($pdo, 'INSERT INTO progreso_retos (cliente_id,reto_id,cantidad_verificada) VALUES (100,1,1)', 'progreso sin criterio de verificacion');
    rejected($pdo, "UPDATE promociones SET publicacion='publicada',aprobada_por=1,aprobada_en=UTC_TIMESTAMP() WHERE id=1", 'publicacion incompleta');

    // Synthetic QA fixtures, all rolled back. They are never seeded in the delivered SQL.
    $pdo->exec("INSERT INTO negocios (id,slug,nombre,whatsapp,whatsapp_confirmado_en) VALUES (100,'negocio-qa','Negocio sintetico QA','10000000',UTC_TIMESTAMP())");
    $pdo->exec("INSERT INTO usuarios (id,negocio_id,nombre,email,password_hash,rol) VALUES (101,100,'Aliado QA','aliado@example.invalid',$hash,'aliado'), (102,1,'Otro aliado QA','otro@example.invalid',$hash,'aliado')");
    $pdo->exec("INSERT INTO reglas_incentivo (id,nombre,grupo_entrega,visitas_por_premio,intervalo_minimo_segundos,reinicia_contador_mensual,relacion_ruleta_anterior,habilitada) VALUES (100,'Fixture QA','qa_fixture',2,1,0,'reemplaza',1)");
    rejected($pdo, "INSERT INTO reglas_incentivo (nombre,grupo_entrega,visitas_por_premio,intervalo_minimo_segundos,reinicia_contador_mensual,relacion_ruleta_anterior,habilitada) VALUES ('Duplicada','qa_fixture',2,1,0,'reemplaza',1)", 'dos reglas para el mismo premio');
    $pdo->exec("INSERT INTO visitas_validas (cliente_id,regla_id,clave_entrada) VALUES (100,100,'qa-entrada')");
    rejected($pdo, "INSERT INTO visitas_validas (cliente_id,regla_id,clave_entrada) VALUES (100,100,'qa-entrada')", 'visita duplicada');
    rejected($pdo, 'UPDATE reglas_incentivo SET visitas_por_premio=3 WHERE id=100', 'cambio retroactivo de regla');
    $pdo->exec("INSERT INTO oportunidades_premio (id,cliente_id,regla_id,numero_ciclo,clave_otorgamiento) VALUES (100,100,100,1,'qa-ciclo-1'), (101,100,100,2,'qa-ciclo-2'), (102,100,100,3,'qa-ciclo-3'), (103,100,100,4,'qa-ciclo-4')");
    rejected($pdo, "INSERT INTO oportunidades_premio (cliente_id,regla_id,numero_ciclo,clave_otorgamiento) VALUES (100,100,1,'qa-duplicado')", 'ciclo duplicado');
    rejected($pdo, "INSERT INTO codigos_premio (oportunidad_id,promocion_id,codigo,oferta_otorgada,vence_en) VALUES (100,4,'QA-DRAFT-001','{}',UTC_TIMESTAMP())", 'codigo de borrador');
    $pdo->exec("INSERT INTO promociones (id,negocio_id,beneficio,productos_servicios_incluidos,horarios,restricciones,publicacion,aprobada_por,aprobada_en) VALUES (100,100,'Beneficio QA sin valor comercial','Servicio sintetico QA','Horario sintetico QA','Solo validacion tecnica','publicada',1,UTC_TIMESTAMP())");
    $pdo->exec("INSERT INTO codigos_premio (id,oportunidad_id,promocion_id,codigo,oferta_otorgada,vence_en) VALUES (100,100,100,'QA-ACTIVO-001','{}',UTC_TIMESTAMP())");
    check((int)scalar($pdo, 'SELECT TIMESTAMPDIFF(SECOND,generado_en,vence_en) FROM codigos_premio WHERE id=100') === 259200, '72 horas exactas');
    check(scalar($pdo, "SELECT estado FROM v_codigos_estado WHERE id=100") === 'Activo', 'Estado Activo');
    rejected($pdo, "INSERT INTO codigos_premio (oportunidad_id,promocion_id,codigo,oferta_otorgada,vence_en) VALUES (100,100,'QA-OTRO-001','{}',UTC_TIMESTAMP())", 'dos codigos por oportunidad');
    rejected($pdo, "INSERT INTO codigos_premio (oportunidad_id,promocion_id,codigo,oferta_otorgada,vence_en) VALUES (103,100,'QA-ACTIVO-001','{}',UTC_TIMESTAMP())", 'codigo duplicado');
    rejected($pdo, 'UPDATE oportunidades_premio SET cliente_id=101 WHERE id=100', 'transferencia de oportunidad');
    rejected($pdo, 'UPDATE promociones SET negocio_id=1 WHERE id=100', 'cambio de negocio con codigos');
    $snapshot = scalar($pdo, 'SELECT oferta_otorgada FROM codigos_premio WHERE id=100');
    $pdo->exec("UPDATE promociones SET beneficio='Oferta QA editada' WHERE id=100");
    check($snapshot === scalar($pdo, 'SELECT oferta_otorgada FROM codigos_premio WHERE id=100'), 'Oferta otorgada inmutable');
    rejected($pdo, 'INSERT INTO redenciones (codigo_id,confirmado_por) VALUES (100,102)', 'redencion por otro negocio');
    rejected($pdo, 'INSERT INTO redenciones (codigo_id,confirmado_por) VALUES (100,100)', 'redencion por cliente');
    $pdo->exec('INSERT INTO redenciones (codigo_id,confirmado_por) VALUES (100,101)');
    check(scalar($pdo, 'SELECT estado FROM v_codigos_estado WHERE id=100') === 'Redimido', 'Estado Redimido');
    rejected($pdo, 'INSERT INTO redenciones (codigo_id,confirmado_por) VALUES (100,1)', 'doble redencion');
    rejected($pdo, 'DELETE FROM redenciones WHERE codigo_id=100', 'eliminar redencion');
    rejected($pdo, 'UPDATE redenciones SET confirmado_por=1 WHERE codigo_id=100', 'alterar redencion');
    rejected($pdo, 'UPDATE codigos_premio SET vence_en=DATE_ADD(vence_en,INTERVAL 1 DAY) WHERE id=100', 'extender vigencia');
    rejected($pdo, 'DELETE FROM codigos_premio WHERE id=100', 'liberar oportunidad emitida');

    // Shift only this SQL connection's clock to test exact expiry; never alter the OS clock.
    $originalTime = (int)scalar($pdo, 'SELECT UNIX_TIMESTAMP()');
    $pdo->exec("INSERT INTO codigos_premio (id,oportunidad_id,promocion_id,codigo,oferta_otorgada,vence_en) VALUES (101,101,100,'QA-VENCIDO-001','{}',UTC_TIMESTAMP()), (102,102,100,'QA-ADMIN-001','{}',UTC_TIMESTAMP())");
    $pdo->exec('INSERT INTO redenciones (codigo_id,confirmado_por) VALUES (102,1)');
    $expires = (int)scalar($pdo, 'SELECT UNIX_TIMESTAMP(vence_en) FROM codigos_premio WHERE id=101');
    $pdo->exec('SET timestamp = '.($expires+1));
    check(scalar($pdo, 'SELECT estado FROM v_codigos_estado WHERE id=101') === 'Vencido', 'Estado Vencido sin cron');
    check(scalar($pdo, 'SELECT estado FROM v_codigos_estado WHERE id=102') === 'Redimido', 'Redimido prevalece tras caducidad');
    rejected($pdo, 'INSERT INTO redenciones (codigo_id,confirmado_por) VALUES (101,1)', 'redencion vencida');
    $pdo->exec('SET timestamp = 0');
    echo "PASS roles, FK, borradores, duplicados, vigencia, estados, aislamiento por negocio e inmutabilidad\n";
} finally {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    $pdo->exec('SET timestamp = 0');
}
check((int)scalar($pdo, 'SELECT COUNT(*) FROM usuarios') === 1, 'Fixtures revertidos');
check((int)scalar($pdo, 'SELECT COUNT(*) FROM codigos_premio') === 0, 'Sin codigos despues de QA');
echo "PASS fixtures revertidos; SQL entregable conserva solo datos iniciales\n";
