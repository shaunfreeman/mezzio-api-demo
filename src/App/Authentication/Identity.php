<?php

declare(strict_types=1);

namespace Cms\App\Authentication;

use JsonSerializable;
use Cms\Users\Entity\UserEntity;

final class Identity implements JsonSerializable
{
    private string $identity;
    private string $name;
    private string $email;
    private string $role;

    public static function fromArray(array $array): Identity
    {
        return new static(
            $array['identity'],
            $array['name'],
            $array['email'],
            $array['role']
        );
    }

    public static function fromUserEntity(UserEntity $userEntity): Identity
    {
        return new static(
            (string) $userEntity->getId(),
            $userEntity->getName(),
            $userEntity->getEmail(),
            $userEntity->getRole()
        );
    }

    private function __construct(string $identity, string $name, string $email, string $role)
    {
        $this->identity = $identity;
        $this->name     = $name;
        $this->email    = $email;
        $this->role     = $role;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getArrayCopy(): array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
