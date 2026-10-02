<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if(PHP_SAPI!=='cli'||!preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket))throw new RuntimeException('Solo QA temporal.');
require __DIR__.'/../../../premia/lib/Panel.php';
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$db->exec("SET time_zone='+00:00'");
$rewards=new ChapitourRewards($db);
if(!$rewards->ready()||!$rewards->offers())throw new RuntimeException('Prepara primero las ofertas QA.');
$email='production-'.bin2hex(random_bytes(6)).'@example.invalid';
$db->beginTransaction();
try {
    $stmt=$db->prepare('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)');
    $stmt->execute(['Migración QA',$email,password_hash('Qa-production-456!',PASSWORD_BCRYPT)]);
    $id=(int)$db->lastInsertId();$rewards->welcome($id);$db->commit();
}catch(Throwable $e){$db->rollBack();throw $e;}
file_put_contents(dirname($socket).'/production-ui.json',json_encode(['id'=>$id,'email'=>$email],JSON_THROW_ON_ERROR));
echo "Fixture temporal con giro de bienvenida preparada.\n";
