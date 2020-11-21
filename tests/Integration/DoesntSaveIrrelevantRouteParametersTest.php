<?php

namespace Tests\Integration;

use Illuminate\Support\Facades\Route;
use Styde\Enlighten\Models\ExampleRequest;

class DoesntSaveIrrelevantRouteParametersTest extends TestCase
{
    /** @test */
    function does_not_save_irrelevant_route_parameters()
    {
        $this->get('parameters/global/local')
            ->assertOk();

        tap(ExampleRequest::first(), function ($request) {
            $this->assertNotNull($request);

            $this->assertSame('GET', $request->request_method);
            $this->assertSame('parameters/global/local', $request->request_path);

            $this->assertSame('parameters/{global}/{local}/{optional?}', $request->route);
            $this->assertSame([
                [
                    'name' => 'global',
                    'pattern' => '[a-z]+',
                    'optional' => false,
                ],
                [
                    'name' => 'local',
                    'pattern' => '*',
                    'optional' => false,
                ],
                [
                    'name' => 'optional',
                    'pattern' => '*',
                    'optional' => true,
                ],
            ], $request->route_parameters);

            $this->assertFalse($request->follows_redirect);

            $this->assertSame('Test route parameters', $request->response_body);
        });
    }
}
