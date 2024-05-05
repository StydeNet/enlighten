<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleRequest;
use Tests\TestCase;

class HideRequestHeadersTest extends TestCase
{
    #[Test]
    function hides_request_headers()
    {
        $request = new ExampleRequest([
            'request_headers' => [
                'host' => 'localhost',
                'accept' => ['application/json'],
                'user-agent' => ['Mozilla'],
            ],
        ]);

        $this->setConfig([
            'enlighten.request.headers' => [
                'hide' => [
                    'host',
                ],
            ],
        ]);

        $this->assertSame([
            'accept' => ['application/json'],
            'user-agent' => ['Mozilla'],
        ], $request->request_headers);
    }

    #[Test]
    function can_overwrite_headers()
    {
        $request = new ExampleRequest([
            'request_headers' => [
                'host' => ['original.host'],
                'accept' => ['application/json'],
            ],
        ]);

        $this->setConfig([
            'enlighten.request.headers' => [
                'overwrite' => [
                    'host' => ['overwritten.host'],
                ],
            ],
        ]);

        $this->assertSame([
            'host' => ['overwritten.host'],
            'accept' => ['application/json'],
        ], $request->request_headers);
    }

    #[Test]
    function hidden_headers_take_precedence_over_overwritten_headers()
    {
        $request = new ExampleRequest([
            'request_headers' => [
                'host' => 'localhost',
                'accept' => ['application/json'],
            ],
        ]);

        $this->setConfig([
            'enlighten.request.headers' => [
                'hide' => [
                    'host',
                ],
                'overwrite' => [
                    'host' => ['overwritten.host'],
                ],
            ],
        ]);

        $this->assertSame([
            'accept' => ['application/json'],
        ], $request->request_headers);
    }
}
