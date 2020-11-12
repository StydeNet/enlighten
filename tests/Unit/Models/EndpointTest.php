<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\Endpoint;
use Tests\TestCase;

class EndpointTest extends TestCase
{
    /** @test */
    function make_an_endpoint_instance()
    {
        $endpoint = new Endpoint('GET', '/users');

        $this->assertSame('GET', $endpoint->method);
        $this->assertSame('/users', $endpoint->route);
    }
}
