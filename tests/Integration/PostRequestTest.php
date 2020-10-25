<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\HttpData;

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
            tap($example->http_data->first(), function (?HttpData $httpData) {
                $this->assertNotNull($httpData);

                $this->assertSame('POST', $httpData->request_method);
                $this->assertSame('user', $httpData->request_path);
                $this->assertSame('user', $httpData->route);

                $this->assertSame([
                    'name' => 'Duilio',
                    'email' => 'duilio@example.test',
                    'password' => 'my-password',
                ], $httpData->request_input);

                $this->assertTrue($httpData->has_redirection_status);
            });
        });
    }

    /** @test */
    function saves_validation_errors()
    {
        $response = $this->post('user', [
            'name' => 'Duilio',
            'password' => 'my-password',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('users', []);

        tap(Example::first()->http_data->first(), function (?HttpData $httpData) {
            $this->assertTrue($httpData->has_redirection_status);

            $this->assertSame([
                'default' => [
                    'email' => ['The email field is required.'],
                ],
            ], $httpData->validation_errors);
        });
    }
}
