<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroup;
use Tests\Integration\App\Models\User;

class ApiRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @testdox Obtiene la lista de usuarios.
     * @description Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema.
     */
    function gets_the_list_of_users()
    {
        $this->withoutExceptionHandling();

        User::factory()->create([
            'name' => 'Duilio Palacios',
            'email' => 'duilio@example.com',
        ]);

        User::factory()->create([
            'name' => 'Jeffer Ochoa',
            'email' => 'jeff.ochoa@example.com',
        ]);

        $this->get('api/users')
            ->assertOk()
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

        tap($group = ExampleGroup::first(), function (ExampleGroup $exampleGroup) {
            $this->assertSame('Tests\Integration\ApiRequestTest', $exampleGroup->class_name);
            $this->assertSame('Api Request', $exampleGroup->title);
            $this->assertNull($exampleGroup->description);
        });

        tap(Example::first(), function (Example $example) use ($group) {
            $this->assertTrue($example->group->is($group));
            $this->assertSame('gets_the_list_of_users', $example->method_name);
            $this->assertSame('Obtiene la lista de usuarios', $example->title);
            $this->assertSame('Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema', $example->description);
            $this->assertSame('GET', $example->http_data->request_method);
            $this->assertSame('api/users', $example->http_data->request_path);

            $this->assertSame('api/users/{status?}/{page?}', $example->http_data->route);
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
            ], $example->http_data->route_parameters);

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
            ], $example->http_data->response_body);
        });
    }
}
