<?php

declare(strict_types=1);

namespace Cms\Users;

use Mezzio\Application;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'delegators' => [
                Application::class => [
                    RoutesDelegator::class,
                ],
            ],
            'factories' => [
                Handler\UserCollectionHandler::class =>
                    Handler\UserCollectionHandlerFactory::class,
                Handler\UserCreateHandler::class =>
                    Handler\UserCreateHandlerFactory::class,
                Handler\UserDeleteHandler::class =>
                    Handler\UserDeleteHandlerFactory::class,
                Repository\UserRepositoryInterface::class =>
                    Repository\Pdo\UserRepositoryFactory::class,
            ]
        ];
    }
}

