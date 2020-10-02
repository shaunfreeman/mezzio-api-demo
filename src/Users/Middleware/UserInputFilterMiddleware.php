<?php

declare(strict_types=1);

namespace Cms\Users\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\Users\Entity\UserEntity;
use Cms\Users\Filter\UserInputFilter;

final class UserInputFilterMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $requestBody    = $request->getParsedBody();
        $cleanData      = (new UserInputFilter())->filter($requestBody);
        $request        = $request->withAttribute(UserEntity::class, $cleanData);

        return $handler->handle($request);
    }
}
