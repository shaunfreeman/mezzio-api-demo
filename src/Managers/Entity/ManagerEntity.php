<?php

declare(strict_types=1);

namespace Cms\Managers\Entity;

use DateTimeImmutable;
use Exception;
use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;

final class ManagerEntity implements EntityInterface
{
    private Uuid $id;
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
        $manager->id        = (isset($data['id'])) ? Uuid::fromString($data['id']) : Uuid::generate();
        $manager->name      = $data['name'];
        $manager->email     = $data['email'];
        $manager->modified  = new DateTimeImmutable($data['modified'] ?? 'now');
        $manager->created   = new DateTimeImmutable($data['created'] ?? 'now');
        return $manager;
    }

    private function __construct()
    {
    }

    public function getArrayCopy(): array
    {
        return [
            'id'        => (string) $this->getId(),
            'name'      => $this->getName(),
            'email'     => $this->getEmail(),
            'modified'  => $this->getModified()->format('Y-m-d H:i:s'),
            'created'   => $this->getCreated()->format('Y-m-d H:i:s'),
        ];
    }

    public function updateFromDto(ManagerDto $dto): ManagerEntity
    {
        $manager            = clone $this;
        $manager->name      = $dto->name;
        $manager->email     = $dto->email;
        $manager->modified  = new DateTimeImmutable('now');

        return $manager;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getModified(): DateTimeImmutable
    {
        return $this->modified;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }
}