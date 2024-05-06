<?php

namespace Tests\Integration;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\CodeExamples\CodeResultTransformer;
use Styde\Enlighten\Enlighten;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;
use Tests\Integration\App\Models\User;

class CaptureCodeExampleTest extends TestCase
{
    #[Test]
    function captures_single_line_snippet(): void
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

    #[Test]
    function captures_snippet_with_key(): void
    {
        $msg = Enlighten::test('hello-world', function () {
            return 'Hello World';
        });

        $this->assertSame('Hello World', $msg);

        // Enlighten internal assertions:
        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_snippet_with_key', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame('hello-world', $snippet->key);
            $this->assertSame("'Hello World';", $snippet->code);
        });
    }

    #[Test]
    function captures_snippet_with_key_using_helper(): void
    {
        $msg = enlighten('hello-world-2', function () {
            return 'Hello World';
        });

        $this->assertSame('Hello World', $msg);

        // Enlighten internal assertions:
        $example = Example::first();

        $this->assertNotNull($example, 'An expected example was not created.');
        $this->assertSame('captures_snippet_with_key_using_helper', $example->method_name);

        tap($example->snippets->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame('hello-world-2', $snippet->key);
            $this->assertSame("'Hello World';", $snippet->code);
        });
    }

    #[Test]
    function captures_snippet_with_sql_query(): void
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

    #[Test]
    function captures_snippet_with_exception(): void
    {
        $this->expectException(BadMethodCallException::class);

        enlighten(function () {
            throw new BadMethodCallException('Enlighten can record exceptions in code snippets');
        });

        $this->saveExampleStatus();

        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);

            $this->assertSame("throw new \BadMethodCallException('Enlighten can record exceptions in code snippets');", $snippet->code);

            $this->assertNull($snippet->result);
        });
    }

    #[Test]
    function captures_objects_returned_by_snippets(): void
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

    #[Test]
    function captures_objects_returned_by_snippets_with_limited_recursion(): void
    {
        CodeResultTransformer::$maxNestedLevel = 1;

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

    #[Test]
    function captures_information_from_functions_returned_by_code_snippets(): void
    {
        enlighten(function () {
            return function () {
                return 1 + 2;
            };
        });

        $this->assertFirstSnippetReturns([
            ExampleSnippet::FUNCTION => ExampleSnippet::ANONYMOUS_FUNCTION,
            ExampleSnippet::PARAMETERS => [],
            ExampleSnippet::RETURN_TYPE => null,
        ]);
    }

    #[Test]
    function captures_information_from_functions_with_parameters(): void
    {
        enlighten(function () {
            return function ($a, int $b = 2, \stdClass $anObject = null) {
                return 1 + 2;
            };
        });

        $this->assertFirstSnippetReturns([
            ExampleSnippet::FUNCTION => ExampleSnippet::ANONYMOUS_FUNCTION,
            ExampleSnippet::PARAMETERS => [
                [
                    ExampleSnippet::TYPE => null,
                    ExampleSnippet::PARAMETER => 'a',
                    ExampleSnippet::OPTIONAL => false,
                    ExampleSnippet::DEFAULT => null,
                ],
                [
                    ExampleSnippet::TYPE => 'int',
                    ExampleSnippet::PARAMETER => 'b',
                    ExampleSnippet::OPTIONAL => true,
                    ExampleSnippet::DEFAULT => 2,
                ],
                [
                    ExampleSnippet::TYPE => \stdClass::class,
                    ExampleSnippet::PARAMETER => 'anObject',
                    ExampleSnippet::OPTIONAL => true,
                    ExampleSnippet::DEFAULT => null,
                ],
            ],
            ExampleSnippet::RETURN_TYPE => null,
        ]);
    }

    #[Test]
    function captures_information_from_functions_with_return_type(): void
    {
        enlighten(function () {
            return function (): int {
                return 1 + 2;
            };
        });

        $this->assertFirstSnippetReturns([
            ExampleSnippet::FUNCTION => ExampleSnippet::ANONYMOUS_FUNCTION,
            ExampleSnippet::PARAMETERS => [],
            ExampleSnippet::RETURN_TYPE => 'int',
        ]);
    }


    private function assertFirstSnippetReturns(array $expected): void
    {
        $example = Example::firstOrFail();

        tap($snippet = $example->snippets()->first(), function ($snippet) use ($expected) {
            $this->assertInstanceOf(ExampleSnippet::class, $snippet);
            $this->assertSame($expected, $snippet->result);
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
