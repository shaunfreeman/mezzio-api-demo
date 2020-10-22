<?php

declare(strict_types=1);

namespace Cms\Managers;

use Mezzio\Application;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Psr\Container\ContainerInterface;
use Cms\App\Authentication\Jwt\JwtMiddleware;
use Cms\App\Middleware\UuidMiddleware;

final class RoutesDelegator
{
    public function __invoke(
        ContainerInterface $container,
        $serviceName,
        callable $callback
    ): Application {
        /** @var Application $app */
        $app = $callback();
        $app->get('/managers', [
            JwtMiddleware::class,
            Handler\ManagerCollectionHandler::class
        ], 'managers');
        $app->post('/managers', [
            JwtMiddleware::class,
            BodyParamsMiddleware::class,
            Middleware\ManagerInputFilterMiddleware::class,
            Handler\ManagerCreateHandler::class
        ], 'managers.create');
        $app->get('/managers/{uuid}', [
            UuidMiddleware::class,
            JwtMiddleware::class,
            Handler\ManagerReadHandler::class
        ], 'managers.read');
        $app->patch('/managers/{uuid}', [
            UuidMiddleware::class,
            JwtMiddleware::class,
            BodyParamsMiddleware::class,
            Middleware\ManagerInputFilterMiddleware::class,
            Handler\ManagerUpdateHandler::class
        ], 'managers.update');
        $app->delete('/managers/{uuid}', [
            UuidMiddleware::class,
            JwtMiddleware::class,
            Handler\ManagerDeleteHandler::class
        ], 'managers.delete');
        return $app;
    }
}
