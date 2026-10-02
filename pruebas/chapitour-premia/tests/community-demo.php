<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA temporal.'); }
require __DIR__.'/../lib/Community.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
$db->exec("SET time_zone='+00:00'");
$community=new ChapitourCommunity($db);
$checks=0;$snapshots=[];
function check($ok,string $message):void { global $checks;if(!$ok)throw new RuntimeException($message);$checks++; }
function counts(PDO $db):array {
    $counts=[];
    foreach(['cp_clientes','cp_premios','cp_panel_giros','cp_panel_puntos_visitas','cp_panel_meta_fotos','cp_panel_meta_preguntas'] as $table) { $counts[$table]=(int)$db->query('SELECT COUNT(*) FROM '.$table)->fetchColumn(); }
    return $counts;
}
check($community->ready(),'Primero prepara el esquema QA de comunidad.');
$before=counts($db);
$db->beginTransaction();
try {
    // Only this transaction sees the fixture changes; roll back all of them on completion.
    $db->exec('UPDATE cp_panel_comunidad SET visible=0');
    $ids=$db->query('SELECT id FROM cp_clientes WHERE activo=1 ORDER BY id LIMIT 21')->fetchAll(PDO::FETCH_COLUMN);
    check(count($ids)===21,'Se necesitan 21 cuentas de las pruebas anteriores.');
    $previous=0;
    foreach([0,1,5,10,19,20,21] as $realCount) {
        for($i=$previous;$i<$realCount;$i++) { $community->savePreferences((int)$ids[$i],sprintf('Participante QA %02d',$i+1),true); }
        $previous=$realCount;
        $full=$community->leaderboard(1,20);
        $demos=array_filter($full['entries'],static function(array $e):bool { return $e['demo']??false; });
        check($full['real_total']===$realCount && $full['demo_total']===max(0,20-$realCount),'Los ejemplos se reemplazan uno por uno: '.$realCount);
        check($full['total']===max(20,$realCount) && count($full['entries'])===20,'La lista mantiene al menos veinte entradas: '.$realCount);
        check(count($demos)===max(0,20-$realCount),'Cantidad exacta de ejemplos visibles: '.$realCount);
        foreach($full['entries'] as $i=>$entry) {
            check(($entry['demo']??false)===($i>=$realCount),'Las cuentas reales siempre aparecen primero.');
            check(array_keys($entry)===(($entry['demo']??false)?['name','score','demo']:['name','score']),'El listado no expone datos privados.');
        }
        $top=$community->leaderboard(1,10);$second=$community->leaderboard(2,10);
        check(array_merge($top['entries'],$second['entries'])===$full['entries'],'Paginación sin duplicar ni saltar perfiles: '.$realCount);
        check($top['has_more'] && $second['has_more']===($realCount>20),'Paginación cuenta ejemplos y personas: '.$realCount);
        $after=$community->leaderboard(2,20);
        check(count($after['entries'])===max(0,$realCount-20) && !$after['has_more'],'Fin de lista correcto: '.$realCount);
        $empty=$community->leaderboard(10000,20);
        check($empty['entries']===[] && !$empty['has_more'],'No se repiten ejemplos fuera de rango.');
        if($realCount===1) { check($community->member((int)$ids[0])['position']===1 && $community->member((int)$ids[0])['points_to_climb']===null,'Los puntajes ficticios no afectan la posición real.'); }
        if($realCount===0) { check(count(array_unique(array_column($full['entries'],'name')))===20,'Veinte nombres ficticios distintos.'); }
        $snapshots[(string)$realCount]=['top'=>$top,'full'=>$full,'next'=>$after];
    }
    check(counts($db)===$before,'Leer ejemplos no crea cuentas, puntos, retos, giros ni premios.');
    $community->savePreferences((int)$ids[20],'Participante QA 21',false);
    check($community->leaderboard()['demo_total']===0,'Una cuenta privada no ocupa un espacio público.');
    $stmt=$db->prepare('UPDATE cp_clientes SET activo=0 WHERE id=?');$stmt->execute([$ids[19]]);
    check($community->leaderboard()['real_total']===19 && $community->leaderboard()['demo_total']===1,'Una cuenta inactiva no ocupa un espacio público.');
    file_put_contents(dirname($socket).'/community-demo-ui.json',json_encode($snapshots,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
} finally { $db->rollBack(); }
check(counts($db)===$before,'Se restauran todos los datos QA.');
echo "PASS $checks comprobaciones: veinte ejemplos etiquetados, reemplazo automático, prioridad real, privacidad, paginación y ausencia de premios o cuentas ficticias.\n";
