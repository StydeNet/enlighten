<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleRequest;

class RouteNotFoundTest extends TestCase
{
    #[Test]
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
