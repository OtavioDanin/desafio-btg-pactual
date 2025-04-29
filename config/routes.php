<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;
use Middleware\Validator\InputOrderValidator;


Router::addGroup('/orders', function () {
    Router::post('/price', [\App\Controller\OrderController::class, 'getTotalPriceOrder'], ['middleware' => [InputOrderValidator::class]]);
    Router::get('/quantity/customer/{idCustomer}', [\App\Controller\OrderController::class, 'getQuantityOrderCustomer']);
});
