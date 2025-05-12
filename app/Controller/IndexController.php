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

namespace App\Controller;

use Throwable;

class IndexController extends AbstractController
{
    public function index()
    {
        try {
            return $this->response
                ->json(['message' => 'success'])
                ->withStatus(200);
        } catch (Throwable $th) {
            return $this->response
                ->json(['message' => $th->getMessage()])
                ->withStatus(500);
        }
    }
}
