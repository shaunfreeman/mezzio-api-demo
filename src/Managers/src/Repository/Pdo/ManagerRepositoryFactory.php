<?php

declare(strict_types=1);

namespace Managers\Repository\Pdo;

use Psr\Container\ContainerInterface;
use App\Storage\PdoFactory;

final class ManagerRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ManagerRepository
    {
        $pdo = $container->get(PdoFactory::class);

        return new ManagerRepository($pdo);
    }
}
