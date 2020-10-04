<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;

class FailedRequestTest extends TestCase
{
    /** @test */
    function creates_example_even_if_the_request_fails()
    {
        $response = $this->get('/server-error');

        $response->assertStatus(500);

        tap(Example::first(), function (?Example $example) {
            $this->assertNotNull($example);
        });
    }
}
