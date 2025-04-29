<?php

declare(strict_types=1);

namespace Middleware\Validator;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Validator\Digits;
use Hyperf\HttpServer\Contract\ResponseInterface as HyperfResponseInterface;
use Throwable;

class InputOrderValidator implements MiddlewareInterface
{
    public function __construct(protected HyperfResponseInterface $response) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = $request->getParsedBody();
            $this->setValidation($body);
            return $handler->handle($request);
        } catch (Exception $ex) {
            return $this->response->json(['message' => $ex->getMessage()])->withStatus(422);
        } catch (Throwable $th) {
            return $this->response->json(['message' => $th->getMessage()])->withStatus(500);
        }
    }

    private function setValidation(array $body): void
    {
        if (empty($body)) {
            throw new Exception('Empty body in request');
        }
        if (!array_key_exists('idOrder', $body)) {
            throw new Exception('Undefide key idOrder in Body');
        }
        $validator = new Digits();
        if (!$validator->isValid($body['idOrder'])) {
            throw new Exception('Only number for idOrder');
        }
    }
}
