<?php

declare(strict_types=1);

namespace Cms\Orders;

use Mezzio\Application;
use Mezzio\Hal\Metadata\MetadataMap;
use Mezzio\Hal\Metadata\RouteBasedCollectionMetadata;
use Mezzio\Hal\Metadata\RouteBasedResourceMetadata;
use ShaunFreeman\PhpMysqlXdevapi\Entity\DocumentEntity;
use ShaunFreeman\PhpMysqlXdevapi\Entity\DocumentEntityCollection;
use ShaunFreeman\PhpMysqlXdevapi\Hydrator\DocumentHydrator;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies'      => $this->getDependencies(),
            //MetadataMap::class  => $this->getHalMetadataMap(),
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
                Handler\OrderListHandler::class             => Handler\OrderListHandlerFactory::class,
                Repository\OrderRepositoryInterface::class  => Repository\Pdo\OrderRepositoryFactory::class
            ],
        ];
    }

    private function getHalMetadataMap()
    {
        return [
            [
               '__class__'              => RouteBasedResourceMetadata::class,
                'resource_class'        => DocumentEntity::class,
                'route'                 => 'order',
                'extractor'             => DocumentHydrator::class,
            ],
            [
                '__class__'             => RouteBasedCollectionMetadata::class,
                'collection_class'      => DocumentEntityCollection::class,
                'collection_relation'   => 'order',
                'route'                 => 'order',
            ],
        ];
    }
}
