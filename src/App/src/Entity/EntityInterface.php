<?php

declare(strict_types=1);

namespace App\Entity;

interface EntityInterface
{
    public static function fromArray(array $data): EntityInterface;

    public function getArrayCopy(): array;
}
