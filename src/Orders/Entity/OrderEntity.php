<?php

declare(strict_types=1);

namespace Cms\Orders\Entity;

use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;
use DateTimeImmutable;
use JsonSerializable;

final class OrderEntity implements EntityInterface, JsonSerializable
{
    private Uuid $uuid;
    private string $claimNumber;
    private string $jobNumber;
    private array $doc;
    private DateTimeImmutable $modified;
    private DateTimeImmutable $created;

    public static function fromArray(array $data): OrderEntity
    {
        return new static();
    }

    private function __construct()
    {
    }

    public function getArrayCopy(): array
    {
        return [
            'id'            => (string) $this->uuid,
            'claim_number'  => $this->claimNumber,
            'job_number'    => $this->jobNumber,
            'doc'           => $this->doc,
            'modified'      => $this->modified->format('Y-m-d H:i:s'),
            'created'       => $this->created->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }
}
