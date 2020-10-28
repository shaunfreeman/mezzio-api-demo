<?php

declare(strict_types=1);

namespace Core\Authentication\Jwt;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

final class JwtMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): JwtMiddleware
    {
        $jwt = new Jwt(
            JwtConfig::formArray($container->get('config')['jwt'] ?? [])
        );

        return new JwtMiddleware(
            $container->get(ProblemDetailsResponseFactory::class),
            $jwt
        );
    }
}
