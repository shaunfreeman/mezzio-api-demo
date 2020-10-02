<?php

declare(strict_types=1);

namespace Cms\Users\Entity;

use JsonSerializable;

final class UserDto implements JsonSerializable
{
    public string $id;
    public string $name;
    public string $email;
    public ?string $password;
    public string $role;
    public string $modified;
    public string $created;

    public function jsonSerialize()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'role'      => $this->role,
            'modified'  => $this->modified,
            'created'   => $this->created,
        ];
    }
}
