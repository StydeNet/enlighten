<?php

namespace Tests\Suites\Api;

use Examples\GetsTheListOfUsersExample;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\App\Models\User;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Obtiene la lista de usuarios.
     * @description Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema.
     */
    function gets_the_list_of_users()
    {
        User::factory()->create([
            'name' => 'Duilio Palacios',
            'email' => 'duilio@example.com',
        ]);

        User::factory()->create([
            'name' => 'Jeffer Ochoa',
            'email' => 'jeff.ochoa@example.com',
        ]);

        $response = $this->get('api/users')
            ->assertSimilarJson([
                'data' => [
                    [
                        'name' => 'Duilio Palacios',
                        'email' => 'duilio@example.com',
                    ],
                    [
                        'name' => 'Jeffer Ochoa',
                        'email' => 'jeff.ochoa@example.com',
                    ],
                ]
            ]);

        tap(new GetsTheListOfUsersExample, function ($example) {
            $this->assertSame('Obtiene la lista de usuarios', $example->title);
            $this->assertSame('Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema', $example->description);
            $this->assertSame('GET', $example->request_method);
            $this->assertSame('api/users', $example->request_path);

            $this->assertSame('api/users/{status?}/{page?}', $example->route);
            $this->assertSame([
                [
                    'name' => 'status',
                    'pattern' => '*',
                    'optional' => true,
                ],
                [
                    'name' => 'page',
                    'pattern' => '*',
                    'optional' => true,
                ]
            ], $example->route_parameters);

            $this->assertSame([
                'data' => [
                    [
                        'name' => 'Duilio Palacios',
                        'email' => 'duilio@example.com',
                    ],
                    [
                        'name' => 'Jeffer Ochoa',
                        'email' => 'jeff.ochoa@example.com',
                    ],
                ]
            ], $example->response_body);
        });
    }
}
