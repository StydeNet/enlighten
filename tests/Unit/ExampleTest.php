<?php

namespace Tests\TestSuites\Unit;

use PHPUnit\Framework\TestCase;
use Styde\Enlighten\Example;

class ExampleTest extends TestCase
{
    /** @test */
    function gets_the_full_path_of_the_request()
    {
        $example = new Example([
            'request_path' => 'api/users',
        ]);

        $this->assertSame('api/users', $example->full_path);

        $example = new Example([
            'request_path' => 'api/users',
            'request_query_parameters' => ['page' => 2, 'status' => 'active'],
        ]);

        $this->assertSame('api/users?page=2&status=active', $example->full_path);
    }

    /** @test */
    function gets_the_response_type_in_a_readable_format()
    {
        $example = new Example([
            'response_headers' => [
                'content-type' => ['application/json'],
            ]
        ]);

        $this->assertSame('JSON', $example->response_type);

        $example = new Example([
            'response_headers' => [
                'content-type' => ['text/html'],
            ]
        ]);

        $this->assertSame('HTML', $example->response_type);
    }

    /** @test */
    function checks_if_a_response_is_a_redirect()
    {
        $example = new Example([
            'response_status' => 200,
        ]);

        $this->assertFalse($example->has_redirection_status);

        $example = new Example([
            'response_status' => 301,
        ]);

        $this->assertTrue($example->has_redirection_status);

        $example = new Example([
            'response_status' => 302,
        ]);

        $this->assertTrue($example->has_redirection_status);

        $example = new Example([
            'response_status' => 308,
        ]);

        $this->assertTrue($example->has_redirection_status);
    }

    /** @test */
    function gets_redirection_location_from_the_response()
    {
        $example = new Example([
            'response_headers' => ['location' => 'http://localhost/foo'],
        ]);

        $this->assertSame('http://localhost/foo', $example->redirection_location);

        $example = new Example;

        $this->assertNull($example->redirection_location);
    }
}
