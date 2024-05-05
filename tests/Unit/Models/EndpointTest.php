<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\Endpoint;
use Tests\TestCase;

class EndpointTest extends TestCase
{
    #[Test]
    function make_an_endpoint_instance()
    {
        $endpoint = new Endpoint('GET', '/users');

        $this->assertSame('GET', $endpoint->method);
        $this->assertSame('/users', $endpoint->route);
    }
}
