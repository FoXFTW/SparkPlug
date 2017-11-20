<?php

return [
    'fetch' => PDO::FETCH_ASSOC,

    'default' => 'sqlite',

    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => '',
            'prefix' => '',
        ],
    ],
];
