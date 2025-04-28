<?php

declare(strict_types=1);

namespace App\Service;

use App\Task\MongoTask;
use Hyperf\Context\ApplicationContext;

class OrderService
{
    public function getTotalPriceOrder(string $idOrder): array
    {
        $client = ApplicationContext::getContainer()->get(MongoTask::class);
        $cursor = $client->query('desafio-btg-pactual.order', ['codigoPedido' => (int)$idOrder]);

        foreach($cursor as $data){
            $items = $data->itens;
        }
        $totalPrice = 0;
        for($i = 0; $i < count($items) ; $i++ ){
            $totalPrice += $items[$i]->preco * $items[$i]->quantidade;
        }
        return ['totalPrice' => (int)number_format($totalPrice, 2), 'items' => $items];
    }
}
