<?php

declare(strict_types=1);

namespace Cms\Managers\Handler;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Cms\Managers\Repository\ManagerRepositoryInterface;

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
