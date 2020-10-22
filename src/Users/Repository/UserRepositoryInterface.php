<?php

declare(strict_types=1);

namespace Cms\Users\Repository;

use Cms\App\ValueObject\Uuid;
use Cms\Users\Entity\UserEntity;

interface UserRepositoryInterface
{
    public function findByEmail(string $email, string $ignore = ''): UserEntity;

    public function find(Uuid $uuid): UserEntity;

    public function findAll(): array;

    public function create(array $data): bool;

    public function update(array $data): bool;

    public function delete(Uuid $uuid): bool;
}
