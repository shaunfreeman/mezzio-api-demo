<?php

declare(strict_types=1);

namespace Cms\App\Authentication;

use Psr\Container\ContainerInterface;
use Cms\Users\Repository\UserRepositoryInterface;

final class AuthenticationFactory
{
    public function __invoke(ContainerInterface $container): Authentication
    {
        return new Authentication(
            $container->get(UserRepositoryInterface::class)
        );
    }
}
