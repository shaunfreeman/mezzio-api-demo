<?php

declare(strict_types=1);

namespace Cms\Managers\Middleware;

use Cms\Managers\Repository\ManagerRepositoryInterface;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class ManagerInputFilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ManagerInputFilterMiddleware
    {
        return new ManagerInputFilterMiddleware(
            $container->get(ProblemDetailsResponseFactory::class),
            $container->get(ManagerRepositoryInterface::class)
        );
    }
}
