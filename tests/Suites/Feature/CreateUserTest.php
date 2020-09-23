<?php

namespace Tests\Suites\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Example;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('user', [
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');

        $this->assertCredentials([
            'name' => 'Duilio',
            'email' => 'duilio@example.test',
            'password' => 'my-password',
        ]);

        tap(Example::first(), function (Example $example) {
            $this->assertSame('POST', $example->request_method);
            $this->assertSame('user', $example->request_path);
            $this->assertSame('user', $example->route);

            $this->assertSame([
                'name' => 'Duilio',
                'email' => 'duilio@example.test',
                'password' => 'my-password',
            ], $example->request_input);

            // @TODO: responseRedirect? responseBodyAsRedirect????
        });
    }
}
