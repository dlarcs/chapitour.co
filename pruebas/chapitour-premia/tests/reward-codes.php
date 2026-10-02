<?php
declare(strict_types=1);
$socket=$argv[1]??'';
if (PHP_SAPI!=='cli' || !preg_match('#^/private/tmp/chapitour-panel-qa\.[A-Za-z0-9]+/mysql\.sock$#',$socket)) { throw new RuntimeException('Solo QA temporal.'); }
require __DIR__.'/../lib/Panel.php';

// Only this CLI fixture intercepts statements; production uses an ordinary PDO connection.
final class CodeProbe {
    public $occupied=''; public $collisions=0; public $attempts=0;
    public $pretendFullThrough=0; public $alwaysCollide=false; public $foreignKeyError=false;
    public $ranges=[];
}
final class CodeStatement extends PDOStatement {
    private $probe; private $params=[];
    protected function __construct(CodeProbe $probe) { $this->probe=$probe; }
    public function execute(?array $params=null): bool {
        $this->params=$params??[];
        if (strpos($this->queryString,'INSERT INTO cp_premios(')===0) {
            $this->probe->attempts++;
            $number=(int)substr($params[5],strrpos($params[5],'-')+1);
            if ($this->probe->foreignKeyError) { $params[4]=0; }
            elseif ($this->probe->alwaysCollide || $this->probe->collisions>0 || $number<=$this->probe->pretendFullThrough) {
                $this->probe->collisions=max(0,$this->probe->collisions-1);
                $params[5]=$this->probe->occupied;
            }
        }
        return parent::execute($params);
    }
    public function fetchColumn(int $column=0): mixed {
        if (strpos($this->queryString,'SELECT ? AS numero')===0) {
            $this->probe->ranges[]=[$this->params[0],$this->params[4]];
            // Simulate a full million-code pool without allocating a million prize fixtures.
            if ($this->probe->pretendFullThrough>0 && $this->params[4]<=$this->probe->pretendFullThrough) { return false; }
        }
        return parent::fetchColumn($column);
    }
}
$probe=new CodeProbe();
$db=new PDO('mysql:unix_socket='.$socket.';dbname=chapitour_panels_qa;charset=utf8mb4','root','',[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false,
    PDO::ATTR_STATEMENT_CLASS=>[CodeStatement::class,[$probe]]]);
$db->exec("SET time_zone='+00:00'");
$db->exec('SET timestamp='.strtotime('2026-10-15 12:00:00 UTC'));
$checks=0;
function query(string $sql,array $args=[]):PDOStatement { global $db;$s=$db->prepare($sql);$s->execute($args);return $s; }
function check(bool $condition,string $label):void {global $checks;if(!$condition)throw new RuntimeException($label);$checks++;}
function ticket():array {
    global $db,$rewards;
    query('INSERT INTO cp_clientes(nombre,email,password_hash) VALUES (?,?,?)',['Códigos QA','codes-'.bin2hex(random_bytes(12)).'@example.invalid',password_hash(bin2hex(random_bytes(16)),PASSWORD_BCRYPT)]);
    $id=(int)$db->lastInsertId();$db->beginTransaction();$rewards->welcome($id);$db->commit();
    return [['db_id'=>$id,'role'=>'client','version'=>1],(string)query('SELECT id FROM cp_panel_giros WHERE cliente_id=?',[$id])->fetchColumn()];
}
function snapshot():array {
    return array_map('intval',[
        query('SELECT COUNT(*) FROM cp_premios')->fetchColumn(),query('SELECT COUNT(*) FROM cp_oportunidades')->fetchColumn(),
        query('SELECT SUM(entregados) FROM cp_promociones')->fetchColumn(),query("SELECT COUNT(*) FROM cp_auditoria WHERE accion='premio_generado'")->fetchColumn()]);
}
$rewards=new ChapitourRewards($db);
check($rewards->ready(),'Preparar las tablas auxiliares de QA primero');
$admin=query("SELECT id FROM cp_usuarios WHERE rol='admin' LIMIT 1")->fetchColumn();
query("INSERT INTO cp_negocios(slug,nombre,categoria) VALUES (?,'Negocio códigos QA','QA')",['codes-'.bin2hex(random_bytes(8))]);
$business=$db->lastInsertId();
query("INSERT INTO cp_promociones(negocio_id,titulo,descripcion,condiciones,activa) VALUES (?,'Prueba de códigos','Sin valor comercial','Solo QA',1)",[$business]);
$promotion=$db->lastInsertId();
query("INSERT INTO cp_panel_promociones(promocion_id,publicacion,beneficio,incluidos,horarios,restricciones,whatsapp_confirmado,aprobada_por,aprobada_at) VALUES (?,'approved','Solo QA','QA','QA','QA','10000000',?,UTC_TIMESTAMP())",[$promotion,$admin]);

[$client,$id]=ticket();$occupied=$rewards->spin($client,$id);$probe->occupied=$occupied;
check((bool)preg_match('/^CHAPI-OCT-[1-9][0-9]{2,5}$/',$occupied),'Código inicial de 3 a 6 números');
query('UPDATE cp_premios SET vence_at=DATE_SUB(UTC_TIMESTAMP(),INTERVAL 1 HOUR) WHERE codigo=?',[$occupied]);
[$client,$id]=ticket();$before=snapshot();$probe->collisions=2;$probe->attempts=0;
$code=$rewards->spin($client,$id);
check($probe->attempts===3 && $code!==$occupied,'Reintenta colisiones incluso con premios vencidos');
check((bool)preg_match('/^CHAPI-OCT-[1-9][0-9]{2,5}$/',$code),'Mantiene 3 a 6 cifras tras colisiones');
check(snapshot()===array_map(static function($n){return $n+1;},$before),'Un premio, oportunidad, cupo y auditoría');
check($rewards->spin($client,$id)===$code,'Reintentar el giro devuelve el mismo código');
check((int)query('SELECT TIMESTAMPDIFF(SECOND,creado_at,vence_at) FROM cp_premios WHERE codigo=?',[$code])->fetchColumn()===259200,'Conserva 72 horas');

// Exercise the real gap query over a small complete range, including its edges.
$gap=new ReflectionMethod(ChapitourRewards::class,'availableCode');$gap->setAccessible(true);
$base=900000;
while ((int)query("SELECT COUNT(*) FROM cp_premios WHERE codigo IN (?,?,?,?)",array_map(static function($n){return 'CHAPI-OCT-'.$n;},range($base,$base+3)))->fetchColumn()>0) { $base+=4; }
check($gap->invoke($rewards,'CHAPI-OCT-',$base,$base+2)==='CHAPI-OCT-'.$base,'Encuentra el comienzo libre');
foreach ([$base,$base+2] as $number) {
    [$c,$t]=ticket();$generated=$rewards->spin($c,$t);query('UPDATE cp_premios SET codigo=? WHERE codigo=?',['CHAPI-OCT-'.$number,$generated]);
}
check($gap->invoke($rewards,'CHAPI-OCT-',$base,$base+2)==='CHAPI-OCT-'.($base+1),'Encuentra un hueco interior');
[$c,$t]=ticket();$generated=$rewards->spin($c,$t);query('UPDATE cp_premios SET codigo=? WHERE codigo=?',['CHAPI-OCT-'.($base+1),$generated]);
check($gap->invoke($rewards,'CHAPI-OCT-',$base,$base+2)===null,'Detecta un rango realmente agotado');
check($gap->invoke($rewards,'CHAPI-OCT-',$base,$base+3)==='CHAPI-OCT-'.($base+3),'Encuentra el último número libre');

[$c,$t]=ticket();$probe->collisions=30;$probe->ranges=[];
$code=$rewards->spin($c,$t);
check((bool)preg_match('/^CHAPI-OCT-[1-9][0-9]{2,5}$/',$code) && count($probe->ranges)===1,'Treinta colisiones buscan un hueco sin ampliar cifras');
foreach ([999999=>7,9999999=>8] as $fullThrough=>$digits) {
    [$c,$t]=ticket();$probe->pretendFullThrough=$fullThrough;$probe->ranges=[];
    $code=$rewards->spin($c,$t);
    check((bool)preg_match('/^CHAPI-OCT-[1-9][0-9]{'.($digits-1).'}$/',$code),'Amplía a '.$digits.' cifras solo tras agotar los rangos anteriores');
    check($probe->ranges[0]===[100,999999] && count($probe->ranges)===$digits-6,'Comprueba cada rango antes de ampliarlo');
    $probe->pretendFullThrough=0;
}
[$c,$t]=ticket();$before=snapshot();$probe->alwaysCollide=true;
try { $rewards->spin($c,$t);throw new RuntimeException('Debió conservar el giro ante colisiones continuas'); }
catch (PanelError $e) { check($e->getCode()===503,'Colisiones continuas devuelven error controlado'); }
$probe->alwaysCollide=false;
check(snapshot()===$before && query('SELECT premio_id FROM cp_panel_giros WHERE id=?',[$t])->fetchColumn()===null,'Rollback conserva giro, cupos e historial');
$probe->foreignKeyError=true;
try { $rewards->spin($c,$t);throw new RuntimeException('Debió propagar el error de clave foránea'); }
catch (PDOException $e) { check((int)($e->errorInfo[1]??0)===1452,'No oculta otros errores de integridad'); }
$probe->foreignKeyError=false;
check(snapshot()===$before,'Otros errores también revierten la emisión');
check((bool)preg_match('/^CHAPI-OCT-[1-9][0-9]{2,5}$/',$rewards->spin($c,$t)),'El giro conservado puede reintentarse');

foreach (['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'] as $index=>$month) {
    $utc=sprintf('2027-%02d-15 12:00:00',$index+1);$db->exec('SET timestamp='.strtotime($utc.' UTC'));
    [$c,$t]=ticket();$monthlyCode=$rewards->spin($c,$t);
    check((bool)preg_match('/^CHAPI-'.$month.'-[1-9][0-9]{2,5}$/',$monthlyCode),'Mes en español: '.$month);
    check(query('SELECT creado_at FROM cp_premios WHERE codigo=?',[$monthlyCode])->fetchColumn()===$utc,'El mes corresponde a la fecha guardada de emisión');
}
foreach (['2027-01-01 04:59:59'=>'DIC','2027-01-01 05:00:00'=>'ENE','2027-10-01 04:59:59'=>'SEP','2027-10-01 05:00:00'=>'OCT'] as $utc=>$month) {
    $db->exec('SET timestamp='.strtotime($utc.' UTC'));[$c,$t]=ticket();$monthlyCode=$rewards->spin($c,$t);
    check(strpos($monthlyCode,'CHAPI-'.$month.'-')===0,'Frontera mensual según Bogotá: '.$utc);
    check((int)query('SELECT TIMESTAMPDIFF(SECOND,creado_at,vence_at) FROM cp_premios WHERE codigo=?',[$monthlyCode])->fetchColumn()===259200,'72 horas incluso al cambiar de mes o año');
}
$db->exec('SET timestamp='.strtotime('2027-11-15 12:00:00 UTC'));
check($rewards->spin($c,$t)===$monthlyCode,'Un reintento en otro mes conserva el código original');
[$c,$t]=ticket();$legacyIssued=$rewards->spin($c,$t);
$legacy='CHAPI-'.strtoupper(bin2hex(random_bytes(10)));
query('UPDATE cp_premios SET codigo=? WHERE codigo=?',[$legacy,$legacyIssued]);
check($rewards->spin($c,$t)===$legacy,'Los códigos del formato anterior no cambian');
$db->exec('SET timestamp=0');
echo "PASS $checks comprobaciones: meses en español y hora de Bogotá, formato, unicidad, colisiones, huecos, crecimiento, idempotencia, vigencia y rollback.\n";
