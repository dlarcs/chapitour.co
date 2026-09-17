<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
$fixture=json_decode(file_get_contents('/private/tmp/chapi-test-environment.json'),true);
$base='http://127.0.0.1:8774/promos/api/index.php';
$jar=tempnam(sys_get_temp_dir(),'chapi_http_'); $csrf=''; $checks=0;
function req(?array $body=null,array $extra=[],?string $raw=null): array {
    global $base,$jar,$csrf;
    $c=curl_init($base.($body===null && $raw===null?'?action=csrf':''));
    $headers=['Content-Type: application/json','X-CSRF-Token: '.$csrf];
    foreach($extra as $name=>$value) { $headers=array_values(array_filter($headers,fn($h)=>stripos($h,$name.':')!==0)); $headers[]=$name.': '.$value; }
    curl_setopt_array($c,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_COOKIEJAR=>$jar,CURLOPT_COOKIEFILE=>$jar,CURLOPT_TIMEOUT=>15,CURLOPT_HTTPHEADER=>$headers]);
    if($body!==null || $raw!==null) { curl_setopt($c,CURLOPT_POST,true); curl_setopt($c,CURLOPT_POSTFIELDS,$raw??json_encode($body)); }
    $out=curl_exec($c); if($out===false) throw new RuntimeException(curl_error($c)); $status=curl_getinfo($c,CURLINFO_HTTP_CODE); curl_close($c);
    return ['status'=>$status,'json'=>json_decode($out,true)];
}
function verify(bool $condition,string $message):void { global $checks; if(!$condition)throw new RuntimeException('FAIL '.$message); $checks++;echo 'OK '.$message."\n"; }
try {
    $first=req(); verify($first['status']===200,'bootstrap CSRF'); $csrf=$first['json']['data']['csrf'];
    verify(req(['action'=>'iniciar'],['X-CSRF-Token'=>'incorrecto'])['status']===403,'rechazo de CSRF inválido');
    verify(req(['action'=>'iniciar'],['Origin'=>'https://example.invalid'])['status']===403,'rechazo de origen ajeno');
    verify(req(null,[],'{broken')['status']===400,'JSON malformado rechazado');
    verify(req(null,[],str_repeat('x',17000))['status']===413,'límite de tamaño');
    verify(req(['action'=>'panel'])['status']===401,'dashboard exige autenticación');
    verify(req(['action'=>'login','usuario'=>"' OR 1=1 --",'password'=>'incorrecta'])['status']===401,'inyección SQL no autentica');
    $login=req(['action'=>'login','usuario'=>$fixture['usuario'],'password'=>$fixture['password']]); verify($login['status']===200,'inicio de sesión real');
    $report=req(['action'=>'panel']); verify($report['status']===200 && count($report['json']['data']['negocios'])===6,'dashboard completo mediante Ajax');
    verify(req(['action'=>'logout'])['status']===200,'cierre de sesión');
    verify(req(['action'=>'panel'])['status']===401,'sesión cerrada pierde acceso');
    $ally=req(['action'=>'login','usuario'=>'aliado1.test','password'=>$fixture['password']]); verify($ally['status']===200,'acceso independiente de aliado');
    $scoped=req(['action'=>'panel']); verify(count($scoped['json']['data']['negocios'])===1 && (int)$scoped['json']['data']['negocios'][0]['id']===1,'alcance de negocio aplicado en servidor');
    verify(req(['action'=>'guardar_negocio','id'=>1])['status']===403,'aliado no puede editar por llamada manual');
    verify(req(['action'=>'no_existe'])['status']===404,'acciones no permitidas rechazadas');
    $state=req(['action'=>'iniciar']); verify($state['status']===200 && $state['json']['data']['amigos_requeridos']===5,'estado público real y meta de cinco');
    verify(req(['action'=>'confirmar_referido'])['status']===409,'no se confirman invitaciones inexistentes');
    foreach(['config/local.php','storage/accesos-iniciales.php','database/001_schema.sql','src/ChapiSecurity.php','bin/install.php'] as $path) {
        $c=curl_init('http://localhost/ChapiTour/promos/'.$path);curl_setopt($c,CURLOPT_RETURNTRANSFER,true);curl_exec($c);$status=curl_getinfo($c,CURLINFO_HTTP_CODE);curl_close($c);verify(in_array($status,[403,404],true),'archivo privado bloqueado: '.$path);
    }
    echo "RESULTADO HTTP: $checks comprobaciones correctas.\n";
} finally { unlink($jar); }
