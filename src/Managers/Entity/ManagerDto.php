<?php

declare(strict_types=1);

namespace Cms\Managers\Entity;

final class ManagerDto
{
    public string $name;
    public string $email;

    public function __construct(string $name, string $email)
    {
        $this->name     = $name;
        $this->email    = $email;
    }
}
