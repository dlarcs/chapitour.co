<?php
declare(strict_types=1);
if (!defined('CHAPI_PROMOS')) { http_response_code(404); exit; }
class ChapiError extends RuntimeException
{
    public int $status;
    public string $errorCode;
    public function __construct(string $message, int $status = 400, string $errorCode = 'INVALID_REQUEST')
    {
        parent::__construct($message);
        $this->status = $status;
        $this->errorCode = $errorCode;
    }
}
