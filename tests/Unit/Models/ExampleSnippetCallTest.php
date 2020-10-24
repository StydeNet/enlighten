<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\ExampleSnippetCall;
use Tests\TestCase;

class ExampleSnippetCallTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_the_formatted_resulting_code(): void
    {
        $snippetCall = ExampleSnippetCall::make([
            'result' => [
                ExampleSnippetCall::CLASS_NAME => 'App\\Models\\User',
                ExampleSnippetCall::ATTRIBUTES => [
                    'name' => 'Joe',
                    'last_name' => 'Jones'
                ]
            ]
        ]);

        $output = implode("\n", [
            '"App\\\\Models\\\\User": {',
            '        "name": "Joe",',
            '        "last_name": "Jones"',
            '    }'
        ]);

        $this->assertSame($output, $snippetCall->result_code);
    }

    /** @test */
    public function gets_the_formatted_resulting_code_with_nested_objects(): void
    {
        $snippetCall = ExampleSnippetCall::make([
            'result' => [
                ExampleSnippetCall::CLASS_NAME => 'App\\Models\\User',
                ExampleSnippetCall::ATTRIBUTES => [
                    'name' => 'Joe',
                    'last_name' => 'Jones',
                    'role' => [
                        ExampleSnippetCall::CLASS_NAME => 'App\\Models\\Role',
                        ExampleSnippetCall::ATTRIBUTES => [
                            'name' => 'admin',
                            'capabilities' => ['update', 'create', 'delete']
                        ]
                    ]
                ]
            ]
        ]);

        $output = implode("\n", [
        '"App\\\\Models\\\\User": {',
        '        "name": "Joe",',
        '        "last_name": "Jones",',
        '        "role": {',
        '            "App\\\\Models\\\\Role": {',
        '                "name": "admin",',
        '                "capabilities": [',
        '                    "update",',
        '                    "create",',
        '                    "delete"',
        '                ]',
        '            }',
        '        }',
        '    }',
        ]);

        $this->assertSame($output, $snippetCall->result_code);
    }
}
