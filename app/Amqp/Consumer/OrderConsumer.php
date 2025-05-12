<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Type;
use PhpAmqpLib\Message\AMQPMessage;
use App\Task\MongoTask;
use App\Trait\OrderTrait;

#[Consumer(exchange: 'order', routingKey: 'order-key', queue: 'order-queue', name: "OrderConsumer", nums: 1)]
class OrderConsumer extends ConsumerMessage
{
    use OrderTrait;

    protected string|Type $type = Type::DIRECT;

    public function __construct(protected MongoTask $clientMongo) {}

    public function consumeMessage($data, AMQPMessage $message): Result
    {
        $document = $this->toArray($message);
        $mongoClient = new MongoTask();
        $hasSave = $mongoClient->save('desafio-btg-pactual.order', $document);
        if ($hasSave) {
            return Result::ACK;
        }
        return Result::REQUEUE;
    }

    public function isEnable(): bool
    {
        return true;
    }
}
