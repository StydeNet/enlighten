<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;
use Tests\Integration\App\Models\User;

class ApiDriverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->setConfig([
            'enlighten.driver' => 'api',
        ]);

        parent::setUp();
    }

    /**
     * @test
     * @testdox Obtiene la lista de usuarios.
     * @description Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema.
     */
    function gets_the_list_of_users()
    {
        $this->withoutExceptionHandling();

        Http::fake();

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

        $this->assertNull(Run::first(), 'An unexpected run was added to the database when using the API driver');
        $this->assertNull(ExampleGroup::first(), 'An unexpected group was added to the database when using the API driver');
        $this->assertNull(Example::first(), 'An unexpected group was added to the database when using the API driver');
        //
        //        tap($group = $run->groups()->first(), function (ExampleGroup $exampleGroup) {
        //            $this->assertSame('Tests\Integration\ApiDriverTest', $exampleGroup->class_name);
        //            $this->assertSame('Api Driver', $exampleGroup->title);
        //            $this->assertSame('integration-api-driver', $exampleGroup->slug);
        //            $this->assertNull($exampleGroup->description);
        //        });

        //        tap($group->examples()->first(), function (Example $example) use ($group) {
        //            $this->assertTrue($example->group->is($group));
        //            $this->assertSame('gets_the_list_of_users', $example->method_name);
        //            $this->assertSame('gets-the-list-of-users', $example->slug);
        //            $this->assertSame('Obtiene la lista de usuarios', $example->title);
        //            $this->assertSame('Obtiene los nombres y correos electrónicos de todos los usuarios registrados en el sistema', $example->description);
        //
        //            tap($example->requests->first(), function ($request) {
        //                $this->assertNotNull($request);
        //
        //                $this->assertSame('GET', $request->request_method);
        //                $this->assertSame('api/users', $request->request_path);
        //
        //                $this->assertSame('api/users/{status?}/{page?}', $request->route);
        //                $this->assertSame([
        //                    [
        //                        'name' => 'status',
        //                        'pattern' => '*',
        //                        'optional' => true,
        //                    ],
        //                    [
        //                        'name' => 'page',
        //                        'pattern' => '*',
        //                        'optional' => true,
        //                    ]
        //                ], $request->route_parameters);
        //
        //                $this->assertFalse($request->follows_redirect);
        //
        //                $this->assertSame([
        //                    'data' => [
        //                        [
        //                            'name' => 'Duilio Palacios',
        //                            'email' => 'duilio@example.com',
        //                        ],
        //                        [
        //                            'name' => 'Jeffer Ochoa',
        //                            'email' => 'jeff.ochoa@example.com',
        //                        ],
        //                    ]
        //                ], $request->response_body);
        //            });
        //        });
    }
}
