<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;
use Tests\Integration\App\Models\User;
use Tests\Integration\Database\Factories\UserFactory;

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

        User::create([
            'name' => 'Duilio Palacios',
            'email' => 'duilio@example.com',
            'password' => '1234',
        ]);

        User::create([
            'name' => 'Jeffer Ochoa',
            'email' => 'jeff.ochoa@example.com',
            'password' => '1234',
        ]);

        $this->get('api/users')
            ->assertOk()
            ->assertJson([
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

        $run = Run::first();

        $this->assertNotNull($run, 'A Run record was not created in the database.');

        tap($group = $run->groups()->first(), function (ExampleGroup $exampleGroup) {
            $this->assertSame('Tests\Integration\ApiRequestTest', $exampleGroup->class_name);
            $this->assertSame('Api Request', $exampleGroup->title);
            $this->assertNull($exampleGroup->description);
        });

        tap($group->examples()->first(), function (Example $example) use ($group) {
            $this->assertTrue($example->group->is($group));
            $this->assertSame('gets_the_list_of_users', $example->method_name);
            $this->assertSame('Obtiene la lista de usuarios', $example->title);
            $this->assertSame('Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema', $example->description);

            tap($example->requests->first(), function ($request) {
                $this->assertNotNull($request);

                $this->assertSame('GET', $request->request_method);
                $this->assertSame('api/users', $request->request_path);

                $this->assertSame('api/users/{status?}/{page?}', $request->route);
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
                ], $request->route_parameters);

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
                ], $request->response_body);
            });
        });
    }
}
