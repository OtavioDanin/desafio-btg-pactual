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

    public function totalPriceTotalByCodeOrder(int $idOrder): array
    {
        $document = $this->clientMongo->find('desafio-btg-pactual.order', ['codigoPedido' => $idOrder]);
        if (empty($document)) {
            throw new OrderException('Order not found', 404);
        }
        return $this->calculateTotalPrice($document);
    }

    private function calculateTotalPrice(array $document)
    {
        $items = 0;
        foreach ($document as $data) {
            $items = $data->itens;
        }
        $totalPrice = 0;
        foreach ($items as $item) {
            $totalPrice += $item->preco * $item->quantidade;
        }
        return ['totalPrice' => (int) number_format($totalPrice, 2), 'items' => $items];
    }

    public function quantityOrderByCodeCustomer(int $idCustomer)
    {
        $document = $this->getOrdersByCustumer($idCustomer);
        return ['quantity' => count($document), 'orders' => $this->getOrders($document)];
    }

    private function getOrders(array $documentOrders)
    {
        $orders = [];
        foreach ($documentOrders as $document) {
            $orders[] = $document->codigoPedido;
        }
        return $orders;
    }

    public function allOrdersByCustomer(int $idCustomer)
    {
        return $this->getOrdersByCustumer($idCustomer);
    }

    private function getOrdersByCustumer($idCustomer): array
    {
        $documents = $this->clientMongo->find('desafio-btg-pactual.order', ['codigoCliente' => $idCustomer]);
        if (empty($documents)) {
            throw new OrderException('Orders not found for customer .' . $idCustomer, 404);
        }
        $docs = [];
        foreach ($documents as $document) {
            $docs[] = $document->codigoPedido;
        }
        return ['codes' => array_values($docs)];
    }
    // $date = new DateTimeImmutable("", new DateTimeZone('America/Sao_Paulo'));
    // echo $date->format('Y-m-d H:i:s.v.u (P)');
}
