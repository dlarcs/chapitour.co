<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/ReminderMessage.php';
require_once __DIR__.'/ReminderDeliveryError.php';

class ChapitourReminderSmtp extends \PHPMailer\PHPMailer\SMTP
{
    public bool $dataStarted = false;
    public function data($msg_data)
    {
        $this->dataStarted = true;
        return parent::data($msg_data);
    }
}

final class ChapitourReminderMailer
{
    private array $config;
    private $smtpFactory;
    public function __construct(array $config, ?callable $smtpFactory=null)
    {
        foreach (['host','username','password','from_email','from_name','encryption'] as $key) {
            if (!is_string($config[$key]??null) || trim($config[$key])==='' || preg_match('/[\r\n\x00]/', $config[$key])) { throw new RuntimeException('MAIL_CONFIGURATION'); }
        }
        // Only an SMTP hostname, never a URI, transport prefix, path or host list.
        if (!preg_match('/\A[a-z0-9](?:[a-z0-9.-]*[a-z0-9])?\z/i', $config['host'])
            || !filter_var($config['from_email'], FILTER_VALIDATE_EMAIL)
            || !in_array($config['encryption'], ['ssl','tls'], true)
            || !filter_var($config['port']??null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1,'max_range'=>65535]])) {
            throw new RuntimeException('MAIL_CONFIGURATION');
        }
        $this->config = $config;
        $this->smtpFactory = $smtpFactory ?? static fn() => new ChapitourReminderSmtp();
    }
    public function __invoke(array $prize, string $token): void
    {
        $this->deliver($prize['cliente_email'], $prize['cliente_nombre'], $token, ChapitourReminderMessage::build($prize));
    }
    public function sendTest(): void
    {
        $this->deliver($this->config['from_email'], $this->config['from_name'], bin2hex(random_bytes(16)), [
            'subject'=>'Prueba de recordatorios de Chapitour',
            'text'=>"Esta es una prueba de envío desde admin@chapitour.co.\n\nEl correo está listo para enviar recordatorios cuando una promoción entre en sus últimas 24 horas. Esta prueba no corresponde a un premio y no activa los envíos automáticos.\n\nChapitour · Vive Chapinero\n",
            'html'=>'<html lang="es"><body style="font-family:Arial,sans-serif;line-height:1.7"><h1>Prueba de correo de Chapitour</h1><p>Esta es una prueba de envío desde <strong>admin@chapitour.co</strong>.</p><p>El correo está listo para enviar recordatorios cuando una promoción entre en sus últimas 24 horas.</p><p>Esta prueba no corresponde a un premio y no activa los envíos automáticos.</p><p>Chapitour · Vive Chapinero</p></body></html>',
        ]);
    }
    private function deliver(string $email, string $name, string $token, array $message): void
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $smtp = ($this->smtpFactory)();
        if (!$smtp instanceof ChapitourReminderSmtp) { throw new LogicException('SMTP_FACTORY'); }
        $mail->setSMTPInstance($smtp);
        $mail->isSMTP();
        $mail->Host = $this->config['host'];
        $mail->Port = (int)$this->config['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->config['username'];
        $mail->Password = $this->config['password'];
        $mail->SMTPSecure = $this->config['encryption'];
        $mail->Timeout = 20;
        $smtp->Timelimit = 30;
        $mail->SMTPDebug = 0;
        $mail->SMTPOptions = ['ssl'=>['verify_peer'=>true,'verify_peer_name'=>true,'allow_self_signed'=>false]];
        try {
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($email, $name);
            $mail->MessageID = '<chapitour-expiry-'.$token.'@'.substr(strrchr($this->config['from_email'], '@'), 1).'>';
            $mail->isHTML(true);
            $mail->Subject = $message['subject'];
            $mail->Body = $message['html'];
            $mail->AltBody = $message['text'];
            if (!$mail->send()) { throw new RuntimeException('SMTP_SEND_FAILED'); }
        } catch (Throwable $e) {
            // Once DATA may have reached the server, never retry automatically: SMTP
            // cannot guarantee whether an interrupted delivery was already accepted.
            throw new ChapitourReminderDeliveryError(!$smtp->dataStarted);
        } finally { $mail->smtpClose(); }
    }
}
