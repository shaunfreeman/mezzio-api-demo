<?php

declare(strict_types=1);

namespace Users\Handler;

use Users\Repository\UserRepositoryInterface;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class UserReadHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserReadHandler
    {
        return new UserReadHandler(
            $container->get(UserRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
