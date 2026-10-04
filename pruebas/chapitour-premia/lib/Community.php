<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

final class ChapitourCommunity
{
    private $db;
    private $ready;
    public function __construct(PDO $db) { $this->db=$db; }
    private function query(string $sql,array $args=[]): PDOStatement {
        $s=$this->db->prepare($sql);$s->execute($args);return $s;
    }
    public function ready(): bool {
        if ($this->ready===null) {
            $this->ready=(int)$this->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name IN ('cp_panel_comunidad','cp_panel_puntos_visitas','cp_panel_meta_fotos','cp_panel_meta_preguntas')")->fetchColumn()===4;
        }
        return $this->ready;
    }
    public function install(): void {
        if ($this->ready()) { return; }
        if ((int)$this->query("SELECT GET_LOCK('chapitour_comunidad_schema',10)")->fetchColumn()!==1) { throw new PanelError('La comunidad se está actualizando. Intenta de nuevo.',503); }
        try {
            $sql=preg_replace('/^\s*--.*$/m','',file_get_contents(__DIR__.'/../database/comunidad_cp.sql'));
            foreach (explode(';',$sql) as $statement) { if (trim($statement)!=='') { $this->db->exec($statement); } }
            $this->ready=null;
        } finally { $this->query("SELECT RELEASE_LOCK('chapitour_comunidad_schema')"); }
    }
    private function requireReady(): void {
        if (!$this->ready()) { throw new PanelError('Un administrador debe abrir su panel para preparar el ranking y las fotos de perfil.',503); }
    }
    public function month(): string {
        $now=(string)$this->query('SELECT UTC_TIMESTAMP()')->fetchColumn();
        return (new DateTimeImmutable($now,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Bogota'))->format('Y-m-01');
    }
    // Called only by the existing visit rule, inside the same account-locked transaction.
    public function recordValidVisit(int $id,string $month): void {
        if (!$this->ready()) { return; }
        if (!$this->db->inTransaction()) { throw new LogicException('La visita requiere una transacción.'); }
        $this->query('INSERT INTO cp_panel_puntos_visitas(cliente_id,mes,cantidad) VALUES (?,?,1) ON DUPLICATE KEY UPDATE cantidad=LEAST(10,cantidad+1)',[$id,$month.'-01']);
    }
    private function scoreSql(): string {
        // One score per account. No points from clicks, request parameters or unauthenticated activity.
        return "SELECT c.id,COALESCE(NULLIF(TRIM(c.nombre),''),'Participante') AS nombre_publico,COALESCE(p.visible,1) AS visible,
            LEAST(10,COALESCE(v.cantidad,0)) AS visitas,
            LEAST(25,COALESCE(q.cantidad,0)*5) AS preguntas,
            LEAST(45,COALESCE(f.cantidad,0)*15) AS fotos,
            COALESCE(s.puntos,0) AS compartir,
            LEAST(10,COALESCE(v.cantidad,0))+LEAST(25,COALESCE(q.cantidad,0)*5)+LEAST(45,COALESCE(f.cantidad,0)*15)+COALESCE(s.puntos,0) AS puntos
            FROM cp_clientes c LEFT JOIN cp_panel_comunidad p ON p.cliente_id=c.id
            LEFT JOIN cp_panel_puntos_visitas v ON v.cliente_id=c.id AND v.mes=?
            LEFT JOIN (SELECT cliente_id,COUNT(DISTINCT negocio_id) AS cantidad FROM cp_panel_meta_preguntas WHERE mes=? AND version=1 GROUP BY cliente_id) q ON q.cliente_id=c.id
            LEFT JOIN (SELECT cliente_id,COUNT(DISTINCT negocio_id) AS cantidad FROM cp_panel_meta_fotos WHERE mes=? GROUP BY cliente_id) f ON f.cliente_id=c.id
            LEFT JOIN (SELECT pr.cliente_id,MAX(CASE WHEN r.objetivo=20 AND pr.cantidad_verificada=20 AND TRIM(COALESCE(r.criterio_verificacion,''))<>'' THEN 20 ELSE 0 END) AS puntos
                FROM cp_panel_progreso pr JOIN cp_panel_retos r ON r.id=pr.reto_id WHERE r.mes=? AND r.tipo='compartir' GROUP BY pr.cliente_id) s ON s.cliente_id=c.id
            WHERE c.activo=1";
    }
    private function args(string $month): array { return [$month,$month,$month,$month]; }
    public function leaderboard(int $page=1,int $size=10): array {
        $month=$this->month();$ready=$this->ready();
        $size=max(1,min(20,$size));$page=max(1,min(10000,$page));$offset=($page-1)*$size;
        $result=['ready'=>$ready,'month'=>$month,'entries'=>[],'page'=>$page,'has_more'=>false,'total'=>0,'real_total'=>0,'demo_total'=>0];
        if (!$ready) { return $result; }
        $realTotal=(int)$this->query("SELECT COUNT(*) FROM cp_clientes c LEFT JOIN cp_panel_comunidad p ON p.cliente_id=c.id WHERE c.activo=1 AND COALESCE(p.visible,1)=1")->fetchColumn();
        $result['real_total']=$realTotal;
        $result['total']=$realTotal;
        $rows=$this->query('SELECT nombre_publico,puntos FROM ('.$this->scoreSql().") score WHERE visible=1 AND TRIM(nombre_publico)<>'' ORDER BY puntos DESC,nombre_publico ASC,id ASC LIMIT ".$size.' OFFSET '.$offset,$this->args($month))->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) { $result['entries'][]=['name'=>$row['nombre_publico'],'score'=>(int)$row['puntos']]; }
        $result['has_more']=$offset+count($result['entries'])<$result['total'];
        return $result;
    }
    public function member(int $id): array {
        $result=['ready'=>$this->ready(),'public_name'=>'','visible'=>false,'photo_url'=>null,'score'=>0,'position'=>null,'points_to_climb'=>null,'breakdown'=>['visits'=>0,'questions'=>0,'photos'=>0,'sharing'=>0]];
        if (!$result['ready']) { return $result; }
        $profile=$this->query('SELECT visible,foto_version FROM cp_panel_comunidad WHERE cliente_id=?',[$id])->fetch(PDO::FETCH_ASSOC);
        if ($profile) { $result['visible']=(bool)$profile['visible'];$result['photo_url']=$profile['foto_version']?'avatar.php?v='.$profile['foto_version']:null; }
        $args=$this->args($this->month());
        $row=$this->query('SELECT * FROM ('.$this->scoreSql().') score WHERE id=?',array_merge($args,[$id]))->fetch(PDO::FETCH_ASSOC);
        if (!$row) { return $result; }
        // Use the account name saved at registration, including the Google display name.
        // Explicit visibility choices are never changed by reading the ranking.
        $result['public_name']=$row['nombre_publico'];
        $result['visible']=(bool)$row['visible'];
        $result['score']=(int)$row['puntos'];
        $result['breakdown']=['visits'=>(int)$row['visitas'],'questions'=>(int)$row['preguntas'],'photos'=>(int)$row['fotos'],'sharing'=>(int)$row['compartir']];
        if ($result['visible']) {
            $higher=$this->query('SELECT COUNT(*) AS cantidad,MIN(puntos) AS siguiente FROM ('.$this->scoreSql().") score WHERE visible=1 AND TRIM(nombre_publico)<>'' AND puntos>?",array_merge($args,[$result['score']]))->fetch(PDO::FETCH_ASSOC);
            $result['position']=(int)$higher['cantidad']+1;
            $result['points_to_climb']=$higher['siguiente']===null?null:(int)$higher['siguiente']-$result['score'];
        }
        return $result;
    }
    public function registerMember(int $id): void {
        $this->requireReady();
        if (!$this->db->inTransaction()) { throw new LogicException('El perfil inicial requiere la transacción de registro.'); }
        // Keep existing visibility choices; the displayed name comes from cp_clientes.
        $this->query('INSERT INTO cp_panel_comunidad(cliente_id,visible) VALUES (?,1) ON DUPLICATE KEY UPDATE cliente_id=VALUES(cliente_id)',[$id]);
    }
    public function savePreferences(int $id,bool $visible): void {
        $this->requireReady();
        $this->query('INSERT INTO cp_panel_comunidad(cliente_id,visible) VALUES (?,?) ON DUPLICATE KEY UPDATE visible=VALUES(visible)',[$id,$visible?1:0]);
    }
    public function prepareUpload(array $file): string {
        $this->requireReady();
        if (($file['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK || !is_string($file['tmp_name']??null) || !is_uploaded_file($file['tmp_name'])) { throw new PanelError('Selecciona una foto JPG, PNG o WebP de hasta 5 MB.',422); }
        if (filesize($file['tmp_name'])>5*1024*1024) { throw new PanelError('La foto no puede superar 5 MB.',422); }
        $bytes=file_get_contents($file['tmp_name']);$info=@getimagesizefromstring($bytes);
        if (!$info || !in_array($info[2],[IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_WEBP],true) || $info[0]<1 || $info[1]<1 || $info[0]>6000 || $info[1]>6000 || $info[0]*$info[1]>16000000) { throw new PanelError('Usa una foto JPG, PNG o WebP de hasta 16 megapíxeles.',422); }
        if (!extension_loaded('gd')) { throw new PanelError('El servidor necesita activar GD para guardar fotografías.',503); }
        $source=@imagecreatefromstring($bytes);
        if (!$source) { throw new PanelError('No pudimos leer la imagen. Selecciona otra foto.',422); }
        $target=null;
        try {
            $size=min(imagesx($source),imagesy($source));$target=imagecreatetruecolor(512,512);
            imagefill($target,0,0,imagecolorallocate($target,255,255,255));
            imagecopyresampled($target,$source,0,0,(int)((imagesx($source)-$size)/2),(int)((imagesy($source)-$size)/2),512,512,$size,$size);
            ob_start();$ok=imagejpeg($target,null,85);$jpeg=ob_get_clean();
            if (!$ok || !is_string($jpeg)) { throw new PanelError('No pudimos guardar la imagen. Intenta con otra foto.',422); }
            return $jpeg;
        } finally { imagedestroy($source);if($target)imagedestroy($target); }
    }
    public function savePhoto(int $id,?string $jpeg): void {
        $this->requireReady();
        $this->query('INSERT INTO cp_panel_comunidad(cliente_id,visible,foto,foto_version) VALUES (?,1,?,?) ON DUPLICATE KEY UPDATE foto=VALUES(foto),foto_version=VALUES(foto_version)',[$id,$jpeg,$jpeg===null?null:bin2hex(random_bytes(16))]);
    }
    public function photo(int $id): ?string {
        if (!$this->ready()) { return null; }
        $photo=$this->query('SELECT foto FROM cp_panel_comunidad WHERE cliente_id=?',[$id])->fetchColumn();
        return is_string($photo)?$photo:null;
    }
    public function delete(int $id): void {
        if (!$this->ready()) { return; }
        $this->query('DELETE FROM cp_panel_comunidad WHERE cliente_id=?',[$id]);
        $this->query('DELETE FROM cp_panel_puntos_visitas WHERE cliente_id=?',[$id]);
    }
}
