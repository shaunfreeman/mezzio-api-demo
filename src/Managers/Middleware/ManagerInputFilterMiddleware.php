<?php

declare(strict_types=1);

namespace Cms\Managers\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\Managers\Entity\ManagerDto;
use Cms\Managers\Entity\ManagerEntity;
use Cms\Managers\Filter\ManagerInputFilter;

final class ManagerInputFilterMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $requestBody    = $request->getParsedBody();
        $cleanData      = (new ManagerInputFilter())->filter($requestBody);
        $dto            = new ManagerDto($cleanData['name'], $cleanData['email']);
        $request        = $request->withAttribute(ManagerEntity::class, $dto);

        return $handler->handle($request);
    }
}
