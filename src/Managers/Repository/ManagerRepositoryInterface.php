<?php

declare(strict_types=1);

namespace Cms\Managers\Repository;

use Cms\App\ValueObject\Uuid;
use Cms\Managers\Entity\ManagerEntity;

interface ManagerRepositoryInterface
{
    public function findByEmail(string $email, string $ignore = ''): ManagerEntity;

    public function find(Uuid $uuid): ManagerEntity;

    public function findAll(): array;

    public function create(array $data): bool ;

    public function update(array $data): bool;

    public function delete(Uuid $uuid): bool;
}
