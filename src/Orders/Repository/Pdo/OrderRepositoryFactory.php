<?php

declare(strict_types=1);

namespace Cms\Orders\Repository\Pdo;

use Psr\Container\ContainerInterface;
use Cms\App\Storage\PdoFactory;

final class OrderRepositoryFactory
{
    public function __invoke(ContainerInterface $container): OrderRepository
    {
        $pdo = $container->get(PdoFactory::class);

        return new OrderRepository($pdo);
    }
}

