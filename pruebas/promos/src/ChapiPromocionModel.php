<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
final class ChapiPromocionModel extends ChapiModel
{
    public function visitor(string $identity, string $ip): array
    {
        $this->query('INSERT INTO cp_visitantes(identidad_hash,ip_hash,referido_token) VALUES (?,?,?) ON DUPLICATE KEY UPDATE ultimo_acceso_at=UTC_TIMESTAMP()',[$identity,$ip,bin2hex(random_bytes(16))]);
        return $this->one('SELECT * FROM cp_visitantes WHERE identidad_hash=?',[$identity]);
    }

    public function initialize(int $id, string $ip): array
    {
        $this->transaction(function () use ($id,$ip) {
            $this->query('INSERT IGNORE INTO cp_participaciones(visitante_id,campana_id) VALUES (?,?)',[$id,$this->campaign]);
            $p=$this->one('SELECT * FROM cp_participaciones WHERE visitante_id=? AND campana_id=? FOR UPDATE',[$id,$this->campaign]);
            $rules=$this->config['reglas'];
            if ($p['ultima_visita_at'] && time()-strtotime($p['ultima_visita_at'].' UTC') < (int)$rules['min_segundos_entre_visitas']) return;
            $visits=(int)$p['visitas']+1;
            $this->query('UPDATE cp_participaciones SET visitas=?,ultima_visita_at=UTC_TIMESTAMP() WHERE visitante_id=? AND campana_id=?',[$visits,$id,$this->campaign]);
            $this->event($id,'visita',null,['numero'=>$visits]);
            $totals=$this->visitTotals($id);
            if ((int)$totals['visitas']===1) {
                $this->query('INSERT IGNORE INTO cp_bienvenidas_ip(campana_id,ip_hash,dia,cantidad) VALUES (?,?,UTC_DATE(),0)',[$this->campaign,$ip]);
                $daily=$this->one('SELECT cantidad FROM cp_bienvenidas_ip WHERE campana_id=? AND ip_hash=? AND dia=UTC_DATE() FOR UPDATE',[$this->campaign,$ip]);
                if ((int)$daily['cantidad'] < (int)$rules['max_bienvenidas_por_ip_dia']) {
                    $this->grant($id,'bienvenida','bienvenida');
                    $this->query('UPDATE cp_bienvenidas_ip SET cantidad=cantidad+1 WHERE campana_id=? AND ip_hash=? AND dia=UTC_DATE()',[$this->campaign,$ip]);
                    $this->programmed($id,$visits);
                } else $this->event($id,'bienvenida_limitada_ip');
                return;
            }
            $mode=$rules['frecuencia'];
            $last=$totals['ultima_programada_at'];
            $byVisits=$mode==='cada_visitas' && (int)$totals['visitas']-(int)$totals['programadas'] >= max(1,(int)$rules['cada_visitas']);
            $byDays=$mode==='cada_dias' && (!$last || time()-strtotime($last.' UTC') >= max(1,(int)$rules['cada_dias'])*86400);
            if ($byVisits || $byDays) {
                $this->grant($id,'frecuencia','frecuencia:'.$totals['visitas']);
                $this->programmed($id,$visits);
            }
        });
        return $this->state($id);
    }

    private function visitTotals(int $id): array
    {
        [$scope,$args]=$this->visitorScope($id,'p');
        return $this->one('SELECT COALESCE(SUM(visitas),0) AS visitas,COALESCE(SUM(ultima_programada_visita),0) AS programadas,MAX(ultima_programada_at) AS ultima_programada_at FROM cp_participaciones p WHERE '.$scope.' AND p.campana_id=?',array_merge($args,[$this->campaign]));
    }

    private function programmed(int $id,int $visits): void
    {
        [$scope,$args]=$this->visitorScope($id,'cp_participaciones');
        $this->query('UPDATE cp_participaciones SET ultima_programada_at=UTC_TIMESTAMP(),ultima_programada_visita=visitas WHERE '.$scope.' AND campana_id=?',array_merge($args,[$this->campaign]));
    }

    public function preview(int $id,string $key): array
    {
        if (empty($this->config['reglas']['modo_pruebas'])) throw new ChapiError('Solo disponible en pruebas.',404);
        $this->transaction(function () use ($id,$key) {
            [$scope,$args]=$this->visitorScope($id,'o');
            if (!$this->one('SELECT id FROM cp_oportunidades o WHERE '.$scope.' AND campana_id=? AND consumida_at IS NULL LIMIT 1',array_merge($args,[$this->campaign]))) {
                $this->grant($id,'prueba','prueba:'.$key);
            }
        });
        return $this->state($id);
    }

    public function businesses(): array
    {
        return $this->query('SELECT DISTINCT n.id,n.nombre,n.slug,n.categoria,n.logo,n.pagina FROM cp_negocios n JOIN cp_promociones p ON p.negocio_id=n.id JOIN cp_campanas c ON c.id=? WHERE c.activa=1 AND n.activo=1 AND p.activa=1 AND (p.cupo_total IS NULL OR p.entregados<p.cupo_total) ORDER BY n.id',[$this->campaign])->fetchAll();
    }

    public function state(int $id): array
    {
        $visitor=$this->one('SELECT referido_token FROM cp_visitantes WHERE id=?',[$id]);
        [$scope,$args]=$this->visitorScope($id,'p');
        $totals=$this->visitTotals($id);
        $available=$this->query('SELECT id,mostrada_at,origen FROM cp_oportunidades p WHERE '.$scope.' AND campana_id=? AND consumida_at IS NULL ORDER BY id',array_merge($args,[$this->campaign]))->fetchAll();
        $prizes=array_map(fn($row)=>$this->prizeData($row),$this->query($this->prizeSelect().'WHERE '.$scope.' AND p.campana_id=? ORDER BY p.id DESC LIMIT 50',array_merge($args,[$this->campaign]))->fetchAll());
        $total=(int)$this->query('SELECT COUNT(*) FROM cp_referidos WHERE propietario_id=? AND campana_id=?',[$id,$this->campaign])->fetchColumn();
        $target=max(1,(int)$this->config['reglas']['amigos_requeridos']);
        $businesses=$this->businesses();
        $unseen=array_values(array_filter($available,fn($o)=>$o['mostrada_at']===null));
        return ['negocios'=>$businesses,'oportunidades'=>count($available),'oportunidad_mostrar'=>$unseen ? (int)$unseen[0]['id'] : null,
            'abrir_automaticamente'=>(bool)($unseen && $businesses),'premios'=>$prizes,
            'invitacion_url'=>$this->config['base_url'].'/?ref='.$visitor['referido_token'],
            'referidos_total'=>$total,'referidos_progreso'=>$total % $target,'amigos_requeridos'=>$target,
            'referidos_habilitados'=>(bool)$this->config['reglas']['referidos_habilitados'],
            'visitas'=>(int)$totals['visitas'],'cada_visitas'=>(int)$this->config['reglas']['cada_visitas'],
            'visitas_para_proxima'=>max(0,(int)$this->config['reglas']['cada_visitas']-((int)$totals['visitas']-(int)$totals['programadas'])),
            'modo_pruebas'=>(bool)$this->config['reglas']['modo_pruebas'],
            'vigencia_horas'=>(int)$this->config['reglas']['vigencia_horas'],'ahora'=>gmdate('c')];
    }

    public function shown(int $id,int $opportunity): void
    {
        [$scope,$args]=$this->visitorScope($id,'cp_oportunidades');
        $q=$this->query('UPDATE cp_oportunidades SET mostrada_at=COALESCE(mostrada_at,UTC_TIMESTAMP()) WHERE id=? AND '.$scope.' AND campana_id=? AND consumida_at IS NULL',array_merge([$opportunity],$args,[$this->campaign]));
        if ($q->rowCount()) $this->event($id,'modal_abierto');
    }

    public function spin(int $id,string $request): array
    {
        return $this->transaction(function () use ($id,$request) {
            [$scope,$args]=$this->visitorScope($id,'p');
            $old=$this->one($this->prizeSelect().'WHERE '.$scope.' AND p.solicitud_id=?',array_merge($args,[$request]));
            if ($old) return $this->prizeData($old);
            $campaign=$this->one('SELECT activa FROM cp_campanas WHERE id=?',[$this->campaign]);
            if (!$campaign || !$campaign['activa']) throw new ChapiError('La campaña está pausada.',409,'CAMPAIGN_PAUSED');
            $op=$this->one('SELECT id,visitante_id FROM cp_oportunidades p WHERE '.$scope.' AND campana_id=? AND consumida_at IS NULL ORDER BY id LIMIT 1 FOR UPDATE',array_merge($args,[$this->campaign]));
            if (!$op) throw new ChapiError('No tienes giros disponibles. Invita amigos o visita el negocio para que validen tu premio.',409,'NO_OPPORTUNITY');
            $options=$this->query('SELECT p.*,n.nombre,n.direccion,n.whatsapp FROM cp_promociones p JOIN cp_negocios n ON n.id=p.negocio_id WHERE p.activa=1 AND n.activo=1 AND (p.cupo_total IS NULL OR p.entregados<p.cupo_total) ORDER BY p.negocio_id FOR UPDATE')->fetchAll();
            if (!$options) throw new ChapiError('Los aliados están preparando sus promociones. Conservamos tu oportunidad.',409,'NO_PROMOTIONS');
            $counts=$this->query('SELECT negocio_id,COUNT(*) AS total FROM cp_premios WHERE campana_id=? GROUP BY negocio_id',[$this->campaign])->fetchAll(PDO::FETCH_KEY_PAIR);
            $min=min(array_map(fn($p)=>(int)($counts[$p['negocio_id']]??0),$options));
            $balanced=array_values(array_filter($options,fn($p)=>(int)($counts[$p['negocio_id']]??0)===$min));
            // Un negocio con más ofertas no obtiene más probabilidades en la ruleta.
            $businessIds=array_values(array_unique(array_column($balanced,'negocio_id')));
            $businessId=$businessIds[random_int(0,count($businessIds)-1)];
            $offers=array_values(array_filter($balanced,fn($p)=>(int)$p['negocio_id']===(int)$businessId));
            $promo=$offers[random_int(0,count($offers)-1)];
            $code='PENDING-'.bin2hex(random_bytes(12));
            $now=gmdate('Y-m-d H:i:s');
            $expires=gmdate('Y-m-d H:i:s',time()+max(1,(int)$this->config['reglas']['vigencia_horas'])*3600);
            $this->query('INSERT INTO cp_premios(oportunidad_id,visitante_id,campana_id,negocio_id,promocion_id,codigo,solicitud_id,titulo,descripcion,condiciones,porcentaje,creado_at,vence_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',[$op['id'],$op['visitante_id'],$this->campaign,$promo['negocio_id'],$promo['id'],$code,$request,$promo['titulo'],$promo['descripcion'],$promo['condiciones'],$promo['porcentaje'],$now,$expires]);
            $prizeId=(int)$this->db->lastInsertId();
            $words=preg_split('/[^A-Z0-9]+/',strtoupper(iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$promo['nombre'])), -1, PREG_SPLIT_NO_EMPTY);
            $initials=count($words)>1 ? implode('',array_map(fn($word)=>substr($word,0,1),$words)) : substr($words[0]??'NEG',0,3);
            $code='CHAPI-'.substr($initials,0,6).'-'.str_pad((string)$prizeId,9,'0',STR_PAD_LEFT);
            $this->query('UPDATE cp_premios SET codigo=? WHERE id=?',[$code,$prizeId]);
            $this->query('INSERT INTO cp_premio_detalles(premio_id,negocio,direccion,whatsapp) VALUES (?,?,?,?)',[$prizeId,$promo['nombre'],$promo['direccion'],$promo['whatsapp']]);
            $this->query('UPDATE cp_oportunidades SET consumida_at=UTC_TIMESTAMP() WHERE id=?',[$op['id']]);
            $this->query('UPDATE cp_promociones SET entregados=entregados+1 WHERE id=?',[$promo['id']]);
            $this->event((int)$op['visitante_id'],'premio_emitido',$prizeId);
            return $this->prizeData($this->one($this->prizeSelect().'WHERE p.id=?',[$prizeId]));
        });
    }

    public function confirmReferral(int $id,string $ip,string $token): array
    {
        if (!$this->config['reglas']['referidos_habilitados']) throw new ChapiError('Las invitaciones están pausadas.',409);
        return $this->transaction(function () use ($id,$ip,$token) {
            $owner=$this->one('SELECT id,ip_hash FROM cp_visitantes WHERE referido_token=?',[$token]);
            if (!$owner || (int)$owner['id']===$id) throw new ChapiError('No puedes confirmar tu propio enlace.',409,'SELF_REFERRAL');
            if ($this->config['reglas']['referidos_ip_distinta']) {
                $sameIp=$this->one('SELECT id FROM cp_referidos WHERE campana_id=? AND propietario_id=? AND ip_hash=?',[$this->campaign,$owner['id'],$ip]);
                if ($owner['ip_hash']===$ip || $sameIp) throw new ChapiError('Esta red ya participa en la invitación. Una misma conexión no suma varias visitas.',409,'REFERRAL_NETWORK');
            }
            $exists=$this->one('SELECT id FROM cp_referidos WHERE campana_id=? AND visitante_id=?',[$this->campaign,$id]);
            if ($exists) throw new ChapiError('Tu visita ya fue contabilizada en esta campaña.',409,'REFERRAL_DUPLICATE');
            $this->query('INSERT INTO cp_referidos(campana_id,propietario_id,visitante_id,ip_hash) VALUES (?,?,?,?)',[$this->campaign,$owner['id'],$id,$ip]);
            $count=(int)$this->query('SELECT COUNT(*) FROM cp_referidos WHERE campana_id=? AND propietario_id=?',[$this->campaign,$owner['id']])->fetchColumn();
            $target=max(1,(int)$this->config['reglas']['amigos_requeridos']);
            if ($count % $target===0) $this->grant((int)$owner['id'],'referidos','referidos:'.$count);
            $this->event($id,'referido_confirmado',null,['propietario_id'=>(int)$owner['id']]);
            return ['message'=>'¡Gracias! Tu visita fue confirmada.'];
        });
    }

    public function track(int $id,string $type,?int $prizeId): array
    {
        $allowed=['modal_cerrado','codigo_copiado','compartir_whatsapp','reclamar_whatsapp'];
        if (!in_array($type,$allowed,true)) throw new ChapiError('Evento inválido.');
        $prize=null;
        if ($prizeId) {
            [$scope,$args]=$this->visitorScope($id,'p');
            $row=$this->one($this->prizeSelect().'WHERE p.id=? AND '.$scope,array_merge([$prizeId],$args));
            if (!$row) throw new ChapiError('Premio no encontrado.',404);
            $prize=$this->prizeData($row);
        }
        if ($type==='reclamar_whatsapp') {
            if (!$prize || $prize['estado']!=='activo') throw new ChapiError('El código no está activo.',409);
            if (!preg_match('/^[1-9][0-9]{7,14}$/D',$prize['whatsapp'])) throw new ChapiError('El aliado debe configurar su WhatsApp.',409);
        }
        $this->event($id,$type,$prizeId);
        if ($type==='reclamar_whatsapp') {
            return ['url'=>$prize['url_whatsapp'],'mensaje'=>$prize['mensaje_whatsapp']];
        }
        return [];
    }
}
