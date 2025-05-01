<?php

declare(strict_types=1);

namespace Middleware\Validator;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HyperfResponseInterface;
use Middleware\Exception\MiddlewareException;
use Throwable;

class InputCustomerValidator implements MiddlewareInterface
{
    public function __construct(protected HyperfResponseInterface $response) {}


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = $request->getParsedBody();
            $this->setValidation($body);
            return $handler->handle($request);
        } catch (MiddlewareException $midEx) {
            return $this->response->json(['message' => $midEx->getMessage()])->withStatus(422);
        } catch (Throwable $th) {
            return $this->response->json(['message' => $th->getMessage()])->withStatus(403);
        }
    }

    private function setValidation(null|array $body)
    {
        if (empty($body)) {
            throw new MiddlewareException('Empty body in request.');
        }
        if (!array_key_exists('idCustomer', $body)) {
            throw new MiddlewareException('Undefide key idCustomer in Body.');
        }
        if (!is_int($body['idCustomer'])) {
            throw new MiddlewareException('Only integer values are allowed, ' . gettype($body['idCustomer']) . ' found.');
        }
    }
}
