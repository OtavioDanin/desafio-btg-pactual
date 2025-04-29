<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class OrderException extends Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
