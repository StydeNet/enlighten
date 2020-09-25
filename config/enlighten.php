<?php

return [
    'exclude' => [],

    'response' => [
        'headers' => [
            'exclude' => [],
            'overwrite' => [],
        ]
    ],

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
