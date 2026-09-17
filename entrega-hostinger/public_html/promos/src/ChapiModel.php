<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
class ChapiModel
{
    protected PDO $db;
    protected array $config;
    protected int $campaign;
    public function __construct(PDO $db, array $config) { $this->db=$db; $this->config=$config; $this->campaign=(int)$config['campana_id']; }
    protected function query(string $sql, array $params = []): PDOStatement { $q=$this->db->prepare($sql); $q->execute($params); return $q; }
    protected function one(string $sql, array $params = []): ?array { return $this->query($sql,$params)->fetch() ?: null; }
    protected function transaction(callable $fn)
    {
        $this->db->beginTransaction();
        try {
            // One campaign lock gives consistent ordering for grants, draws, referrals and redemption.
            $this->query('SELECT id FROM cp_campanas WHERE id=? FOR UPDATE',[$this->campaign]);
            $result=$fn(); $this->db->commit(); return $result;
        } catch (Throwable $e) { if ($this->db->inTransaction()) $this->db->rollBack(); throw $e; }
    }
    protected function event(?int $visitor, string $type, ?int $prize=null, array $data=[]): void
    {
        $this->query('INSERT INTO cp_eventos(campana_id,visitante_id,premio_id,tipo,datos) VALUES (?,?,?,?,?)',[$this->campaign,$visitor,$prize,$type,json_encode($data,JSON_UNESCAPED_UNICODE)]);
    }
    protected function audit(int $user, string $action, ?int $id=null, array $data=[]): void
    {
        $this->query('INSERT INTO cp_auditoria(usuario_id,accion,entidad_id,datos) VALUES (?,?,?,?)',[$user,$action,$id,json_encode($data,JSON_UNESCAPED_UNICODE)]);
    }
    protected function grant(int $visitor, string $origin, string $key): void
    {
        $q=$this->query('INSERT IGNORE INTO cp_oportunidades(visitante_id,campana_id,origen,origen_clave) VALUES (?,?,?,?)',[$visitor,$this->campaign,$origin,$key]);
        if ($q->rowCount()) $this->event($visitor,'oportunidad_concedida',null,['origen'=>$origin]);
    }
    protected function prizeData(array $row): array
    {
        $row['estado']=$row['redimido_at'] ? 'redimido' : (strtotime($row['vence_at'].' UTC') <= time() ? 'vencido' : 'activo');
        foreach (['creado_at','vence_at','redimido_at'] as $field) if ($row[$field]) $row[$field]=str_replace(' ','T',$row[$field]).'Z';
        foreach (['id','negocio_id'] as $field) $row[$field]=(int)$row[$field];
        unset($row['visitante_id'],$row['solicitud_id']);
        return $row;
    }
    protected function prizeSelect(): string
    {
        return 'SELECT p.*, n.nombre AS negocio, n.slug, n.categoria, n.logo, n.whatsapp, n.direccion, n.pagina FROM cp_premios p JOIN cp_negocios n ON n.id=p.negocio_id ';
    }
}
