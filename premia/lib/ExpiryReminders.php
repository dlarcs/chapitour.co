<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
require_once __DIR__.'/ReminderDeliveryError.php';

final class ChapitourExpiryReminders
{
    private PDO $db;
    private $send;
    public function __construct(PDO $db, callable $send) { $this->db=$db; $this->send=$send; }
    private function query(string $sql, array $args=[]): PDOStatement
    {
        $stmt=$this->db->prepare($sql); $stmt->execute($args); return $stmt;
    }
    public function ready(): bool
    {
        return (bool)$this->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='cp_panel_recordatorios'")->fetchColumn();
    }
    public function install(): void
    {
        if ($this->db->inTransaction()) { throw new LogicException('REMINDER_TRANSACTION'); }
        $this->db->exec(file_get_contents(__DIR__.'/../database/recordatorios_cp.sql'));
    }
    private function candidates(int $limit): array
    {
        $ledger=$this->ready();
        return $this->query('SELECT p.id,c.email FROM cp_premios p
            JOIN cp_cliente_visitantes cv ON cv.visitante_id=p.visitante_id
            JOIN cp_clientes c ON c.id=cv.cliente_id'
            .($ledger?' LEFT JOIN cp_panel_recordatorios r ON r.premio_id=p.id':'')
            ." WHERE c.activo=1 AND p.redimido_at IS NULL AND p.vence_at>UTC_TIMESTAMP()
            AND p.vence_at<=DATE_ADD(UTC_TIMESTAMP(),INTERVAL 24 HOUR)"
            .($ledger?" AND (r.premio_id IS NULL OR (r.estado IN ('pending','retry') AND r.intentos<3 AND (r.proximo_intento_at IS NULL OR r.proximo_intento_at<=UTC_TIMESTAMP())))":'')
            .' ORDER BY p.vence_at,p.id LIMIT '.max(1,min(500,$limit)))->fetchAll(PDO::FETCH_ASSOC);
    }
    public function preview(int $limit=50): array
    {
        $rows=$this->candidates($limit);
        return ['schema_ready'=>$this->ready(),'eligible'=>count(array_filter($rows,static fn($row)=>filter_var($row['email'],FILTER_VALIDATE_EMAIL)!==false)),
            'invalid_email'=>count(array_filter($rows,static fn($row)=>filter_var($row['email'],FILTER_VALIDATE_EMAIL)===false)),'batch_limit'=>$limit];
    }
    private function currentPrize(int $id): ?array
    {
        // Hold the account and award locks during the short SMTP delivery. Profile
        // deletion, email changes and redemption use these same rows.
        $prize=$this->query('SELECT p.*,c.id AS cliente_id,c.nombre AS cliente_nombre,c.email AS cliente_email
            FROM cp_premios p JOIN cp_cliente_visitantes cv ON cv.visitante_id=p.visitante_id
            JOIN cp_clientes c ON c.id=cv.cliente_id
            WHERE p.id=? AND c.activo=1 AND p.redimido_at IS NULL AND p.vence_at>UTC_TIMESTAMP()
            AND p.vence_at<=DATE_ADD(UTC_TIMESTAMP(),INTERVAL 24 HOUR) FOR UPDATE',[$id])->fetch(PDO::FETCH_ASSOC);
        if (!$prize) { return null; }
        $prize['negocio_nombre']=(string)$this->query('SELECT COALESCE(NULLIF(d.negocio,\'\'),n.nombre) FROM cp_negocios n LEFT JOIN cp_premio_detalles d ON d.premio_id=? WHERE n.id=?',[$id,$prize['negocio_id']])->fetchColumn();
        return $prize;
    }
    public function run(int $limit=50): array
    {
        if (!$this->ready()) { throw new RuntimeException('REMINDER_SCHEMA_MISSING'); }
        if ($this->db->inTransaction()) { throw new LogicException('REMINDER_TRANSACTION'); }
        $summary=['sent'=>0,'retry'=>0,'uncertain'=>0,'skipped'=>0,'busy'=>false];
        $lock='chapitour-reminder-'.substr(hash('sha256',(string)$this->query('SELECT DATABASE()')->fetchColumn()),0,40);
        if ((int)$this->query('SELECT GET_LOCK(?,0)',[$lock])->fetchColumn()!==1) { $summary['busy']=true; return $summary; }
        try {
            // With the process-wide DB lock acquired, any old sending row belonged
            // to an interrupted worker. It may already have reached SMTP.
            $summary['uncertain']=$this->query("UPDATE cp_panel_recordatorios SET estado='uncertain',error_codigo='WORKER_INTERRUPTED',actualizado_at=UTC_TIMESTAMP() WHERE estado='sending'")->rowCount();
            foreach ($this->candidates($limit) as $candidate) {
                $id=(int)$candidate['id'];
                $this->query("INSERT IGNORE INTO cp_panel_recordatorios(premio_id,token) VALUES (?,?)",[$id,bin2hex(random_bytes(16))]);
                $row=$this->query('SELECT token FROM cp_panel_recordatorios WHERE premio_id=?',[$id])->fetch(PDO::FETCH_ASSOC);
                // Persist the claim before sending, so a process crash cannot
                // roll it back into an apparently unsent prize.
                $claimed=$this->query("UPDATE cp_panel_recordatorios SET estado='sending',intentos=intentos+1,iniciado_at=UTC_TIMESTAMP(),actualizado_at=UTC_TIMESTAMP(),error_codigo=NULL
                    WHERE premio_id=? AND estado IN ('pending','retry') AND intentos<3",[$id])->rowCount();
                if (!$claimed) { continue; }
                try {
                    $this->db->beginTransaction();
                    $prize=$this->currentPrize($id);
                    if (!$prize || !filter_var($prize['cliente_email'],FILTER_VALIDATE_EMAIL)) {
                        $this->query("UPDATE cp_panel_recordatorios SET estado='skipped',error_codigo=?,actualizado_at=UTC_TIMESTAMP() WHERE premio_id=?",[$prize?'INVALID_EMAIL':'NO_LONGER_ELIGIBLE',$id]);
                        $this->db->commit(); $summary['skipped']++; continue;
                    }
                    ($this->send)($prize,$row['token']);
                    $this->query("UPDATE cp_panel_recordatorios SET estado='sent',enviado_at=UTC_TIMESTAMP(),proximo_intento_at=NULL,actualizado_at=UTC_TIMESTAMP() WHERE premio_id=?",[$id]);
                    $this->db->commit(); $summary['sent']++;
                } catch (Throwable $e) {
                    if ($this->db->inTransaction()) { $this->db->rollBack(); }
                    $safe=$e instanceof ChapitourReminderDeliveryError && $e->safeToRetry;
                    $status=$safe?'retry':'uncertain';
                    $this->query("UPDATE cp_panel_recordatorios SET estado=?,error_codigo=?,proximo_intento_at=DATE_ADD(UTC_TIMESTAMP(),INTERVAL 15 MINUTE),actualizado_at=UTC_TIMESTAMP() WHERE premio_id=?",
                        [$status,$safe?'SMTP_BEFORE_DATA':'DELIVERY_UNCERTAIN',$id]);
                    $summary[$status]++;
                }
            }
            $summary['needs_review']=(int)$this->query("SELECT COUNT(*) FROM cp_panel_recordatorios WHERE estado='uncertain' OR (estado='retry' AND intentos>=3)")->fetchColumn();
            return $summary;
        } finally { $this->query('SELECT RELEASE_LOCK(?)',[$lock]); }
    }
}
