<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\HttpData;
use Tests\TestCase;

class HttpDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_example_has_an_http_data_relationship()
    {
        $group = $this->createExampleGroup();

        $example = $this->createExampleInGroup($group);

        $this->assertInstanceOf(HasOne::class, $example->http_data());
        $this->assertFalse($example->http_data->exists);
        $this->assertFalse($example->is_http);

        $example->http_data()->create($this->getHttpDataAttributes());

        $example->refresh();

        $this->assertTrue($example->http_data->exists);
        $this->assertTrue($example->is_http);
        $this->assertInstanceOf(HttpData::class, $example->http_data);
    }

    /** @test */
    function gets_the_full_path_of_the_request()
    {
        $data = new HttpData([
            'request_path' => 'api/users',
        ]);

        $this->assertSame('api/users', $data->full_path);

        $data = new HttpData([
            'request_path' => 'api/users',
            'request_query_parameters' => ['page' => 2, 'status' => 'active'],
        ]);

        $this->assertSame('api/users?page=2&status=active', $data->full_path);
    }

    /** @test */
    function gets_the_response_type_in_a_readable_format()
    {
        $data = new HttpData([
            'response_headers' => [
                'content-type' => ['application/json'],
            ]
        ]);

        $this->assertSame('JSON', $data->response_type);

        $data = new HttpData([
            'response_headers' => [
                'content-type' => ['text/html'],
            ]
        ]);

        $this->assertSame('HTML', $data->response_type);

        $data = new HttpData([
           // without headers
        ]);

        $this->assertSame('UNDEFINED', $data->response_type);
    }

    /** @test */
    function checks_if_a_response_is_a_redirect()
    {
        $data = new HttpData([
            'response_status' => 200,
        ]);

        $this->assertFalse($data->has_redirection_status);

        $data = new HttpData([
            'response_status' => 301,
        ]);

        $this->assertTrue($data->has_redirection_status);

        $data = new HttpData([
            'response_status' => 302,
        ]);

        $this->assertTrue($data->has_redirection_status);

        $data = new HttpData([
            'response_status' => 308,
        ]);

        $this->assertTrue($data->has_redirection_status);
    }

    /** @test */
    function gets_redirection_location_from_the_response()
    {
        $data = new HttpData([
            'response_headers' => ['location' => 'http://localhost/foo'],
        ]);

        $this->assertSame('http://localhost/foo', $data->redirection_location);

        $data = new HttpData();

        $this->assertNull($data->redirection_location);
    }
}
