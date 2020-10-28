<?php

declare(strict_types=1);

namespace Core\Authentication;

use Exception;

interface AuthenticationInterface
{
    /**
     * @param string $identity
     * @param string $password
     * @return Identity
     * @throws Exception
     */
    public function authenticate(string $identity, string $password): Identity;
}
