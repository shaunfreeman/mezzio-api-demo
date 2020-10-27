<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class UuidMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): UuidMiddleware
    {
        return new UuidMiddleware(
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
