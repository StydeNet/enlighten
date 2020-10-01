<?php

namespace Tests\TestSuites\Integration;

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

            $this->assertTrue($example->has_redirection_status);
        });
    }

    /** @test */
    function the_email_must_be_required()
    {
        $response = $this->post('user', [
            'name' => 'Duilio',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('users', []);

        tap(Example::first(), function (Example $example) {
            $this->assertTrue($example->has_redirection_status);

            $this->assertSame([
                'default' => [
                    'email' => ['The email field is required.'],
                ],
            ], $example->validation_errors);
        });
    }
}
