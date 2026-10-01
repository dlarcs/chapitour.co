<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

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
            // UTC elapsed time: midnight, refreshes, devices and parallel requests cannot bypass 24 hours.
            if ($v['ultima_visita_at']!==null && strtotime($now.' UTC')-strtotime($v['ultima_visita_at'].' UTC')<86400) { return; }
            $count=(int)$v['visitas_ciclo']+1;
            $cycle=(int)$v['ciclos'];
            if ($count===8) {
                $cycle++; $count=0;
                $this->query('INSERT INTO cp_panel_giros(cliente_id,ciclo,creado_at) VALUES (?,?,?)',[$a['db_id'],$cycle,$now]);
            }
            $this->query('UPDATE cp_panel_visitas SET ultima_visita_at=?,mes=?,visitas_ciclo=?,ciclos=? WHERE cliente_id=?',[$now,$month,$count,$cycle,$a['db_id']]);
        });
    }
    public function status(?array $a): array {
        $p=$this->policy(); $configured=$p && $p['reinicio_mensual']!==null;
        $offers=$this->ready()?$this->offers():[];
        $ticket=null;
        if ($configured && $a && $a['role']==='client') {
            $ticket=$this->query('SELECT id FROM cp_panel_giros WHERE cliente_id=? AND premio_id IS NULL ORDER BY id LIMIT 1',[$a['db_id']])->fetchColumn() ?: null;
        }
        $reason=!$configured?'configuration_pending':(!$a?'login_required':($a['role']!=='client'?'client_required':(!$offers?'promotions_pending':(!$ticket?'visits_pending':'ready'))));
        return ['enabled'=>(bool)($configured && $offers),'visits_per_reward'=>8,'new_visit_after'=>86400,'replaces_previous_rule'=>true,
            'monthly_visit_reset'=>$configured?(bool)$p['reinicio_mensual']:null,'setup_required'=>!$this->ready(),
            'eligible_promotions'=>count($offers),'wheel_business_ids'=>array_map(static function($o){return (string)$o['negocio_id'];},$offers),
            'can_spin'=>$reason==='ready','reason'=>$reason,'ticket_id'=>$ticket?(string)$ticket:null];
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
            $this->query("INSERT INTO cp_oportunidades(visitante_id,campana_id,origen,origen_clave,mostrada_at,consumida_at) VALUES (?,?,'panel_8_visitas',?,UTC_TIMESTAMP(),UTC_TIMESTAMP())",[$visitor,$p['campana_id'],'cuenta:'.$a['db_id'].':ciclo:'.$g['ciclo']]);
            $opportunity=$this->db->lastInsertId();
            $code='CHAPI-'.strtoupper(bin2hex(random_bytes(10)));
            $this->query('INSERT INTO cp_premios(oportunidad_id,visitante_id,campana_id,negocio_id,promocion_id,codigo,solicitud_id,titulo,descripcion,condiciones,porcentaje,creado_at,vence_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),DATE_ADD(UTC_TIMESTAMP(),INTERVAL 72 HOUR))',
                [$opportunity,$visitor,$p['campana_id'],$offer['negocio_id'],$offer['id'],$code,'panel-giro-'.$g['id'],$offer['titulo'],$offer['descripcion'],$offer['condiciones'],$offer['porcentaje']]);
            $prize=$this->db->lastInsertId();
            $this->query('INSERT INTO cp_premio_detalles(premio_id,negocio,direccion,whatsapp) VALUES (?,?,?,?)',[$prize,$offer['nombre'],$offer['direccion'],$offer['whatsapp_confirmado']]);
            $this->query('UPDATE cp_panel_giros SET premio_id=? WHERE id=?',[$prize,$g['id']]);
            $this->query('UPDATE cp_promociones SET entregados=entregados+1 WHERE id=?',[$offer['id']]);
            $this->query("INSERT INTO cp_auditoria(accion,entidad_id,datos) VALUES ('premio_generado',?,?)",[$prize,json_encode(['origen'=>'paneles','cliente_id'=>$a['db_id'],'giro_id'=>$g['id']])]);
            return $code;
        });
    }
}
