<?php

declare(strict_types=1);

namespace App\Service;

use App\Task\MongoTask;
use Hyperf\Context\ApplicationContext;
use App\Exception\OrderException;

class OrderService
{
    protected MongoTask $clientMongo;

    public function __construct()
    {
        $this->clientMongo = ApplicationContext::getContainer()->get(MongoTask::class);
    }
    public function totalPriceTotalByCodeOrder(string $idOrder): array
    {
        $document = $this->clientMongo->find('desafio-btg-pactual.order', ['codigoPedido' => (int)$idOrder]);
        if (empty($document)) {
            throw new OrderException('Order ' . $idOrder . ' not found', 404);
        }
        return $this->calculateTotalPrice($document);
    }

    private function calculateTotalPrice($document)
    {
        $items = 0;
        foreach ($document as $data) {
            $items = $data->itens;
        }
        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item->preco * $item->quantidade;
        }
        return ['totalPrice' => (int)number_format($totalPrice, 2), 'items' => $items];
    }

    public function quantityOrderByCodeCustomer($idClient)
    {
        $document = $this->clientMongo->find('desafio-btg-pactual.order', ['codigoCliente' => (int)$idClient]);
        if (empty($document)) {
            throw new OrderException('Customer code ' . $idClient . ' not found', 404);
        }
        return ['quantity' => count($document), 'orders' =>$this->getOrders($document)];
    }

    public function getOrders(array $documentOrders)
    {
        $orders = [];
        foreach ($documentOrders as $document) {
            $orders[] = $document->codigoPedido;
        }
        return $orders;
    }
}
