<?php

declare(strict_types=1);


namespace Cms\Users\Middleware;


use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

final class UserInputFilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): UserInputFilterMiddleware
    {
        return new UserInputFilterMiddleware(
            $container->get(ResponseInterface::class),
            $container->get(ProblemDetailsResponseFactory::class)
        );
    }
}
