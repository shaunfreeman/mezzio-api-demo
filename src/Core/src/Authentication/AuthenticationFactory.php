<?php

declare(strict_types=1);

namespace Core\Authentication;

use Psr\Container\ContainerInterface;
use Users\Repository\UserRepositoryInterface;

final class AuthenticationFactory
{
    public function __invoke(ContainerInterface $container): Authentication
    {
        return new Authentication(
            $container->get(UserRepositoryInterface::class)
        );
    }
}
