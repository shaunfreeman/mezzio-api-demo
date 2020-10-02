<?php

declare(strict_types=1);

namespace Cms\App\Repository;

use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;

interface RepositoryInterface
{
    public function find(Uuid $uuid): EntityInterface;

    public function findAll(int $offset, int $limit): array;

    public function save(EntityInterface $entity): EntityInterface;

    public function delete(Uuid $uuid): bool;
}
