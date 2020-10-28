<?php

declare(strict_types=1);

namespace Managers\Entity;

use DateTimeImmutable;
use Exception;
use Core\Entity\EntityInterface;
use Core\ValueObject\Uuid;
use JsonSerializable;

final class ManagerEntity implements EntityInterface, JsonSerializable
{
    private Uuid $uuid;
    private string $name;
    private string $email;
    private DateTimeImmutable $modified;
    private DateTimeImmutable $created;

    /**
     * @param array $data
     * @return ManagerEntity
     * @throws Exception
     */
    public static function fromArray(array $data): ManagerEntity
    {
        $manager            = new static();
        $manager->uuid        = (isset($data['id'])) ? Uuid::fromString($data['id']) : Uuid::generate();
        $manager->name      = $data['name'];
        $manager->email     = $data['email'];
        $manager->modified  = new DateTimeImmutable($data['modified'] ?? 'now');
        $manager->created   = new DateTimeImmutable($data['created'] ?? 'now');
        return $manager;
    }

    public function updateFromDto(ManagerDto $dto): ManagerEntity
    {
        $manager            = clone $this;
        $manager->name      = $dto->name;
        $manager->email     = $dto->email;
        $manager->modified  = new DateTimeImmutable('now');

        return $manager;
    }

    private function __construct()
    {
    }

    public function getArrayCopy(): array
    {
        return [
            'id'        => (string) $this->uuid,
            'name'      => $this->name,
            'email'     => $this->email,
            'modified'  => $this->modified->format('Y-m-d H:i:s'),
            'created'   => $this->created->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
