<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Styde\Enlighten\RequestInspector;
use Styde\Enlighten\RouteInspector;
use Tests\TestCase;

class ReplaceRequestHeadersTest extends TestCase
{
    /** @test */
    function can_remove_headers()
    {
        $request = new Request([], [], [], [], [], [
            'HTTP_HOST' => 'localhost',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_USER_AGENT' => 'Mozilla',
        ]);

        $request->setRouteResolver(fn() => new Route('GET', 'users', fn() => null));

        $requestInspector = new RequestInspector(new RouteInspector, [
            'headers' => [
                'ignore' => [
                    'host',
                ],
            ]
        ]);

        $headers = $requestInspector->getDataFrom($request)->getHeaders();

        $this->assertSame([
            'accept' => ['application/json'],
            'user-agent' => ['Mozilla'],
        ], $headers);
    }

    /** @test */
    function can_overwrite_headers()
    {
        $request = new Request([], [], [], [], [], [
            'HTTP_HOST' => 'localhost',
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $request->setRouteResolver(fn() => new Route('GET', 'users', fn() => null));

        $requestInspector = new RequestInspector(new RouteInspector, [
            'headers' => [
                'overwrite' => [
                    'host' => ['127.0.0.1'],
                ],
            ]
        ]);

        $headers = $requestInspector->getDataFrom($request)->getHeaders();

        $this->assertSame([
            'host' => ['127.0.0.1'],
            'accept' => ['application/json'],
        ], $headers);
    }

    /** @test */
    function ignored_headers_take_precedence_over_overwritten_headers()
    {
        $request = new Request([], [], [], [], [], [
            'HTTP_HOST' => 'localhost',
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $request->setRouteResolver(fn() => new Route('GET', 'users', fn() => null));

        $requestInspector = new RequestInspector(new RouteInspector, [
            'headers' => [
                'ignore' => [
                    'host',
                ],
                'overwrite' => [
                    'host' => ['127.0.0.1'],
                ],
            ]
        ]);

        $headers = $requestInspector->getDataFrom($request)->getHeaders();

        $this->assertSame([
            'accept' => ['application/json'],
        ], $headers);
    }
}
