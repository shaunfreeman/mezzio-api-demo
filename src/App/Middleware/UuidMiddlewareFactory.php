<?php

declare(strict_types=1);

namespace Cms\App\Middleware;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

final class UuidMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): UuidMiddleware
    {
        return new UuidMiddleware(
            $container->get(ResponseInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
