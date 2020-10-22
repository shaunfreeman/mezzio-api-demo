<?php

declare(strict_types=1);

namespace Cms\Managers\Handler;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Cms\Managers\Repository\ManagerRepositoryInterface;

final class ManagerCreateHandlerFactory
{
    public function __invoke(ContainerInterface $container): ManagerCreateHandler
    {
        return new ManagerCreateHandler(
            $container->get(ManagerRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
