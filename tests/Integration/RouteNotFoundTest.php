<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\ExampleRequest;

class RouteNotFoundTest extends TestCase
{
    /** @test */
    function gets_a_non_existing_url()
    {
        $this->get('not-found-url')
            ->assertNotFound();

        tap(ExampleRequest::first(), function ($request) {
            $this->assertSame('not-found-url', $request->request_path);
            $this->assertNull($request->route);
            $this->assertNull($request->route_parameters);
        });
    }
}
