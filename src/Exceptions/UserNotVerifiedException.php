<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UserNotVerifiedException extends Exception
{
    public function __construct(string $message = 'Account not verified', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
