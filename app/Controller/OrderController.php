<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\OrderException;
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
            $idOrder = $request->input('idOrder');
            $price = $this->orderService->totalPriceTotalByCodeOrder($idOrder);
            return $response
                ->json($price)
                ->withStatus(200);
        } catch(OrderException $orderException){
            return $response
                ->json(['message' => $orderException->getMessage(), 'data' => []])
                ->withStatus($orderException->getCode());
        }
         catch (Throwable $th) {
            return $response
                ->json(['message' => $th->getMessage()])
                ->withStatus(500);
        }
    }

    public function getQuantityOrderCustomer(RequestInterface $request, ResponseInterface $response)
    {
        try{
            $idCustomer = $request->route('idCustomer');
            $order = $this->orderService->quantityOrderByCodeCustomer($idCustomer);
            return $response
                ->json($order)
                ->withStatus(200);
        } catch(OrderException $orderException){
            return $response
                ->json(['message' => $orderException->getMessage(), 'data' => []])
                ->withStatus($orderException->getCode());
        } catch(Throwable $th){
            return $response
                ->json(['message' => $th->getMessage()])
                ->withStatus(500);
        }
    }
}
