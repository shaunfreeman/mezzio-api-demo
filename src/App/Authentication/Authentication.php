<?php

declare(strict_types=1);

namespace Cms\App\Authentication;

use Exception;
use Cms\Users\Repository\UserRepositoryInterface;
use Cms\Users\ValueObject\Password;

final class Authentication implements AuthenticationInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
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
