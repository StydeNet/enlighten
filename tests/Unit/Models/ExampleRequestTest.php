<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\ExampleRequest;
use Tests\TestCase;

class ExampleRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_example_can_have_many_requests()
    {
        $run = $this->createRun();

        $group = $this->createExampleGroup($run);

        $example = $this->createExample($group);

        $this->assertInstanceOf(HasMany::class, $example->requests());
        $this->assertInstanceOf(Collection::class, $example->requests);
        $this->assertCount(0, $example->requests);
        $this->assertFalse($example->is_http);

        $example->requests()->create($this->getExampleRequestAttributes());

        $example->refresh();

        $this->assertCount(1, $example->requests);
        $this->assertTrue($example->is_http);
    }

    /** @test */
    function gets_the_full_path_of_the_request()
    {
        $data = new ExampleRequest([
            'request_path' => 'api/users',
        ]);

        $this->assertSame('api/users', $data->full_path);

        $data = new ExampleRequest([
            'request_path' => 'api/users',
            'request_query_parameters' => ['page' => 2, 'status' => 'active'],
        ]);

        $this->assertSame('api/users?page=2&status=active', $data->full_path);
    }

    /** @test */
    function gets_the_response_type_in_a_readable_format()
    {
        $data = new ExampleRequest([
            'response_headers' => [
                'content-type' => ['application/json'],
            ]
        ]);

        $this->assertSame('JSON', $data->response_type);

        $data = new ExampleRequest([
            'response_headers' => [
                'content-type' => ['text/html'],
            ]
        ]);

        $this->assertSame('HTML', $data->response_type);

        $data = new ExampleRequest([
           // without headers
        ]);

        $this->assertSame('NO RESPONSE', $data->response_type);
    }

    /** @test */
    function checks_if_a_response_is_a_redirect()
    {
        $data = new ExampleRequest([
            'response_status' => 200,
        ]);

        $this->assertFalse($data->has_redirection_status);

        $data = new ExampleRequest([
            'response_status' => 301,
        ]);

        $this->assertTrue($data->has_redirection_status);

        $data = new ExampleRequest([
            'response_status' => 302,
        ]);

        $this->assertTrue($data->has_redirection_status);

        $data = new ExampleRequest([
            'response_status' => 308,
        ]);

        $this->assertTrue($data->has_redirection_status);
    }

    /** @test */
    function gets_redirection_location_from_the_response()
    {
        $data = new ExampleRequest([
            'response_headers' => ['location' => ['http://localhost/foo']],
        ]);

        $this->assertSame('http://localhost/foo', $data->redirection_location);

        $data = new ExampleRequest();

        $this->assertNull($data->redirection_location);
    }

    /** @test */
    public function gets_the_response_status_based_on_the_response_code(): void
    {
        $data = new ExampleRequest(['response_status' => 200]);

        $this->assertSame('success', $data->getStatus());

        $data = new ExampleRequest(['response_status' => 302]);

        $this->assertSame('default', $data->getStatus());

        $data = new ExampleRequest(['response_status' => 404]);

        $this->assertSame('warning', $data->getStatus());

        $data = new ExampleRequest(['response_status' => 500]);

        $this->assertSame('failure', $data->getStatus());

        $data = new ExampleRequest(['response_status' => null]);

        $this->assertSame('failure', $data->getStatus());
    }
}
