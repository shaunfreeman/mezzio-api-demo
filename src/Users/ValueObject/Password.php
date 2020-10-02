<?php

declare(strict_types=1);

namespace Cms\Users\ValueObject;

final class Password
{
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verify(string $inputPassword, string $password): bool
    {
        return password_verify($inputPassword, $password);
    }

    public static function checkHash(string $password): string
    {
        if (password_needs_rehash($password, PASSWORD_DEFAULT)) {
            $password = self::hash($password);
        }

        return $password;
    }
}
