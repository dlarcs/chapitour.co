<?php
// Regression for the former demo-filled ranking: only registered accounts remain.
declare(strict_types=1);
$socket=$argv[1]??'';
if(PHP_SAPI!=='cli'||!preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket))throw new RuntimeException('Solo QA temporal.');
require (getenv('CHAPITOUR_TEST_APP')==='legacy'?__DIR__.'/../lib/Community.php':__DIR__.'/../../../premia/lib/Community.php');
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);$db->exec("SET time_zone='+00:00'");
$community=new ChapitourCommunity($db);$checks=0;$snapshots=[];
function check($ok,string $message):void {global $checks;if(!$ok)throw new RuntimeException($message);$checks++;}
function counts(PDO $db):array{$counts=[];foreach(['cp_clientes','cp_premios','cp_panel_giros','cp_panel_puntos_visitas','cp_panel_meta_fotos','cp_panel_meta_preguntas'] as $table)$counts[$table]=(int)$db->query('SELECT COUNT(*) FROM '.$table)->fetchColumn();return $counts;}
check($community->ready(),'Primero prepara el esquema QA.');$before=counts($db);
$db->beginTransaction();
try{
    $db->exec("INSERT INTO cp_panel_comunidad(cliente_id,nombre_publico,visible) SELECT id,'',0 FROM cp_clientes WHERE activo=1 ON DUPLICATE KEY UPDATE visible=0");
    $ids=$db->query('SELECT id FROM cp_clientes WHERE activo=1 ORDER BY id LIMIT 21')->fetchAll(PDO::FETCH_COLUMN);
    check(count($ids)===21,'Se necesitan 21 cuentas QA.');$previous=0;
    foreach([0,1,5,10,19,20,21] as $realCount){
        for($i=$previous;$i<$realCount;$i++){
            $stmt=$db->prepare('UPDATE cp_clientes SET nombre=? WHERE id=?');$stmt->execute([sprintf('Participante QA %02d',$i+1),$ids[$i]]);
            $stmt=$db->prepare('UPDATE cp_panel_comunidad SET nombre_publico=? WHERE cliente_id=?');$stmt->execute(['Alias anterior '.$i,$ids[$i]]);
            $community->savePreferences((int)$ids[$i],true);
        }
        $previous=$realCount;$full=$community->leaderboard(1,20);
        check($full['total']===$realCount && $full['real_total']===$realCount && $full['demo_total']===0,'Cuenta únicamente personas registradas: '.$realCount);
        check(count($full['entries'])===min(20,$realCount),'No completa la lista con relleno: '.$realCount);
        $score=PHP_INT_MAX;
        foreach($full['entries'] as $entry){
            check(array_keys($entry)===['name','score'],'Solo nombre y puntaje, sin correos, fotos o perfiles de ejemplo');
            check(strncmp($entry['name'],'Participante QA ',16)===0,'La entrada pertenece a una cuenta de prueba registrada');
            check($entry['score']<=$score,'Orden descendente por puntaje');$score=$entry['score'];
        }
        $top=$community->leaderboard(1,10);$second=$community->leaderboard(2,10);
        check(array_merge($top['entries'],$second['entries'])===$full['entries'],'Paginación sin duplicados ni saltos');
        check($top['has_more']===($realCount>10) && $second['has_more']===($realCount>20),'Fin de página con cantidad real');
        $after=$community->leaderboard(2,20);check(count($after['entries'])===max(0,$realCount-20) && !$after['has_more'],'Fin de lista');
        $empty=$community->leaderboard(10000,20);check(!$empty['entries'] && !$empty['has_more'],'Fuera de rango no inventa filas');
        $snapshots[(string)$realCount]=['top'=>$top,'full'=>$full,'next'=>$after];
    }
    check(counts($db)===$before,'La consulta no crea cuentas, puntos, retos, giros ni premios');
    $community->savePreferences((int)$ids[20],false);
    check($community->leaderboard()['total']===20,'Respeta una cuenta que elige ocultarse');
    $stmt=$db->prepare('UPDATE cp_clientes SET activo=0 WHERE id=?');$stmt->execute([$ids[19]]);
    check($community->leaderboard()['total']===19,'No publica cuentas inactivas');
    // Existing accounts use their complete name even without public-profile preferences.
    $id=(int)$ids[18];$stmt=$db->prepare('DELETE FROM cp_panel_comunidad WHERE cliente_id=?');$stmt->execute([$id]);
    $profile=$community->member($id);$name=$profile['public_name'];
    check($profile['visible'] && $name==='Participante QA 19','Cuenta existente sin preferencias muestra su nombre');
    check($community->member($id)['public_name']===$name,'Nombre estable entre consultas');
    $stmt=$db->prepare('SELECT COUNT(*) FROM cp_panel_comunidad WHERE cliente_id=?');$stmt->execute([$id]);check((int)$stmt->fetchColumn()===0,'Lectura no modifica la base');
    $community->savePhoto($id,null);check($community->member($id)['visible'] && $community->member($id)['public_name']===$name,'Foto no oculta la cuenta ni cambia su nombre');
    $community->savePreferences($id,false);$community->savePhoto($id,null);$community->registerMember($id);
    check(!$community->member($id)['visible'] && $community->member($id)['public_name']===$name,'Preferencias explícitas se conservan');
    $longName=str_repeat('Á',65).' Pérez';
    $stmt=$db->prepare('UPDATE cp_clientes SET nombre=? WHERE id=?');$stmt->execute([$longName,$id]);
    $community->savePreferences($id,true);
    check($community->member($id)['public_name']===$longName,'Nombre completo mayor de 40 caracteres sin truncar');
    check(in_array($longName,array_column($community->leaderboard(1,20)['entries'],'name'),true),'Nombre largo en la lista pública');
    $stmt->execute(['Ana <b>María</b> Pérez',$id]);
    check($community->member($id)['public_name']==='Ana <b>María</b> Pérez','Conserva el nombre para que la interfaz lo escape');
    file_put_contents(dirname($socket).'/community-demo-ui.json',json_encode($snapshots,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
}finally{$db->rollBack();}
check(counts($db)===$before,'Datos temporales restaurados');
echo "PASS $checks comprobaciones: solo registros reales, sin relleno, nombres completos de la cuenta, privacidad, orden, paginación y ausencia de cambios en premios.\n";
