<?php

declare(strict_types=1);

namespace Managers\Entity;

use App\Entity\DtoInterface;

final class ManagerDto implements DtoInterface
{
    public string $name;
    public string $email;
    public array $errors;

    public function getErrors(): array
    {
        return $this->errors;
    }
}
