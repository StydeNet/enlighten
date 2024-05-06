<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\ExampleCreator;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class FailedRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force the example group to be recreated after each test
        // Since we are also refreshing the Enlighten database
        // after each test method is executed by PHPUnit.
        ExampleCreator::clearExampleGroupBuilder();
    }

    #[Test]
    function creates_example_even_if_the_request_fails(): void
    {
        $response = $this->get('/server-error');

        $response->assertStatus(500);

        tap(Example::first(), function (?Example $example) {
            $this->assertNotNull($example);
        });
    }

    #[Test]
    function creates_example_with_request_data_without_exception_handling(): void
    {
        $this->withoutExceptionHandling();

        try {
            $this->get('/server-error');
        } catch (Throwable) {
            $example = Example::first();

            $this->assertNotNull($example);

            tap($example->requests->first(), function (?ExampleRequest $request) {
                $this->assertNotNull($request);

                $this->assertSame('GET', $request->request_method);
                $this->assertSame('server-error', $request->request_path);

                $this->assertNull($request->route);
                $this->assertNull($request->response_status);
                $this->assertNull($request->response_body);
            });

            return;
        }

        $this->fail("The HTTP request (/server-error) didn't fail as expected.");
    }

    #[Test]
    function saves_the_information_from_the_http_exceptions_with_exception_handling(): void
    {
        $this->get('/server-error')
            ->assertStatus(500);

        $this->saveExampleStatus();

        $example = Example::first();

        $this->assertNotNull($example);

        tap($example->requests->first(), function (ExampleRequest $request) {
            $this->assertEquals(500, $request->response_status);
        });

        tap($example->exception, function ($exception) {
            $this->assertNotNull($exception);

            $this->assertSame(HttpException::class, $exception->class_name);
            $this->assertSame(0, $exception->code);
            $this->assertSame('Server error', $exception->message);
            $this->assertIsArray($exception->trace);

            $this->assertStringEndsWith(
                str_replace('/', DIRECTORY_SEPARATOR, 'src/Illuminate/Foundation/helpers.php'),
                $exception->trace[0]['file']
            );
            $this->assertSame(\Illuminate\Foundation\Application::class, $exception->trace[0]['class']);
        });
    }

    #[Test]
    function saves_the_information_from_the_http_exceptions_without_exception_handling(): void
    {
        $this->withoutExceptionHandling();

        try {
            $this->get('/server-error');
        } catch (Throwable $exception) {
            $this->saveExampleStatus();

            $example = Example::first();

            $this->assertNotNull($example);

            tap($example->requests->first(), function (?ExampleRequest $request) {
                $this->assertNull($request->response_status);
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
