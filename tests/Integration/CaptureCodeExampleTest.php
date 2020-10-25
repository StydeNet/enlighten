<?php

namespace Tests\Integration;

use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;
use Styde\Enlighten\Utils\ResultTransformer;
use Tests\Integration\App\Models\User;

class CaptureCodeExampleTest extends TestCase
{
    /** @test */
    function captures_single_line_snippet()
    {
        $sum = Enlighten::test(function () {
            $a = 1;
            $b = 2;

            return $a + $b;
        });

        $this->assertSame(3, $sum);

        // Enlighten internal assertions:
        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_single_line_snippet', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame(implode("\n", [
                '$a = 1;',
                '$b = 2;',
                '',
                '$a + $b;',
            ]), $snippet->code);
            $this->assertSame(3, $snippet->result);
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
            $this->assertSame(implode("\n", [
                'User::create([',
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
            $this->assertNull($query->request_id);
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

            $this->assertNull($snippet->result);
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
        ResultTransformer::$maxNestedLevel = 1;

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
}

class DemoClassForSnippetExample
{
    public $message = 'this data can be collected';
    public $nestedObject;
    private $private = "this data won't be collected";

    public function __construct()
    {
        $this->nestedObject = new DemoNestedClassForSnippetExample;
    }
}

class DemoNestedClassForSnippetExample
{
    public $nested = 'nested attribute';
}
