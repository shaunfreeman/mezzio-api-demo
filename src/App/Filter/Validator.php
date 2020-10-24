<?php

declare(strict_types=1);

namespace Cms\App\Filter;

use Closure;

final class Validator
{
    public static function required(string $value): bool
    {
        return !empty($value);
    }

    public static function stringLength(int $maxlength): Closure
    {
        return function (string $value) use ($maxlength): bool {
            return strlen($value) <= $maxlength;
        };
    }

    public static function inArray(array $array): Closure
    {
        return function (string $value) use ($array): bool {
            return in_array($value, $array);
        };
    }
}
