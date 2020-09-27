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
}
