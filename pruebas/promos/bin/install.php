<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true);
$root=dirname(__DIR__);
if (is_file($root.'/config/local.php')) { fwrite(STDERR,"La instalación ya tiene configuración. No se sobrescriben cuentas ni datos.\n"); exit(1); }
$options=getopt('',['base-url:']);
$url=rtrim($options['base-url']??'http://localhost/ChapiTour','/');
if (!filter_var($url,FILTER_VALIDATE_URL) || !in_array(parse_url($url,PHP_URL_SCHEME),['http','https'],true)) exit("URL inválida.\n");
$adminDsn=getenv('CHAPI_INSTALL_DSN')?:'mysql:host=127.0.0.1;charset=utf8mb4';
$pdo=new PDO($adminDsn,getenv('CHAPI_INSTALL_USER')?:'root',getenv('CHAPI_INSTALL_PASSWORD')?:'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$pdo->exec('CREATE DATABASE IF NOT EXISTS chapitour_promos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$pdo->exec('USE chapitour_promos');
$tables=$pdo->query('SHOW TABLES')->fetchAll();
if ($tables) exit("La base chapitour_promos ya contiene tablas. Importa las migraciones manualmente después de revisarlas.\n");
foreach (['001_schema.sql','002_negocios.sql'] as $file) {
    foreach (explode(';',file_get_contents($root.'/database/'.$file)) as $sql) if (trim($sql)!=='') $pdo->exec($sql);
}
$password=bin2hex(random_bytes(24));
$pdo->exec("CREATE USER 'chapitour_app'@'localhost' IDENTIFIED BY ".$pdo->quote($password));
$pdo->exec("GRANT SELECT,INSERT,UPDATE,DELETE ON chapitour_promos.* TO 'chapitour_app'@'localhost'");
$config=['dsn'=>'mysql:host=127.0.0.1;dbname=chapitour_promos;charset=utf8mb4','db_user'=>'chapitour_app','db_password'=>$password,'app_key'=>bin2hex(random_bytes(32)),'base_url'=>$url,'secure_cookies'=>parse_url($url,PHP_URL_SCHEME)==='https'];
$guard="<?php\nif (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }\nreturn ";
file_put_contents($root.'/config/local.php',$guard.var_export($config,true).";\n",LOCK_EX);
$accounts=[['usuario'=>'admin','rol'=>'admin','negocio_id'=>null,'negocio'=>'Administración Chapitour']];
foreach ($pdo->query('SELECT id,slug,nombre FROM cp_negocios ORDER BY id') as $row) $accounts[]=['usuario'=>$row['slug'],'rol'=>'aliado','negocio_id'=>$row['id'],'negocio'=>$row['nombre']];
$credentials=[];
$q=$pdo->prepare('INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol) VALUES (?,?,?,?)');
foreach ($accounts as $account) {
    $temporary=bin2hex(random_bytes(10));
    $q->execute([$account['negocio_id'],$account['usuario'],password_hash($temporary,PASSWORD_DEFAULT),$account['rol']]);
    $credentials[]=['negocio'=>$account['negocio'],'usuario'=>$account['usuario'],'password_temporal'=>$temporary];
}
$credentialFile=$root.'/storage/accesos-iniciales.php';
file_put_contents($credentialFile,"<?php\n// Archivo privado. No subir al repositorio ni compartir públicamente.\nif (PHP_SAPI !== 'cli') { http_response_code(404); exit; }\nreturn ".var_export($credentials,true).";\n",LOCK_EX);
chmod($credentialFile,0600);
echo "Base chapitour_promos instalada. 6 aliados y 1 administrador.\nAccesos privados: promos/storage/accesos-iniciales.php\nPanel: ".$url."/promos/panel/\nLas promociones requieren configurar beneficio y condiciones antes de activarse.\n";
