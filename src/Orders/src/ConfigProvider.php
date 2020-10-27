<?php

declare(strict_types=1);

namespace Orders;

use Mezzio\Application;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies'      => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    private function getDependencies(): array
    {
        return [
            'delegators' => [
                Application::class => [
                    RoutesDelegator::class,
                ],
            ],
            'factories'  => [
                Handler\OrderListHandler::class             => Handler\OrderListHandlerFactory::class,
                Repository\OrderRepositoryInterface::class  => Repository\Pdo\OrderRepositoryFactory::class
            ],
        ];
    }
}
