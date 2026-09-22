<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli') { http_response_code(404); exit; }
define('CHAPI_PROMOS',true);require dirname(__DIR__).'/src/bootstrap.php';
$options=getopt('',['usuario:','negocio:','admin','reset']);
$name=$options['usuario']??'';
if(!preg_match('/^[a-zA-Z0-9._-]{3,100}$/D',$name)) exit("Usa --usuario=nombre --negocio=ID o --admin. Para recuperar acceso: --usuario=nombre --reset.\n");
$db=chapi_db();$password=bin2hex(random_bytes(10));$hash=password_hash($password,PASSWORD_DEFAULT);
if(isset($options['reset'])) {
    $q=$db->prepare('UPDATE cp_usuarios SET password_hash=?,cambiar_password=1,version_sesion=version_sesion+1 WHERE usuario=? AND activo=1');$q->execute([$hash,$name]);
    if(!$q->rowCount())exit("Usuario activo no encontrado.\n");
} else {
    $admin=isset($options['admin']);$business=$admin?null:filter_var($options['negocio']??null,FILTER_VALIDATE_INT);
    if(!$admin && (!$business || $business<1))exit("Indica --negocio=ID para un aliado.\n");
    $q=$db->prepare('INSERT INTO cp_usuarios(negocio_id,usuario,password_hash,rol) VALUES (?,?,?,?)');$q->execute([$business,$name,$hash,$admin?'admin':'aliado']);
}
$q=$db->prepare('INSERT INTO cp_auditoria(accion,datos) VALUES (?,?)');$q->execute([isset($options['reset'])?'password_reset_cli':'usuario_creado_cli',json_encode(['usuario'=>$name])]);
$file=dirname(__DIR__).'/storage/acceso-'.$name.'.php';
file_put_contents($file,"<?php\nif(PHP_SAPI!=='cli'){http_response_code(404);exit;}\nreturn ".var_export(['usuario'=>$name,'password_temporal'=>$password],true).";\n",LOCK_EX);chmod($file,0600);
echo 'Credencial temporal guardada en '.$file.". Debe cambiarse al entrar.\n";
