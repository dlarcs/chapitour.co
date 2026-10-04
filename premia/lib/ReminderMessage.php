<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

final class ChapitourReminderMessage
{
    public static function build(array $prize): array
    {
        $name = trim($prize['cliente_nombre']) ?: 'explorador de Chapinero';
        $expires = (new DateTimeImmutable($prize['vence_at'], new DateTimeZone('UTC')))
            ->setTimezone(new DateTimeZone('America/Bogota'))->format('d/m/Y \a \l\a\s H:i');
        $url = 'https://chapitour.co/#mis-promociones';
        $text = "Hola, $name:\n\nTu promoción de Chapitour está en sus últimas 24 horas. ¡Todavía estás a tiempo de disfrutarla!\n\n"
            ."Negocio: {$prize['negocio_nombre']}\nPromoción: {$prize['descripcion']}\nCódigo: {$prize['codigo']}\n"
            ."Vence el $expires (hora de Bogotá).\n"
            .(trim($prize['condiciones'])!=='' ? "Condiciones: {$prize['condiciones']}\n" : '')
            ."\nConsulta tu promoción y el contacto del negocio: $url\nInicia sesión con la cuenta en la que guardaste tu premio.\n\n"
            ."El negocio confirma la redención. Si ya utilizaste la promoción, puedes ignorar este mensaje.\n\nChapitour · Vive Chapinero\n";
        $esc = static fn($s) => htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $html = '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
            .'<body style="margin:0;background:#f4f1f7;color:#24202b;font-family:Arial,sans-serif"><table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td style="padding:24px 12px">'
            .'<table role="presentation" align="center" width="100%" style="max-width:560px;background:#ffffff;border-radius:16px" cellspacing="0" cellpadding="0"><tr><td style="padding:30px 24px">'
            .'<p style="font-size:13px;font-weight:bold;letter-spacing:2px;color:#b90075">CHAPITOUR.CO</p><h1 style="font-size:28px;line-height:1.2">¡Que no se te pase tu premio!</h1>'
            .'<p>Hola, '.$esc($name).'.</p><p style="line-height:1.7">Tu promoción está en sus <strong>últimas 24 horas</strong>. Todavía estás a tiempo de disfrutarla.</p>'
            .'<div style="background:#f6f1f8;border-radius:12px;padding:20px;line-height:1.7;overflow-wrap:anywhere"><strong>'.$esc($prize['negocio_nombre']).'</strong><p>'.$esc($prize['descripcion']).'</p>'
            .'<p>Tu código<br><strong style="font-size:22px;color:#870059">'.$esc($prize['codigo']).'</strong></p><p><strong>Vence el '.$esc($expires).'</strong><br>Hora de Bogotá</p>'
            .(trim($prize['condiciones'])!==''?'<p style="font-size:13px">'.$esc($prize['condiciones']).'</p>':'').'</div>'
            .'<p style="margin:28px 0"><a href="'.$url.'" style="display:inline-block;background:#b90075;color:#fff;text-decoration:none;padding:15px 22px;border-radius:9px;font-weight:bold">Ver mi promoción</a></p>'
            .'<p style="font-size:13px;line-height:1.7">Inicia sesión con la cuenta en la que guardaste tu premio para consultar el contacto del negocio.</p>'
            .'<p style="font-size:12px;line-height:1.7;color:#655c6b">El negocio confirma la redención. Si ya utilizaste la promoción, puedes ignorar este mensaje.</p>'
            .'<p style="font-size:12px;color:#655c6b">Chapitour · Vive Chapinero</p></td></tr></table></td></tr></table></body></html>';
        return ['subject'=>'Tu promoción de Chapitour vence pronto', 'text'=>$text, 'html'=>$html];
    }
}
