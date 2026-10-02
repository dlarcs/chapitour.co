<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

final class ChapitourGoogleAuth
{
    private $clientId;
    private $keyFetcher;
    public function __construct(?string $clientId=null, ?callable $keyFetcher=null) {
        if ($clientId===null) {
            $config=[]; $path=__DIR__.'/../config/google.local.php';
            if (is_file($path)) { $config=require $path; }
            $env=getenv('CHAPITOUR_GOOGLE_CLIENT_ID');
            $clientId=$env!==false?$env:($config['client_id']??'');
        }
        $this->clientId=is_string($clientId) && preg_match('/^[a-zA-Z0-9_-]+\.apps\.googleusercontent\.com$/',$clientId)?$clientId:'';
        $this->keyFetcher=$keyFetcher;
    }
    public function ready(): bool { return $this->clientId!=='' && is_file(__DIR__.'/../vendor/autoload.php') && extension_loaded('openssl') && extension_loaded('curl'); }
    public function settings(): array {
        if (!$this->ready()) { return ['enabled'=>false,'client_id'=>'','nonce'=>'']; }
        if (empty($_SESSION['google_nonce']) || (int)($_SESSION['google_nonce_at']??0)<time()-600) {
            $_SESSION['google_nonce']=bin2hex(random_bytes(32));$_SESSION['google_nonce_at']=time();
        }
        return ['enabled'=>true,'client_id'=>$this->clientId,'nonce'=>$_SESSION['google_nonce']];
    }
    private function keys(bool $refresh=false): array {
        if ($this->keyFetcher) { return ($this->keyFetcher)(); }
        $cache=__DIR__.'/../var/google-certs.json';
        if (!$refresh && is_file($cache)) {
            $saved=json_decode((string)file_get_contents($cache),true);
            if (is_array($saved) && ($saved['expires']??0)>time() && isset($saved['keys']['keys'])) { return $saved['keys']; }
        }
        $maxAge=300; $ch=curl_init('https://www.googleapis.com/oauth2/v3/certs');
        curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>10,
            CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2,
            CURLOPT_HEADERFUNCTION=>static function($ch,$line) use (&$maxAge) {
                if (preg_match('/^cache-control:.*max-age=(\d+)/i',$line,$m)) { $maxAge=min(86400,(int)$m[1]); }
                return strlen($line);
            }]);
        $body=curl_exec($ch);$status=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
        $keys=is_string($body) && strlen($body)<65536?json_decode($body,true):null;
        if ($status!==200 || !is_array($keys) || empty($keys['keys'])) { throw new PanelError('No pudimos contactar con Google. Intenta de nuevo.',503); }
        if (is_writable(dirname($cache))) { file_put_contents($cache,json_encode(['expires'=>time()+$maxAge,'keys'=>$keys]),LOCK_EX);chmod($cache,0600); }
        return $keys;
    }
    public function verify(string $credential): array {
        if (!$this->ready()) { throw new PanelError('El acceso con Google todavía requiere configuración del administrador.',503); }
        if (strlen($credential)>12000 || empty($_SESSION['google_nonce']) || (int)($_SESSION['google_nonce_at']??0)<time()-600) { throw new PanelError('Vuelve a abrir el acceso con Google e intenta de nuevo.',401); }
        require_once __DIR__.'/../vendor/autoload.php';
        try {
            $keys=$this->keys();
            try { $claims=(array)\Firebase\JWT\JWT::decode($credential,\Firebase\JWT\JWK::parseKeySet($keys,'RS256')); }
            catch (UnexpectedValueException $e) {
                // Google rotates signing keys. Refresh once; never trust a key supplied by the browser.
                $claims=(array)\Firebase\JWT\JWT::decode($credential,\Firebase\JWT\JWK::parseKeySet($this->keys(true),'RS256'));
            }
            if (($claims['aud']??null)!==$this->clientId || !in_array($claims['iss']??null,['accounts.google.com','https://accounts.google.com'],true)
                || ($claims['exp']??0)<=time() || ($claims['email_verified']??false)!==true
                || !is_string($claims['nonce']??null) || !hash_equals($_SESSION['google_nonce'],$claims['nonce'])
                || !is_string($claims['sub']??null) || $claims['sub']==='' || strlen($claims['sub'])>255
                || !is_string($claims['email']??null) || !filter_var($claims['email'],FILTER_VALIDATE_EMAIL) || strlen($claims['email'])>150) {
                throw new UnexpectedValueException('Identidad no válida.');
            }
            return ['subject_hash'=>hash('sha256',$claims['sub']),'email'=>mb_strtolower($claims['email']),
                'name'=>mb_substr(trim((string)($claims['name']??''))?:explode('@',$claims['email'])[0],0,80)];
        } catch (PanelError $e) { throw $e; }
        catch (Throwable $e) { throw new PanelError('No pudimos verificar tu cuenta de Google. Vuelve a intentarlo.',401); }
    }
}
