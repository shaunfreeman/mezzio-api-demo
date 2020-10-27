<?php

declare(strict_types=1);

namespace Users\Entity;

use App\Entity\DtoInterface;

final class UserDto implements DtoInterface
{
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public array $errors;

    public function getErrors(): array
    {
        return $this->errors;
    }
}
