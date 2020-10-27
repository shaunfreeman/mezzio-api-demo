<?php

declare(strict_types=1);

namespace App;

use Mezzio\Application;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Psr\Container\ContainerInterface;
use App\Authentication\Jwt\JwtMiddleware;

final class RoutesDelegator
{
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback): Application
    {
        /** @var Application $app */
        $app = $callback();

        $app->get('/', Handler\HomePageHandler::class, 'home');
        $app->post('/authenticate', [
            BodyParamsMiddleware::class,
            Authentication\AuthenticationHandler::class,
        ], 'auth');
        $app->get('/ping', Handler\PingHandler::class, 'api.ping');
        $app->post('/upload', [
            JwtMiddleware::class,
            Handler\UploadHandler::class,
        ], 'upload');

        return $app;
    }
}
