<?php

return [
    'tests' => [
        // Add expressions to exclude test class names and test method names.
        // i.e. Tests\Unit\* excludes all tests in the Tests\Unit\ suite,
        // validates_* excludes all tests that start with validates_.
        'exclude' => [],
    ],

    // 'request' => [...],

    'response' => [
        'headers' => [
            'exclude' => [],
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
