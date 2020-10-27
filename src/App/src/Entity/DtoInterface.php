<?php

declare(strict_types=1);

namespace App\Entity;

interface DtoInterface
{
    public function getErrors(): array;
}
