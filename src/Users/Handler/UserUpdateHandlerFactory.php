<?php

declare(strict_types=1);

namespace Cms\Users\Handler;

use Cms\Users\Repository\UserRepositoryInterface;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class UserUpdateHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserUpdateHandler
    {
        return new UserUpdateHandler(
            $container->get(UserRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
