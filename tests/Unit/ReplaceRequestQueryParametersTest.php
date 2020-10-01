<?php

namespace Tests\TestSuites\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Styde\Enlighten\RequestInspector;
use Styde\Enlighten\RouteInspector;
use Tests\TestCase;

class ReplaceRequestQueryParametersTest extends TestCase
{
    /** @test */
    function can_remove_and_overwrite_query_parameters()
    {
        $request = new Request([
            'page' => 2,
            'host' => 'original_host',
            'token' => 'secret_token',
        ]);

        $request->setRouteResolver(fn() => new Route('GET', 'users', fn() => null));

        $requestInspector = new RequestInspector(new RouteInspector, [
            'query' => [
                'ignore' => [
                    'token',
                ],
                'overwrite' => [
                    'token' => '1234',
                    'host' => 'replaced_host',
                ]
            ]
        ]);

        $parameters = $requestInspector->getInfoFrom($request)->getQueryParameters();

        $this->assertSame(['page' => 2, 'host' => 'replaced_host'], $parameters);
    }
}
