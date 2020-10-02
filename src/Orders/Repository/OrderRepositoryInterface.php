<?php

declare(strict_types=1);

namespace Cms\Orders\Repository;

use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;
use Cms\Orders\Entity\OrderEntity;

interface OrderRepositoryInterface
{
    public function find(Uuid $uuid): OrderEntity;

    public function findAll(int $offset, int $limit): array;

    public function save(OrderEntity $entity): OrderEntity;

    public function delete(Uuid $uuid): bool;
}

