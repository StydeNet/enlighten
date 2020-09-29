<?php

namespace Tests\TestSuites\Unit;

use Styde\Enlighten\ResponseInspector;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ResponseInspectorTest extends TestCase
{
    /** @test */
    function can_remove_headers()
    {
        $response = new Response('', 200, [
            'secret-token' => 'this-should-be-removed',
            'content-type' => 'application/json',
        ]);

        $responseInspector = new ResponseInspector([
            'headers' => [
                'ignore' => [
                    'secret-token'
                ],
            ]
        ]);

        $headers = $responseInspector->getInfoFrom($response)->getHeaders();

        $this->assertSame(['application/json'], $headers['content-type']);
        $this->assertArrayNotHasKey('secret-token', $headers);
    }

    /** @test */
    function can_overwrite_headers()
    {
        $response = new Response('', 200, [
            'token' => 'this-value-should-be-replaced',
            'content-type' => 'application/json',
        ]);

        $responseInspector = new ResponseInspector([
            'headers' => [
                'overwrite' => [
                    'token' => '******',
                    'key-not-present' => 'this-value-should-not-be-included'
                ],
            ]
        ]);

        $headers = $responseInspector->getInfoFrom($response)->getHeaders();

        $this->assertSame(['application/json'], $headers['content-type']);
        $this->assertSame('******', $headers['token']);
        $this->assertArrayNotHasKey('key-not-present', $headers);
    }

    /** @test */
    function ignored_headers_take_precedence_over_overwritten_headers()
    {
        $response = new Response('', 200, [
            'token' => 'this-value-should-be-removed',
            'content-type' => 'application/json',
        ]);

        $responseInspector = new ResponseInspector([
            'headers' => [
                'ignore' => [
                    'token'
                ],
                'overwrite' => [
                    'token' => '******',
                ],
            ]
        ]);

        $headers = $responseInspector->getInfoFrom($response)->getHeaders();

        $this->assertSame(['application/json'], $headers['content-type']);
        $this->assertArrayNotHasKey('token', $headers);
    }
}
