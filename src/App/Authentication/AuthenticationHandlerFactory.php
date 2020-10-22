<?php

declare(strict_types=1);

namespace Cms\App\Authentication;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Cms\App\Authentication\Jwt\Jwt;
use Cms\App\Authentication\Jwt\JwtConfig;

final class AuthenticationHandlerFactory
{
    public function __invoke(ContainerInterface $container): AuthenticationHandler
    {
        $jwt = new Jwt(
            JwtConfig::formArray($container->get('config')['jwt'] ?? [])
        );

        return new AuthenticationHandler(
            $container->get(AuthenticationInterface::class),
            $jwt,
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
