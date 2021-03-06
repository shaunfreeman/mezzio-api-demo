<?php

declare(strict_types=1);

namespace Users\Middleware;

use Users\Repository\UserRepositoryInterface;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class UserInputFilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): UserInputFilterMiddleware
    {
        return new UserInputFilterMiddleware(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(UserRepositoryInterface::class)
        );
    }
}
