<?php

declare(strict_types=1);

namespace Cms\Orders\Entity;

use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;
use DateTimeImmutable;

final class OrderEntity implements EntityInterface
{
    private Uuid $uid;
    private string $claimNumber;
    private string $jobNumber;
    private DateTimeImmutable $modified;
    private DateTimeImmutable $created;

    public static function fromArray(array $data): OrderEntity
    {
        // TODO: Implement fromArray() method.
    }

    public function getArrayCopy(): array
    {
        // TODO: Implement getArrayCopy() method.
    }
}

