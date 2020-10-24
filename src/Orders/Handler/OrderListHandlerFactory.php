<?php

declare(strict_types=1);

namespace Cms\Orders\Handler;

use Psr\Container\ContainerInterface;
use Cms\Orders\Repository\OrderRepositoryInterface;

final class OrderListHandlerFactory
{
    public function __invoke(ContainerInterface $container): OrderListHandler
    {
        $orderRepository = $container->get(OrderRepositoryInterface::class);

        return new OrderListHandler($orderRepository);
    }
}
