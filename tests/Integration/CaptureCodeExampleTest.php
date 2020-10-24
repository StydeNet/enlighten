<?php

namespace Tests\Integration;

use Illuminate\Database\Eloquent\Collection;
use Styde\Enlighten\CodeExampleCreator;
use Styde\Enlighten\CodeSnippet;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;
use Styde\Enlighten\Models\ExampleSnippetCall;
use Tests\Integration\App\Models\User;

class CaptureCodeExampleTest extends TestCase
{
    /** @test */
    function captures_single_line_snippet()
    {
        $sum = Enlighten::test(function ($a, $b) {
            return $a + $b;
        })(1, 2);

        $this->assertSame(3, $sum);

        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_single_line_snippet', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame('$a + $b;', $snippet->code);

            tap($snippet->calls()->first(), function (ExampleSnippetCall $call) {
                $this->assertSame(['a' => 1, 'b' => 2], $call->arguments);
                $this->assertSame(3, $call->result);
            });
        });
    }

    /** @test */
    function captures_multiple_results_from_one_code_snippet()
    {
        $division = enlighten(function ($dividend, $divisor) {
            if ($divisor == 0) {
                throw new \InvalidArgumentException('Cannot divide by zero');
            }

            return $dividend / $divisor;
        });

        $this->assertSame(3, $division(6, 2));
        $this->assertSame(4, $division(12, 3));

        tap(ExampleSnippet::first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame(implode("\n", [
                'if ($divisor == 0) {',
                '    throw new \InvalidArgumentException(\'Cannot divide by zero\');',
                '}',
                '',
                '$dividend / $divisor;'
            ]), $snippet->code);

            tap($snippet->calls->shift(), function ($call) {
                $this->assertInstanceOf(ExampleSnippetCall::class, $call);
                $this->assertSame(['dividend' => 6, 'divisor' => 2], $call->arguments);
                $this->assertSame(3, $call->result);
            });

            tap($snippet->calls->shift(), function ($call) {
                $this->assertInstanceOf(ExampleSnippetCall::class, $call);
                $this->assertSame(['dividend' => 12, 'divisor' => 3], $call->arguments);
                $this->assertSame(4, $call->result);
            });
        });
    }

    /** @test */
    function captures_snippet_with_default_value()
    {
        $sum = enlighten(function ($a, $b = 3) {
            return $a + $b;
        });

        $this->assertSame(5, $sum(2));

        tap(ExampleSnippet::first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            tap($snippet->calls->first(), function (ExampleSnippetCall $call) {
                $this->assertSame(['a' => 2, 'b' => 3], $call->arguments);
                $this->assertSame(5, $call->result);
            });
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
        })();

        $this->assertInstanceOf(User::class, $user);

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame(implode("\n", [
                "User::create([",
                "    'name' => 'Duilio',",
                "    'email' => 'duilio@styde.net',",
                "    'password' => 'password',",
                ']);'
            ]), $snippet->code);
        });

        tap($example->queries()->first(), function ($query) use ($snippet) {
            $this->assertSame('insert into "users" ("name", "email", "password", "updated_at", "created_at") values (?, ?, ?, ?, ?)', $query->sql);
            $this->assertSame([
                'Duilio',
                'duilio@styde.net',
                'password',
            ], array_slice($query->bindings, 0, 3));
            $this->assertNull($query->http_data_id);
            $this->assertSame($snippet->calls()->first()->id, $query->snippet_call_id);
        });
    }

    /** @test */
    function captures_snippet_with_exception()
    {
        $this->expectException(\BadMethodCallException::class);

        enlighten(function ($param) {
            throw new \BadMethodCallException('Enlighten can record exceptions in code snippets');
        })('value');

        $this->saveTestExample();

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame("throw new \BadMethodCallException('Enlighten can record exceptions in code snippets');", $snippet->code);
        });

        tap($snippet->calls()->first(), function ($snippetCall) {
            $this->assertSame(['param' => 'value'], $snippetCall->arguments);
            $this->assertNull($snippetCall->result);
        });
    }

    /** @test */
    function captures_objects_returned_by_snippets()
    {
        enlighten(function () {
            return new DemoClassForSnippetExample;
        })();

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
            ], $snippet->calls()->first()->result);
        });
    }

    /** @test */
    function captures_objects_returned_by_snippets_with_limited_recursion()
    {
        CodeSnippet::$maxNestedLevel = 1;

        enlighten(function () {
            return new DemoClassForSnippetExample;
        })();

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
            ], $snippet->calls()->first()->result);
        });
    }

    /** @test */
    function captures_object_type_parameters()
    {
        CodeSnippet::$maxNestedLevel = 2;

        $users = new Collection([
            new User(['name' => 'Duilio']),
            new User(['name' => 'Jeff']),
        ]);

        $names = enlighten(function ($users) {
            return $users->pluck('name')->join(', ');
        })($users);

        $this->assertSame('Duilio, Jeff', $names);

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame('$users->pluck(\'name\')->join(\', \');', $snippet->code);

            tap($snippet->calls()->first(), function (ExampleSnippetCall $snippetCall) {
                $this->assertSame([
                    'users' => [
                        ExampleSnippet::CLASS_NAME => 'Illuminate\Database\Eloquent\Collection',
                        ExampleSnippet::ATTRIBUTES => [
                            'items' => [
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
                                ]
                            ],
                        ],
                    ],
                ], $snippetCall->arguments);

                $this->assertSame('Duilio, Jeff', $snippetCall->result);
            });
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
