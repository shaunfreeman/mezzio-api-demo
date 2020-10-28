<?php

declare(strict_types=1);

namespace Core\Entity;

interface EntityInterface
{
    public static function fromArray(array $data): EntityInterface;

    public function getArrayCopy(): array;
}
