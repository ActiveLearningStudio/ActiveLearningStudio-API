<?php declare(strict_types=1);

return [
    'hosts' => [
        [
            'host' => env('ELASTIC_HOST', 'localhost:9200'),
            'user' => env('ELASTIC_USER', 'user'),
            'pass' => env('ELASTIC_PASS', 'pass')
        ]
    ]
];
