<?php

declare(strict_types=1);

namespace Users\Handler;

use Psr\Container\ContainerInterface;
use Users\Repository\UserRepositoryInterface;

final class UserCollectionHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserCollectionHandler
    {
        return new UserCollectionHandler(
            $container->get(UserRepositoryInterface::class)
        );
    }
}
