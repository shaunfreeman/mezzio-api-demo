<?php

declare(strict_types=1);

namespace Cms\Users\Filter;

use Cms\App\Filter\InputFilter;
use Cms\Users\ValueObject\Password;

final class UserInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->filterDefinition = [
            'name' => [
                ['filter' => FILTER_SANITIZE_STRING],
                ['filter' => FILTER_CALLBACK, 'options' => 'trim'],
                ['filter' => FILTER_CALLBACK, 'options' => 'strtolower'],
                ['filter' => FILTER_CALLBACK, 'options' => 'ucwords'],
            ],
            'email' => [
                ['filter' => FILTER_SANITIZE_EMAIL],
                ['filter' => FILTER_CALLBACK, 'options' => 'trim'],
            ],
            'password' => [
                ['filter' => FILTER_SANITIZE_STRING],
                ['filter' => FILTER_CALLBACK, 'options' => 'trim'],
                ['filter' => FILTER_CALLBACK, 'options' => [UserInputFilter::class, 'filterPassword']]
            ],
            'role' => [
                ['filter' => FILTER_SANITIZE_STRING],
                ['filter' => FILTER_CALLBACK, 'options' => 'trim'],
            ],
        ];
    }

    protected static function filterPassword(?string $value): ?string
    {
        if (!empty($value) && is_string($value)) {
            return Password::hash($value);
        }

        return null;
    }
}
