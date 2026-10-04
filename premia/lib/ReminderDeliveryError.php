<?php
declare(strict_types=1);
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) { http_response_code(404); exit; }

final class ChapitourReminderDeliveryError extends RuntimeException
{
    public bool $safeToRetry;
    public function __construct(bool $safeToRetry)
    {
        $this->safeToRetry = $safeToRetry;
        parent::__construct($safeToRetry ? 'SMTP_BEFORE_DATA' : 'SMTP_UNCERTAIN');
    }
}
