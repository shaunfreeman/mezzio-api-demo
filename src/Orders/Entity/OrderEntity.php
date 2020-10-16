<?php

declare(strict_types=1);

namespace Cms\Orders\Entity;

use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;
use DateTimeImmutable;

final class OrderEntity implements EntityInterface
{
    private Uuid $id;
    private string $claimNumber;
    private string $jobNumber;
    private array $doc;
    private DateTimeImmutable $modified;
    private DateTimeImmutable $created;

    public static function fromArray(array $data): OrderEntity
    {
        // TODO: Implement fromArray() method.
    }

    public function getArrayCopy(): array
    {
        return [
            'id'            => (string) $this->id,
            'claim_number'  => $this->claimNumber,
            'job_number'    => $this->jobNumber,
            'doc'           => $this->doc,
            'modified'      => $this->modified->format('Y-m-d H:i:s'),
            'created'       => $this->created->format('Y-m-d H:i:s'),
        ];
    }
}

