<?php
declare(strict_types=1);
$socket=$argv[2]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo se permite la instancia temporal de QA.'); }
$db=new PDO('mysql:unix_socket='.$socket.';charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->exec("SET time_zone='+00:00'");
function run(PDO $db,string $sql,array $args=[]): void { $s=$db->prepare($sql);$s->execute($args); }
if (($argv[1]??'')==='setup') {
    if ($db->query("SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name='chapitour_panels_qa'")->fetchColumn()) { throw new RuntimeException('Usa una base temporal nueva.'); }
    $dump=file_get_contents($argv[3]??'');
    $db->exec('CREATE DATABASE chapitour_panels_qa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $db->exec('USE chapitour_panels_qa');
    preg_match_all('/(?:CREATE TABLE|ALTER TABLE)[\s\S]*?;\n/',$dump,$ddl);
    foreach ($ddl[0] as $sql) { $db->exec($sql); }
    foreach (['cp_negocios','cp_promociones'] as $t) {
        preg_match('/INSERT INTO `'.$t.'`[\s\S]*?;\n/',$dump,$m); $db->exec($m[0]);
    }
    $hash=password_hash('Qa-admin-old-456!',PASSWORD_BCRYPT);
    run($db,"INSERT INTO cp_usuarios(id,usuario,password_hash,rol,activo,cambiar_password) VALUES (100,'laurazoro@gmail.com',?,'admin',1,1)",[$hash]);
    $hash=password_hash('Qa-ally-456!',PASSWORD_BCRYPT);
    run($db,"INSERT INTO cp_usuarios(id,negocio_id,usuario,password_hash,rol,activo,cambiar_password) VALUES (101,1,'street@example.invalid',?,'aliado',1,0),(102,4,'garage@example.invalid',?,'aliado',1,0)",[$hash,$hash]);
    $hash=password_hash('Qa-client-456!',PASSWORD_BCRYPT);
    run($db,"INSERT INTO cp_clientes(id,nombre,email,password_hash) VALUES (100,'Cliente QA','cliente@example.invalid',?),(101,'Otro cliente QA','otro@example.invalid',?)",[$hash,$hash]);
    run($db,"INSERT INTO cp_clientes(id,nombre,email,password_hash) VALUES (102,'Cliente existente QA','nuevo-cliente@example.invalid',?)",[password_hash('Qa-new-client-456!',PASSWORD_BCRYPT)]);
    $db->exec("INSERT INTO cp_campanas(id,nombre,activa) VALUES (1,'Campaña QA desactivada',0)");
    foreach ([100,101] as $id) {
        run($db,'INSERT INTO cp_visitantes(id,identidad_hash,ip_hash,referido_token) VALUES (?,?,?,?)',[$id,hash('sha256','qa-'.$id),hash('sha256','ip-'.$id),md5('qa-'.$id)]);
        run($db,'INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$id,$id]);
    }
    foreach ([['QA-ACTIVE',100,1,6,1,null],['QA-EXPIRED',100,1,6,73,null],['QA-REDEEMED',100,1,6,1,101],['QA-OTHER',101,4,2,1,null],['QA-RACE',100,1,6,1,null]] as $i=>$c) {
        $id=100+$i; [$code,$visitor,$business,$promo,$hours,$by]=$c;
        run($db,"INSERT INTO cp_oportunidades(id,visitante_id,campana_id,origen,origen_clave) VALUES (?,?,1,'fixture',?)",[$id,$visitor,'fixture-'.$id]);
        run($db,"INSERT INTO cp_premios(id,oportunidad_id,visitante_id,campana_id,negocio_id,promocion_id,codigo,solicitud_id,titulo,descripcion,condiciones,creado_at,vence_at,redimido_por,redimido_at) VALUES (?,?,?,1,?,?,?,?,?,?,?,DATE_SUB(UTC_TIMESTAMP(),INTERVAL ? HOUR),DATE_ADD(DATE_SUB(UTC_TIMESTAMP(),INTERVAL ? HOUR),INTERVAL 72 HOUR),?,?)",[$id,$id,$visitor,$business,$promo,$code,sprintf('00000000-0000-4000-8000-%012d',$id),'Oferta fixture QA','Oferta fixture QA, sin valor comercial','Solo QA aislado',$hours,$hours,$by,$by?gmdate('Y-m-d H:i:s'):null]);
        run($db,"INSERT INTO cp_premio_detalles(premio_id,negocio,whatsapp) VALUES (?,'Negocio QA','10000000')",[$id]);
    }
    echo "PASS esquema original cp_ importado; fixtures sintéticos aislados.\n";
} elseif (($argv[1]??'')==='verify') {
    $db->exec('USE chapitour_panels_qa');
    if ((int)$db->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name LIKE 'cp_panel_%'")->fetchColumn()!==10) { throw new RuntimeException('Faltan extensiones'); }
    if ((int)$db->query("SELECT COUNT(*) FROM cp_premios WHERE codigo LIKE 'DEMO-%'")->fetchColumn()!==0) { throw new RuntimeException('Se generaron premios ficticios'); }
    if ($db->query("SELECT descripcion FROM cp_premios WHERE codigo='QA-ACTIVE'")->fetchColumn()!=='Oferta fixture QA, sin valor comercial') { throw new RuntimeException('Se cambió el snapshot'); }
    if ((int)$db->query("SELECT COUNT(*) FROM cp_auditoria WHERE accion='premio_redimido' AND entidad_id=104")->fetchColumn()!==1) { throw new RuntimeException('Carrera duplicó redención'); }
    if ((int)$db->query('SELECT activa FROM cp_campanas WHERE id=1')->fetchColumn()!==0) { throw new RuntimeException('Se activó la campaña'); }
    echo "PASS tablas auxiliares, snapshot conservado, carrera con una sola redención y campaña desactivada.\n";
} elseif (($argv[1]??'')==='stop') { $db->exec('SHUTDOWN'); }
