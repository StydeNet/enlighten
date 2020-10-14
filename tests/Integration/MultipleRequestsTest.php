<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\HttpData;

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

        $this->assertCount(2, $example->http_data);

        tap($example->http_data->first(), function (HttpData $httpData) {
            $this->assertSame('request/1', $httpData->request_path);
        });

        tap($example->http_data->last(), function (HttpData $httpData) {
            $this->assertSame('request/2', $httpData->request_path);
        });
    }
}
