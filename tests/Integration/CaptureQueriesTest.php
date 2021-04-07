<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleQuery;
use Tests\Integration\App\Models\User;

class CaptureQueriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_stores_the_queries_executed_during_the_test()
    {
        User::create([
            'name' => 'Duilio',
            'email' => 'duilio@styde.net',
            'password' => 'password',
        ]);

        $this->get('/user/1')
            ->assertOk();

        $user = User::first();

        $this->assertNotNull($user);
        $this->assertSame('Duilio', $user->name);

        $example = Example::first();

        $this->assertNotNull($example, 'The Example was not recorded as expected');

        $this->assertNotNull($example->requests->first(), 'The Example HTTP data was not recorded as expected');

        tap($example->queries->shift(), function (ExampleQuery $exampleQuery) {
            $this->assertNotNull($exampleQuery);

            $this->assertSame('insert into "users" ("name", "email", "password", "updated_at", "created_at") values (?, ?, ?, ?, ?)', $exampleQuery->sql);
            $this->assertNotNull($exampleQuery->time);
            $this->assertIsArray($exampleQuery->bindings);
            $this->assertSame('Duilio', $exampleQuery->bindings[0]);
            $this->assertSame('duilio@styde.net', $exampleQuery->bindings[1]);
            $this->assertSame('password', $exampleQuery->bindings[2]);
            $this->assertNull($exampleQuery->request_id);
            $this->assertSame('test', $exampleQuery->context);
        });

        tap($example->queries->shift(), function (ExampleQuery $exampleQuery) use ($example) {
            $this->assertNotNull($exampleQuery);

            $this->assertSame('select * from "users" where "id" = ? limit 1', $exampleQuery->sql);
            $this->assertSame([0 => '1'], $exampleQuery->bindings);
            $this->assertSame('request', $exampleQuery->context);
            $this->assertTrue($exampleQuery->request->is($example->requests->first()));
        });

        tap($example->queries->shift(), function (ExampleQuery $exampleQuery) {
            $this->assertNotNull($exampleQuery);

            $this->assertSame('select * from "users" limit 1', $exampleQuery->sql);
            $this->assertSame([], $exampleQuery->bindings);
            $this->assertNull($exampleQuery->request_id);
            $this->assertSame('test', $exampleQuery->context);
        });
    }

    /** @test */
    function links_queries_to_the_snippet_context()
    {
        $user = enlighten(function () {
            return User::create([
                'name' => 'Duilio',
                'email' => 'duilio@styde.net',
                'password' => 'password',
            ]);
        });

        $this->assertNotNull($user);
        $this->assertSame('Duilio', $user->name);

        $example = Example::first();

        $this->assertNotNull($example, 'The Example was not recorded as expected');

        $this->assertNotNull($example->snippets->first(), 'The Example HTTP data was not recorded as expected');

        tap($example->queries->shift(), function ($exampleQuery) use ($example) {
            $this->assertInstanceOf(ExampleQuery::class, $exampleQuery);

            $this->assertSame('insert into "users" ("name", "email", "password", "updated_at", "created_at") values (?, ?, ?, ?, ?)', $exampleQuery->sql);
            $this->assertSame($example->snippets->first()->id, $exampleQuery->snippet_id);
            $this->assertSame('snippet', $exampleQuery->context);
        });
    }
}
