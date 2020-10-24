<?php

declare(strict_types=1);

namespace Cms\Users\Handler;

use Cms\Users\Repository\UserRepositoryInterface;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class UserDeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserDeleteHandler
    {
        return new UserDeleteHandler(
            $container->get(UserRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
