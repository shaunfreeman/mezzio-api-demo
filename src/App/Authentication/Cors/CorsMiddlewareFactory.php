<?php

declare(strict_types=1);

namespace Cms\App\Authentication\Cors;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

final class CorsMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $corsConfig = $container->get('config')['cors'] ?? [];

        return new CorsMiddleware(
            $container->get(ResponseInterface::class),
            $container->get(ProblemDetailsResponseFactory::class),
            $corsConfig
        );
    }
}
