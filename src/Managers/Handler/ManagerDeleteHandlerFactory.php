<?php

declare(strict_types=1);

namespace Cms\Managers\Handler;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Cms\Managers\Repository\ManagerRepositoryInterface;

final class ManagerDeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): ManagerDeleteHandler
    {
        return new ManagerDeleteHandler(
            $container->get(ManagerRepositoryInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
