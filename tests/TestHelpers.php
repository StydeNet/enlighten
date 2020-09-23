<?php

namespace Tests;

use Styde\Enlighten\Example;

trait TestHelpers
{
    protected function assertExampleIsNotCreated()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('user', [
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');

        $this->assertSame(0, Example::count());
    }
}
