<?php

return [
    // Set to false to disable the Enlighten Dashboard.
    'dashboard' => true,

    'driver' => 'database',

    // Add values to this array to hide certain sections
    // from your views. For all valid sections check
    // the constants in \Styde\Enlighten\Section.
    'hide' => [
        // 'queries',
        // 'html',
        // 'blade',
        // 'route_parameters',
        // 'request_input',
        // 'request_headers',
        // 'response_headers',
        // 'session',
        // 'exception',
    ],

    // Default directory to export the documentation.
    'docs_base_dir' => 'public/docs',
    // Default base URL for exported the documentation.
    'docs_base_url' => '/docs',

    // Display / hide quick access links to open your IDE from the UI
    'developer_mode' => true,
    'editor' => 'phpstorm', // phpstorm, vscode or sublime

    'tests' => [
        // Add regular expressions to skip certain test classes and test methods.
        // i.e. Tests\Unit\* will ignore all the tests in the Tests\Unit\ suite,
        // validates_* will ignore all the tests that start with "validates_".
        'ignore' => [],
    ],

    // Use the arrays below to hide or obfuscate parameters
    // from the HTTP requests including headers, input,
    // query parameters, session data, and others.
    'request' => [
        'headers' => [
            'hide' => [],
            'overwrite' => [],
        ],
        'query' => [
            'hide' => [],
            'overwrite' => [],
        ],
        'input' => [
            'hide' => [],
            'overwrite' => [],
        ],
    ],

    'response' => [
        'headers' => [
            'hide' => [],
            'overwrite' => [],
        ],
        'body' => [
            'hide' => [],
            'overwrite' => [],
        ]
    ],

    'session' => [
        'hide' => [],
        'overwrite' => [],
    ],

    // Configure a default view for the panel. Options: features, modules and endpoints.
    'area_view' => 'features',

    // Customise the name and view template of each area that will be shown in the panel.
    // By default, each area slug will represent a "test suite" in the tests directory.
    // Each area can have a different view style, ex: features, modules or endpoints.
    'areas' => [
        [
            'slug' => 'api',
            'name' => 'API',
            'view' => 'endpoints',
        ],
        [
            'slug' => 'feature',
            'name' => 'Features',
            'view' => 'modules',
        ],
        [
            'slug' => 'unit',
            'name' => 'Unit',
            'view' => 'features',
        ],
    ],

    // If you want to use "modules" or "endpoints" as the area view,
    // you will need to configure the modules adding their names
    // and patterns to match the test classes and/or routes.
    'modules' => [
        [
            'name' => 'Users',
            'classes' => ['*User*'],
            'routes' => ['users/*'],
        ],
        [
            'name' => 'Other Modules',
            'classes' => ['*'],
        ],
    ]
];
