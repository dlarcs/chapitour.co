<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
require_once __DIR__.'/Community.php';

/** Server-owned visits, eligibility and award issuance. No browser counters or clock. */
final class ChapitourRewards
{
    private $db;
    private $ready;
    public function __construct(PDO $db) { $this->db=$db; }
    private function query(string $sql, array $args=[]): PDOStatement {
        $s=$this->db->prepare($sql); $s->execute($args); return $s;
    }
    public function ready(): bool {
        if ($this->ready===null) {
            $n=$this->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name IN ('cp_panel_campana','cp_panel_visitas','cp_panel_giros')")->fetchColumn();
            $this->ready=(int)$n===3 && (bool)$this->query('SELECT id FROM cp_panel_campana WHERE id=1')->fetchColumn();
        }
        return $this->ready;
    }
    public function install(): void {
        if ($this->ready()) { return; }
        if ((int)$this->query("SELECT GET_LOCK('chapitour_rewards_schema',10)")->fetchColumn()!==1) {
            throw new PanelError('La ruleta se está actualizando. Intenta de nuevo.',503);
        }
        try {
            $sql=file_get_contents(__DIR__.'/../database/ruleta_cp.sql');
            foreach (explode(';',preg_replace('/^\s*--.*$/m','',$sql)) as $statement) {
                if (trim($statement)!=='') { $this->db->exec($statement); }
            }
            $this->db->beginTransaction();
            if (!$this->query('SELECT id FROM cp_panel_campana WHERE id=1')->fetchColumn()) {
                // The legacy engine stays disabled for this campaign; only this account-based rule issues awards.
                $this->query("INSERT INTO cp_campanas(nombre,activa) VALUES ('Chapitour: ocho visitas válidas',0)");
                $this->query('INSERT INTO cp_panel_campana(id,campana_id,reinicio_mensual) VALUES (1,?,1)',[$this->db->lastInsertId()]);
            }
            $this->db->commit(); $this->ready=true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) { $this->db->rollBack(); } throw $e;
        } finally { $this->query("SELECT RELEASE_LOCK('chapitour_rewards_schema')"); }
    }
    private function policy(bool $lock=false): ?array {
        if (!$this->ready()) { return null; }
        return $this->query('SELECT * FROM cp_panel_campana WHERE id=1'.($lock?' LOCK IN SHARE MODE':''))->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function offers(bool $lock=false): array {
        return $this->query("SELECT p.*,n.nombre,n.direccion,m.whatsapp_confirmado FROM cp_promociones p
            JOIN cp_negocios n ON n.id=p.negocio_id JOIN cp_panel_promociones m ON m.promocion_id=p.id
            WHERE n.activo=1 AND p.activa=1 AND m.publicacion='approved' AND m.aprobada_por IS NOT NULL AND m.aprobada_at IS NOT NULL
            AND TRIM(m.incluidos)<>'' AND TRIM(m.horarios)<>'' AND TRIM(m.restricciones)<>''
            AND m.whatsapp_confirmado REGEXP '^[1-9][0-9]{7,14}$'
            AND (p.cupo_total IS NULL OR p.entregados<p.cupo_total) ORDER BY p.id".($lock?' FOR UPDATE':''))->fetchAll(PDO::FETCH_ASSOC);
    }
    private function clientTransaction(array $a, callable $fn) {
        $this->db->beginTransaction();
        try {
            $r=$this->query('SELECT activo,version_sesion FROM cp_clientes WHERE id=? FOR UPDATE',[$a['db_id']])->fetch(PDO::FETCH_ASSOC);
            if (!$r || !(int)$r['activo'] || (int)$r['version_sesion']!==$a['version']) { throw new PanelError('Vuelve a iniciar sesión.',403); }
            $result=$fn(); $this->db->commit(); return $result;
        } catch (Throwable $e) { if ($this->db->inTransaction()) { $this->db->rollBack(); } throw $e; }
    }
    public function welcome(int $clientId): void {
        // Called inside registration's transaction: an account and its welcome ticket are created together.
        if (!$this->db->inTransaction()) { throw new LogicException('La bienvenida requiere una transacción.'); }
        if (!$this->ready()) { throw new PanelError('Un administrador debe terminar de preparar la ruleta.',503); }
        $now=$this->query('SELECT UTC_TIMESTAMP()')->fetchColumn();
        $month=(new DateTimeImmutable($now,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Bogota'))->format('Y-m');
        // Registration is the starting point, not the first of the next eight return visits.
        $this->query('INSERT INTO cp_panel_visitas(cliente_id,ultima_visita_at,mes) VALUES (?,?,?) ON DUPLICATE KEY UPDATE cliente_id=VALUES(cliente_id)',[$clientId,$now,$month]);
        $this->query('INSERT INTO cp_panel_giros(cliente_id,ciclo,creado_at) VALUES (?,0,?) ON DUPLICATE KEY UPDATE cliente_id=VALUES(cliente_id)',[$clientId,$now]);
    }
    public function visit(array $a): void {
        if (!$this->ready()) { return; }
        $this->clientTransaction($a,function () use ($a) {
            $p=$this->policy(true);
            if ($p['reinicio_mensual']===null) { return; }
            $now=$this->query('SELECT UTC_TIMESTAMP()')->fetchColumn();
            $month=(new DateTimeImmutable($now,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Bogota'))->format('Y-m');
            $this->query('INSERT IGNORE INTO cp_panel_visitas(cliente_id,mes) VALUES (?,?)',[$a['db_id'],$month]);
            $v=$this->query('SELECT * FROM cp_panel_visitas WHERE cliente_id=? FOR UPDATE',[$a['db_id']])->fetch(PDO::FETCH_ASSOC);
            if ($p['reinicio_mensual'] && $v['mes']!==$month) {
                $this->query('UPDATE cp_panel_visitas SET visitas_ciclo=0,mes=? WHERE cliente_id=?',[$month,$a['db_id']]);
                $v['visitas_ciclo']=0;
            }
            // UTC elapsed time: midnight, refreshes, devices and parallel requests cannot bypass 4 hours.
            if ($v['ultima_visita_at']!==null && strtotime($now.' UTC')-strtotime($v['ultima_visita_at'].' UTC')<14400) { return; }
            $count=(int)$v['visitas_ciclo']+1;
            $cycle=(int)$v['ciclos'];
            if ($count===8) {
                $cycle++; $count=0;
                $this->query('INSERT INTO cp_panel_giros(cliente_id,ciclo,creado_at) VALUES (?,?,?)',[$a['db_id'],$cycle,$now]);
            }
            $this->query('UPDATE cp_panel_visitas SET ultima_visita_at=?,mes=?,visitas_ciclo=?,ciclos=? WHERE cliente_id=?',[$now,$month,$count,$cycle,$a['db_id']]);
            (new ChapitourCommunity($this->db))->recordValidVisit($a['db_id'],$month);
        });
    }
    public function status(?array $a): array {
        $p=$this->policy(); $configured=$p && $p['reinicio_mensual']!==null;
        $offers=$this->ready()?$this->offers():[];
        $ticket=null;
        if ($configured && $a && $a['role']==='client') {
            $ticket=$this->query('SELECT id,ciclo FROM cp_panel_giros WHERE cliente_id=? AND premio_id IS NULL ORDER BY id LIMIT 1',[$a['db_id']])->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        $guestAvailable=false;
        if (!$a) {
            $visitor=$this->guestVisitorId();
            $guestAvailable=$this->guestIdentity()!==null && (!$visitor || (!$this->guestOwner($visitor) && !$this->guestAward($visitor)));
            if ($configured && $guestAvailable) { $ticket=['id'=>'guest-welcome','ciclo'=>0]; }
        }
        $reason=!$configured?'configuration_pending':($a && $a['role']!=='client'?'client_required':(!$a && !$guestAvailable?'login_required':(!$offers?'promotions_pending':(!$ticket?'visits_pending':'ready'))));
        return ['enabled'=>(bool)($configured && $offers),'setup_required'=>!$this->ready(),
            'eligible_promotions'=>count($offers),'wheel_business_ids'=>array_map(static function($o){return (string)$o['negocio_id'];},$offers),
            'can_spin'=>$reason==='ready','reason'=>$reason,'ticket_id'=>$ticket?(string)$ticket['id']:null,
            'ticket_kind'=>$ticket?((int)$ticket['ciclo']===0?'welcome':'visits'):null];
    }
    private function availableCode(string $prefix,int $minimum,int $maximum): ?string {
        // Find a gap, including the beginning of the range. Random collisions alone do not mean exhaustion.
        $number=$this->query("SELECT ? AS numero WHERE NOT EXISTS (SELECT 1 FROM cp_premios WHERE codigo=?)
            UNION ALL
            SELECT CAST(SUBSTRING_INDEX(p.codigo,'-',-1) AS UNSIGNED)+1 AS numero FROM cp_premios p
            WHERE p.codigo REGEXP ?
            AND CAST(SUBSTRING_INDEX(p.codigo,'-',-1) AS UNSIGNED)>=? AND CAST(SUBSTRING_INDEX(p.codigo,'-',-1) AS UNSIGNED)<?
            AND NOT EXISTS (SELECT 1 FROM cp_premios usado WHERE usado.codigo=CONCAT(?,CAST(SUBSTRING_INDEX(p.codigo,'-',-1) AS UNSIGNED)+1))
            LIMIT 1",[$minimum,$prefix.$minimum,'^'.$prefix.'[1-9][0-9]{2,17}$',$minimum,$maximum,$prefix])->fetchColumn();
        return $number===false?null:$prefix.$number;
    }
    public function spin(array $a, string $ticket): string {
        if (!$this->ready()) { throw new PanelError('La ruleta todavía no está configurada.',503); }
        if (!ctype_digit($ticket) || (int)$ticket<1) { throw new PanelError('Actualiza la ruleta e intenta de nuevo.',422); }
        return $this->clientTransaction($a,function () use ($a,$ticket) {
            $p=$this->policy(true);
            if ($p['reinicio_mensual']===null) { throw new PanelError('Falta definir la regla mensual de visitas.',422); }
            $g=$this->query('SELECT * FROM cp_panel_giros WHERE id=? AND cliente_id=? FOR UPDATE',[$ticket,$a['db_id']])->fetch(PDO::FETCH_ASSOC);
            if (!$g) { throw new PanelError('Todavía no tienes este giro disponible.',403); }
            // A lost response or double click returns the same award, even if offers subsequently change.
            if ($g['premio_id']!==null) { return (string)$this->query('SELECT codigo FROM cp_premios WHERE id=?',[$g['premio_id']])->fetchColumn(); }
            $offers=$this->offers(true);
            if (!$offers) { throw new PanelError('Aún no hay promociones aprobadas disponibles. Tu giro se conserva.',422); }
            $offer=$offers[random_int(0,count($offers)-1)];
            $visitor=$this->query('SELECT visitante_id FROM cp_cliente_visitantes WHERE cliente_id=? ORDER BY visitante_id LIMIT 1',[$a['db_id']])->fetchColumn();
            if (!$visitor) {
                $this->query('INSERT INTO cp_visitantes(identidad_hash,ip_hash,referido_token) VALUES (?,?,?)',[hash('sha256',random_bytes(32)),hash('sha256',random_bytes(32)),bin2hex(random_bytes(16))]);
                $visitor=$this->db->lastInsertId();
                $this->query('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$a['db_id'],$visitor]);
            }
            $origin=(int)$g['ciclo']===0?'panel_bienvenida':'panel_8_visitas';
            $this->query('INSERT INTO cp_oportunidades(visitante_id,campana_id,origen,origen_clave,mostrada_at,consumida_at) VALUES (?,?,?,?,UTC_TIMESTAMP(),UTC_TIMESTAMP())',[$visitor,$p['campana_id'],$origin,'cuenta:'.$a['db_id'].':ciclo:'.$g['ciclo']]);
            $opportunity=$this->db->lastInsertId();
            $issued=$this->issue((int)$visitor,(int)$p['campana_id'],(int)$opportunity,$offer,'panel-giro-'.$g['id'],['origen'=>'paneles','cliente_id'=>$a['db_id'],'giro_id'=>$g['id']]);
            $this->query('UPDATE cp_panel_giros SET premio_id=? WHERE id=?',[$issued['id'],$g['id']]);
            $code=$issued['code'];
            return $code;
        });
    }

    private function guestIdentity(): ?string {
        $token=$_COOKIE['CHAPITOUR_WELCOME']??'';
        return is_string($token) && preg_match('/^[a-f0-9]{64}$/D',$token) ? hash('sha256','panel-welcome-v1:'.$token) : null;
    }
    public function guestVisitorId(): ?int {
        $identity=$this->guestIdentity();
        if ($identity===null) { return null; }
        $id=$this->query('SELECT id FROM cp_visitantes WHERE identidad_hash=?',[$identity])->fetchColumn();
        return $id===false?null:(int)$id;
    }
    private function guestVisitorLock(): int {
        if (!$this->db->inTransaction()) { throw new LogicException('El visitante requiere una transacción.'); }
        $identity=$this->guestIdentity();
        if ($identity===null) { throw new PanelError('Actualiza la página para preparar tu bienvenida.',422); }
        // Take an exclusive duplicate-row lock immediately; INSERT IGNORE can deadlock
        // when simultaneous retries later upgrade their shared locks to FOR UPDATE.
        $this->query('INSERT INTO cp_visitantes(identidad_hash,ip_hash,referido_token) VALUES (?,?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)',[$identity,hash('sha256',random_bytes(32)),bin2hex(random_bytes(16))]);
        $visitor=(int)$this->db->lastInsertId();
        // Read by the already locked primary key, without upgrading a secondary-index gap lock.
        $stored=$this->query('SELECT identidad_hash FROM cp_visitantes WHERE id=? FOR UPDATE',[$visitor])->fetchColumn();
        if ($stored!==$identity) { throw new PanelError('No pudimos preparar tu bienvenida. Intenta de nuevo.',503); }
        return $visitor;
    }
    private function guestOwner(int $visitor) {
        return $this->query('SELECT cliente_id FROM cp_cliente_visitantes WHERE visitante_id=?'.($this->db->inTransaction()?' FOR UPDATE':''),[$visitor])->fetchColumn();
    }
    private function guestAward(int $visitor): ?array {
        return $this->query("SELECT id,codigo FROM cp_premios WHERE visitante_id=? AND solicitud_id='guest-welcome'".($this->db->inTransaction()?' FOR UPDATE':''),[$visitor])->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function attachGuest(int $clientId): void {
        // Authentication owns the transaction and the client lock. Never transfer another account's visitor.
        if ($this->guestIdentity()===null) { return; }
        $visitor=$this->guestVisitorLock();
        $owner=$this->guestOwner($visitor);
        if ($owner && (int)$owner!==$clientId) { return; }
        if (!$owner) { $this->query('INSERT INTO cp_cliente_visitantes(cliente_id,visitante_id) VALUES (?,?)',[$clientId,$visitor]); }
        $prize=$this->guestAward($visitor);
        if ($prize) {
            // Registration already created cycle zero. Its reward is the one won before signing up.
            $this->query('UPDATE cp_panel_giros SET premio_id=? WHERE cliente_id=? AND ciclo=0 AND premio_id IS NULL',[$prize['id'],$clientId]);
        }
    }
    public function spinGuest(string $ticket): string {
        if ($ticket!=='guest-welcome') { throw new PanelError('Actualiza la ruleta e intenta de nuevo.',422); }
        if (!$this->ready()) { throw new PanelError('La ruleta todavía no está disponible.',503); }
        // InnoDB can choose a victim when different visitors compete for the last offer.
        // Each failed transaction rolls back completely; retrying cannot issue twice.
        for ($attempt=0; ; $attempt++) {
            try { return $this->spinGuestTransaction(); }
            catch (PDOException $e) {
                if ($attempt>=3 || !in_array((int)($e->errorInfo[1]??0),[1213,1205],true)) { throw $e; }
                usleep(random_int(10000,40000));
            }
        }
    }
    private function spinGuestTransaction(): string {
        $this->db->beginTransaction();
        try {
            $visitor=$this->guestVisitorLock();
            if ($this->guestOwner($visitor)) { throw new PanelError('Ingresa a tu cuenta para consultar tus promociones.',403); }
            $prize=$this->guestAward($visitor);
            if ($prize) { $this->db->commit(); return $prize['codigo']; }
            $policy=$this->policy(true);
            if ($policy['reinicio_mensual']===null) { throw new PanelError('La ruleta se está preparando. Intenta más tarde.',503); }
            $offers=$this->offers(true);
            if (!$offers) { throw new PanelError('Pronto tendremos promociones disponibles. Tu bienvenida se conserva.',422); }
            $offer=$offers[random_int(0,count($offers)-1)];
            $this->query("INSERT INTO cp_oportunidades(visitante_id,campana_id,origen,origen_clave,mostrada_at,consumida_at) VALUES (?,?,'panel_invitado','bienvenida',UTC_TIMESTAMP(),UTC_TIMESTAMP())",[$visitor,$policy['campana_id']]);
            $opportunity=(int)$this->db->lastInsertId();
            $issued=$this->issue($visitor,(int)$policy['campana_id'],$opportunity,$offer,'guest-welcome',['origen'=>'bienvenida_invitado']);
            $this->db->commit(); return $issued['code'];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) { $this->db->rollBack(); } throw $e;
        }
    }
    private function issue(int $visitor,int $campaign,int $opportunity,array $offer,string $requestId,array $audit): array {
        $createdAt=(string)$this->query('SELECT UTC_TIMESTAMP()')->fetchColumn();
        $month=(int)(new DateTimeImmutable($createdAt,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Bogota'))->format('n');
        $months=['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
        $prefix='CHAPI-'.$months[$month-1].'-';
        $prize=null; $minimum=100; $maximum=999999; $attempt=0;
        while ($prize===null) {
            if ($attempt<30) { $code=$prefix.random_int($minimum,$maximum); }
            else {
                $code=$this->availableCode($prefix,$minimum,$maximum);
                if ($code===null) {
                    if ($maximum>intdiv(PHP_INT_MAX-9,10)) { throw new PanelError('No pudimos asignar un código disponible. Tu giro se conserva; intenta de nuevo.',503); }
                    $minimum=$maximum+1; $maximum=$maximum*10+9; $attempt=0;
                    continue;
                }
            }
            try {
                $this->query('INSERT INTO cp_premios(oportunidad_id,visitante_id,campana_id,negocio_id,promocion_id,codigo,solicitud_id,titulo,descripcion,condiciones,porcentaje,creado_at,vence_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,DATE_ADD(?,INTERVAL 72 HOUR))',
                    [$opportunity,$visitor,$campaign,$offer['negocio_id'],$offer['id'],$code,$requestId,$offer['titulo'],$offer['descripcion'],$offer['condiciones'],$offer['porcentaje'],$createdAt,$createdAt]);
                $prize=$this->db->lastInsertId();
                break;
            } catch (PDOException $e) {
                // The unique index protects every issued code, including expired or redeemed ones.
                // Retry only a code collision; other database errors must roll back the whole spin.
                if ((int)($e->errorInfo[1]??0)!==1062 || !preg_match('/for key [\'`](?:[^\'`]+\.)?codigo[\'`]/i',(string)($e->errorInfo[2]??''))) { throw $e; }
                $attempt++;
                if ($attempt>=60) { throw new PanelError('No pudimos asignar un código disponible. Tu giro se conserva; intenta de nuevo.',503); }
            }
        }
        $this->query('INSERT INTO cp_premio_detalles(premio_id,negocio,direccion,whatsapp) VALUES (?,?,?,?)',[$prize,$offer['nombre'],$offer['direccion'],$offer['whatsapp_confirmado']]);
        $this->query('UPDATE cp_promociones SET entregados=entregados+1 WHERE id=?',[$offer['id']]);
        $this->query("INSERT INTO cp_auditoria(accion,entidad_id,datos) VALUES ('premio_generado',?,?)",[$prize,json_encode($audit)]);
        return ['id'=>(int)$prize,'code'=>$code];
    }
}
