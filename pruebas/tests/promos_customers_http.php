<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
$base='http://127.0.0.1:8785/pruebas/promos/api/index.php';
$jar=tempnam(sys_get_temp_dir(),'chapi_customer_http_'); $csrf=''; $checks=0;
function req(?array $body=null,array $extra=[]): array {
    global $base,$jar,$csrf;
    $c=curl_init($base.($body===null?'?action=csrf':''));
    $headers=['Content-Type: application/json','X-CSRF-Token: '.$csrf];
    foreach($extra as $name=>$value) { $headers=array_values(array_filter($headers,fn($h)=>stripos($h,$name.':')!==0)); $headers[]=$name.': '.$value; }
    curl_setopt_array($c,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_COOKIEJAR=>$jar,CURLOPT_COOKIEFILE=>$jar,CURLOPT_TIMEOUT=>15,CURLOPT_HTTPHEADER=>$headers]);
    if($body!==null) { curl_setopt($c,CURLOPT_POST,true); curl_setopt($c,CURLOPT_POSTFIELDS,json_encode($body)); }
    $out=curl_exec($c); if($out===false) throw new RuntimeException(curl_error($c)); $status=curl_getinfo($c,CURLINFO_HTTP_CODE); curl_close($c);
    return ['status'=>$status,'json'=>json_decode($out,true)];
}
function verify(bool $condition,string $message):void { global $checks; if(!$condition)throw new RuntimeException('FAIL '.$message); $checks++;echo 'OK '.$message."\n"; }
function callData(array $body):array { $r=req($body); if($r['status']!==200)throw new RuntimeException(json_encode($r)); return $r['json']['data']; }
try {
    $first=req(); verify($first['status']===200,'bootstrap CSRF'); $csrf=$first['json']['data']['csrf'];
    verify(req(['action'=>'cliente_registro'],['X-CSRF-Token'=>'incorrecto'])['status']===403,'registro exige CSRF válido');
    verify(req(['action'=>'cliente_panel'],['Origin'=>'https://example.invalid'])['status']===403,'historial rechaza origen ajeno');
    $initial=callData(['action'=>'iniciar']); verify($initial['amigos_requeridos']===8,'meta pública de ocho amigos');
    callData(['action'=>'probar','solicitud_id'=>bin2hex(random_bytes(18))]);
    $spin=callData(['action'=>'girar','solicitud_id'=>bin2hex(random_bytes(18))]); $prize=$spin['premio'];
    $panel=callData(['action'=>'cliente_panel']); verify($panel['total']===1 && $panel['premios'][0]['codigo']===$prize['codigo'],'cliente anónimo ve el código de la ruleta');
    verify(req(['action'=>'redimir','codigo'=>$prize['codigo'],'confirmado'=>true])['status']===401,'cliente no redime su propio código');
    verify(req(['action'=>'evento','tipo'=>'reclamar_whatsapp','premio_id'=>1])['status']===404,'cliente no accede al premio de otro visitante');
    $email='http.'.bin2hex(random_bytes(5)).'@example.test'; $password='ChapiTest-only-4829!';
    callData(['action'=>'cliente_registro','nombre'=>'Cliente HTTP','email'=>$email,'password'=>$password]);
    $registered=callData(['action'=>'cliente_panel']); verify($registered['total']===1 && $registered['cliente']['email']===$email,'registro conserva código y crea sesión');
    $cookies=file_get_contents($jar); verify(str_contains($cookies,'chapi_pruebas_session') && str_contains($cookies,'chapi_pruebas_visitante'),'cookies independientes para pruebas');
    callData(['action'=>'cliente_salir']); $loggedOut=callData(['action'=>'cliente_panel']);
    verify($loggedOut['cliente']===null && $loggedOut['total']===0,'cerrar sesión oculta historial vinculado');
    verify(req(['action'=>'cliente_login','email'=>$email,'password'=>'Incorrecto-12345'])['status']===401,'contraseña incorrecta no recupera historial');
    callData(['action'=>'probar','solicitud_id'=>bin2hex(random_bytes(18))]);
    $second=callData(['action'=>'girar','solicitud_id'=>bin2hex(random_bytes(18))])['premio'];
    callData(['action'=>'cliente_login','email'=>$email,'password'=>$password]);
    $joined=callData(['action'=>'cliente_panel']); verify($joined['total']===2,'iniciar sesión une historial nuevo con el anterior');
    $forged=callData(['action'=>'cliente_panel','visitante_id'=>1,'cliente_id'=>1,'_visitantes'=>[1]]); verify($forged['total']===2,'identificadores enviados no cambian el alcance');
    callData(['action'=>'login','usuario'=>'admin.test','password'=>$password]);
    $adminPrize=callData(['action'=>'consultar_codigo','codigo'=>$prize['codigo']])['premio'];
    verify($adminPrize['mensaje_whatsapp']===$prize['mensaje_whatsapp'],'administrador y cliente comparten comprobante');
    verify(req(['action'=>'redimir','codigo'=>$prize['codigo'],'confirmado'=>true])['status']===403,'administrador no suplanta redención del negocio');
    callData(['action'=>'logout']);
    callData(['action'=>'login','usuario'=>'aliado'.$prize['negocio_id'].'.test','password'=>$password]);
    verify(req(['action'=>'redimir','codigo'=>$prize['codigo'],'confirmado'=>false])['status']===400,'negocio debe confirmar redención');
    callData(['action'=>'redimir','codigo'=>$prize['codigo'],'confirmado'=>true]);
    $redeemed=callData(['action'=>'cliente_panel','estado'=>'redimido']);
    verify($redeemed['total']===1 && $redeemed['premios'][0]['codigo']===$prize['codigo'] && count($redeemed['premios'][0]['seguimiento'])===2,'cliente recibe mismo código redimido con seguimiento');
    verify(req(['action'=>'redimir','codigo'=>$prize['codigo'],'confirmado'=>true])['status']===409,'doble redención rechazada por HTTP');
    callData(['action'=>'logout']);
    verify(callData(['action'=>'cliente_panel'])['cliente']['email']===$email,'cerrar panel no cierra cuenta del cliente');
    foreach(['config/local.php','src/ChapiCustomerModel.php','database/006_clientes_seguimiento.sql'] as $path) {
        $c=curl_init('http://127.0.0.1:8785/pruebas/promos/'.$path);curl_setopt($c,CURLOPT_RETURNTRANSFER,true);curl_exec($c);$status=curl_getinfo($c,CURLINFO_HTTP_CODE);curl_close($c);verify(in_array($status,[403,404],true),'archivo privado bloqueado: '.$path);
    }
    echo "RESULTADO HTTP CLIENTES: $checks comprobaciones correctas.\n";
} finally { unlink($jar); }
