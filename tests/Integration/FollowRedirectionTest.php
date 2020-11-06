<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleRequest;

class FollowRedirectionTest extends TestCase
{
    /** @test */
    function saves_the_first_request_and_the_last_response()
    {
        $this->followingRedirects()
            ->get('redirect-1')
            ->assertOk()
            ->assertSee('Final Response');

        $example = Example::first();
        $this->assertNotNull($example);

        $this->assertCount(3, $example->requests);

        // First request
        tap($example->requests->shift(), function (ExampleRequest $request) {
            $this->assertNotNull($request);

            $this->assertSame('redirect-1', $request->request_path);
            $this->assertSame('redirect-1', $request->route);
            $this->assertSame(302, $request->response_status);
            $this->assertSame('http://localhost/redirect-2', $request->redirection_location);
        });

        // Second request
        tap($example->requests->shift(), function (ExampleRequest $request) {
            $this->assertNotNull($request);

            $this->assertSame('redirect-2', $request->request_path);
            $this->assertSame('redirect-2', $request->route);
            $this->assertSame(302, $request->response_status);
            $this->assertSame('http://localhost/redirect-3', $request->redirection_location);
        });

        // Third request
        tap($example->requests->shift(), function (ExampleRequest $request) {
            $this->assertNotNull($request);

            $this->assertSame('redirect-3', $request->request_path);
            $this->assertSame('redirect-3', $request->route);
            $this->assertSame(200, $request->response_status);
            $this->assertSame('Final Response', $request->response_body);
        });
    }
}
