<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$root='/private/tmp/chapitour-config-qa-'.bin2hex(random_bytes(6));
mkdir($root,0700,true);
foreach (['api.php','lib/Panel.php','lib/Rewards.php','config/database.php'] as $name) {
    $target=$root.'/'.$name;
    if (!is_dir(dirname($target))) { mkdir(dirname($target),0700,true); }
    copy(__DIR__.'/../'.$name,$target);
}
$runner=$root.'/run.php';
file_put_contents($runner, <<<'CODE'
<?php
foreach (['HOST','NAME','USER','PASSWORD','SOCKET'] as $key) { putenv('CHAPITOUR_DB_'.$key); }
$_SERVER['REQUEST_METHOD']='GET';
$_SERVER['SCRIPT_NAME']='/api.php';
$_SERVER['SCRIPT_FILENAME']=__DIR__.'/api.php';
register_shutdown_function(function () { fwrite(STDERR,'HTTP_STATUS='.http_response_code()); });
require __DIR__.'/api.php';
CODE
);
function process(string $path): array {
    $pipes=[];
    $command=escapeshellarg(PHP_BINARY).' -d session.save_path='.escapeshellarg(dirname($path)).' '.escapeshellarg($path);
    $p=proc_open($command,[0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']],$pipes);
    fclose($pipes[0]); $out=stream_get_contents($pipes[1]);$err=stream_get_contents($pipes[2]);fclose($pipes[1]);fclose($pipes[2]);
    $code=proc_close($p); return [$code,$out,$err];
}
foreach ([null,"<?php return 'INVALID_RETURN';", "<?php return ['host'=>'localhost','database'=>'','username'=>'qa','password'=>'DO_NOT_OUTPUT_SECRET'];"] as $bad) {
    if ($bad!==null) { file_put_contents($root.'/config/database.local.php',$bad); }
    [$code,$out,$err]=process($runner);
    $body=json_decode($out,true);
    if ($code!==0 || ($body['code']??'')!=='DATABASE_CONFIGURATION' || strpos($err,'HTTP_STATUS=503')===false || strpos($out,'DO_NOT_OUTPUT_SECRET')!==false || strpos($out,$root)!==false) {
        throw new RuntimeException('La API no devolvio un diagnostico seguro para configuracion ausente o invalida.');
    }
}
// A complete environment must work without the ignored file. A nonexistent socket
// stops at the PDO connection boundary, without querying any database.
$envRoot=$root.'/environment';mkdir($envRoot,0700);
copy(__DIR__.'/../config/database.php',$envRoot.'/database.php');
file_put_contents($envRoot.'/run.php', <<<'CODE'
<?php
putenv('CHAPITOUR_DB_HOST=localhost');putenv('CHAPITOUR_DB_NAME=fixture');putenv('CHAPITOUR_DB_USER=fixture');putenv('CHAPITOUR_DB_PASSWORD=DO_NOT_OUTPUT_SECRET');
putenv('CHAPITOUR_DB_SOCKET='.__DIR__.'/missing.sock');
try { require __DIR__.'/database.php'; exit(2); }
catch (PDOException $e) { echo 'PDO_BOUNDARY'; }
CODE
);
[$code,$out,$err]=process($envRoot.'/run.php');
if ($code!==0 || $out!=='PDO_BOUNDARY') { throw new RuntimeException('Las variables completas no reemplazan el archivo privado ausente.'); }
// Exercise PDO failures through the API without contacting a real database.
foreach ([1044=>'DATABASE_ACCESS_DENIED',1045=>'DATABASE_LOGIN_FAILED',1049=>'DATABASE_NOT_FOUND',1146=>'DATABASE_SCHEMA_MISSING',1054=>'DATABASE_SCHEMA_MISMATCH',2002=>'DATABASE_UNREACHABLE',2003=>'DATABASE_UNREACHABLE',1062=>'DATABASE_ERROR',9999=>'DATABASE_ERROR'] as $driver=>$expected) {
    file_put_contents($root.'/config/database.php', '<?php $e=new PDOException("DO_NOT_OUTPUT_SECRET: SQL and private paths"); $e->errorInfo=["HY000",'.(int)$driver.',"DO_NOT_OUTPUT_SECRET"]; throw $e;');
    [$code,$out,$err]=process($runner);
    $body=json_decode($out,true);
    $status=$driver===1062?409:503;
    if ($code!==0 || ($body['code']??'')!==$expected || strpos($err,'HTTP_STATUS='.$status)===false
        || strpos($out.$err,'DO_NOT_OUTPUT_SECRET')!==false || strpos($out,$root)!==false) {
        throw new RuntimeException('Diagnostico PDO incorrecto o con detalles privados para '.$driver);
    }
}
echo "PASS configuracion y errores MySQL: diagnosticos explicitos sin secretos; variables de entorno sin archivo local.\n";
