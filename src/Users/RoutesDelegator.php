<?php

declare(strict_types=1);

namespace Cms\Users;

use Mezzio\Application;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Psr\Container\ContainerInterface;
use Cms\App\Authentication\Jwt\JwtMiddleware;
use Cms\Users\Handler\UserCreateHandler;
use Cms\Users\Middleware\UserInputFilterMiddleware;

final class RoutesDelegator
{
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback): Application
    {
        /** @var Application $app */
        $app = $callback();
        $app->get('/users', [
            JwtMiddleware::class,
            Handler\UserCollectionHandler::class
        ], 'users');
        $app->post('/users', [
            JwtMiddleware::class,
            BodyParamsMiddleware::class,
            UserInputFilterMiddleware::class,
            UserCreateHandler::class
        ], 'users.create');
        return $app;
    }
}

