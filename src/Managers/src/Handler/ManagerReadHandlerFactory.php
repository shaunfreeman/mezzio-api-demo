<?php

declare(strict_types=1);

namespace Managers\Handler;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Managers\Repository\ManagerRepositoryInterface;

final class ManagerReadHandlerFactory
{
    public function __invoke(ContainerInterface $container): ManagerReadHandler
    {
        return new ManagerReadHandler(
            $container->get(ManagerRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
