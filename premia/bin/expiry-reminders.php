<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
ini_set('display_errors','0');
$mode=$argv[1]??'--dry-run';
if (count($argv)>2 || !in_array($mode,['--dry-run','--install','--check','--test','--send'],true)) {
    fwrite(STDERR,"Uso: php expiry-reminders.php [--dry-run|--install|--check|--test|--send]\n"); exit(1);
}
try {
    require __DIR__.'/../lib/ExpiryReminders.php';
    if (in_array($mode,['--check','--test','--send'],true)) {
        $config=require __DIR__.'/../config/mail.php';
        require __DIR__.'/../lib/ReminderMailer.php';
        $sender=new ChapitourReminderMailer($config);
        if ($mode==='--test') {
            $sender->sendTest();
            echo json_encode(['test_accepted_by_smtp'=>true,'inbox_delivery_verified'=>false])."\n";
            exit(0);
        }
        if (!$config['enabled']) { throw new RuntimeException('MAIL_DISABLED'); }
    }
    $db=require __DIR__.'/../config/database.php';
    $job=new ChapitourExpiryReminders($db,$sender??static function(){throw new LogicException('DRY_RUN_ONLY');});
    if ($mode==='--install') { $job->install(); $result=['installed'=>$job->ready()]; }
    elseif ($mode==='--dry-run') { $result=$job->preview(); }
    elseif ($mode==='--check') { $result=['configuration_valid'=>true,'schema_ready'=>$job->ready(),'smtp_delivery_verified'=>false]; }
    else { $result=$job->run(); }
    echo json_encode($result,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)."\n";
    exit(!empty($result['needs_review']) || !empty($result['retry']) || ($mode==='--check'&&!$result['schema_ready']) ? 2 : 0);
} catch (Throwable $e) {
    // Never log SMTP credentials, recipient addresses, reward codes or SQL errors.
    $allowed=['MAIL_CONFIGURATION','MAIL_DISABLED','REMINDER_SCHEMA_MISSING'];
    $code=in_array($e->getMessage(),$allowed,true)?$e->getMessage():'REMINDER_JOB_FAILED';
    fwrite(STDERR,json_encode(['error'=>$code])."\n"); exit(1);
}
