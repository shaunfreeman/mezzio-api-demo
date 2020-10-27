<?php

declare(strict_types=1);

namespace Orders\Repository\Pdo;

use Psr\Container\ContainerInterface;
use App\Storage\PdoFactory;

final class OrderRepositoryFactory
{
    public function __invoke(ContainerInterface $container): OrderRepository
    {
        $pdo = $container->get(PdoFactory::class);

        return new OrderRepository($pdo);
    }
}
