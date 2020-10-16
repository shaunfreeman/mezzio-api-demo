<?php

declare(strict_types=1);

namespace Cms\Managers\Filter;

use Cms\App\Filter\InputFilter;

final class ManagerInputFilter extends InputFilter
{
    public function init()
    {
        $this->sanitizeDefinition = [
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
        ];
    }
}
