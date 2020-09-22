<?php

namespace Tests\Suites\Feature;

use Examples\ShowsUserDataExample;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Example;
use Tests\App\Models\User;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Get user data by ID
     * @description Retrieves the public-user data
     */
    public function shows_user_data(): void
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

        tap(Example::first(), function (Example $example) use ($user) {
            $this->assertSame('Get user data by ID', $example->title);
            $this->assertSame('Retrieves the public-user data', $example->description);
            $this->assertSame('GET', $example->request_method);
            $this->assertSame("user/{$user->id}", $example->request_path);
            $this->assertSame('user/{user}', $example->route);
            $this->assertSame([
                [
                    'name' => 'user',
                    'pattern' => '\d+',
                    'optional' => false,
                ]
            ], $example->route_parameters);

            $this->assertStringContainsString($user->name, $example->response_body);
            $this->assertStringContainsString($user->email, $example->response_body);

            $this->assertStringContainsString('{{ $user->name }}', $example->response_template);
            $this->assertStringContainsString('{{ $user->email }}', $example->response_template);
        });
    }
}
