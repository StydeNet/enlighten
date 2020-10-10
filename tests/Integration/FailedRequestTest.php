<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\HttpData;

class FailedRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creates_example_even_if_the_request_fails()
    {
        $response = $this->get('/server-error');

        $response->assertStatus(500);

        tap(Example::first(), function (?Example $example) {
            $this->assertNotNull($example);
        });
    }

    /** @test */
    function creates_example_with_request_data_without_exception_handling()
    {
        $this->withoutExceptionHandling();

        try {
            $this->get('/server-error');
        } catch (\Throwable $throwable) {
            $example = Example::first();

            tap($example, function (?Example $example) {
                $this->assertNotNull($example);
            });

            tap($example->http_data, function (?HttpData $httpData) {
                $this->assertSame('GET', $httpData->request_method);
                $this->assertSame('server-error', $httpData->request_path);

                $this->assertNull($httpData->route);
                $this->assertNull($httpData->response_status);
                $this->assertNull($httpData->response_body);
            });

            return;
        }

        $this->fail("The HTTP request (/server-error) didn't fail as expected.");
    }
}
