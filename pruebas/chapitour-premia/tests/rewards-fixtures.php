<?php
declare(strict_types=1);
$socket=$argv[2]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA aislado.'); }
require __DIR__.'/../lib/Panel.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
$db->exec("SET time_zone='+00:00'");
function sql(string $query,array $args=[]): PDOStatement { global $db;$s=$db->prepare($query);$s->execute($args);return $s; }
function check($actual,$expected,string $name): void { if ($actual!==$expected) { throw new RuntimeException($name.': '.json_encode([$actual,$expected])); } }
function client(int $id): array {
    sql('INSERT INTO cp_clientes(id,nombre,email,password_hash) VALUES (?,?,?,?)',[$id,'Ruleta QA '.$id,'ruleta-'.$id.'@example.invalid',password_hash('Qa-ruleta-456!',PASSWORD_BCRYPT)]);
    return ['db_id'=>$id,'role'=>'client','version'=>1];
}
function clockAt(string $utc): void { sql('SET timestamp='.strtotime($utc.' UTC')); }
$rewards=new ChapitourRewards($db);
if (($argv[1]??'')==='clock') {
    $a=client(300);clockAt('2026-09-23 18:00:00');
    $rewards->visit($a);$rewards->visit($a);
    check((int)sql('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=300')->fetchColumn(),1,'Recargas');
    clockAt('2026-09-24 17:59:59');$rewards->visit($a);
    check((int)sql('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=300')->fetchColumn(),1,'Antes de 24h');
    for($day=24;$day<=30;$day++){clockAt('2026-09-'.$day.' 18:00:00');$rewards->visit($a);}
    check((int)sql('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=300')->fetchColumn(),1,'Un giro cada ocho');
    check((int)sql('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=300')->fetchColumn(),0,'Nuevo ciclo');
    $ticket=(string)sql('SELECT id FROM cp_panel_giros WHERE cliente_id=300')->fetchColumn();
    try {$rewards->spin($a,$ticket);throw new RuntimeException('Un borrador genero un premio');}catch(PanelError $e){check($e->getCode(),422,'Sin ofertas aprobadas');}
    $b=client(301);clockAt('2026-10-01 04:58:00');$rewards->visit($b);
    sql('UPDATE cp_panel_visitas SET visitas_ciclo=7 WHERE cliente_id=301');
    clockAt('2026-10-01 05:01:00');$rewards->visit($b);
    check((int)sql('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=301')->fetchColumn(),0,'Medianoche Bogota reinicia sin conceder otra visita');
    clockAt('2026-10-02 04:58:00');$rewards->visit($b);
    check((int)sql('SELECT visitas_ciclo FROM cp_panel_visitas WHERE cliente_id=301')->fetchColumn(),1,'Primera visita del nuevo mes');
    check((int)sql('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=301')->fetchColumn(),0,'No mezcla visitas entre meses');
    $rewards->visit($a);
    check((int)sql('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id=300 AND premio_id IS NULL')->fetchColumn(),1,'El giro ganado se conserva');
    check((int)sql('SELECT COUNT(*) FROM cp_premios WHERE codigo LIKE \'CHAPI-%\'')->fetchColumn(),0,'No hay premios de borradores');
    echo "PASS 24h exactas, ocho visitas, recargas, frontera mensual en Bogota, conservacion de giros y borradores excluidos.\n";
} elseif (($argv[1]??'')==='seed') {
    foreach([400,401,402] as $id) {
        client($id);
        sql("INSERT INTO cp_panel_visitas(cliente_id,mes,visitas_ciclo,ultima_visita_at) VALUES (?,DATE_FORMAT(DATE_SUB(UTC_TIMESTAMP(),INTERVAL 5 HOUR),'%Y-%m'),7,DATE_SUB(UTC_TIMESTAMP(),INTERVAL 24 HOUR))",[$id]);
    }
    echo "PASS tres cuentas QA preparadas con siete visitas.\n";
} elseif (($argv[1]??'')==='cap') {
    sql('UPDATE cp_promociones SET cupo_total=entregados+1 WHERE id=6');
} elseif (($argv[1]??'')==='verify') {
    check((int)sql("SELECT COUNT(*) FROM cp_premios WHERE codigo LIKE 'CHAPI-%'")->fetchColumn(),2,'Una emision por ciclo y ultimo cupo');
    check((int)sql("SELECT COUNT(*) FROM cp_premios WHERE codigo LIKE 'CHAPI-%' AND TIMESTAMPDIFF(SECOND,creado_at,vence_at)=259200")->fetchColumn(),2,'Vigencia de 72h');
    check((int)sql("SELECT COUNT(*) FROM cp_premios WHERE codigo LIKE 'CHAPI-%' AND promocion_id<>6")->fetchColumn(),0,'Solo aprobada');
    check((int)sql("SELECT COUNT(*) FROM cp_auditoria WHERE accion='premio_generado'")->fetchColumn(),2,'Auditoria no duplicada');
    check((int)sql('SELECT COUNT(*) FROM cp_panel_giros WHERE cliente_id IN (401,402) AND premio_id IS NULL')->fetchColumn(),1,'Ultimo cupo no consume giro del otro cliente');
    echo "PASS premios unicos, 72h, auditoria, cupos y giros conservados.\n";
}
