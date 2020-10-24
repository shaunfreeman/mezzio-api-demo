<?php

declare(strict_types=1);

namespace Cms\App\Filter;

final class Sanitizer
{
    public static function trim(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    public static function titleCase(string $value): string
    {
        return ucwords(strtolower($value), " \t\r\n\f\v'-");
    }
}
