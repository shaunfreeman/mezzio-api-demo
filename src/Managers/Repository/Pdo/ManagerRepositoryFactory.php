<?php

declare(strict_types=1);

namespace Cms\Managers\Repository\Pdo;

use Psr\Container\ContainerInterface;
use Cms\App\Storage\PdoFactory;

final class ManagerRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ManagerRepository
    {
        $pdo = $container->get(PdoFactory::class);

        return new ManagerRepository($pdo);
    }
}
