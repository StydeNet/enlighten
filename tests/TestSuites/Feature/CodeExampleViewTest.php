<?php

namespace Tests\Suites\Feature;

use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroup;
use Tests\TestCase;

class CodeExampleViewTest extends TestCase
{

    /** @test */
    public function get_code_example_view(): void
    {
        $codeExample = $this->createCodeExample();

        $response =$this->get(route('enlighten.example.show', ['example' => $codeExample]));

        $response->assertOk();
        $response->assertViewIs('enlighten::example.show');
        $response->assertViewHas('codeExample', $codeExample);
    }

    protected function createCodeExample()
    {
        $group = ExampleGroup::create([
            'class_name' => 'Tests\Feature\CreateUserTest',
            'title' => 'Create User',
        ]);

        return Example::create([
            'title' => 'Creates a new user',
            'group_id' => $group,
            'method_name' => 'creates_a_new_user',
            'description' => 'register new users in the system.',
            'request_path' => 'user',
            'request_headers' => [
                "accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "accept-charset" => "ISO-8859-1,utf-8;q=0.7,*;q=0.7",
                "accept-language" => "en-us,en;q=0.5"
            ],
            'request_method' => 'POST',
            'request_query_parameters' => [],
            'request_input' => [
                "name" => "Jeff",
                "email" => "jeff@example.test",
                "password" => "my-password"
            ],
            'route' => 'user',
            'route_parameters' => [],
            'response_status' => 302,
            'response_headers' => [
                "date" => [
                    "Wed, 23 Sep 2020 09:53:15 GMT"
                ],
                "location" => [
                    "http://localhost"
                ],
                "content-type" => [
                    "text/html; charset=UTF-8"
                ],
                "cache-control" => [
                    "no-cache, private"
                ]
            ],
            'response_body' => '<!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="UTF-8" />
                        <meta http-equiv="refresh" content="0;url=\'http://localhost\'" />

                        <title>Redirecting to http://localhost</title>
                    </head>
                    <body>
                    Redirecting to <a href="http://localhost">http://localhost</a>.
                    </body>
                </html>',
            'response_template' => null
        ]);
    }
}
