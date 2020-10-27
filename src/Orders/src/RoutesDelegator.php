<?php

declare(strict_types=1);

namespace Orders;

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use App\Authentication\Jwt\JwtMiddleware;

final class RoutesDelegator
{
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback): Application
    {
        /** @var Application $app */
        $app = $callback();

        $app->get('/orders', [
            //JwtMiddleware::class,
            Handler\OrderListHandler::class
        ], 'orders');

        return $app;
    }
}
