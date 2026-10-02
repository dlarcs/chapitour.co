<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

/** Photo points are self-reported; correct answers do not verify physical attendance. */
final class ChapitourChallenges
{
    private $db;
    private $ready;
    private const VERSION = 1;
    public function __construct(PDO $db) { $this->db=$db; }
    private function query(string $sql,array $args=[]): PDOStatement {
        $s=$this->db->prepare($sql); $s->execute($args); return $s;
    }
    public function ready(): bool {
        if ($this->ready===null) {
            $this->ready=(int)$this->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name IN ('cp_panel_meta_fotos','cp_panel_meta_preguntas')")->fetchColumn()===2;
        }
        return $this->ready;
    }
    public function install(): void {
        if ($this->ready()) { return; }
        if ((int)$this->query("SELECT GET_LOCK('chapitour_metas_schema',10)")->fetchColumn()!==1) {
            throw new PanelError('Las metas se están actualizando. Intenta de nuevo.',503);
        }
        try {
            $sql=preg_replace('/^\s*--.*$/m','',file_get_contents(__DIR__.'/../database/metas_cp.sql'));
            foreach (explode(';',$sql) as $statement) { if (trim($statement)!=='') { $this->db->exec($statement); } }
            $this->ready=true;
        } finally { $this->query("SELECT RELEASE_LOCK('chapitour_metas_schema')"); }
    }
    public function month(): string {
        $now=(string)$this->query('SELECT UTC_TIMESTAMP()')->fetchColumn();
        return (new DateTimeImmutable($now,new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Bogota'))->format('Y-m-01');
    }
    private function catalog(): array {
        // Prompts are anonymous in the interface. Open answers are recorded, not graded against invented menus.
        return [
            'capital-queer'=>['instagram'=>'capital_queer_','key'=>'r1','questions'=>[
                ['id'=>'respuesta','label'=>'¿A qué público está dirigido este lugar?'],
            ]],
            'gran-chela'=>['instagram'=>'granychela.club','key'=>'r2','questions'=>[
                ['id'=>'respuesta','label'=>'Recorre su larguísimo pasillo. ¿Qué dos tipos de bebidas preparadas destacan en este lugar para salir de fiesta?'],
            ]],
            'garage-disco-bar'=>['instagram'=>'garagediscobar_','key'=>'r3','questions'=>[
                ['id'=>'respuesta','label'=>'Cuando te tomes un cóctel, dinos su nombre.'],
            ]],
            'pictogramas'=>['instagram'=>null,'key'=>'r4','questions'=>[
                ['id'=>'pais_1','label'=>'Mira el techo y encuentra tres banderas de países diferentes. Primer país'],
                ['id'=>'pais_2','label'=>'Segundo país'],
                ['id'=>'pais_3','label'=>'Tercer país'],
            ]],
            'street-grill'=>['instagram'=>'streetgrillbbq','key'=>'r5','questions'=>[
                ['id'=>'respuesta','label'=>'Además de comida rápida, ¿qué especialidad tenemos?'],
            ]],
            'jimar-factory'=>['instagram'=>'jimar_factory_bogota','key'=>null,'questions'=>[]],
        ];
    }
    private function places(bool $lock=false): array {
        $catalog=$this->catalog();
        $rows=$this->query('SELECT id,nombre,slug FROM cp_negocios WHERE activo=1 AND slug IN ('.implode(',',array_fill(0,count($catalog),'?')).') ORDER BY id'.($lock?' FOR UPDATE':''),array_keys($catalog))->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) { $row['definition']=$catalog[$row['slug']]; }
        return $rows;
    }
    public function state(int $clientId): array {
        $month=$this->month(); $ready=$this->ready();
        $photos=$ready?$this->query('SELECT negocio_id FROM cp_panel_meta_fotos WHERE cliente_id=? AND mes=?',[$clientId,$month])->fetchAll(PDO::FETCH_COLUMN):[];
        $answers=$ready?$this->query('SELECT negocio_id FROM cp_panel_meta_preguntas WHERE cliente_id=? AND mes=? AND version=?',[$clientId,$month,self::VERSION])->fetchAll(PDO::FETCH_COLUMN):[];
        $photoPlaces=[]; $questionPlaces=[];
        foreach ($this->places() as $b) {
            $photoPlaces[]=['id'=>(string)$b['id'],'name'=>$b['nombre'],'instagram'=>$b['definition']['instagram'],'complete'=>in_array($b['id'],$photos)];
            if ($b['definition']['questions']) {
                $questionPlaces[]=['id'=>$b['definition']['key'],'name'=>'Pregunta '.substr($b['definition']['key'],1),'complete'=>in_array($b['id'],$answers),'questions'=>array_map(static function($q){return ['id'=>$q['id'],'label'=>$q['label']];},$b['definition']['questions'])];
            }
        }
        usort($questionPlaces,static function($a,$b){return strcmp($a['id'],$b['id']);});
        return [
            ['type'=>'fotografia','title'=>'Etiqueta en Instagram desde 3 negocios','target'=>3,'progress'=>count($photos),'month'=>$month,'ready'=>$ready,'places'=>$photoPlaces,'method'=>'self_reported'],
            ['type'=>'preguntas','title'=>'¿Cuánto conoces estos lugares?','target'=>count($questionPlaces),'progress'=>count(array_filter($questionPlaces,static function($p){return $p['complete'];})),'month'=>$month,'ready'=>$ready,'version'=>self::VERSION,'places'=>$questionPlaces,'locations'=>$photoPlaces],
        ];
    }
    private function selected(array $input): array {
        if (!$this->ready()) { throw new PanelError('Un administrador debe abrir su panel para preparar las nuevas metas.',503); }
        if (($input['month']??null)!==$this->month()) { throw new PanelError('Comenzó un nuevo mes. Actualiza la página para continuar con tus metas.',409); }
        $id=$input['business_id']??null;
        if (!is_string($id) || !ctype_digit($id)) { throw new PanelError('Selecciona un negocio de la lista.',422); }
        foreach ($this->places(true) as $place) { if ((string)$place['id']===$id) { return $place; } }
        throw new PanelError('Este negocio no está disponible para el reto.',422);
    }
    // The caller holds a client row lock and checks the current session inside its transaction.
    public function recordPhoto(int $clientId,array $input): bool {
        $place=$this->selected($input);
        $count=(int)$this->query('SELECT COUNT(*) FROM cp_panel_meta_fotos WHERE cliente_id=? AND mes=?',[$clientId,$input['month']])->fetchColumn();
        if ($count>=3) { return false; }
        return $this->query('INSERT IGNORE INTO cp_panel_meta_fotos(cliente_id,mes,negocio_id,creado_at) VALUES (?,?,?,UTC_TIMESTAMP())',[$clientId,$input['month'],$place['id']])->rowCount()===1;
    }
    private function normalize(string $answer): string {
        $answer=strtr(mb_strtolower(trim($answer)),['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u']);
        $answer=preg_replace('/[\p{M}]/u','',$answer);
        return trim(preg_replace('/[^a-z0-9ñ]+/u',' ',$answer));
    }
    public function answer(int $clientId,array $input): bool {
        $reportedPlace=$this->selected($input); $questionPlace=null;
        foreach ($this->places() as $place) { if ($place['definition']['key']===($input['question_id']??'')) { $questionPlace=$place; break; } }
        if ((string)($input['version']??'')!==(string)self::VERSION || !$questionPlace || !$questionPlace['definition']['questions']) { throw new PanelError('Actualiza la página para consultar las preguntas disponibles.',409); }
        $answers=[];
        foreach ($questionPlace['definition']['questions'] as $question) {
            $answer=$input['answer_'.$question['id']]??null;
            if (!is_string($answer) || !mb_check_encoding($answer,'UTF-8') || mb_strlen($answer)>250 || trim($answer)==='') { throw new PanelError('Completa todas las respuestas (máximo 250 caracteres por respuesta).',422); }
            $answers[$question['id']]=trim($answer);
        }
        if ($questionPlace['definition']['key']==='r4' && count(array_unique(array_map([$this,'normalize'],array_values($answers))))!==3) { throw new PanelError('Escribe tres países diferentes.',422); }
        return $this->query('INSERT IGNORE INTO cp_panel_meta_preguntas(cliente_id,mes,negocio_id,version,lugar_reportado_id,respuestas,creado_at) VALUES (?,?,?,?,?,?,UTC_TIMESTAMP())',[$clientId,$input['month'],$questionPlace['id'],self::VERSION,$reportedPlace['id'],json_encode($answers,JSON_UNESCAPED_UNICODE)])->rowCount()===1;
    }
    public function deleteProgress(int $clientId): void {
        if (!$this->ready()) { return; }
        $this->query('DELETE FROM cp_panel_meta_fotos WHERE cliente_id=?',[$clientId]);
        $this->query('DELETE FROM cp_panel_meta_preguntas WHERE cliente_id=?',[$clientId]);
    }
}
