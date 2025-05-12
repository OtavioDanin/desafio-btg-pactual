<?php

declare(strict_types=1);

namespace App\Trait;

use PhpAmqpLib\Message\AMQPMessage;

trait OrderTrait
{
    public function toArray(AMQPMessage $message): array
    {
        return json_decode($message->getBody(), true);
    }
}
