<?php

namespace Tests\Integration;

class FailedRequestTest extends TestCase
{
    /** @test */
    function creates_an_example_of_a_failed_request()
    {
        $response = $this->get('/server-error');

        $response->assertStatus(500);
    }
}
