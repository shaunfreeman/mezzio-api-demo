<?php

declare(strict_types=1);

namespace Managers\Handler;

use Psr\Container\ContainerInterface;
use Managers\Repository\ManagerRepositoryInterface;

final class ManagerCollectionHandlerFactory
{
    public function __invoke(ContainerInterface $container): ManagerCollectionHandler
    {
        $repository = $container->get(ManagerRepositoryInterface::class);

        return new ManagerCollectionHandler($repository);
    }
}
