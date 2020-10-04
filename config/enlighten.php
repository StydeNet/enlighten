<?php

return [
    'enabled' => true,

    'tests' => [
        // Add expressions to ignore test class names and test method names.
        // i.e. Tests\Unit\* will ignore all tests in the Tests\Unit\ suite,
        // validates_* will ignore all the tests that start with validates_.
        'ignore' => [],
    ],

     'request' => [
        'headers' => [
            'ignore' => [],
            'overwrite' => [],
        ],
        'query' => [
            'ignore' => [],
            'overwrite' => [],
        ],
        'input' => [
            'ignore' => [],
            'overwrite' => [],
        ],
     ],

    'response' => [
        'status' => [
            'ignore' => [404],
        ],
        'headers' => [
            'ignore' => [],
            'overwrite' => [],
        ]
    ],

    // Configure the test suites that will be shown in the frontend.
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
