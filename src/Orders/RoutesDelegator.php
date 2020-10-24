<?php

declare(strict_types=1);

namespace Cms\Orders;

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Cms\App\Authentication\Jwt\JwtMiddleware;

final class RoutesDelegator
{
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback): Application
    {
        /** @var Application $app */
        $app = $callback();

        $app->get('/order', [
            JwtMiddleware::class,
            Handler\OrderListHandler::class
        ], 'order');

        return $app;
    }
}
