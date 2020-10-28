<?php

declare(strict_types=1);

namespace Users\Repository\Pdo;

use Psr\Container\ContainerInterface;
use Core\Storage\PdoFactory;

final class UserRepositoryFactory
{
    public function __invoke(ContainerInterface $container): UserRepository
    {
        return new UserRepository(
            $container->get(PdoFactory::class)
        );
    }
}
