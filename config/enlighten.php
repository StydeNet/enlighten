<?php

return [
    'enabled' => true,

    // Display/hide quick access links to open your IDE from the UI
    'developer_mode' => true,

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

    // - PRESENTATION OPTIONS:

    // Configure all the areas that will be shown in the frontend.
    // Each area represents a "test suite" in the tests/ folder.
    // 'areas' => [...],

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
