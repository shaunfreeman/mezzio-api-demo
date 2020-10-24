<?php

declare(strict_types=1);

namespace Cms\Users\Entity;

use DateTimeImmutable;
use Exception;
use JsonSerializable;
use Cms\App\Entity\EntityInterface;
use Cms\App\ValueObject\Uuid;

final class UserEntity implements EntityInterface, JsonSerializable
{
    const USER_ROLE_ADMIN   = 'admin';
    const USER_ROLE_MANAGER = 'manager';
    const USER_ROLE_STAFF   = 'staff';

    const USER_ROLES = [
        self::USER_ROLE_ADMIN   => 'Admin',
        self::USER_ROLE_MANAGER => 'Manager',
        self::USER_ROLE_STAFF   => 'Staff',
    ];

    private Uuid $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private DateTimeImmutable $modified;
    private DateTimeImmutable $created;

    /**
     * @param array $data
     * @return UserEntity
     * @throws Exception
     */
    public static function fromArray(array $data): UserEntity
    {
        $user           = new static();
        $user->id       = (isset($data['id'])) ? Uuid::fromString($data['id']) : Uuid::generate();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = $data['password'];
        $user->role     = $data['role'];
        $user->modified = new DateTimeImmutable('now');
        $user->created  = new DateTimeImmutable($data['created'] ?? 'now');
        return $user;
    }

    public function updateFromDto(UserDto $dto): UserEntity
    {
        $user           = clone $this;
        $user->name     = $dto->name;
        $user->email    = $dto->email;
        $user->role     = $dto->role;
        $user->modified = new DateTimeImmutable('now');

        if ($dto->password) {
            $user->password = $dto->password;
        }

        return  $user;
    }

    private function __construct()
    {
    }

    public function getArrayCopy(): array
    {
        return [
            'id'        => (string) $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'password'  => $this->password,
            'role'      => $this->role,
            'modified'  => $this->modified->format('Y-m-d H:i:s'),
            'created'   => $this->created->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        $array = $this->getArrayCopy();
        unset($array['password']);

        return $array;
    }

    public static function getChoices(): array
    {
        $choices = [];

        foreach (self::USER_ROLES as $value => $label) {
            $choices[] = ['label' => $label, 'value' => $value];
        }

        return $choices;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getModified(): DateTimeImmutable
    {
        return $this->modified;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }
}
