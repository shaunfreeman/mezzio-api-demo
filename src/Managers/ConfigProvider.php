<?php

declare(strict_types=1);

namespace Cms\Managers;

use Mezzio\Application;

final class ConfigProvider
{
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
            'factories'  => [
                Handler\ManagerCollectionHandler::class =>
                    Handler\ManagerCollectionHandlerFactory::class,
                Handler\ManagerCreateHandler::class =>
                    Handler\ManagerCreateHandlerFactory::class,
                Handler\ManagerReadHandler::class =>
                    Handler\ManagerReadHandlerFactory::class,
                Handler\ManagerUpdateHandler::class =>
                    Handler\ManagerUpdateHandlerFactory::class,
                Handler\ManagerDeleteHandler::class =>
                    Handler\ManagerDeleteHandlerFactory::class,
                Repository\ManagerRepositoryInterface::class =>
                    Repository\Pdo\ManagerRepositoryFactory::class,
            ],
        ];
    }
}
