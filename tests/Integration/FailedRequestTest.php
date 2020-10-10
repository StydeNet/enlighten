<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\HttpData;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
        } catch (Throwable $throwable) {
            $example = Example::first();

            $this->assertNotNull($example);

            tap($example->http_data->first(), function (?HttpData $httpData) {
                $this->assertNotNull($httpData);

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

    /** @test */
    function saves_the_information_from_the_http_exceptions_with_exception_handling()
    {
        $this->get('/server-error')
            ->assertStatus(500);

        $this->saveTestExample();

        $example = Example::first();

        $this->assertNotNull($example);

        tap($example->http_data->first(), function (HttpData $httpData) {
            $this->assertEquals(500, $httpData->response_status);
        });

        tap($example->exception, function ($exception) {
            $this->assertNotNull($exception);

            $this->assertSame(HttpException::class, $exception->class_name);
            $this->assertSame(0, $exception->code);
            $this->assertSame('Server error', $exception->message);
            $this->assertIsArray($exception->trace);

            $this->assertStringEndsWith('src/Illuminate/Foundation/helpers.php', $exception->trace[0]['file']);
            $this->assertSame('Illuminate\Foundation\Application', $exception->trace[0]['class']);

            // $exception->trace ?
        });
    }

    /** @test */
    function saves_the_information_from_the_http_exceptions_without_exception_handling()
    {
        $this->withoutExceptionHandling();

        try {
            $this->get('/server-error');
        } catch (Throwable $exception) {
            $this->saveTestExample();

            $example = Example::first();

            $this->assertNotNull($example);

            tap($example->http_data->first(), function (?HttpData $httpData) {
                $this->assertNull($httpData->response_status);
            });

            tap($example->exception, function ($exception) {
                $this->assertNotNull($exception);
                $this->assertSame('Server error', $exception->message);
            });

            return;
        }

        $this->fail("The HTTP request (/server-error) didn't fail as expected.");
    }
}
