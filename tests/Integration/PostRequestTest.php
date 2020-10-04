<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;

class PostRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creates_an_example_of_a_post_request()
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
            $this->assertSame('POST', $example->http_data->request_method);
            $this->assertSame('user', $example->http_data->request_path);
            $this->assertSame('user', $example->http_data->route);

            $this->assertSame([
                'name' => 'Duilio',
                'email' => 'duilio@example.test',
                'password' => 'my-password',
            ], $example->http_data->request_input);

            $this->assertTrue($example->http_data->has_redirection_status);
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
            $this->assertTrue($example->http_data->has_redirection_status);

            $this->assertSame([
                'default' => [
                    'email' => ['The email field is required.'],
                ],
            ], $example->http_data->validation_errors);
        });
    }
}
