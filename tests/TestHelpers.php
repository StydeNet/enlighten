<?php

namespace Tests;

use Styde\Enlighten\Example;

trait TestHelpers
{
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
        $this->withoutExceptionHandling();

        $response = $this->post('user', [
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');
    }

    protected function setConfig(array $config)
    {
        $this->app->config->set($config);
    }
}
