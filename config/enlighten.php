<?php

return [
    'tests' => [
        'exclude' => [],
    ],

    // 'request' => [...],

    'response' => [
        'headers' => [
            'exclude' => [],
            'overwrite' => [],
        ]
    ],

    // 'test-suites' => [...],

    'modules' => [
        [
            'name' => 'Users',
            'pattern' => ['*Users*']
        ],
        [
            'name' => 'Other Modules',
            'pattern' => ['*'],
        ],
    ]
];
