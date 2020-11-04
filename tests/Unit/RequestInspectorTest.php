<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Styde\Enlighten\HttpExamples\RequestInspector;
use Styde\Enlighten\HttpExamples\RouteInspector;
use Tests\TestCase;

class RequestInspectorTest extends TestCase
{
    /** @test */
    function gets_the_form_data_from_the_request_without_query_parameters()
    {
        $request = new Request([
            'query' => 'parameter',
        ], [
            'input' => 'value',
        ]);

        $request->setMethod('POST');

        $request->setRouteResolver(function () {
            return new Route('GET', 'users', function () {
            });
        });

        $requestInspector = new RequestInspector(new RouteInspector, []);

        $input = $requestInspector->getDataFrom($request)->getInput();

        $this->assertSame(['input' => 'value'], $input);
    }
}
