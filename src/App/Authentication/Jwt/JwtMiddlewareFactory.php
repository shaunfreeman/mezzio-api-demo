<?php

declare(strict_types=1);

namespace Cms\App\Authentication\Jwt;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

final class JwtMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): JwtMiddleware
    {
        $jwt = new Jwt(
            JwtConfig::formArray($container->get('config')['jwt'] ?? [])
        );

        return new JwtMiddleware(
            $container->get(ResponseInterface::class),
            $container->get(ProblemDetailsResponseFactory::class),
            $jwt
        );
    }
}
