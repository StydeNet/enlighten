<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleRequest;

class MultipleRequestsTest extends TestCase
{
    /** @test */
    function can_store_multiple_requests()
    {
        $this->withoutExceptionHandling();

        $this->get('request/1')
            ->assertSeeText('Request 1');

        $this->get('request/2')
            ->assertSeeText('Request 2');

        $example = Example::first();

        $this->assertNotNull($example);

        $this->assertCount(2, $example->requests);

        tap($example->requests->first(), function (ExampleRequest $request) {
            $this->assertSame('request/1', $request->request_path);
        });

        tap($example->requests->last(), function (ExampleRequest $request) {
            $this->assertSame('request/2', $request->request_path);
        });
    }
}
