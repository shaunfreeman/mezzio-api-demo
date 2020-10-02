<?php

declare(strict_types=1);

namespace Cms\Managers\Repository;

use Cms\App\ValueObject\Uuid;

interface ManagerRepositoryInterface
{
    public function find(Uuid $uuid): array;

    public function findAll(): array;

    public function create(array $data): bool ;

    public function update(array $data): bool;

    public function delete(Uuid $uuid): bool;
}
