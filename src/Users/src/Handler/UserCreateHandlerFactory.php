<?php

declare(strict_types=1);

namespace Users\Handler;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Users\Repository\UserRepositoryInterface;

final class UserCreateHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserCreateHandler
    {
        return new UserCreateHandler(
            $container->get(UserRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
