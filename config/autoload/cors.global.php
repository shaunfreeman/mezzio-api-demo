<?php

declare(strict_types=1);

return [
    'cors' => [
        'origin.server' => 'http://localhost:80',
        'origin' => ['http://localhost:4200'],
        'headers.allow' => [
            'Authorization',
            'Content-Type',
        ],
        'headers.expose' => [],
        'credentials' => true,
        'cache' => 0,
    ],
];
