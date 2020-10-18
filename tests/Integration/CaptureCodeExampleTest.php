<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleSnippet;
use Tests\Integration\App\Models\User;

class CaptureCodeExampleTest extends TestCase
{
    /** @test */
    function captures_snippet_example()
    {
        $sum = enlighten(function ($a, $b) {
            return $a + $b;
        }, 1, 2);

        $this->assertSame(3, $sum);

        $this->saveTestExample();

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

        $this->saveTestExample();

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

        $this->saveTestExample();

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
            $this->assertNull($query->http_data_id);
            $this->assertSame($snippet->id, $query->snippet_id);
        });
    }

    /** @test */
    function captures_snippet_with_exception()
    {
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
}
