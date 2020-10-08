<?php

declare(strict_types=1);

namespace Cms\Users\Entity;


final class UserDto
{
    public string $name;
    public string $email;
    public ?string $password;
    public string $role;

    public function __construct(
        string $name,
        string $email,
        ?string $password,
        string $role
    ) {
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
    }
}
