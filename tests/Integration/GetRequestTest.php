<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\Integration\App\Models\User;

/**
 * @testdox Shows the user's information
 * @description This endpoint allows you to get all the info from a specific user.
 */
class GetRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Get user data by ID
     * @description Retrieves the public-user data
     */
    public function creates_an_example_of_a_get_request(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'name' => 'Duilio Palacios',
            'email' => 'user@example.test'
        ]);

        $response = $this->get(route('user.show', ['user' => $user]));

        $response
            ->assertOk()
            ->assertViewIs('user.show')
            ->assertSee('Duilio Palacios')
            ->assertSee('user@example.test');

        tap(ExampleGroup::first(), function (ExampleGroup $exampleGroup) {
            $this->assertSame('Tests\Integration\GetRequestTest', $exampleGroup->class_name);
            $this->assertSame("Shows the user's information", $exampleGroup->title);
            $this->assertSame('This endpoint allows you to get all the info from a specific user', $exampleGroup->description);
        });

        tap(Example::first(), function (Example $example) use ($user) {
            $this->assertSame('Get user data by ID', $example->title);
            $this->assertSame('Retrieves the public-user data', $example->description);
            $this->assertSame('GET', $example->http_data->request_method);
            $this->assertSame("user/{$user->id}", $example->http_data->request_path);
            $this->assertSame('user/{user}', $example->http_data->route);
            $this->assertSame([
                [
                    'name' => 'user',
                    'pattern' => '\d+',
                    'optional' => false,
                ]
            ], $example->http_data->route_parameters);

            $this->assertStringContainsString($user->name, $example->http_data->response_body);
            $this->assertStringContainsString($user->email, $example->http_data->response_body);

            $this->assertStringContainsString('{{ $user->name }}', $example->http_data->response_template);
            $this->assertStringContainsString('{{ $user->email }}', $example->http_data->response_template);
        });
    }
}
