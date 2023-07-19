<?php

namespace App\Exception;

use Doctrine\DBAL\Exception;
use Throwable;

class CardValidationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}