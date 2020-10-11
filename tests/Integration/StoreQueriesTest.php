<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleQuery;
use Tests\Integration\App\Models\User;

class StoreQueriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_stores_the_queries_executed_during_the_test()
    {
        User::first();

        $example = Example::first();

        $this->assertNotNull($example);

        tap($example->queries->first(), function (ExampleQuery $exampleQuery) {
            $this->assertNotNull($exampleQuery);

            $this->assertSame('select * from "users" limit 1', $exampleQuery->sql);
            $this->assertNotNull($exampleQuery->time);
            $this->assertSame([], $exampleQuery->bindings);
        });
    }
}
