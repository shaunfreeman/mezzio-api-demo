<?php

declare(strict_types=1);

namespace Cms\Users\Repository\Pdo;

use Psr\Container\ContainerInterface;
use Cms\App\Storage\PdoFactory;

final class UserRepositoryFactory
{
    public function __invoke(ContainerInterface $container): UserRepository
    {
        $pdo = $container->get(PdoFactory::class);
        return new UserRepository($pdo);
    }
}

