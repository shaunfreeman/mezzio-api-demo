<?php

declare(strict_types=1);

namespace Core\Authentication;

use Exception;
use Users\Repository\UserRepositoryInterface;
use Users\ValueObject\Password;

final class Authentication implements AuthenticationInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(string $identity, string $password): Identity
    {
        $user = $this->userRepository
            ->findByEmail($identity);

        if (!Password::verify($password, $user->getPassword())) {
            throw new Exception('Password is incorrect.');
        }

        return  Identity::fromUserEntity($user);
    }
}
