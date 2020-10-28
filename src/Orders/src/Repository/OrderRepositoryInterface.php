<?php

declare(strict_types=1);

namespace Orders\Repository;

use Core\ValueObject\Uuid;
use Orders\Entity\OrderEntity;

interface OrderRepositoryInterface
{
    public function find(Uuid $uuid): OrderEntity;

    public function findAll(int $offset, int $limit): array;

    public function save(OrderEntity $entity): OrderEntity;

    public function delete(Uuid $uuid): bool;
}
