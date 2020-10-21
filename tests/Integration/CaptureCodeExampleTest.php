<?php

namespace Tests\Integration;

use Illuminate\Database\Eloquent\Collection;
use Styde\Enlighten\CodeExampleCreator;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;
use Tests\Integration\App\Models\User;

class CaptureCodeExampleTest extends TestCase
{
    /** @test */
    function captures_snippet_example()
    {
        $a = 1;
        $b = 2;

        $sum = enlighten(function ($a, $b) {
            return $a + $b;
        }, $a, $b);

        $this->assertSame(3, $sum);

        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_snippet_example', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame(['a' => 1, 'b' => 2], $snippet->params);
            $this->assertSame('$a + $b;', $snippet->code);
            $this->assertSame(3, $snippet->result);
        });
    }

    /** @test */
    function captures_snippet_with_default_value()
    {
        $sum = enlighten(function ($a, $b = 3) {
            return $a + $b;
        }, 2);

        $this->assertSame(5, $sum);

        tap(ExampleSnippet::first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame(['a' => 2, 'b' => 3], $snippet->params);
            $this->assertSame('$a + $b;', $snippet->code);
            $this->assertSame(5, $snippet->result);
        });
    }

    /** @test */
    function captures_snippet_with_sql_query()
    {
        $user = enlighten(function () {
            return User::create([
                'name' => 'Duilio',
                'email' => 'duilio@styde.net',
                'password' => 'password',
            ]);
        });

        $this->assertInstanceOf(User::class, $user);

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame("User::create([
                'name' => 'Duilio',
                'email' => 'duilio@styde.net',
                'password' => 'password',
            ]);", $snippet->code);
        });

        tap($example->queries()->first(), function ($query) use ($snippet) {
            $this->assertSame('insert into "users" ("name", "email", "password", "updated_at", "created_at") values (?, ?, ?, ?, ?)', $query->sql);
            $this->assertSame([
                'Duilio',
                'duilio@styde.net',
                'password',
            ], array_slice($query->bindings, 0, 3));
            $this->assertNull($query->http_data_id);
            $this->assertSame($snippet->id, $query->snippet_id);
        });
    }

    /** @test */
    function captures_snippet_with_exception()
    {
        $this->expectException(\BadMethodCallException::class);

        enlighten(function () {
            throw new \BadMethodCallException('Enlighten can record exceptions in code snippets');
        });

        $this->saveTestExample();

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame("throw new \BadMethodCallException('Enlighten can record exceptions in code snippets');", $snippet->code);
        });
    }

    /** @test */
    function captures_objects_returned_by_snippets()
    {
        enlighten(function () {
            return new DemoClassForSnippetExample;
        });

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame('new DemoClassForSnippetExample;', $snippet->code);
            $this->assertSame([
                ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoClassForSnippetExample',
                ExampleSnippet::ATTRIBUTES => [
                    'message' => 'this data can be collected',
                    'nestedObject' => [
                        ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoNestedClassForSnippetExample',
                        ExampleSnippet::ATTRIBUTES => [
                            'nested' => 'nested attribute',
                        ]
                    ]
                ],
            ], $snippet->result);
        });
    }

    /** @test */
    function captures_objects_returned_by_snippets_with_limited_recursion()
    {
        CodeExampleCreator::$maxNestedLevel = 1;

        enlighten(function () {
            return new DemoClassForSnippetExample;
        });

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertSame('new DemoClassForSnippetExample;', $snippet->code);

            $this->assertSame([
                ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoClassForSnippetExample',
                ExampleSnippet::ATTRIBUTES => [
                    'message' => 'this data can be collected',
                    'nestedObject' => [
                        ExampleSnippet::CLASS_NAME => 'Tests\Integration\DemoNestedClassForSnippetExample',
                        ExampleSnippet::ATTRIBUTES => null,
                    ]
                ],
            ], $snippet->result);
        });
    }

    /** @test */
    function captures_object_type_parameters()
    {
        CodeExampleCreator::$maxNestedLevel = 2;

        $users = new Collection([
            new User(['name' => 'Duilio']),
            new User(['name' => 'Jeff']),
        ]);

        $names = enlighten(function ($users) {
            return $users->pluck('name')->join(', ');
        }, $users);

        $this->assertSame('Duilio, Jeff', $names);

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame([
                'users' => [
                    ExampleSnippet::CLASS_NAME => 'Illuminate\Database\Eloquent\Collection',
                    ExampleSnippet::ATTRIBUTES => [
                        [
                            ExampleSnippet::CLASS_NAME => User::class,
                            ExampleSnippet::ATTRIBUTES => [
                                'name' => 'Duilio',
                            ]
                        ],
                        [
                            ExampleSnippet::CLASS_NAME => User::class,
                            ExampleSnippet::ATTRIBUTES => [
                                'name' => 'Jeff',
                            ]
                        ],
                    ],
                ],
            ], $snippet->params);

            $this->assertSame('$users->pluck(\'name\')->join(\', \');', $snippet->code);
            $this->assertSame('Duilio, Jeff', $snippet->result);
        });
    }
}

class DemoClassForSnippetExample {
    public $message = 'this data can be collected';
    public $nestedObject;
    private $private = "this data won't be collected";

    public function __construct()
    {
        $this->nestedObject = new DemoNestedClassForSnippetExample;
    }
}

class DemoNestedClassForSnippetExample {
    public $nested = 'nested attribute';
}
