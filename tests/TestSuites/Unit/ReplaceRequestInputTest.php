<?php

namespace Tests\TestSuites\Unit;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Styde\Enlighten\RequestInspector;
use Styde\Enlighten\RouteInspector;
use Tests\TestCase;

class ReplaceRequestInputTest extends TestCase
{
    /** @test */
    function can_remove_and_overwrite_request_input_values()
    {
        $request = new Request([], [
            'username' => 'original_username',
            'email' => 'original@example.test',
            'password' => 'secret-password',
        ]);

        $request->setMethod('POST');

        $request->setRouteResolver(fn() => new Route('GET', 'users', fn() => null));

        $requestInspector = new RequestInspector(new RouteInspector, [
            'input' => [
                'exclude' => [
                    'password',
                ],
                'overwrite' => [
                    'password' => '1234',
                    'email' => 'replaced@example.test',
                ]
            ]
        ]);

        $input = $requestInspector->getInfoFrom($request)->getInput();

        $expected = [
            'username' => 'original_username',
            'email' => 'replaced@example.test',
        ];
        $this->assertSame($expected, $input);
    }
}
