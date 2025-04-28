<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Service\OrderService;
use Throwable;

class OrderController extends AbstractController
{
    /**
     * @var OrderService
     */
    public function __construct(private OrderService $orderService) {}

    public function getTotalPriceOrder(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $idOrder = $request->route('idOrder');
            $price = $this->orderService->getTotalPriceOrder($idOrder);
            return $response
                ->json($price)
                ->withStatus(200);
        } catch (Throwable $th) {
            return $response
                ->json(['message' => $th->getMessage()])
                ->withStatus(500);
        }
    }
}
