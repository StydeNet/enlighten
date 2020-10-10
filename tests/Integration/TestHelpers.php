<?php

namespace Tests\Integration;

use Styde\Enlighten\Models\Example;

trait TestHelpers
{
    protected function loadViewsFrom($dir): void
    {
        $this->app['view']->addLocation($dir);
    }

    protected function assertExampleIsCreated()
    {
        $this->sendPostRequest();

        $this->assertSame(1, Example::count(), 'The expected example was not created.');
    }

    protected function assertExampleIsNotCreated()
    {
        $this->sendPostRequest();

        $this->assertSame(0, Example::count(), 'An unexpected example was created.');
    }

    protected function sendPostRequest()
    {
        $response = $this->post('user', [
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');
    }
}
