<?php

declare(strict_types=1);

namespace Cms\Users\Middleware;

use Cms\Users\Entity\UserDto;
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
        $dto            = new UserDto(
            $cleanData['name'],
            $cleanData['email'],
            $cleanData['password'],
            $cleanData['role']
        );
        $request        = $request->withAttribute(UserEntity::class, $dto);

        return $handler->handle($request);
    }
}
