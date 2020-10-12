<?php

declare(strict_types=1);

namespace Cms\App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new JsonResponse([
            'welcome'   => 'Mezzio API Demo',
            'jwt'       => $request->getAttribute('token')
        ]);
    }
}
