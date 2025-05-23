<?php

declare(strict_types=1);

namespace Middleware\Exception;

use Exception;

class MiddlewareException extends Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
