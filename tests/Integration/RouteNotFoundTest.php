<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\HttpData;

class RouteNotFoundTest extends TestCase
{
    /** @test */
    function gets_a_non_existing_url()
    {
        $this->get('not-found-url')
            ->assertNotFound();

        tap(HttpData::first(), function ($httpData) {
            $this->assertSame('not-found-url', $httpData->request_path);
            $this->assertNull($httpData->route);
            $this->assertNull($httpData->route_parameters);
        });
    }
}
