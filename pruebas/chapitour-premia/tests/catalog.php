<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if(PHP_SAPI!=='cli'||!preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket))throw new RuntimeException('Solo QA temporal.');
require __DIR__.'/../../../premia/lib/Panel.php';
$_SESSION=['csrf'=>'qa'];$checks=0;
function check($ok,string $message):void{global $checks;if(!$ok)throw new RuntimeException($message);$checks++;}
foreach(['Street Grill','Capital Queer','Gran&Chela Club','Garage Disco Bar','Pictogramas Café Bar','Jimar Factory'] as $name){
    $key=ChapitourCatalog::key('aliado-recreado',$name);check($key!==null,'Reconoce '.$name);
    $page=ChapitourCatalog::page($key);
    foreach(['path','image'] as $field)check(is_file(__DIR__.'/../../../'.$page[$field]),'La página y la imagen existen: '.$name);
}
check(ChapitourCatalog::key('aliado-desconocido','Otro Pictogramas')===null,'No asigna una identidad por coincidencias parciales.');
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->exec("SET time_zone='+00:00'");$db->beginTransaction();
try{
    $id=(int)$db->query("SELECT id FROM cp_negocios WHERE slug='pictogramas'")->fetchColumn();check($id>0,'Existe el negocio QA.');
    $stmt=$db->prepare("UPDATE cp_negocios SET activo=1,nombre='Pictogramas Café Bar',slug='aliado-recreado-qa',pagina='',logo='',categoria='Aliado de Chapitour' WHERE id=?");$stmt->execute([$id]);
    $state=(new ChapitourPanel($db))->state();$business=null;
    foreach($state['businesses'] as $b)if($b['id']===(string)$id)$business=$b;
    check($business['path']==='bar/Pictograma/index.php'&&$business['image']==='bar/Pictograma/img/logo.jpeg','Recupera página y logo sin escribirlos en la base.');
    check($business['icon']==='coffee'&&$business['category']==='Café bar','Recupera la presentación del aliado.');
    $goals=(new ChapitourChallenges($db))->state(1);
    check(in_array((string)$id,array_column($goals[0]['places'],'id'),true),'El negocio recreado participa en Instagram.');
    check(in_array('r4',array_column($goals[1]['places'],'id'),true),'Recupera la pregunta de las banderas.');
    $stmt=$db->prepare("UPDATE cp_negocios SET pagina='bar/Pictograma/index.php?origen=perfil' WHERE id=?");$stmt->execute([$id]);
    foreach((new ChapitourPanel($db))->state()['businesses'] as $b)if($b['id']===(string)$id)check($b['path']==='bar/Pictograma/index.php?origen=perfil','Conserva una página configurada explícitamente.');
}finally{$db->rollBack();}
echo "PASS $checks comprobaciones: páginas e imágenes existentes, aliados recreados, metas y conservación de la configuración.\n";
