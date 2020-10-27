<?php

declare(strict_types=1);

namespace Managers\Handler;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Managers\Repository\ManagerRepositoryInterface;

final class ManagerUpdateHandlerFactory
{
    public function __invoke(ContainerInterface $container): ManagerUpdateHandler
    {
        return new ManagerUpdateHandler(
            $container->get(ManagerRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
