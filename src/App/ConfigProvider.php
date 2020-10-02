<?php

declare(strict_types=1);

namespace Cms\App;

use Mezzio\Application;

/**
 * The configuration provider for the App module
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    private function getDependencies() : array
    {
        return [
            'delegators' => [
                Application::class => [
                    RoutesDelegator::class,
                ],
            ],
            'invokables' => [
                Handler\PingHandler::class      => Handler\PingHandler::class,
                Handler\HomePageHandler::class  => Handler\HomePageHandler::class,
                Handler\UploadHandler::class    => Handler\UploadHandler::class,
            ],
            'factories' => [
                Authentication\AuthenticationHandler::class =>
                    Authentication\AuthenticationHandlerFactory::class,
                Authentication\AuthenticationInterface::class =>
                    Authentication\AuthenticationFactory::class,

                Authentication\Cors\CorsMiddleware::class =>
                    Authentication\Cors\CorsMiddlewareFactory::class,
                Authentication\Jwt\JwtMiddleware::class =>
                    Authentication\Jwt\JwtMiddlewareFactory::class,
                Middleware\UuidMiddleware::class =>
                    Middleware\UuidMiddlewareFactory::class,
                Storage\PdoFactory::class => Storage\PdoFactory::class,

            ],
        ];
    }
}
